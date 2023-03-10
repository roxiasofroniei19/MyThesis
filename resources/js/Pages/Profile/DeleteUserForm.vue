<template>
  <jet-action-section>
    <template #title> Stergerea contului </template>

    <template #description> Ștergeți-vă definitiv contul. </template>

    <template #content>
      <div class="max-w-xl text-sm text-gray-600">
        Odată ce contul dvs. este șters, toate resursele și datele sale vor fi
        șterse definitiv. Înainte de a vă șterge contul, vă rugăm să descărcați
        orice date sau informații pe care doriți să le păstrați.
      </div>

      <div class="mt-5">
        <jet-danger-button @click="confirmUserDeletion">
          Șterge contul
        </jet-danger-button>
      </div>

      <!-- Delete Account Confirmation Modal -->
      <jet-dialog-modal :show="confirmingUserDeletion" @close="closeModal">
        <template #title> Șterge contul </template>

        <template #content>
          Sigur doriți să vă ștergeți contul? Odată ce contul dvs. este șterse,
          toate resursele și datele sale vor fi șterse definitiv. Vă rugăm să
          introduceți parola pentru a confirma că doriți permanent sterge-ti
          contul.

          <div class="mt-4">
            <jet-input
              type="password"
              class="mt-1 block w-3/4"
              placeholder="Password"
              ref="password"
              v-model="form.password"
              @keyup.enter="deleteUser"
            />

            <jet-input-error :message="form.errors.password" class="mt-2" />
          </div>
        </template>

        <template #footer>
          <jet-secondary-button @click="closeModal">
            Anulare
          </jet-secondary-button>

          <jet-danger-button
            class="ml-2"
            @click="deleteUser"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Sterge contul
          </jet-danger-button>
        </template>
      </jet-dialog-modal>
    </template>
  </jet-action-section>
</template>

<script>
import JetActionSection from "@/Jetstream/ActionSection";
import JetDialogModal from "@/Jetstream/DialogModal";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";

export default {
  components: {
    JetActionSection,
    JetDangerButton,
    JetDialogModal,
    JetInput,
    JetInputError,
    JetSecondaryButton,
  },

  data() {
    return {
      confirmingUserDeletion: false,

      form: this.$inertia.form({
        password: "",
      }),
    };
  },

  methods: {
    confirmUserDeletion() {
      this.confirmingUserDeletion = true;

      setTimeout(() => this.$refs.password.focus(), 250);
    },

    deleteUser() {
      this.form.delete(route("current-user.destroy"), {
        preserveScroll: true,
        onSuccess: () => this.closeModal(),
        onError: () => this.$refs.password.focus(),
        onFinish: () => this.form.reset(),
      });
    },

    closeModal() {
      this.confirmingUserDeletion = false;

      this.form.reset();
    },
  },
};
</script>
