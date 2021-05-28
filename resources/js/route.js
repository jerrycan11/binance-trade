import {createWebHistory, createRouter} from "vue-router";


const HomeComponent = () => import('./components/HomeComponent.vue');
const CryptoHome = () => import('./components/CryptoHome.vue');
let routes = [
    {
        path: '/crypto/',
        name: 'crypto',
        component: HomeComponent
    }, {
        path: '/crypto/home',
        name: 'home',
        component: CryptoHome
    }
];

const router = new createRouter({
    history: createWebHistory(),
    routes: routes,
});

router.beforeEach((to, from, next) => {
    console.log(to, from);
    next();
});

export default router;

