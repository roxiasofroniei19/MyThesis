<template>
  <jet-authentication-card>
    <template #logo>
      <jet-authentication-card-logo />
    </template>

    <div class="mb-4 text-sm text-gray-600">
      Vă mulțumim pentru înscriere! Înainte de a începe, ați putea să vă
      confirmați adresa de e-mail făcând clic pe linkul pe care tocmai vi l-am
      trimis? Dacă nu ați primit e-mailul, vă vom trimite cu plăcere altul.
    </div>

    <div
      class="mb-4 font-medium text-sm text-green-600"
      v-if="verificationLinkSent"
    >
      Un nou link de verificare a fost trimis la adresa de e-mail pe care ați furnizat-o
      în timpul înregistrării.
    </div>

    <form @submit.prevent="submit">
      <div class="mt-4 flex items-center justify-between">
        <jet-button
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Retrimite email de verificare
        </jet-button>

        <inertia-link
          :href="route('logout')"
          method="post"
          as="button"
          class="underline text-sm text-gray-600 hover:text-gray-900"
          >Deconectare</inertia-link
        >
      </div>
    </form>
  </jet-authentication-card>
</template>

<script>
import JetAuthenticationCard from "@/Jetstream/AuthenticationCard";
import JetAuthenticationCardLogo from "@/Jetstream/AuthenticationCardLogo";
import JetButton from "@/Jetstream/Button";

export default {
  components: {
    JetAuthenticationCard,
    JetAuthenticationCardLogo,
    JetButton,
  },

  props: {
    status: String,
  },

  data() {
    return {
      form: this.$inertia.form(),
    };
  },

  methods: {
    submit() {
      this.form.post(this.route("verification.send"));
    },
  },

  computed: {
    verificationLinkSent() {
      return this.status === "verification-link-sent";
    },
  },
};
</script>
