/* eslint-env node, jquery */

import Vue from 'vue';
import App from './components/App.vue';

import VueGeolocation from 'vue-browser-geolocation';
Vue.use(VueGeolocation);

import Vue2Filters from 'vue2-filters';
Vue.use(Vue2Filters);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#root',

    /**
     * The component's statisticalData.
     * @returns {Object} The component's statisticalData.
     */
    data() {
        return {};
    },

    render: h => h(App),
});

export default app;
