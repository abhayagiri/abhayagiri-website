require('./bootstrap');

window.Vue = require('vue');

Vue.component('click-outside', require('./components/ClickOutside.vue').default);
Vue.component('search-form', require('./components/search/Form.vue').default);
Vue.component('search-type-subpage', require('./components/search/type/Subpage.vue').default);

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
