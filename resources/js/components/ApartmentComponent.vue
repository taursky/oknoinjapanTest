<template>
    <div class="apartments-container">
        <h1>Apartments</h1>

        <!-- Фильтры -->
        <div class="filter-block">
            <h3>Найти по свойствам</h3>
            <form class="filter-form" @submit.prevent="applyFilters">
                <!-- Имя владельца -->
                <div class="filter-input">
                    <label for="owner_name">Имя</label>
                    <input type="text"
                           v-model="filter.owner_name"
                           name="owner_name"
                           id="owner_name"
                           class="form-control"
                           @input="debouncedFilter">
                </div>

                <!-- Город -->
                <div class="filter-input">
                    <label for="city">Город</label>
                    <div class="checkbox-group">
                        <div v-for="city in availableCities" :key="city.id" class="checkbox-item">
                            <input type="checkbox"
                                   :id="'city_' + city.id"
                                   v-model="filter.cities"
                                   :value="city.id"
                                   @change="applyFilters">
                            <label :for="'city_' + city.id">{{ city.name }}</label>
                        </div>
                    </div>
                </div>

                <!-- Адрес -->
                <div class="filter-input">
                    <label for="address">Адрес</label>
                    <input type="text"
                           v-model="filter.address"
                           name="address"
                           id="address"
                           class="form-control"
                           @input="debouncedFilter">
                </div>

                <!-- Спальни -->
                <div class="filter-input">
                    <label for="bedrooms">Спальни</label>
                    <input type="number"
                           v-model="filter.bedrooms"
                           id="bedrooms"
                           class="form-control"
                           min="0"
                           @change="applyFilters">
                </div>

                <!-- Этажность дома -->
                <div class="filter-input">
                    <label>Этажность</label>
                    <div class="range-inputs">
                        <input
                            type="number"
                            placeholder="От"
                            v-model.number="filter.house_floors.min"
                            min="1"
                            class="form-control"
                            @change="applyFilters"
                        >
                        <span>—</span>
                        <input
                            type="number"
                            placeholder="До"
                            v-model.number="filter.house_floors.max"
                            min="1"
                            class="form-control"
                            @change="applyFilters"
                        >
                    </div>
                </div>

                <!-- Этаж -->
                <div class="filter-input">
                    <label>Этаж</label>
                    <div class="range-inputs">
                        <input
                            type="number"
                            placeholder="От"
                            v-model.number="filter.floor.min"
                            min="1"
                            class="form-control"
                            @change="applyFilters"
                        >
                        <span>—</span>
                        <input
                            type="number"
                            placeholder="До"
                            v-model.number="filter.floor.max"
                            min="1"
                            class="form-control"
                            @change="applyFilters"
                        >
                    </div>
                </div>

                <!-- Цена -->
                <div class="filter-input">
                    <label>Стоимость</label>
                    <div class="range-inputs">
                        <input
                            type="number"
                            placeholder="От"
                            v-model.number="filter.price.min"
                            min="0"
                            class="form-control"
                            @change="applyFilters"
                        >
                        <span>—</span>
                        <input
                            type="number"
                            placeholder="До"
                            v-model.number="filter.price.max"
                            min="0"
                            class="form-control"
                            @change="applyFilters"
                        >
                    </div>
                </div>

                <!-- Опции -->
                <div class="filter-input">
                    <label>Опции</label>
                    <div class="checkbox-group">
                        <div v-for="option in availableOptions" :key="option.id" class="checkbox-item">
                            <input type="checkbox"
                                   :id="'option_' + option.id"
                                   v-model="filter.selectedOptions"
                                   :value="option.id"
                                   @change="applyFilters">
                            <label :for="'option_' + option.id">{{ option.name }}</label>
                        </div>
                    </div>
                </div>

                <!-- Дополнительные условия -->
                <div class="filter-input">
                    <label>Дополнительные условия</label>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input type="checkbox"
                                   id="not_first_floor"
                                   v-model="filter.notFirstFloor"
                                   @change="applyFilters">
                            <label for="not_first_floor">Не первый этаж</label>
                        </div>
                        <div class="checkbox-item">
                            <input type="checkbox"
                                   id="not_last_floor"
                                   v-model="filter.notLastFloor"
                                   @change="applyFilters">
                            <label for="not_last_floor">Не последний этаж</label>
                        </div>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="button" class="btn btn-secondary" @click="resetFilters">
                        Сбросить
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Применить
                    </button>
                </div>
            </form>
        </div>

        <!-- Результаты -->
        <div class="results-container">
            <div v-if="loading" class="loading-indicator">
                Загрузка данных...
            </div>

            <div v-if="!loading && filteredApartments.length === 0" class="no-results">
                Квартиры не найдены. Попробуйте изменить параметры поиска.
            </div>

            <div v-if="!loading && filteredApartments.length > 0">
                <div class="apartments-header">
                    <div class="header-item">Имя</div>
                    <div class="header-item">Город</div>
                    <div class="header-item">Адрес</div>
                    <div class="header-item">Спальни</div>
                    <div class="header-item">Этажность</div>
                    <div class="header-item">Этаж</div>
                    <div class="header-item">Опции</div>
                    <div class="header-item">Стоимость</div>
                </div>

                <div v-for="apartment in filteredApartments" :key="apartment.id" class="apartment-row">
                    <div class="apartment-cell">{{ apartment.owner_name }}</div>
                    <div class="apartment-cell">{{ apartment.city.name }}</div>
                    <div class="apartment-cell">{{ apartment.address }}</div>
                    <div class="apartment-cell">{{ apartment.bedrooms }}</div>
                    <div class="apartment-cell">{{ apartment.house_floors }}</div>
                    <div class="apartment-cell">{{ apartment.floor }}</div>
                    <div class="apartment-cell">
                        <span v-for="option in apartment.options" :key="option.id" class="option-tag">
                            {{ option.name }}
                        </span>
                    </div>
                    <div class="apartment-cell">{{ formatPrice(apartment.price) }}</div>
                </div>

                <Paginate v-if="totalPages > 1"
                    v-model="currentPage"
                    :value="currentPage"
                    :page-count="totalPages"
                    :margin-pages="2"
                    :click-handler="changePage"
                    :prev-text="'Назад'"
                    :next-text="'Вперед'"
                    :container-class="'pagination'"
                    :page-class="'page-item'"
                    :prevClass="'control-button'"
                    :nextClass="'control-button'"
                    :active-class="'active'">
                </Paginate>
            </div>
        </div>
    </div>
