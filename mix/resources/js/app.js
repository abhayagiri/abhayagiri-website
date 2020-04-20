require('./bootstrap');

window.Vue = require('vue');

import languageBundle from '../../../resources/lang/index';
import VueI18n from 'vue-i18n';
Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: window.Locale,
    messages: languageBundle,
});

import InstantSearch from 'vue-instantsearch';
Vue.use(InstantSearch);

Vue.component('instant-search-form', require('./components/search/InstantSearchForm.vue').default);
// Vue.component('click-outside', require('./components/ClickOutside.vue').default);

// See https://github.com/abhayagiri/abhayagiri-website/issues/120
Vue.component('book-cart-country', require('./components/books/BookCartCountry.vue').default);

const app = new Vue({
    el: '#root',
    i18n: i18n
});

import { EventBus } from './scripts/event_bus';

// Handle menu/search buttons
// TODO move this functionality into Vue.

$('body').click(function (event) {
    var target = $(event.target);
    if (target.is('#header-menu') || target.parents('#header-menu').length) {
        // In menu, pass!
    } else if (target.is('#header-search') || target.parents('#header-search').length) {
        // In search, pass!
    } else if (target.is('#header-menu-button') || target.parents('#header-menu-button').length) {
        event.preventDefault();
        $('#root').toggleClass('menu-active').removeClass('search-active');
    } else if (target.is('#header-search-button') || target.parents('#header-search-button').length) {
        event.preventDefault();
        $('#root').removeClass('menu-active').toggleClass('search-active');
        if ($('#root').hasClass('search-active')) {
            EventBus.$emit('search');
        }
    } else {
        // Button click outside when menu or search is open...
        $('#root').removeClass('menu-active').removeClass('search-active');
    }
});

$('[data-toggle="popover"]').popover();
