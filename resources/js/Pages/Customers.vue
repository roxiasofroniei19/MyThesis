<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Clienti
        <Button
          class="ml-3"
          @click="toggleNewAppointmentModal()"
          v-if="$page.props.user.isAdmin"
          >Adauga un student</Button
        >
      </h2>
    </template>
    <div
      class="shadow max-w-7xl mx-auto my-4 py-3 sm:px-6 lg:px-8 bg-green-100"
      v-if="notificationStatus == 200"
    >
      Studentul a fost adaugat cu succes!
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
        <div class="max-w-7xl mx-auto p-6">
          <div class="text-lg my-2">Cauta un student</div>
          <div class="flex flex-wrap">
            <div class="m-1">
              <div class="mt-1 relative rounded-md shadow-sm">
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                ></div>
                <input
                  type="text"
                  name="nameInput"
                  id="nameInput"
                  placeholder="Cautare dupa nume"
                  class="focus:ring-red-500 focus:border-red-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                  v-model="customerSearch"
                  v-on:input="searchByCustomerName()"
                />
              </div>
            </div>
            <div class="ml-4 m-1">
              <div class="mt-1 relative rounded-md shadow-sm">
                <div
                  class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                ></div>
                <input
                  type="text"
                  name="pageInput"
                  id="pageInput"
                  placeholder="ex: 10"
                  class="focus:ring-red-500 focus:border-red-500 block w-3/6 pl-7 pr-1 sm:text-sm border-gray-300 rounded-md"
                  v-model="pageToJumpTo"
                  v-on:change="loadCustomersPage(pageToJumpTo)"
                />
                <div class="absolute inset-y-0 right-0 flex items-center">
                  <label for="jumpToPage" class="sr-only">jumpToPage</label>
                  <button
                    type="button"
                    id="jumpToPage"
                    name="jumpToPage"
                    class="focus:ring-red-500 focus:border-red-500 h-full py-0 px-2 border-transparent bg-red-500 text-white sm:text-sm rounded-md"
                  >
                    Sari la pagina
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="max-w-7xl mx-auto flex flex-wrap">
        <div class="mx-auto my-4" v-if="results > 0 && loaded">
          <SecondaryButton
            class="m-1"
            v-on:click="previousPage()"
            :disabled="currentPageNumber < 2 ? true : false"
            >Inapoi</SecondaryButton
          >
          Pagina {{ currentPageNumber }} / {{ totalPageNumber }}
          <SecondaryButton
            class="m-1"
            v-on:click="nextPage()"
            :disabled="currentPageNumber == totalPageNumber ? true : false"
            >Inainte</SecondaryButton
          >
        </div>
        <div class="mx-auto" v-if="results < 1 && loaded">
          Nu exista rezultate.
        </div>
      </div>
      <div class="max-w-7xl mx-auto flex flex-wrap" v-if="loaded">
        <ClientCard
          v-for="customer in customersDisplayed"
          :key="customer.ClientId"
          :clientId="customer.ClientId"
          :currentUserId="userId"
          @deleteClient="removeFromList"
        >
          <template #title>{{ customer.ClientName }}</template>
          <template #content>
            <ul>
              <li>
                <span class="font-bold">Nume:</span> {{ customer.ClientName }}
              </li>
              <li>
                <span class="font-bold">Nr. Telefon:</span>
                {{ customer.ClientPhone }}
              </li>
              <li>
                <span class="font-bold">Email:</span>
                {{ customer.ClientEmail }}
              </li>
              <li>
                <span class="font-bold">Specializare:</span>
                {{ customer.ClientVATNumber }}
              </li>
              <li>
                <span class="font-bold">ID Student:</span>
                {{ customer.ClientRegistrationNumber }}
              </li>
            </ul>
          </template>
        </ClientCard>
      </div>
      <div class="max-w-7xl mx-auto flex flex-wrap" v-if="!loaded">
        <Spinner class="mx-auto"></Spinner>
      </div>
      <div
        class="max-w-7xl mx-auto flex flex-wrap"
        v-if="results > 0 && loaded"
      >
        <div class="mx-auto my-4">
          <SecondaryButton
            class="m-1"
            v-on:click="previousPage()"
            :disabled="currentPageNumber < 2 ? true : false"
            >Inapoi</SecondaryButton
          >
          Pagina {{ currentPageNumber }} / {{ totalPageNumber }}
          <SecondaryButton
            class="m-1"
            v-on:click="nextPage()"
            :disabled="currentPageNumber == totalPageNumber ? true : false"
            >Inainte</SecondaryButton
          >
        </div>
      </div>
    </div>
  </app-layout>

  <Modal :show="newAppointmentModalStatus">
    <div class="p-4">
      <div class="w-full h-25 text-lg py-3">Adauga un student</div>
      <div class="flex flex-wrap py-3 border-t-2">
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
            >Adresa student</span
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
            >ID Student:</span
          >
          <input
            type="text"
            class="inline-block w-3/6"
            v-model="formClientCIF"
          />
        </div>
      </div>
      <span class="mt-4"><i>Campurile marcate cu * sunt obligatorii</i></span>
      <div class="py-3 border-t-2 flex justify-end">
        <Button class="mr-3" @click="addNewClient()">Salveaza</Button>
        <DangerButton @click="toggleNewAppointmentModal()"
          >Renunta</DangerButton
        >
      </div>
    </div>
  </Modal>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ClientCard from "@/Components/ClientCard";
