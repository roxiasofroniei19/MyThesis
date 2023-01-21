<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Administrare conturi
      </h2>
    </template>

    <div>
      <div
        class="shadow max-w-7xl mx-auto my-4 py-3 sm:px-6 lg:px-8 bg-green-100"
        v-if="notificationStatus == 200"
      >
        Utilizatorul a fost adaugat cu succes!
      </div>
      <div
        class="shadow max-w-7xl mx-auto my-4 py-3 sm:px-6 lg:px-8 bg-red-100"
        v-if="notificationStatus != 200 && notificationStatus != null"
      >
        Se pare ca a aparut o problema. Verifica datele introduse si reincearca.
      </div>
      <div
        class="max-w-7xl mx-auto mt-4 py-10 sm:px-6 lg:px-8 bg-white flex flex-wrap"
      >
        <span class="text-lg w-full">Adauga un cont nou</span>
        <div class="m-2">
          <label for="name" class="mr-2">Nume</label>
          <input
            v-model="userName"
            type="text"
            name="name"
            id="name"
            class="border-0 border-b-2 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent"
          />
        </div>
        <div class="m-2">
          <label for="name" class="mr-2">Email</label>
          <input
            v-model="userMail"
            type="text"
            name="Email"
            id="Email"
            class="border-0 border-b-2 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent"
          />
        </div>
        <Button class="m-2" @click="addNewUser()">Adauga</Button>
        <span class="w-full mt-6"
          ><i
            >Parola pentru conturile noi este "MyThesisUVT". Este puternic
            recomandat sa fie schimbata!</i
          ></span
        >
      </div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div class="flex flex-col">
          <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
              class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
            >
              <div
                class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg"
              >
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
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
                        Drepturi
                      </th>
                      <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Sterge</span>
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="person in people[0]" :key="person.email">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                              {{ person.name }}
                            </div>
                            <div class="text-sm text-gray-500">
                              {{ person.email }}
                            </div>
                          </div>
                        </div>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                      >
                        <span v-if="person.isAdmin">Administrator</span>
                        <span v-else>Angajat</span>
                      </td>
                      <td
                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                      >
                        <DangerButton v-if="person.id != $page.props.user.id" @click="deleteUser(person.id)">Sterge</DangerButton>
                      </td>
                    </tr>
                  </tbody>
                </table>
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
import DangerButton from "@/Jetstream/DangerButton";
import Button from "@/Jetstream/Button";

export default {
  props: ["sessions"],

  components: {
    AppLayout,
    DangerButton,
    Button,
  },

  data() {
    return {
      people: [],
      userName: null,
      userMail: null,
      notificationStatus: null,
    };
  },

  methods: {
    getAllUsers() {
      (async () => {
        try {
          axios.get("/getAllUsers").then((response) => {
            this.people = [];
            this.people.push(response.data.users);
            console.log(this.people[0]);
          });
        } catch (err) {
          console.log(err);
        }
      })();
    },
    addNewUser() {
      const payload = {
        userName: this.userName,
        userMail: this.userMail,
      };
      axios
        .post("/addNewUser", payload)
        .then((response) => {
          this.notificationStatus = response.status;
          this.getAllUsers();
        })
        .catch((error) => {
          this.errorMessage = error.message;
          this.notificationStatus = 500;
          console.error("A aparut o eroare!", error);
        });
    },
    deleteUser(id) {
       const payload = {
        userId: id,
      };
      axios
        .post("/deleteUser", payload)
        .then((response) => {
          this.getAllUsers();
        })
        .catch((error) => {
          this.errorMessage = error.message;
          console.error("A aparut o eroare!", error);
        });
    }
  },
  mounted() {
    this.getAllUsers();
  },
};
</script>
