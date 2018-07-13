
window._         = require('lodash');
window.axios     = require('axios');
window.pluralize = require('./vendor/pluralize/pluralize');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.vuedata.csrfToken;

window.Vue   = require('vue');

Vue.component('main-component', require('@Components/MainComponent.vue'));
Vue.component('index-component', require('@Components/IndexComponent.vue'));

const app = new Vue({
    el: '#app'
});

