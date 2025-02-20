<template>
  <div class="p-6 max-w-6xl mx-auto">
    <h2 class="text-2xl font-bold mb-4">Liste des Managers</h2>

    <!-- Barre de recherche -->
    <div class="flex justify-between space-x-5">
        <div class=" w-3/4 left-0">
          <input
            v-model="search"
            type="text"
            id="search"
            placeholder="Rechercher un manager..."
            class="p-2 border rounded mb-4 w-full"
          />
        </div>
        <div class="w-1/4 flex items-center justify-end" v-if="user.role === 'admin'">
          <button @click="openAddModal()" class="flex bg-teal-950 hover:bg-teal-800 text-white text-sm py-1 px-2 rounded" tooltip="Ajouter un manager"><Plus /></button>
        </div>
    </div>

    <!-- Table des clients -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-200">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-4 py-2 cursor-pointer" @click="sortBy('id')">ID ⬍</th>
            <th class="px-4 py-2 cursor-pointer" @click="sortBy('name')">Nom ⬍</th>
            <th class="px-4 py-2 cursor-pointer" @click="sortBy('email')">Email ⬍</th>
            <th class="px-4 py-2 cursor-pointer" @click="sortBy('role')">Role ⬍</th>
            <th class="px-4 py-2 cursor-pointer">Action ⬍</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="manager in paginatedManagers" :key="manager.id" class="border-t"  :class="(user.email === manager.email) ? 'bg-indigo-300' : ''">
              <td class="px-4 py-2">{{ manager.id }}</td>
              <td class="px-4 py-2 font-bold">{{ manager.name }}</td>
              <td class="px-4 py-2">{{ manager.email }}</td>
              <td class="px-4 py-2">
                  <span v-if="manager.role === 'admin'" class="bg-slate-800 text-white px-2 py-1 rounded">Admin</span>
                  <span v-else class="bg-slate-400 text-gray-950 px-2 py-1 rounded">Manager</span>
              </td>
              <td class="flex px-4 py-2 space-x-3">
                  <router-link
                   v-if="manager.role !== 'admin'"
                  :to="`/dashboard/managers/${manager.id}`"
                  class="bg-indigo-600 text-white px-1 py-1 rounded-lg text-sm hover:bg-indigo-700"
                  >
                  <Eye class="w-4 h-4" />
                  </router-link>
                  <button
                   v-if="manager.role !== 'admin'"
                  @click="confirmDelete(manager)"
                  class="bg-red-600 text-white px-1 py-1 rounded-lg text-sm hover:bg-red-700"
                  >
                  <Trash class="w-4 h-4" />
                  </button>
              </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 bg-gray-300 rounded">
        Précédent
      </button>
      <span>Page {{ currentPage }} / {{ totalPages }}</span>
      <button @click="nextPage" :disabled="currentPage >= totalPages" class="px-4 py-2 bg-gray-300 rounded">
        Suivant
      </button>
    </div>
  </div>

  <!-- 🚀 MODALE -->
  <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
      <h2 class="text-xl font-bold mb-4">Ajouter un manager :</h2>

      <form @submit.prevent="storeManager" class="mt-4">
        <div class="py-2">
          <label for="name">Nom Complet</label>
          <input type="text" id="name" placeholder="Entrer un nom" v-model="newManager.name" class="w-full border p-2 rounded-lg" />
        </div>
        <div class="py-2">
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="Entrer un email" v-model="newManager.email" class="w-full border p-2 rounded-lg" />
        </div>
        <div class="py-2">
          <label for="address">Adresse</label>
          <input type="text" id="address" placeholder="Entrer une adresse" v-model="newManager.address" class="w-full border p-2 rounded-lg" />
        </div>
        <div class="flex justify-end mt-4">
          <button @click="closeModal" class="px-4 py-2 bg-gray-300 rounded-lg mr-2">Annuler</button>
          <button type="submit" :disabled="buttonLoading" class="px-4 py-2 min-w-28 bg-blue-600 text-white rounded-lg">
            <span v-if="!buttonLoading">Enregistrer</span>
            <div v-else class="flex items-center">
              <svg class="mr-3 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span class="font-medium"> Enregistrement ... </span>
            </div>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
  
