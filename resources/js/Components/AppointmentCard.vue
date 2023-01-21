<template>
  <div class="mx-auto card-size">
    <div class="overflow-hidden shadow-md">
      <!-- card header -->
      <div
        class="px-6 py-4 bg-white border-b border-gray-200 font-bold uppercase"
      >
        <slot name="title"></slot>
        <div class="text-gray-500 text-sm">
          <slot name="datetime"></slot>
        </div>
      </div>

      <!-- card body -->
      <div class="p-6 bg-white border-b border-gray-200">
        <slot name="content"></slot>
      </div>

      <!-- card footer -->
      <div
        v-if="$page.props.user.isAdmin && scheduleId != null"
        class="p-6 bg-white border-gray-200 text-right flex flex-wrap justify-items-end justify-end"
      >
        <!-- button link -->
        <a
          class="bg-yellow-600 mr-3 shadow-md text-xs text-white font-bold py-2 md:px-6 px-3 hover:bg-yellow-700 rounded uppercase mt-1"
          :href="editScheduleUrl"
          >Modifica</a
        >
        <a
          class="bg-red-600 shadow-md text-xs text-white font-bold py-2 md:px-6 px-3 hover:bg-red-700 rounded uppercase mt-1"
          href="#"
          @click="toggleDeleteAppointmentModal()"
          >Sterge</a
        >
      </div>
    </div>
  </div>
  <ConfirmationModal :show="confirmationModalStatus">
    <template #title>Stergere programare</template>
    <template #content
      >Sunteti sigur ca doriti stergerea acestei programari?</template
    >
    <template #footer
      ><Button class="m-1" @click="toggleDeleteAppointmentModal()">Anuleaza</Button
      ><DangerButton class="m-1" @click="deleteAppointment()"
        >Sterge</DangerButton
      ></template
    >
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
  props: ["scheduleId", "currentUserId"],
  data() {
    return {
      editScheduleUrl: "programari/modifica/" + this.scheduleId,
      confirmationModalStatus: false,
    };
  },
  methods: {
    toggleDeleteAppointmentModal() {
      this.confirmationModalStatus = !this.confirmationModalStatus;
    },
    deleteAppointment() {
      const payload = {
        userId: this.currentUserId,
        scheduleIdToDelete: this.scheduleId,
      };
      axios
        .post("/deleteSchedule", payload)
        .then((response) => {
          this.$emit("deleteSchedule", this.scheduleId);
        })
        .catch((error) => {
          this.errorMessage = error.message;
          console.error("A aparut o eroare!", error);
        });
    },
  },
  mounted() {},
};
</script>

<style>
.card-size {
  flex: 1;
  min-width: 30em;
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