require('./bootstrap');
import {createApp} from 'vue';
import ElementPlus from 'element-plus';
import App from './components/App.vue';
import router from "./route";
import 'element-plus/lib/theme-chalk/index.css';

const lodash = require('lodash');


const app = createApp(App);
app.use(ElementPlus);
app.use(router);
app.use(lodash);
app.mount('#app');
