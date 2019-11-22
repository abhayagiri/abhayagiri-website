<template>
  <click-outside :handle="handleClickOutside">
    <div class="container my-3 position-relative">
      <div class="row">
        <input ref="searchInput" class="form-control form-control-sm" type="text" v-model="q" @focus="handleFocus()" />
      </div>
      <div class="position-absolute w-100 mx-n3 shadow overflow-auto search-results">
        <div class="bg-light border" v-if="showResults">
          <div class="col-12" v-for="(result, index) in results" :key="result.id">
            <div class="row" :class="{ 'border-top': index > 0 }">
              <component class="p-2" :is="'search-type-' + result.model_basename" :result="result"></component>
            </div>
          </div>
          <div class="col-12">
            <div class="row">
              <p class="m-0 p-2 w-100 font-italic text-muted text-right">{{ algoliaMessage }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </click-outside>
</template>

<script>
import { EventBus } from './../../scripts/event_bus';

export default {
  data() {
    return {
      q: '',
      results: [],
      queryEvent: null,
      showResults: false,
    };
  },

  mounted() {
    EventBus.$on('search', () => {
      Vue.nextTick(() => {
        this.$refs.searchInput.focus();
      })
    });
  },

  methods: {
    performSearch() {
      axios
        .get(`/api/search?q=${this.q}&language=${window.Locale}`)
        .then(response => {
          this.results = response.data;
          if (this.results.length > 0) {
            this.showResults = true;
          }
        })
        .catch(error => {
          this.showResults = false;
          console.error('There was an error retrieving the results: ' + error.response.statusText);
        });
    },

    handleFocus() {
      if (this.results.length > 0) {
        this.showResults = true;
      }
    },

    handleClickOutside() {
      this.showResults = false;
    },
  },

  watch: {
    q(newValue, oldValue) {
      if (this.queryEvent) {
        clearTimeout(this.queryEvent);
      }

      this.queryEvent = setTimeout(() => {
        this.performSearch();
      }, 750);
    },
  },

  computed: {
    algoliaMessage() {
      return (window.Locale == 'en' ? 'Powered by ' : 'K̄hạbkhelụ̄̀xn doy ') + 'Algolia';
    },
  },
}
</script>