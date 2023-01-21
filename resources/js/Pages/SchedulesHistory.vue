<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Programarile clientului:
        <span class="font-black text-red-800">{{ clientName }}</span>
      </h2>
    </template>
    <div class="py-1">
      <div
        class="max-w-7xl mx-auto flex flex-wrap overflow-x-auto"
        v-if="schedules[0] != 0 && loaded == true"
      >
        <table class="w-full max-w-7xl overflow-x-auto divide-y divide-gray-200 mt-4">
          <thead class="bg-gray-50">
            <tr>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                #
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Nume
              </th>

              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Descriere
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Adresa programare
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Operatiune
              </th>
              <th
                scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Data
              </th>
              <th
                v-if="$page.props.user.isAdmin"
                scope="col"
                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Actiune
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 w-full">
            <tr
              class="bg-white hover:bg-gray-200"
              v-for="(schedule, index) in schedules[0]"
              :key="schedule.ScheduleId"
            >
              <td class="p-2">
                {{ index }}
              </td>
              <td class="p-2">
                {{ schedule.ScheduleName }}
              </td>
              <td class="p-2">
                {{ schedule.ScheduleDescription }}
              </td>
              <td class="p-2">
                {{ schedule.ScheduleAddress }}
              </td>
              <td class="p-2">
                {{ schedule.ScheduleType }}
              </td>
              <td class="p-2">
                {{ schedule.ScheduleDate }} {{ schedule.ScheduleTime }}
              </td>
              <td
                class="p-2 flex justify-center"
                v-if="$page.props.user.isAdmin"
              >
                <a
                  href="#"
                  @click="deleteSchedule(schedule.ScheduleId)"
                  class="mx-auto inline-block"
                  ><span class="text-red-300 text-center inline-block"
                    >Sterge</span
                  ></a
                >
              </td>
            </tr>
          </tbody>
        </table>
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
  props: ["clientHistoryId"],
  data() {
    return {
      time1: null,
      time2: null,
      time3: null,
      newAppointmentModalStatus: false,
      schedules: [],
      shedulesStartDate: null,
      shedulesEndDate: null,
      loaded: false,
      limit: 50,
      activeFilters: false,
      customerNames: [],
      scheduleTypes: ["Deratizare", "Dezinfectie", "Dezinsectie"],
      clientName: null,

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
    loadSchedules() {
      (async () => {
        try {
          axios
            .get("/getAllScheduleHistory/" + this.clientHistoryId)
            .then((response) => {
              this.clientName = response.data.schedules[0].ClientName;
              this.schedules = [];
              this.schedules.push(response.data.schedules);
              this.loaded = true;
              this.activeFilters = false;
              this.shedulesStartDate = null;
              this.shedulesEndDate = null;
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
    deleteSchedule(id) {
      const payload = {
        userId: this.currentUserId,
        scheduleIdToDelete: id,
      };
      axios
        .post("/deleteSchedule", payload)
        .then((response) => {
          this.removeFromList(id);
        })
        .catch((error) => {
          this.errorMessage = error.message;
          console.error("A aparut o eroare!", error);
        });
    },
  },
  beforeMount() {
    this.getUserId();
  },
  mounted() {
    this.loadSchedules();
  },
};
</script>

<style scoped>
</style>
