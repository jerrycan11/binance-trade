import {createWebHistory, createRouter} from "vue-router";


const HomeComponent = () => import('./components/HomeComponent.vue');
let routes = [
    {
        path: '/crypto/',
        name: 'crypto',
        component: HomeComponent
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

