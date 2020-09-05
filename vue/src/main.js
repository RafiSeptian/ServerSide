import Vue from 'vue'
import App from './App.vue'
import VueRouter from 'vue-router';
import Axios from 'axios';

Vue.use(VueRouter);

window.Fire = new Vue();
window.axios = Axios;
window.token = localStorage.getItem('token');

Vue.config.productionTip = false;

const routes = [
    {
      path: '/',
        component: require('./components/Auth.vue').default
    },
    {
      path: '/home',
        component: require('./components/Home.vue').default
    },
    {
        path: '/board/:id',
        component: require('./components/Board.vue').default
    }
];

const router = new VueRouter({
    mode: 'history',
    routes
});

new Vue({
  render: h => h(App),
    router
}).$mount('#app')
