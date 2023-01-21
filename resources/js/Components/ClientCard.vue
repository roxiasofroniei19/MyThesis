<template>
  <div class="max-w-7xl mx-auto card-size mt-3">
    <div class="overflow-hidden shadow-md">
      <!-- card header -->
      <div
        class="px-6 py-4 bg-white border-b border-gray-200 font-bold uppercase"
      >
        <slot name="title"></slot>
      </div>

      <!-- card body -->
      <div class="p-6 bg-white border-b border-gray-200">
        <slot name="content"></slot>
      </div>

      <!-- card footer -->
      <div
        class="p-6 bg-white border-gray-200 text-right flex flex-wrap justify-items-end justify-end"
      >
        <!-- button link -->
        <!-- <a
          class="bg-yellow-600 mr-3 shadow-md text-sm text-white font-bold py-3 md:px-8 px-4 hover:bg-yellow-700 rounded uppercase mt-2"
          href="#"
          >Modifica</a
        > -->
        <a v-if="$page.props.user.isAdmin"
          class="m-1 bg-yellow-600 shadow-md text-xs text-white font-bold py-2 md:px-6 px-3 hover:bg-yellow-700 rounded uppercase mt-1"
          :href="editClientUrl"
          >Modifica</a
        >
        <a v-if="$page.props.user.isAdmin"
          class="m-1 bg-red-600 shadow-md text-xs text-white font-bold py-2 md:px-6 px-3 hover:bg-red-700 rounded uppercase mt-1"
          href="#"
           @click="toggleDeleteClientModal()">Sterge</a
        >
      </div>
    </div>
  </div>
  <ConfirmationModal :show="confirmationModalStatus">
    <template #title>Stergere client</template>
    <template #content>Sunteti sigur ca doriti stergerea clientului?</template>
    <template #footer><Button class="m-1" @click="toggleDeleteClientModal()">Anuleaza</Button><DangerButton class="m-1" @click="deleteClient()">Sterge</DangerButton></template>
  </ConfirmationModal>
</template>

<script>
import ConfirmationModal from "@/Jetstream/ConfirmationModal";
import DangerButton from "@/Jetstream/DangerButton";
import Button from "@/Jetstream/Button";

export default {
  components: {
    ConfirmationModal,
    DangerButton,
    Button,
  },
  props: ['clientId', 'currentUserId'],
  data() {
    return {
      historyUrl: "/programari/istoric/" + this.clientId,
      editClientUrl: "/clienti/modifica/" + this.clientId,
      confirmationModalStatus: false,
    }
  },
  methods: {
    toggleDeleteClientModal() {
      this.confirmationModalStatus = !this.confirmationModalStatus;
    },
    deleteClient() {
      const payload = {
        userId: this.currentUserId,
        clientIdToDelete: this.clientId
      };

      this.toggleDeleteClientModal();

      axios
        .post("/deleteClient", payload)
        .then((response) => {
          console.log()
          this.$emit('deleteClient', this.clientId);
        })
        .catch((error) => {
          this.errorMessage = error.message;
          console.error("A aparut o eroare!", error);
        });
    },
  },
};
</script>

<style>
.card-size {
  flex: 1;
  min-width: 25em;
  margin: 5px;
}

@media (max-width: 575.98px) {
  .card-size {
  flex: 1;
  min-width: 17em;
  margin: 5px;
}
 }
</style>
