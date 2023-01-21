<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Prezentări
        <Button
          class="m-3"
          @click="toggleNewAppointmentModal()"
          v-if="$page.props.user.isAdmin"
          >Adauga o prezentare</Button
        >
        <a href="/getTodaysSchedules/pdf" v-if="todaysSchedulesCount > 0">
          <SecondaryButton class="m-3"
            >PDF prezentările zilei</SecondaryButton
          ></a
        >

        <a href="/getTodaysSchedules/pdf" v-else>
          <DangerButton class="m-3">PDF prezentările zilei</DangerButton></a
        >

        <a href="/getTomorrowsSchedules/pdf" v-if="tomorrowsScheduleCount > 0">
          <SecondaryButton class="m-3"
            >PDF prezentările zilei urmatoare</SecondaryButton
          ></a
        >

        <a href="/getTomorrowsSchedules/pdf" v-else>
          <DangerButton class="m-3 bg-red-300"
            >PDF prezentările zilei urmatoare</DangerButton
          ></a
        >
      </h2>
    </template>
    <div
      class="shadow max-w-7xl mx-auto my-4 py-3 sm:px-6 lg:px-8 bg-green-100"
      v-if="notificationStatus == 200"
    >
      Prezentarea a fost adaugata cu succes!
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
          <div class="text-lg my-2">Filtre</div>
          <div class="flex flex-wrap">
            <div class="date-filter flex-1 mt-2">
              Pentru data
              <input
                type="date"
                name="for"
                id="dateFor"
                v-on:change="setSchedulesDate()"
                v-model="schedulesDate"
              />
            </div>
            <div class="date-filter flex-1 mt-2">
              Incepand cu data
              <input
                type="date"
                name="from"
                id="dateFrom"
                v-on:change="setSchedulesStartDate()"
                v-model="shedulesStartDate"
              />
            </div>
            <div class="date-filter flex-1 mt-2">
              Pana la data
              <input
                type="date"
                name="to"
                id="dateTo"
                v-on:change="setSchedulesEndDate()"
                v-model="shedulesEndDate"
              />
            </div>
          </div>
          <div class="conditions flex flex-wrap mt-8">
            <div class="flex items-center mr-4 mb-4 flex-wrap">
              <Button class="ml-1 mt-1" v-on:click="getTodaysSchedules()"
                >Astazi</Button
              >
              <Button class="ml-1 mt-1" v-on:click="getFutureSchedules()"
                >Viitoare</Button
              >
              <!-- <Button class="ml-1 mt-1" v-on:click="getCompletedSchedules()"
                  >Finalizate</Button
                >
                <Button class="ml-1 mt-1" v-on:click="getCanceledSchedules()"
                  >Anulate</Button
                > -->
            </div>
          </div>
          <SecondaryButton class="text-xs mt-4" v-on:click="loadSchedules()"
            >Anuleaza toate filtrele</SecondaryButton
          >
        </div>
      </div>

      <div
        class="max-w-7xl mx-auto flex flex-wrap"
        v-if="schedules[0] != 0 && loaded == true"
      >
        <AppointmentCard
          v-for="schedule in schedules[0]"
          :key="schedule.ScheduleId"
          :scheduleId="schedule.ScheduleId"
          :currentUserId="userId"
          @deleteSchedule="removeFromList"
          class="mt-3"
        >
          <template #title> {{ schedule.ClientName }} </template>
          <template #datetime>
            Data:
            <span class="font-bold">{{ schedule.ScheduleDate }}</span> | Ora:
            <span class="font-bold">{{ schedule.ScheduleTime }}</span>
          </template>
          <template #content>
            <ul>
              <li>
                <span class="font-bold">Titlu prezentare:</span>
                {{ schedule.ScheduleName }}
              </li>
              <li>
                <span class="font-bold">Descriere:</span>
                <span v-if="schedule.ScheduleDescription">{{
                  schedule.ScheduleDescription
                }}</span>
                <span v-else> - </span>
              </li>
              <li>
                <span class="font-bold">Locație: </span>
                <span v-if="schedule.ClientAddress">{{
                  schedule.ClientAddress
                }}</span>
                <span v-else> - </span>
              </li>
              <li>
                <span class="font-bold">Locație: </span>
                <span v-if="schedule.ScheduleAddress">{{
                  schedule.ScheduleAddress
                }}</span>
                <span v-else> - </span>
              </li>
              <li>
                <span class="font-bold">Nr. Telefon: </span>
                {{ schedule.ClientPhone }}
              </li>
            </ul>
          </template>
        </AppointmentCard>
      </div>
      <div
        class="max-w-7xl mx-auto flex flex-wrap bg-white shadow p-4"
        v-if="schedules[0] == 0"
      >
        Nu exista programari!
      </div>
      <div class="max-w-7xl mx-auto flex flex-wrap" v-if="loaded == false">
        <Spinner class="mx-auto"></Spinner>
      </div>
      <div class="max-w-7xl mx-auto flex flex-wrap" v-if="!activeFilters">
        <Button class="mx-auto my-6" v-on:click="loadMoreSchedules()"
          >Incarca mai multe</Button
        >
      </div>
    </div>
  </app-layout>

  <Modal :show="newAppointmentModalStatus">
    <div class="p-4">
      <div class="w-full h-25 text-lg py-3 font-bold">
        Adauga o prezentare noua
      </div>
      <div class="flex flex-wrap py-3 border-t-2">
        <div class="w-full py-2 flex flex-wrap">
          <span class="text-lg mr-3 inline-block w-1/3 text-center"
            >Numele prezentării*</span
          >
          <textarea
            class="inline-block w-3/6 border-1"
            v-model="formScheduleName"
          ></textarea>
        </div>
        <div class="w-full py-2 flex flex-wrap">
          <span class="text-lg mr-3 w-1/3 text-center">Student*</span>
          <Select2
            :settings="{width: '100%'}"
            :options="customerNames"
            v-model="selectedCustomer"
            placeholder="Alege un client pentru programare"
          />
        </div>
        <div class="w-full py-2 flex flex-wrap">
          <span class="text-lg mr-3 w-1/3 text-center"
            >Data si ora prezentării*</span
          >
          <input
            class="inline-block w-3/6"
            type="datetime-local"
            name="appointmentDateTime"
            id="appointmentDateTime"
            v-model="formDateTime"
          />
        </div>
        <div class="w-full py-2 flex flex-wrap">
          <span class="text-lg mr-3 inline-block w-1/3 text-center"
            >Locația</span
          >
          <textarea
            class="inline-block w-3/6"
            v-model="formScheduleAddress"
          ></textarea>
        </div>
        <div class="w-full py-2 flex flex-wrap">
          <span class="text-lg mr-3 inline-block w-1/3 text-center"
            >Descriere</span
          >
          <textarea
            class="inline-block w-3/6"
            v-model="formScheduleDescription"
          ></textarea>
        </div>
      </div>
      <div class="pt-4"><i>Campurile marcate cu * sunt obligatorii</i></div>
      <div class="py-3 border-t-2 flex justify-end">
        <Button class="mr-3" @click="addNewSchedule()">Salveaza</Button>
        <DangerButton @click="toggleNewAppointmentModal()"
          >Renunta</DangerButton
        >
      </div>
    </div>
  </Modal>
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
  data() {
    return {
      time1: null,
      time2: null,
      time3: null,
      newAppointmentModalStatus: false,
      schedules: [],
      shedulesStartDate: null,
      shedulesEndDate: null,
      schedulesDate: null,
      loaded: false,
      limit: 50,
      activeFilters: false,
      customerNames: [],
      scheduleTypes: ["Deratizare", "Dezinfectie", "Dezinsectie"],

      todaysSchedulesCount: 0,
      tomorrowsScheduleCount: 0,

      //Form data
      selectedCustomer: null,
      formScheduleDescription: null,
      formScheduleAddress: null,
      formDateTime: null,
      selectedScheduleType: null,
      formScheduleName: null,

      // User data
      userId: null,

      // Notifications
      notificationStatus: null,
    };
  },
  methods: {
    toggleNewAppointmentModal() {
      this.newAppointmentModalStatus = !this.newAppointmentModalStatus;
    },
    loadSchedules() {
      (async () => {
        try {
          axios.get("/getAllSchedules/" + this.limit).then((response) => {
            this.schedules = [];
            this.schedules.push(response.data.schedules);
            this.loaded = true;
            this.activeFilters = false;
            this.shedulesStartDate = null;
            this.shedulesEndDate = null;
            this.schedulesDate = null;
          });
        } catch (err) {
          console.log(err);
        }
      })();
    },
    getTodaysSchedules() {
      (async () => {
        try {
          axios.get("/getTodaysSchedules").then((response) => {
            this.schedules = [];
            this.schedules.push(response.data.schedules);
            this.activeFilters = true;
            this.getTodaysSchedulesCount();
          });
        } catch (err) {
          console.log(err);
        }
      })();
    },
    getFutureSchedules() {
      (async () => {
        try {
          axios.get("/getFutureSchedules").then((response) => {
            this.schedules = [];
            this.schedules.push(response.data.schedules);
            this.activeFilters = true;
          });
        } catch (err) {
          console.log(err);
        }
      })();
    },
    getCompletedSchedules() {
      //TODO
    },
    getCanceledSchedules() {
      //TODO
    },
    setSchedulesStartDate() {
      if (this.shedulesEndDate == null) {
        (async () => {
          try {
            axios
              .get("/getSchedulesFromStartingDate/" + this.shedulesStartDate)
              .then((response) => {
                this.schedules = [];
                this.schedules.push(response.data.schedules);
                this.activeFilters = true;
              });
          } catch (err) {
            console.log(err);
          }
        })();
      } else {
        this.getSchedulesFromDateToDate();
      }
    },
    setSchedulesEndDate() {
      if (this.shedulesStartDate == null) {
        (async () => {
          try {
            axios
              .get("/getSchedulesBeforeEndDate/" + this.shedulesEndDate)
              .then((response) => {
                this.schedules = [];
                this.schedules.push(response.data.schedules);
                this.activeFilters = true;
              });
          } catch (err) {
            console.log(err);
          }
        })();
      } else {
        this.getSchedulesFromDateToDate();
      }
    },
    getSchedulesFromDateToDate() {
      (async () => {
        try {
          axios
            .get(
              "/getSchedulesFromDateToDate/" +
                this.shedulesStartDate +
                "/" +
                this.shedulesEndDate
            )
            .then((response) => {
              this.schedules = [];
              this.schedules.push(response.data.schedules);
              this.activeFilters = true;
            });
        } catch (err) {
          console.log(err);
        }
      })();
    },
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
    loadMoreSchedules() {
      this.limit = this.limit + 50;
      this.loadSchedules();
    },
    getTodaysSchedulesCount() {
      (async () => {
        try {
          axios.get("/getTodaysSchedulesCount").then((response) => {
            this.todaysSchedulesCount = response.data;
          });
        } catch (err) {
          console.log(err);
        }
      })();
    },
    getTomorrowsSchedulesCount() {
      (async () => {
        try {
          axios.get("/getTomorrowsSchedulesCount").then((response) => {
            this.tomorrowsScheduleCount = response.data;
          });
        } catch (err) {
          console.log(err);
        }
      })();
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
    addNewSchedule() {
      const payload = {
        customerId: this.selectedCustomer,
        scheduleDescription: this.formScheduleDescription,
        scheduleAddress: this.formScheduleAddress,
        scheduleDateTime: this.formDateTime,
        scheduleType: this.selectedScheduleType,
        scheduleName: this.formScheduleName,
        userId: this.userId,
      };
      axios
        .post("/addNewSchedule", payload)
        .then((response) => {
          this.toggleNewAppointmentModal();
          this.loadSchedules();
          this.notificationStatus = response.status;
        })
        .catch((error) => {
          this.toggleNewAppointmentModal();
          this.notificationStatus = 500;
          this.errorMessage = error.message;
          console.error("A aparut o eroare!", error);
        });
    },
    removeFromList(id) {
      this.schedules[0].forEach((schedule) => {
        if (schedule.ScheduleId == id) {
          const itemIndex = this.schedules[0].indexOf(schedule);
          if (itemIndex !== -1) {
            this.schedules[0].splice(itemIndex, 1);
          }
        }
      });

      this.schedules[0][0].ScheduleId;
    },
    setSchedulesDate() {
      (async () => {
        try {
          axios
            .get("/getSchedulesForDate/" + this.schedulesDate)
            .then((response) => {
              this.schedules = [];
              this.schedules.push(response.data.schedules);
              this.activeFilters = true;
            });
        } catch (err) {
          console.log(err);
        }
      })();
    },
  },
  beforeMount() {
    this.getUserId();
    this.getTodaysSchedulesCount();
    this.getTomorrowsSchedulesCount();
  },
  mounted() {
    this.loadSchedules();
    this.getAllCustomerNames();
  },
};
</script>

<style scoped>
</style>
