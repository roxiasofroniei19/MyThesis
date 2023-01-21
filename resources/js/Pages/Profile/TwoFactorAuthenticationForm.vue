<template>
  <jet-action-section>
    <template #title> Autentificare in doi pași </template>

    <template #description>
      Adăugați securitate suplimentară contului dvs. utilizând autentificarea cu
      doi factori.
    </template>

    <template #content>
      <h3 class="text-lg font-medium text-gray-900" v-if="twoFactorEnabled">
        Ați activat autentificarea cu doi factori.
      </h3>

      <h3 class="text-lg font-medium text-gray-900" v-else>
        Nu ați activat autentificarea cu doi factori.
      </h3>

      <div class="mt-3 max-w-xl text-sm text-gray-600">
        <p>
          Când este activată autentificarea cu doi factori, vi se va solicita un
          indicativ sigur, aleatoriu în timpul autentificării. Puteți prelua
          acest simbol din aplicația Google Authenticator sau Microsoft
          Authentificator a telefonului.
        </p>
      </div>

      <div v-if="twoFactorEnabled">
        <div v-if="qrCode">
          <div class="mt-4 max-w-xl text-sm text-gray-600">
            <p class="font-semibold">
              Autentificarea cu doi factori este acum activată. Scanați
              următorul QR cod utilizând aplicația de autentificare a
              telefonului.
            </p>
          </div>

          <div
            class="mt-4 dark:p-4 dark:w-56 dark:bg-white"
            v-html="qrCode"
          ></div>
        </div>

        <div v-if="recoveryCodes.length > 0">
          <div class="mt-4 max-w-xl text-sm text-gray-600">
            <p class="font-semibold">
              Stocați aceste coduri de recuperare într-un manager de parole
              securizat. Ei pot să fie folosit pentru a recupera accesul la
              contul dvs. dacă factorii dvs. doi dispozitivul de autentificare
              este pierdut.
            </p>
          </div>

          <div
            class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg"
          >
            <div v-for="code in recoveryCodes" :key="code">
              {{ code }}
            </div>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <div v-if="!twoFactorEnabled">
          <jet-confirms-password @confirmed="enableTwoFactorAuthentication">
            <jet-button
              type="button"
              :class="{ 'opacity-25': enabling }"
              :disabled="enabling"
            >
              Activați
            </jet-button>
          </jet-confirms-password>
        </div>

        <div v-else>
          <jet-confirms-password @confirmed="regenerateRecoveryCodes">
            <jet-secondary-button class="mr-3" v-if="recoveryCodes.length > 0">
              Regenerați codurile de recuperare
            </jet-secondary-button>
          </jet-confirms-password>

          <jet-confirms-password @confirmed="showRecoveryCodes">
            <jet-secondary-button
              class="mr-3"
              v-if="recoveryCodes.length === 0"
            >
              Afișați codurile de recuperare
            </jet-secondary-button>
          </jet-confirms-password>

          <jet-confirms-password @confirmed="disableTwoFactorAuthentication">
            <jet-danger-button
              :class="{ 'opacity-25': disabling }"
              :disabled="disabling"
            >
              Dezactivează
            </jet-danger-button>
          </jet-confirms-password>
        </div>
      </div>
    </template>
  </jet-action-section>
</template>

<script>
import JetActionSection from "@/Jetstream/ActionSection";
import JetButton from "@/Jetstream/Button";
import JetConfirmsPassword from "@/Jetstream/ConfirmsPassword";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";

export default {
  components: {
    JetActionSection,
    JetButton,
    JetConfirmsPassword,
    JetDangerButton,
    JetSecondaryButton,
  },

  data() {
    return {
      enabling: false,
      disabling: false,

      qrCode: null,
      recoveryCodes: [],
    };
  },

  methods: {
    enableTwoFactorAuthentication() {
      this.enabling = true;

      this.$inertia.post(
        "/user/two-factor-authentication",
        {},
        {
          preserveScroll: true,
          onSuccess: () =>
            Promise.all([this.showQrCode(), this.showRecoveryCodes()]),
          onFinish: () => (this.enabling = false),
        }
      );
    },

    showQrCode() {
      return axios.get("/user/two-factor-qr-code").then((response) => {
        this.qrCode = response.data.svg;
      });
    },

    showRecoveryCodes() {
      return axios.get("/user/two-factor-recovery-codes").then((response) => {
        this.recoveryCodes = response.data;
      });
    },

    regenerateRecoveryCodes() {
      axios.post("/user/two-factor-recovery-codes").then((response) => {
        this.showRecoveryCodes();
      });
    },

    disableTwoFactorAuthentication() {
      this.disabling = true;

      this.$inertia.delete("/user/two-factor-authentication", {
        preserveScroll: true,
        onSuccess: () => (this.disabling = false),
      });
    },
  },

  computed: {
    twoFactorEnabled() {
      return !this.enabling && this.$page.props.user.two_factor_enabled;
    },
  },
};
</script>
