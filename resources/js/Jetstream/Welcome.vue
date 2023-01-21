<template>
  <div>
    <div class="p-6 sm:px-20  border-b border-gray-200">


      <div class="my-8 text-2xl">
        Programarile zilei 
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
                <span class="font-bold">Nume programare:</span>
                {{ schedule.ScheduleName }}
              </li>
              <li>
                <span class="font-bold">Descriere:</span>
                {{ schedule.ScheduleDescription }}
              </li>
              <li>
                <span class="font-bold">Adresa client:</span>
                {{ schedule.ClientAddress }}
              </li>
              <li>
                <span class="font-bold">Adresa programare:</span>
                <span v-if="schedule.ScheduleAddress">{{
                  schedule.ScheduleAddress
                }}</span>
                <span v-else> - </span>
              </li>
              <li>
                <span class="font-bold">Nr. Telefon:</span>
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
    <div class="p-6 sm:px-20  border-b border-gray-200 mt-4">


      <div class="my-8 text-2xl">
        Programari viitoare 
      </div>
      <div
        class="max-w-7xl mx-auto flex flex-wrap"
        v-if="futureSchedules[0] != 0 && loaded == true"
      >
        <AppointmentCard
          v-for="schedule in futureSchedules[0]"
          :key="schedule.ScheduleId"
          :scheduleId="schedule.ScheduleId"
          :currentUserId="userId"
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
                <span class="font-bold">Nume programare:</span>
                {{ schedule.ScheduleName }}
              </li>
              <li>
                <span class="font-bold">Descriere:</span>
                {{ schedule.ScheduleDescription }}
              </li>
              <li>
                <span class="font-bold">Adresa client:</span>
                {{ schedule.ClientAddress }}
              </li>
              <li>
                <span class="font-bold">Adresa programare:</span>
                <span v-if="schedule.ScheduleAddress">{{
                  schedule.ScheduleAddress
                }}</span>
                <span v-else> - </span>
              </li>
              <li>
                <span class="font-bold">Nr. Telefon:</span>
                {{ schedule.ClientPhone }}
              </li>
            </ul>
          </template>
        </AppointmentCard>
      </div>
      <div
        class="max-w-7xl mx-auto flex flex-wrap bg-white shadow p-4"
        v-else
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
  </div>
</template>

<script>
import JetApplicationLogo from "@/Jetstream/ApplicationLogo";
import AppointmentCard from "@/Components/AppointmentCard";
import Button from "@/Jetstream/Button";
import Modal from "@/Jetstream/Modal";
import DangerButton from "@/Jetstream/DangerButton.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import Spinner from "@/Components/Spinner";

export default {
  components: {
    JetApplicationLogo,
    AppointmentCard,
    Button,
    Modal,
    DangerButton,
    SecondaryButton,
    Spinner,
  },
  props: ["user"],
  data() {
    return {
      time1: null,
      time2: null,
      time3: null,
      newAppointmentModalStatus: false,
      schedules: [],
      futureSchedules: [],
      shedulesStartDate: null,
      shedulesEndDate: null,
      loaded: false,
      limit: 50,
      activeFilters: false,
      customerNames: [],
      selectedCustomer: "Alege un client",
      scheduleTypes: ["Deratizare", "Plosnite"],
    };
  },
  methods: {
    getTodaysSchedules() {
      (async () => {
        try {
          axios.get("/getTodaysSchedules").then((response) => {
            this.schedules = [];
            this.schedules.push(response.data.schedules);
            this.activeFilters = true;
            this.loaded = true;
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
            this.futureSchedules = [];
            this.futureSchedules.push(response.data.schedules);
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
  },
  mounted() {
    this.getTodaysSchedules();
    this.getFutureSchedules();
  },
};
</script>
