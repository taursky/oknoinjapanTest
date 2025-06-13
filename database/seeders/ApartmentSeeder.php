<?php

namespace Database\Seeders;

use App\Models\Apartment;
use App\Models\City;
use App\Models\Options;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = public_path('files/data.csv');

        if (!file_exists($csvFile)) {
            $this->command->error("CSV file not found: {$csvFile}");
            return;
        }

        $file = fopen($csvFile, 'r');
        fgetcsv($file, 0, ';'); // Пропускаем заголовок

        // Очищаем таблицы перед заполнением
        DB::table('apartments_options')->truncate();
        Apartment::truncate();
        City::truncate();
        Options::truncate();

        while (($row = fgetcsv($file, 0, ';')) !== false) {
            // Обработка города
            $city = City::firstOrCreate(['name' => trim($row[1])]);
            // Создание квартиры
            $apartment = Apartment::create([
                'city_id' => $city->id,
                'owner_name' => trim($row[0]),
                'address' => trim($row[2]),
                'bedrooms' => (int)$row[3],
                'house_floors' => (int)$row[4],
                'floor' => (int)$row[5],
                'price' => (float)$row[7],
            ]);

            // Обработка опций
            $options = array_map('trim', explode(',', $row[6]));
            foreach ($options as $optionName) {
                $option = Options::firstOrCreate(['name' => $optionName]);
                $apartment->options()->attach($option->id);
            }
        }

        fclose($file);
        $this->command->info('Apartments, cities and options seeded successfully!');
    }
}