</template>

<script>
import debounce from 'lodash.debounce';
import Paginate from './PaginateComponent.vue';
export default {
    components: {Paginate},
    data() {
        return {
            loading: false,
            currentPage: 1,
            itemsPerPage: 10,
            filter: {
                owner_name: '',
                cities: [],
                address: '',
                bedrooms: null,
                price: {
                    min: null,
                    max: null,
                },
                house_floors: {
                    min: null,
                    max: null,
                },
                floor: {
                    min: null,
                    max: null,
                },
                selectedOptions: [],
                notFirstFloor: false,
                notLastFloor: false
            },
            availableCities: [],
            availableOptions: [],
            filteredApartments: [],
            totalItems: 0
        }
    },
    computed: {
        totalPages() {
            return Math.ceil(this.totalItems / this.itemsPerPage);
        }
    },
    watch: {
        currentPage() {
            this.fetchApartments();
        }
    },
    created() {
        this.debouncedFilter = debounce(this.applyFilters, 500);
    },
    mounted() {
        this.fetchApartments();
    },
    methods: {
        async fetchApartments() {
            try {
                this.loading = true;
                const response = await axios.post('/api/v1/apartments/filter', {
                    filter: this.cleanFilter(),
                    page: this.currentPage,
                    per_page: this.itemsPerPage
                });

                this.filteredApartments = response.data.apartments;
                this.availableCities = response.data.filter.cities;
                this.availableOptions = response.data.filter.options;
                this.totalItems = response.data.total_count;

                // Обновляем диапазоны в фильтре
                this.filter.price.min = response.data.filter.price.min;
                this.filter.price.max = response.data.filter.price.max;
                this.filter.house_floors.min = response.data.filter.house_floors.min;
                this.filter.house_floors.max = response.data.filter.house_floors.max;
                this.filter.floor.min = response.data.filter.floor.min;
                this.filter.floor.max = response.data.filter.floor.max;

            } catch (error) {
                console.error('Ошибка при загрузке квартир:', error);
            } finally {
                this.loading = false;
            }
        },
        cleanFilter() {
            // Удаляем пустые значения из фильтра перед отправкой
            const clean = {...this.filter};

            if (!clean.owner_name) delete clean.owner_name;
            if (!clean.address) delete clean.address;
            if (clean.bedrooms === null) delete clean.bedrooms;

            if (clean.price.min === null) delete clean.price.min;
            if (clean.price.max === null) delete clean.price.max;
            if (Object.keys(clean.price).length === 0) delete clean.price;

            // Аналогично для других диапазонов...

            if (!clean.selectedOptions.length) delete clean.selectedOptions;
            if (!clean.cities.length) delete clean.cities;

            return clean;
        },
        applyFilters() {
            this.currentPage = 1;
            this.fetchApartments();
        },
        resetFilters() {
            this.filter = {
                owner_name: '',
                cities: [],
                address: '',
                bedrooms: null,
                price: {
                    min: null,
                    max: null,
                },
                house_floors: {
                    min: null,
                    max: null,
                },
                floor: {
                    min: null,
                    max: null,
                },
                selectedOptions: [],
                notFirstFloor: false,
                notLastFloor: false
            };
            this.currentPage = 1;
            this.fetchApartments();
        },
        changePage(page) {
            this.currentPage = page;
            window.scrollTo({top: 0, behavior: 'smooth'});
        },
        formatPrice(price) {
            return new Intl.NumberFormat('ru-RU', {
                style: 'currency',
                currency: 'RUB',
                maximumFractionDigits: 0
            }).format(price);
        }
    }
}
</script>

<style scoped>
.apartments-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.filter-block {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.filter-form {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.filter-input {
    margin-bottom: 0.1rem;
}

.filter-input label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}
.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.range-inputs {
    display: flex;
    align-items: center;
    gap: 10px;
}

.range-inputs input {
    flex: 1;
    min-width: 0;
}

.checkbox-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.filter-actions {
    grid-column: 1 / -1;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
}

.btn {
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    border: 1px solid #007bff;
}

.btn-primary:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    border: 1px solid #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
}

.results-container {
    margin-top: 20px;
}

.apartments-header, .apartment-row {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 10px;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.apartments-header {
    font-weight: 600;
    background-color: #f1f1f1;
    border-radius: 4px;
    padding: 12px 10px;
}

.apartment-cell {
    padding: 8px;
    word-break: break-word;
}

.option-tag {
    display: inline-block;
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 4px;
    margin-right: 4px;
    margin-bottom: 4px;
    font-size: 12px;
}

.loading-indicator, .no-results {
    text-align: center;
    padding: 20px;
    color: #666;
}
</style>
