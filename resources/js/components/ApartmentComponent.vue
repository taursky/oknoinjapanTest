<template>
    <div class="">
        <h1>Apatrtments</h1>
        <div class="s_form-block">
            <h3>Найти по свойствам</h3>
            <form class="s_form" @submit="selectApartments">
                <div class="s_input">
                    <label for="owner_name">Имя</label>
                    <input type="text" name="owner_name" id="owner_name" class="form-control">
                </div>
                <div class="s_input">
                    <label for="city">Город</label>
                    <input type="text" name="city" id="city" class="form-control">
                </div>
                <div class="s_input">
                    <label for="address">Адрес</label>
                    <input type="text" name="address" id="address" class="form-control">
                </div>
                <div class="s_input">
                    <label for="bedrooms">Спальни</label>
                    <input type="text" name="bedrooms" id="bedrooms" class="form-control">
                </div>
                <div class="s_input">
                    <label for="house_floors">Этажность</label>
                    <!--Диапазон (между $X и $Y)-->
                    <div class="range-inputs">
<!--                        <input type="text" name="house_floors" id="house_floors" class="form-control" v-model="house_floors">-->
                        <input
                            type="number"
                            placeholder="От"
                            v-model="houseFloorsRange.min"
                            class="form-control"
                        >
                        <span>—</span>
                        <input
                            type="number"
                            placeholder="До"
                            v-model="houseFloorsRange.max"
                            class="form-control"
                        >
                    </div>
                </div>
                <div class="s_input">
                    <!--Диапазон (между $X и $Y)-->
                    <label for="floor">Этаж</label>
<!--                    <input type="text" name="floor" id="floor" class="form-control" v-model="floor">-->
                    <dib class="range-inputs">
                        <input
                            type="number"
                            placeholder="От"
                            v-model="floorRange.min"
                            class="form-control"
                        >
                        <span>—</span>
                        <input
                            type="number"
                            placeholder="До"
                            v-model="floorRange.max"
                            class="form-control"
                        >
                    </dib>
                </div>
                <div class="s_input">
                    <label for="price">Стоимость</label>
<!--                    <input type="text" name="price" id="price" class="form-control" v-model="price">-->
                    <div class="range-inputs">
                        <input
                            type="number"
                            placeholder="От"
                            v-model="priceRange.min"
                            class="form-control"
                        >
                        <span>—</span>
                        <input
                            type="number"
                            placeholder="До"
                            v-model="priceRange.max"
                            class="form-control"
                        >
                    </div>
                </div>
                <div class="s_input">
                    <div>&nbsp;</div>
                    <button class="btn btn-dark">
                        выбрать
                    </button>

                </div>
            </form>
        </div>
        <div>
            <div class="apartments-table">
                <div class="apartments-table_item">Имя</div>
                <div class="apartments-table_item">Город</div>
                <div class="apartments-table_item">Адрес</div>
                <div class="apartments-table_item">Спальни</div>
                <div class="apartments-table_item">Этажность</div>
                <div class="apartments-table_item">Этаж</div>
                <div class="apartments-table_item">Опции</div>
                <div class="apartments-table_item">Стоимость</div>
            </div>
            <div v-for="apartment in selected_apartments" class="apartments-table">
                <div class="apartments-table_item">{{apartment.owner_name}}</div>
                <div class="apartments-table_item">{{apartment.city}}</div>
                <div class="apartments-table_item">{{apartment.address}}</div>
                <div class="apartments-table_item">{{apartment.bedrooms}}</div>
                <div class="apartments-table_item">{{apartment.house_floors}}</div>
                <div class="apartments-table_item">{{apartment.floor}}</div>
                <div class="apartments-table_item">
                    <div v-for="opt in apartment.options">
                        <small>{{opt}}</small>
                    </div>
                </div>
                <div class="apartments-table_item">{{moneyFormat(apartment.price)}}</div>
            </div>
        </div>

        <Paginate
            :page-count="pages"
            :margin-pages="2"
            :click-handler="numberPage"
            :prev-text="'пред'"
            :next-text="'след'"
            :hide-prev-next="true"
            :container-class="'page-navigation'">
        </Paginate>
    </div>
</template>
<script>
import Paginate from './PaginateComponent.vue';
import { ref } from 'vue';
export default {
    components: { Paginate },
    props: {
        apartments: {
            type: [Array],
            default: null,
        },
    },
    data() {
        return {
            itemsOnPage: 10,
            currentPage: 1,
            pages: 4,
            selected_apartments: [],
            price: 0,
            floor: 1,

        }
    },
    mounted() {
        this.selected_apartments = this.apartments;
    },
    setup() {
        const floorRange = ref({ min: null, max: null });
        const houseFloorsRange = ref({ min: null, max: null });
        const priceRange = ref({ min: null, max: null });

        const selectApartments = () => {
            // Здесь обрабатываем данные формы
            console.log({
                floorRange: floorRange.value,
                houseFloorsRange: houseFloorsRange.value
            });
        };

        return {
            floorRange,
            houseFloorsRange,
            priceRange,
            selectApartments
        };
    },
    methods: {
        selectApartments: function (e) {
            e.preventDefault();

        },
        numberPage: function (page) {
            //show number page
            this.currentPage = page;
            this.getApartments();
            //поднимаем страницу вверх!!
            // this.scrollToTop();
        },
        getApartments: function () {
            //get info
        },
        moneyFormat: function (sum) {
            return new Intl.NumberFormat('ru-RU', {style: 'currency', currency: 'RUB', maximumFractionDigits: 0}).format(sum)
        },
    },
}
</script>
<style>
.apartments-table {
    position: relative;
    display: grid;
    grid-template-columns: repeat(8, 2fr);
}
.apartments-table_item {
    padding: 2px 4px;
    border: 1px solid #101010;
}
.page-navigation {
    align-items: center;
    display: flex;
    flex: 1;
    justify-content: center;
}

.page-navigation li,
.page-navigation__item {
    display: inline-block;
    font-size: 1rem;
    font-weight: 500;
    padding: 4px 8px;
    margin: 2px;
    border: 1px solid #101010;
    border-radius: 5px;
    cursor: pointer;
}
.page-navigation li a {
    color: #101010;
    text-decoration: none;
}
.page-navigation .active {
    border-color: darkred;
    background: #e8e8e8;
}
.page-navigation .active a {
    font-weight: 600;
    color: #cb2525;
}
.page-navigation .disabled a {
    color: #a4a4a4;
}
.page-navigation__item.disabled {
    cursor: auto;
    color: rgba(33, 37, 41, 0.55);
    border: 1px solid rgba(33, 37, 41, 0.45);
}
.s_input label {
    font-weight: 600;
}
.s_form {
    position: relative;
    display: flex;
    margin-bottom: 25px;
}
.s_form input {
    margin-right: 5px;
}
.range-inputs {
    display: flex;
    align-items: center;
    gap: 10px;
}
.range-inputs input {
    width: 80px;
}
</style>
