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

Vue.prototype.$ta = (object, attribute, fallback) => {
    return object[attribute + '_' + window.Locale] || fallback;
};

Vue.prototype.$l = (url) => {
    if(window.Locale === 'th') {
        return `/${window.Locale}/${url}`;
    }

    return `/${url}`;
};

const app = new Vue({
    el: '#root',
    i18n: i18n
});

import { EventBus } from './scripts/event_bus';

// Handle menu/search buttons
// TODO move this functionality into Vue.
$('body').click(function (event) {
    var target = $(event.target);
    if (target.is('#nav') || target.parents('#nav').length) {
        // In nav, pass!
    } else if (target.is('#search') || target.parents('#search').length) {
        // In search, pass!
    } else if (target.is('.btn-menu') || target.parents('.btn-menu').length) {
        event.preventDefault();
        $('#nav').toggle();
        $('#search').hide();
    } else if (target.is('.btn-search') || target.parents('.btn-search').length) {
        event.preventDefault();
        $('#search').toggle();
        if($('#search').is(':visible')) {
            EventBus.$emit('search');
        }
        $('#nav').hide();
    } else {
        $('#nav').hide();
        $('#search').hide();
    }
});

$('[data-toggle="popover"]').popover();
