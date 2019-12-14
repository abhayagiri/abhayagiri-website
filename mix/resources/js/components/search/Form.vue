<template>
  <click-outside :handle="handleClickOutside">
    <div class="container my-3 position-relative">
      <div class="row">
        <input ref="searchInput" class="form-control form-control-sm" type="text" v-model="q" @focus="handleFocus()" />
      </div>
      <div class="position-absolute w-100 mx-n3 shadow overflow-auto search-results">
        <div class="bg-light border" v-if="showResults">
          <p class="pl-2 mt-1 mb-0 w-100 font-italic text-muted" style="font-size: 13px;">{{ resultCountMessage }}</p>
          <div class="col-12 pt-2" v-if="searching">
            <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
          </div>
          <div class="col-12" v-for="(result, index) in results" :key="result.id">
            <div class="row" :class="{ 'border-top': index > 0 }">
              <component class="p-2" :is="'search-type-' + result.model_basename" :result="result"></component>
            </div>
          </div>
          <div class="col-12">
            <div class="row">
              <p class="m-0 p-2 w-100 font-italic text-muted text-right" style="font-size: 12px;">{{ algoliaMessage }}</p>
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
      searching: false,
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
        })
        .catch(error => {
          this.showResults = false;
          console.error('There was an error retrieving the results: ' + error.response.statusText);
        })
        .finally(() => {
          this.searching = false;
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

      this.results = [];
      this.showResults = true;
      this.searching = true;

      this.queryEvent = setTimeout(() => {
        this.performSearch();
      }, 750);
    },
  },

  computed: {
    algoliaMessage() {
      return (window.Locale == 'en' ? 'Powered by ' : 'K̄hạbkhelụ̄̀xn doy ') + 'Algolia';
    },

    resultCountMessage() {
      if(this.searching) {
        return '';
      }

      return this.results.length > 0 ? 'Results: ' + this.results.length : 'No results!';
    },
  },
}
</script>