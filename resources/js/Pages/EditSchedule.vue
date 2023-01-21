<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Modifică prezentarea cu titlul
        <span class="text-red-400">{{ schedule[0].ScheduleName }}</span>
      </h2>
    </template>
    <div
      class="shadow max-w-7xl mx-auto my-4 py-3 sm:px-6 lg:px-8 bg-green-100"
      v-if="notificationStatus == 200"
    >
      Prezentarea a fost modificata cu succes!
    </div>
    <div
      class="shadow max-w-7xl mx-auto my-4 py-3 sm:px-6 lg:px-8 bg-red-100"
      v-if="notificationStatus != 200 && notificationStatus != null"
    >
      Se pare ca a aparut o problema. Verifica datele introduse si reincearca.
    </div>
    <div class="py-1">
      <div
        class="filters shadow max-w-7xl mx-auto my-4 sm:px-6 lg:px-8 bg-white"
      >
        <div class="p-6">
          <div class="text-lg my-2">Modificare</div>
          <div class="flex flex-wrap">
            <div class="w-full p-4">
              <div class="w-full py-2 flex flex-wrap">
                <span class="text-lg mr-3 inline-block w-1/3 text-center"
                  >Titlul prezentarii*</span
                >
                <textarea
                  class="inline-block w-3/6 border-1"
                  v-model="scheduleName"
                ></textarea>
              </div>
              <div class="w-full py-2 flex flex-wrap">
                <span class="text-lg mr-3 w-1/3 text-center">Student*</span>
                <Select2
                  :options="customerNames"
                  :settings="{ width: '100%' }"
                  v-model="selectedCustomer"
                  placeholder="Alege un client pentru programare"
                />
              </div>
              <div class="w-full py-2 flex flex-wrap">
                <span class="text-lg mr-3 w-1/3 text-center"
                  >Data si ora prezentarii*</span
                >
                <input
                  class="inline-block w-3/6"
                  type="datetime-local"
                  name="appointmentDateTime"
                  id="appointmentDateTime"
                  v-model="scheduleDateTime"
                />
              </div>
              <div class="w-full py-2 flex flex-wrap">
                <span class="text-lg mr-3 inline-block w-1/3 text-center"
                  >Locatie</span
                >
                <textarea
                  class="inline-block w-3/6"
                  v-model="scheduleAddress"
                ></textarea>
              </div>
              <div class="w-full py-2 flex flex-wrap">
                <span class="text-lg mr-3 inline-block w-1/3 text-center"
                  >Descriere</span
                >
                <textarea
                  class="inline-block w-3/6"
                  v-model="scheduleDescription"
                ></textarea>
              </div>
              <div class="w-full mt-4 flex justify-center">
                <Button class="block mx-auto" @click="editSchedule()">Salveaza</Button>
              </div>
            </div>
          </div>
        </div>
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
  props: ["schedule"],
  data() {
    return {
      scheduleDateTime:
        this.schedule[0].ScheduleDate + "T" + this.schedule[0].ScheduleTime,
      scheduleAddress: this.schedule[0].ScheduleAddress,
      scheduleName: this.schedule[0].ScheduleName,
      scheduleDescription: this.schedule[0].ScheduleDescription,
      selectedCustomer: this.schedule[0].ClientId,
      selectedScheduleType: this.schedule[0].ScheduleType,
      customerNames: [],
      userId: null,
      notificationStatus: null,

      scheduleTypes: ["Deratizare", "Dezinfectie", "Dezinsectie"],
    };
  },
  methods: {
    getAllCustomerNames() {
      (async () => {
        try {
          axios.get("/getAllCustomerNames").then((response) => {
            this.customerNames = [];
            response.data.customerNames.forEach((customer) => {
              this.customerNames.push({
                id: customer.ClientId,
                text: customer.ClientName,
              });
            });
          });
        } catch (err) {
          console.log(err);
        }
      })();
    },
    editSchedule() {
        const payload = {
        customerId: this.selectedCustomer,
        scheduleDescription: this.scheduleDescription,
        scheduleAddress: this.scheduleAddress,
        scheduleDateTime: this.scheduleDateTime,
        scheduleType: this.selectedScheduleType,
        scheduleName: this.scheduleName,
        scheduleId: this.schedule[0].ScheduleId,
        scheduleClientId: this.selectedCustomer,
        userId: this.userId,
      };
      axios
        .post("/editSchedule", payload)
        .then((response) => {
            console.log(payload);
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
      this.getAllCustomerNames();
      this.getUserId();
  },
  mounted() {},
};
</script>

<style scoped>
</style>
