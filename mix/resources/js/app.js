/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('click-outside', require('./components/ClickOutside.vue').default);
Vue.component('search-form', require('./components/search/Form.vue').default);
Vue.component('search-type-subpage', require('./components/search/type/Subpage.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#root',
});

import { EventBus } from './scripts/event_bus';

// Show the navigation menu when clicking the menu button.
$('body').click(function (event) {
    var target = $(event.target);
    if (target.is('#nav') || target.parents('#nav').length) {
        // pass
    } else if (target.is('.btn-menu') || target.parents('.btn-menu').length) {
        event.preventDefault();
        $('#nav').toggle();
    } else {
        $('#nav').hide();
    }
});

// Show the search bar when clicking the search button.
$('.btn-search').click(function(event) {
    event.preventDefault();
    var searchContainer = $('#search');
    if(searchContainer.is(':hidden')) {
        EventBus.$emit('search');
    }
    searchContainer.toggle();
});

$('[data-toggle="popover"]').popover();
