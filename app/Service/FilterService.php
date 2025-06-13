<?php

namespace App\Service;

use App\Models\Apartment;
use App\Models\City;
use App\Models\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterService
{
    public function getFilter(Request $request)
    {
        $filter = $request->get('filter', []);
        $page = (int)$request->get('page', 1);
        $per_page = (int)$request->get('per_page', 10);

        // Основной запрос для фильтрации квартир
        $query = Apartment::with(['city', 'options']);

        // Применяем фильтры
        $this->applyFilters($query, $filter);

        // Получаем отфильтрованные квартиры с пагинацией
        $apartments = $query->paginate($per_page, ['*'], 'page', $page);
//        $offset = $page * $per_page;
//        $apartments = $query->limit($per_page)->offset($offset)->get();
        // Получаем ID всех отфильтрованных квартир
        $apartmentIds = $apartments->pluck('id')->toArray();

        // Получаем доступные города, опции и диапазоны только для отфильтрованных квартир
        $availableCities = $this->getAvailableCities($apartmentIds);
        $availableOptions = $this->getAvailableOptions($apartmentIds);

        $ranges = $this->getApartmentRanges($query);

        return response()->json([
            'total_count' => $apartments->total(),
            'current_page' => $apartments->currentPage(),
            'per_page' => $apartments->perPage(),
            'filter' => [
                'options' => $availableOptions,
                'cities' => $availableCities,
                'house_floors' => $ranges['house_floors'],
                'floor' => $ranges['floor'],
                'price' => $ranges['price'],
            ],
            'apartments' => $apartments->items(),
        ]);
    }

    protected function applyFilters($query, $filter)
    {
        // Фильтр по имени владельца
        if (!empty($filter['owner_name'])) {
            $query->where('owner_name', 'like', '%' . $filter['owner_name'] . '%');
        }

        // Фильтр по городам
        if (!empty($filter['cities'])) {
            $query->whereIn('city_id', $filter['cities']);
        }

        // Фильтр по адресу
        if (!empty($filter['address'])) {
            $query->where('address', 'like', '%' . $filter['address'] . '%');
        }

        // Фильтр по количеству спален
        if (!empty($filter['bedrooms'])) {
            $query->where('bedrooms', $filter['bedrooms']);
        }

        // Фильтр по цене
        if (!empty($filter['price']['min'])) {
            $query->where('price', '>=', $filter['price']['min']);
        }
        if (!empty($filter['price']['max'])) {
            $query->where('price', '<=', $filter['price']['max']);
        }

        // Фильтр по этажности дома
        if (!empty($filter['house_floors']['min'])) {
            $query->where('house_floors', '>=', $filter['house_floors']['min']);
        }
        if (!empty($filter['house_floors']['max'])) {
            $query->where('house_floors', '<=', $filter['house_floors']['max']);
        }

        // Фильтр по этажу
        if (!empty($filter['floor']['min'])) {
            $query->where('floor', '>=', $filter['floor']['min']);
        }
        if (!empty($filter['floor']['max'])) {
            $query->where('floor', '<=', $filter['floor']['max']);
        }

        // Фильтр "не первый этаж"
        if (!empty($filter['notFirstFloor'])) {
            $query->where('floor', '!=', 1);
        }

        // Фильтр "не последний этаж"
        if (!empty($filter['notLastFloor'])) {
            $query->whereRaw('floor != house_floors');
        }

        // Фильтр по опциям
        if (!empty($filter['selectedOptions'])) {
            $query->whereHas('options', function($q) use ($filter) {
                $q->whereIn('options.id', $filter['selectedOptions']);
            });
        }
    }

    protected function getAvailableCities(array $apartmentIds)
    {
        return City::whereHas('apartments', function($query) use ($apartmentIds) {
            $query->whereIn('apartments.id', $apartmentIds);
        })->get(['id', 'name']);
    }

    protected function getAvailableOptions(array $apartmentIds)
    {
        return Options::whereHas('apartments', function($query) use ($apartmentIds) {
            $query->whereIn('apartments.id', $apartmentIds);
        })->get(['id', 'name']);
    }

    /**
     * @param $query
     * @return array
     */
    protected function getApartmentRanges($query): array
    {
        $rangeQuery = clone $query;
        $res = [
            'house_floors' => [
                'min' => 0,
                'max' => 0,
            ],
            'floor' => [
                'min' => 0,
                'max' => 0,
            ],
            'price' => [
                'min' => 0,
                'max' => 0,
            ],
        ];
        $ranges = $rangeQuery->select([
            DB::raw('MIN(house_floors) as house_floors_min'),
            DB::raw('MAX(house_floors) as house_floors_max'),
            DB::raw('MIN(floor) as floor_min'),
            DB::raw('MAX(floor) as floor_max'),
            DB::raw('MIN(price) as price_min'),
            DB::raw('MAX(price) as price_max'),
        ])->first();
        if ($ranges) {
            $res = [
                'house_floors' => [
                    'min' => (float)$ranges->house_floors_min ?? 0,
                    'max' => (float)$ranges->house_floors_max ?? 0,
                ],
                'floor' => [
                    'min' => (float)$ranges->floor_min ?? 0,
                    'max' => (float)$ranges->floor_max ?? 0,
                ],
                'price' => [
                    'min' => (float)$ranges->price_min ?? 0,
                    'max' => (float)$ranges->price_max ?? 0,
                ],
            ];
        }

        return $res;
    }
}