<script setup>
  import { ref, computed, onMounted, watchEffect } from "vue";
  import axios from "axios";
  import AppDatas from "../../Services/app.js";
  import { Eye, Trash, Plus } from "lucide-vue-next";
  import Swal from "sweetalert2";
  
  const isModalOpen = ref(false);
  const baseUrl = AppDatas.baseUrl;
  const managers = ref([]);
  const search = ref("");
  const currentPage = ref(1);
  const itemsPerPage = 5;
  const sortKey = ref("id");
  const sortOrder = ref(1);
  const authUser = ref({})
  const user = ref({ id: '',name: '', email: '', address: '', role: '' })
  const buttonLoading = ref(false);
  const errorMessage = ref('');

  const newManager = ref({
    name: "",
    email: "",
    password: "",
    address: ""
  });

  
  const openAddModal = () => {
    isModalOpen.value = true;

  };

  
  const closeModal = () => {
    isModalOpen.value = false;
  };

  
  const storeManager = async () => {

    if (newManager.value.name === "" || newManager.value.email === "")
    {
      errorMessage.value = "Nom et Email requis";
      return;
    }

    let payload = {
      name: newManager.value.name,
      email: newManager.value.email,
      address: newManager.value.address,
    };


    try {
      buttonLoading.value = true;
      const response = await axios.post(
        `${AppDatas.baseUrl}/managers`,
        payload
      );
      
      fetchManagers();
      
      closeModal();
    } catch (error) {
      console.error("Erreur lors de l\'ajout.", error);
      errorMessage.value = "Erreur lors de l\'ajout du manager. Veuillez réessayer.";
    } finally {
      buttonLoading.value = false;
      toastFunction("success", "Yes, un nouveau dans la team !");
    }
  };

  
  const fetchManagers = async () => {
    try {
      const response = await axios.get(`${baseUrl}/managers`);
      managers.value = response.data;
    } catch (error) {
      console.error("Erreur lors du chargement des managers", error);
    }
  };

  
  const getUserInfo = async () => {
      try {
          const response = await axios.get(`${AppDatas.baseUrl}/user`, {
              headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
          })
          authUser.value = response.data
      } catch (error) {
          console.error("Erreur lors de la récupération des données de l'utilisateur", error)
      }
  }

  watchEffect(() => {
      if (authUser.value && Object.keys(authUser.value).length > 0) {
          user.value = {
              id: authUser.value.id || '',
              name: authUser.value.name || '',
              email: authUser.value.email || '',
              address: authUser.value.address || '',
              role: authUser.value.role || '',
          }
      }

      
  
  console.log(user.value)
  })
  
  onMounted(() => {
    fetchManagers();
    getUserInfo()

  });
  
  // Filtrer la liste des clients
  const filteredManagers = computed(() => {
    return managers.value.filter((manager) =>
      manager.name.toLowerCase().includes(search.value.toLowerCase()) ||
      manager.email.toLowerCase().includes(search.value.toLowerCase())
    );
  });
  
  // Trier les clients dynamiquement
  const sortedManagers = computed(() => {
    return [...filteredManagers.value].sort((a, b) => {
      if (a[sortKey.value] < b[sortKey.value]) return -1 * sortOrder.value;
      if (a[sortKey.value] > b[sortKey.value]) return 1 * sortOrder.value;
      return 0;
    });
  });
  
  // Pagination
  const totalPages = computed(() => Math.ceil(sortedManagers.value.length / itemsPerPage));
  const paginatedManagers = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    return sortedManagers.value.slice(start, start + itemsPerPage);
  });
  
  const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
  };
  
  const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
  };
  
  const sortBy = (key) => {
    if (sortKey.value === key) {
      sortOrder.value *= -1; // Inverser le tri
    } else {
      sortKey.value = key;
      sortOrder.value = 1;
    }
  };
  
    // ⚡ Fonction pour supprimer un manager
    const confirmDelete = async (manager) => {
      Swal.fire({
          title: "Êtes-vous sûr ?",
          text: `Voulez-vous vraiment supprimer le manager ${manager.name} ?`,
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#3085d6",
          confirmButtonText: "Oui, supprimer !",
          cancelButtonText: "Annuler",
      }).then(async (result) => {
          if (result.isConfirmed) {
              try {
                  await axios.delete(`${AppDatas.baseUrl}/managers/${manager.id}`);
  
                  Swal.fire({
                      title: "Supprimé !",
                      text: "Le Manager a été supprimé avec succès.",
                      icon: "success",
                      timer: 2000,
                      showConfirmButton: false,
                  });
  
                  // Rafraîchir la liste après suppression en utilisant filter
                  managers.value = managers.value.filter(c => c.id !== manager.id);
              } catch (error) {
                  Swal.fire({
                      title: "Erreur",
                      text: "Une erreur s'est produite lors de la suppression.",
                      icon: "error",
                  });
                  console.error("Erreur lors de la suppression", error);
              }
          }
      });
  };

  const toastFunction = (type, message) => {
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Toast.fire({
      icon: type,
      title: message
    });
  };
  
  // Charger les infos utilisateur au démarrage
  getUserInfo()

</script>
  
  <style scoped>
  th {
    cursor: pointer;
  }
  th:hover {
    background-color: #f3f4f6;
  }
  </style>
  