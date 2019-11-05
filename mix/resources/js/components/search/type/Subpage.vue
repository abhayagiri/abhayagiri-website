<template>
  <div>
    <a :href="result.path">{{ title }}</a>
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
  },

  computed: {
    title() {
      return this.result[`title_${window.Locale}`];
    },

    body() {
      return this.result[`body_${window.Locale}`];
    },
  },
}
</script>