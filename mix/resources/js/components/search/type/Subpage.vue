<template>
  <div class="w-100 search-result-item" @click="handleClick()">
    <span class="text-primary">{{ title }}</span>
    <span class="text-muted">{{ result.path }}</span>
    <p v-html="truncateBody(body)"></p>
  </div>
</template>

<script>
export default {
  props: {
    result: {
      type: Object,
      required: true,
    },
  },

  methods: {
    truncateBody(text) {
      return _.truncate(text, {
        length: 150,
      });
    },

    handleClick() {
      window.location.href = this.result.path;
    }
  },

  computed: {
    title() {
      return this.result[`title_${window.Locale}`];
    },

    body() {
      return this.result[`body_html_${window.Locale}`];
    },
  },
}
</script>

<style lang="scss">
.search-result-item {
  cursor: pointer;
  &:hover {
    background-color: #edf2f7;
  }
}
</style>