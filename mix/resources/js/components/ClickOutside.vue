<script>
export default {
  props: {
    handle: Function,
    required: true,
  },

  mounted() {
    const listener = e => {
      if (e.target !== this.$el && !this.$el.contains(e.target)) {
        this.handle();
      }
    };

    document.addEventListener('click', listener);
    this.$once('hook:destroyed', () => {
      document.removeEventListener('click', listener);
    });
  },

  render() {
    return this.$slots.default[0];
  },
}
</script>
