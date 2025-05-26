import { createApp } from 'vue';
import App from './components/App.vue';

const app = createApp()
app.component('app', App);
createApp(App).mount('#app');
