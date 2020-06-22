<template>
  <div>
    <input type="hidden" class='form-control' name="recaptcha" required v-model="reCaptchaResponse" />
    <vue-recaptcha
        ref="recaptcha"
        :sitekey="sitekey"
        :loadRecaptchaScript="true"
        :language="language"
        @verify="handleReCaptchaVerify"
        @expired="handleReCaptchaExpired"
    ></vue-recaptcha>
  </div>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha';

export default {
  components: {
    VueRecaptcha,
  },

  props: {
    sitekey: {
      type: String,
      required: true,
    },
    language: {
      type: String,
      required: true,
    }
  },

  data() {
    return {
      reCaptchaResponse: null,
    }
  },

  methods: {
    handleReCaptchaVerify(response) {
      this.reCaptchaResponse = response;
    },

    handleReCaptchaExpired() {
      this.reCaptchaResponse = null;
    },
  },
}
</script>
