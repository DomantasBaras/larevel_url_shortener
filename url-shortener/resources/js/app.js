import { createApp } from 'vue';
import UrlForm from './components/UrlForm.vue';

const app = createApp({});
app.component('url-form', UrlForm);
app.mount('#app');
