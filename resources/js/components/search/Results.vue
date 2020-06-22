<template>
  <div class="container my-3 position-relative">
    <div class="row">
      <input class="form-control form-control-sm" type="text" v-model="q" />
    </div>
    <click-outside :handle="handleClickOutside">
      <div class="position-absolute w-100 mx-n3" style="z-index: 1000;">
        <div class="bg-light border" v-if="showResults">
          <div class="col-12" v-for="(result, index) in results" :key="result.id">
            <div class="row" :class="{ 'border-top': index > 0 }">
              <div class="p-2">
                <a :href="result.path">{{ result.title_en }}</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </click-outside>
  </div>
</template>

<script>
export default {

  data() {
    return {
      q: '',
      results: [],
      queryEvent: null,
      showResults: false,
    };
  },

  methods: {
    performSearch() {
      axios
        .get(`/api/search?q=${this.q}`)
        .then(response => {
          this.results = response.data;
          if (this.results.length > 0) {
            this.showResults = true;
          }
        })
        .catch(error => {
          console.error(error.response.statusText);
        });
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
}
</script>