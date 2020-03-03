<template>
<div class="contact container">
  <template v-if="noOptionSelected">
    <legend>{{ $t('contact.contact') }}</legend>

    <div class="contact-text" v-html="renderPreamble"></div>

    <div class="row">
      <div class="col-md-6">
        <ul class="contact-options list-group">
          <a :href="$l('contact/' + contactOption.slug)" class="list-group-item" v-for="contactOption in contactOptions" :key="contactOption.id">{{ $ta(contactOption, 'name') }}</a>
        </ul>
      </div>
    </div>
  </template>
  <template v-else>
    <legend v-html="renderTitle"></legend>
    <div class="row">
      <div class="col-sm-12">
        <div class="contact-text" v-html="renderBody"></div>
        <div v-if="showForm" class='contact container'>
          <form class="contact-form form-horizontal">
            <hr class="contact-separator" />

            <div class='form-group row'>
                <label class='control-label col-md-2 text-right' htmlFor="name"><b>{{ $t('contact.name') }}</b></label>
                <div class='col-md-6'>
                  <input type="text" id="name" name="name" class='form-control' v-model="form.name" required />
                </div>
            </div>

            <div class='form-group row'>
              <label class='control-label col-md-2 text-right' htmlFor="inputIcon"><b>{{ $t('contact.email-address') }}</b></label>
              <div class='col-md-6'>
                <div class='input-prepend'>
                  <span class='add-on'><i class='icon-envelope'></i></span>
                  <input class='form-control' id="email" name="email" type="email" v-model="form.email" required />
                </div>
              </div>
            </div>

            <div v-if="selectContactOption" class='form-group row'>
              <label class='control-label col-md-2 text-right' htmlFor="email"><b>{{ $t('contact.message') }}</b></label>
              <div class='col-md-6'>
                <textarea id="message" name="message" rows="12" class="form-control" v-model="form.message" required></textarea>
              </div>
            </div>

            <div class='form-group row'>
              <label class='control-label col-md-2 text-right'></label>
              <div class='col-md-6'>
                <vue-recaptcha
                  ref="recaptcha"
                  sitekey="6LeCoGAUAAAAALQSh8ycS5x7ZkRAzgWMWttFuzC3"
                  @verify="handleReCaptchaVerify"
                  @expired="handleReCaptchaExpired"
                  :loadRecaptchaScript="true"
                  :language="this.reCaptchaLanguage"
                ></vue-recaptcha>
              </div>
            </div>

            <div class='form-group row'>
                <div class="col-md-2"></div>
                <div class="col-md-6">
                  <button type="submit" class='btn btn-large btn-primary' :disabled="! canSubmit" @click.prevent="submitForm">
                    {{ $t('contact.send-message') }}
                  </button>

                  <button type="button" class='btn btn-large' @click.prevent="clearForm">{{ $t('contact.cancel') }}</button>
                </div>
            </div>
          </form>
        </div>
        <div v-if="showEmailSentMessage">
          <legend>{{ $t('contact.your-message-title') }}</legend>
          <blockquote className="blockquote user-message">{{ this.form.message }}</blockquote>
        </div>
      </div>
      <div class="col-sm-12">
          <a :href="$l('contact')" class="btn btn-secondary">{{ $t('contact.back') }}</a>
      </div>
    </div>
  </template>
</div>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha';

export default {
  components: {
    VueRecaptcha,
  },

  props: {
    preamble: {
      type: Object,
      required: true,
    },
    initialContactOption: {
      type: Object,
      required: false,
      default: null,
    },
    contactOptions: {
      type: Array,
      required: true,
    },
  },

  data() {
    return {
      selectedContactOption: this.initialContactOption,
      form: {
        name: '',
        email: '',
        message: '',
      },
      reCaptchaResponse: null,
      reCaptchaLanguage: window.Locale,
      loading: false,
      emailSent: false,
    };
  },

  methods: {
    selectContactOption(contactOption) {
      this.selectedContactOption = contactOption;
    },

    handleReCaptchaVerify(response) {
      this.reCaptchaResponse=  response;
    },

    handleReCaptchaExpired() {
      this.reCaptchaResponse = null;
    },

    clearForm() {
      this.form.name = '';
      this.form.email = '';
      this.form.message = '';
      this.$refs.recaptcha.reset();
      this.loading = false;
    },

    submitForm() {
      this.loading = true;

      axios
        .post('/api/contact', {
          'contact-option': this.selectedContactOption,
          name: this.form.name,
          email: this.form.email,
          message: this.form.message,
          'g-recaptcha-response': this.reCaptchaResponse,
        })
        .then(response => {
          this.emailSent = true;
        })
        .catch(error => {
          swal({
            type: 'error',
            title: $t('contact.whoops'),
            text: Object.keys(error.response.data).reduce((content, index) => {
              return content + error.response.data[index].join("\n") + "\n";
            }, ''),
          });

          this.loading = false;
        });
    },
  },

  computed: {
    noOptionSelected() {
      return this.selectedContactOption == null;
    },

    renderPreamble() {
      return this.preamble.value;
    },

    renderTitle() {
      return this.$ta(this.selectedContactOption, 'name');
    },

    renderBody() {
      return this.$ta(this.selectedContactOption, 'body_html');
    },

    showEmailSentMessage() {
      return this.emailSent;
    },

    showForm() {
      return this.selectedContactOption.active && ! this.emailSent;
    },

    canSubmit() {
      return ! this.loading && this.form.name != '' && this.form.email != '' && this.form.message != '' && this.reCaptchaResponse != null
    },
  },
}
</script>