import Button from "@/Jetstream/Button";
import Modal from "@/Jetstream/Modal";
import DangerButton from "@/Jetstream/DangerButton.vue";
import SecondaryButton from "@/Jetstream/SecondaryButton.vue";
import Spinner from "@/Components/Spinner";

export default {
  components: {
    AppLayout,
    ClientCard,
    Button,
    Modal,
    DangerButton,
    SecondaryButton,
    Spinner,
  },
  data() {
    return {
      time1: null,
      time2: null,
      time3: null,
      newAppointmentModalStatus: false,
      customers: [],
      customersDisplayed: [],
      results: 0,
      currentPageNumber: 0,
      totalPageNumber: 0,
      pageToJumpTo: null,
      customerSearch: null,
      loaded: false,
      userId: null,

      formClientName: null,
      formClientAddress: null,
      formClientPhone: null,
      formClientCUI: null,
      formClientCIF: null,

      notificationStatus: null,
    };
  },
  methods: {
    toggleNewAppointmentModal() {
      this.newAppointmentModalStatus = !this.newAppointmentModalStatus;
    },
    loadCustomers() {
      (async () => {
        try {
          axios.get("/getAllCustomers").then((response) => {
            this.customers = [];
            this.customers.push(response.data.customers);
            this.results = this.customers[0].length;
            this.totalPageNumber = Math.ceil(this.results / 20);
            this.currentPageNumber = 1;
            this.loadCustomersPage(this.currentPageNumber);
            this.loaded = true;
          });
        } catch (err) {
          console.log(err);
        }
      })();
    },
    loadCustomersPage(pageNumber) {
      if (
        pageNumber != null &&
        pageNumber > 0 &&
        pageNumber <= this.totalPageNumber
      ) {
        this.currentPageNumber = pageNumber;

        this.customersDisplayed = this.customers[0].slice(
          (pageNumber - 1) * 20,
          pageNumber * 20
        );
      }
    },
    previousPage() {
      if (this.currentPageNumber > 1) {
        this.currentPageNumber = this.currentPageNumber - 1;
        this.loadCustomersPage(this.currentPageNumber);
        this.scrollToTop();
      }
    },
    nextPage() {
      if (this.currentPageNumber < this.totalPageNumber) {
        this.currentPageNumber = this.currentPageNumber + 1;
        this.loadCustomersPage(this.currentPageNumber);
        this.scrollToTop();
      }
    },
    reloadPageNumbers() {
      this.totalPageNumber = Math.ceil(this.results / 20);
      this.currentPageNumber = 1;
    },
    searchByCustomerName() {
      this.customersDisplayed = [];
      const exprToSearch = new RegExp(this.customerSearch, "i");
      this.customers[0].forEach((customer) => {
        console.log(customer.ClientName.includes(this.customerSearch));
        if (exprToSearch.test(customer.ClientName)) {
          this.customersDisplayed.push(customer);
        }
      });
      this.results = this.customersDisplayed.length;
      this.reloadPageNumbers();
    },
    scrollToTop() {
      window.scrollTo(0, 0);
    },
    addNewClient() {
      const payload = {
        clientName: this.formClientName,
        clientAddress: this.formClientAddress,
        clientPhone: this.formClientPhone,
        clientCUI: this.formClientCUI,
        clientCIF: this.formClientCIF,
      };
      axios
        .post("/addNewCustomer", payload)
        .then((response) => {
          this.toggleNewAppointmentModal();
          this.loadCustomers();
          this.notificationStatus = response.status;
        })
        .catch((error) => {
          this.toggleNewAppointmentModal();
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
    removeFromList(id) {
      this.customers[0].forEach((customer) => {
        if (customer.ClientId == id) {
          const itemIndex = this.customers[0].indexOf(customer);
          if (itemIndex !== -1) {
            this.customers[0].splice(itemIndex, 1);
            this.loadCustomers();
            this.reloadPageNumbers();
          }
        }
      });
    },
  },
  beforeMount() {
    this.getUserId();
  },
  mounted() {
    this.loadCustomers();
  },
};
</script>

<style scoped>
input[type="radio"] + label span {
  transition: background 0.2s, transform 0.2s;
}

input[type="radio"] + label span:hover,
input[type="radio"] + label:hover span {
  transform: scale(1.2);
}

input[type="radio"]:checked + label span {
  background-color: #3490dc;
  box-shadow: 0px 0px 0px 2px white inset;
}

input[type="radio"]:checked + label {
  color: #3490dc;
}
</style>
