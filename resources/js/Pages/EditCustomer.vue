<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Modifică studentul cu numele
        <span class="text-red-400">{{ client.ClientName }}</span>
      </h2>
    </template>
    <div
      class="shadow max-w-7xl mx-auto my-4 py-3 sm:px-6 lg:px-8 bg-green-100"
      v-if="notificationStatus == 200"
    >
      Studentul a fost modificat cu succes!
    </div>
    <div
      class="shadow max-w-7xl mx-auto my-4 py-3 sm:px-6 lg:px-8 bg-red-100"
      v-if="notificationStatus != 200 && notificationStatus != null"
    >
      Se pare ca a aparut o problema. Verifica datele introduse si reincearca.
    </div>
    <div class="p-4 container max-w-7xl mx-auto bg-white mt-4">
      <div class="flex flex-wrap py-3">
        <div class="w-full py-2 flex">
          <span class="text-lg mr-3 inline-block w-1/4 text-center"
            >Nume student*</span
          >
          <textarea
            class="inline-block w-3/6"
            v-model="formClientName"
          ></textarea>
        </div>
        <div class="w-full py-2 flex">
          <span class="text-lg mr-3 inline-block w-1/4 text-center"
            >Adresa</span
          >
          <textarea
            class="inline-block w-3/6"
            v-model="formClientAddress"
          ></textarea>
        </div>
        <div class="w-full py-2 flex">
          <span class="text-lg mr-3 inline-block w-1/4 text-center"
            >Nr. Telefon</span
          >
          <input
            type="tel"
            class="inline-block w-3/6"
            v-model="formClientPhone"
          />
        </div>
        <div class="w-full py-2 flex">
          <span class="text-lg mr-3 inline-block w-1/4 text-center"
            >Email</span
          >
          <input
            type="email"
            class="inline-block w-3/6"
            v-model="formClientEmail"
          />
        </div>
        <div class="w-full py-2 flex">
          <span class="text-lg mr-3 inline-block w-1/4 text-center"
            >Specializare</span
          >
          <input
            type="text"
            class="inline-block w-3/6"
            v-model="formClientCUI"
          />
        </div>
        <div class="w-full py-2 flex">
          <span class="text-lg mr-3 inline-block w-1/4 text-center"
            >ID Student</span
          >
          <input
            type="text"
            class="inline-block w-3/6"
            v-model="formClientCIF"
          />
        </div>
      </div>
      <span class="mt-6"><i>Campurile marcate cu * sunt obligatorii</i></span>
      <div class="py-3 border-t-2 flex justify-end">
        <Button class="mr-3" @click="editClient()">Salveaza</Button>
        <a href="/clienti"
          ><DangerButton @click="returnToPreviousPage()"
            >Renunta</DangerButton
          ></a
        >
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import AppointmentCard from "@/Components/AppointmentCard";
import Button from "@/Jetstream/Button";
import Modal from "@/Jetstream/Modal";
import DangerButton from "@/Jetstream/DangerButton.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import Spinner from "@/Components/Spinner";
import Select2 from "vue3-select2-component";

export default {
  components: {
    AppLayout,
    AppointmentCard,
    Button,
    Modal,
    DangerButton,
    SecondaryButton,
    Spinner,
    Select2,
  },
  props: ["client"],
  data() {
    return {
      userId: null,
      notificationStatus: null,
      formClientName: this.client.ClientName,
      formClientAddress: this.client.ClientAddress,
      formClientPhone: this.client.ClientPhone,
      formClientCUI: this.client.ClientVATNumber,
      formClientCIF: this.client.ClientRegistrationNumber,
      formClientEmail: this.client.ClientEmail,
    };
  },
  methods: {
    editClient() {
      const payload = {
        clientId: this.client.ClientId,
        clientName: this.formClientName,
        clientAddress: this.formClientAddress,
        clientEmail: this.formClientEmail,
        clientPhone: this.formClientPhone,
        clientCUI: this.formClientCUI,
        clientCIF: this.formClientCIF,
      };
      axios
        .post("/editClient", payload)
        .then((response) => {
          this.notificationStatus = response.status;
        })
        .catch((error) => {
          this.notificationStatus = 500;
          this.errorMessage = error.message;
          console.error("A aparut o eroare!", error);
        });
    },
    getUserId() {
      (async () => {
        try {
          axios.get("/getUserId").then((response) => {
            this.userId = response.data;
          });
        } catch (err) {
          console.log(err);
        }
      })();
    },
  },
  beforeMount() {
    this.getUserId();
  },
  mounted() {},
};
</script>

<style scoped>
</style>
