import './bootstrap';
import '../css/app.css';

import { createApp } from 'vue';
import ApartmentComponent from './components/ApartmentComponent.vue';

const app = createApp({});
app.component('apartment-component', ApartmentComponent);

app.mount('#app_vue');
