<template>
    <Layout>
      <div class="p-6 max-w-6xl mx-auto">
      <h2 class="text-lg font-semibold mb-4">Liste des Managers</h2>
  
      <!-- Barre de recherche -->
      <input
        v-model="search"
        type="text"
        placeholder="Rechercher un manager..."
        class="p-2 border rounded w-full mb-4"
      />
  
      <!-- Table des clients -->
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-4 py-2 cursor-pointer" @click="sortBy('id')">ID ⬍</th>
              <th class="px-4 py-2 cursor-pointer" @click="sortBy('name')">Nom ⬍</th>
              <th class="px-4 py-2 cursor-pointer" @click="sortBy('email')">Email ⬍</th>
              <th class="px-4 py-2 cursor-pointer" @click="sortBy('phone')">Téléphone ⬍</th>
              <th class="px-4 py-2 cursor-pointer" @click="sortBy('role')">Role ⬍</th>
              <th class="px-4 py-2 cursor-pointer">Action ⬍</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="manager in paginatedManagers" :key="manager.id" class="border-t"  :class="(user.email === manager.email) ? 'bg-indigo-300' : ''">
                <td class="px-4 py-2">{{ manager.id }}</td>
                <td class="px-4 py-2 font-bold">{{ manager.name }}</td>
                <td class="px-4 py-2">{{ manager.email }}</td>
                <td class="px-4 py-2 text-center">{{ manager.phone ?? '---------'}}</td>
                <td class="px-4 py-2">
                    <span v-if="manager.role === 'admin'" class="bg-slate-800 text-white px-2 py-1 rounded">Admin</span>
                    <span v-else class="bg-slate-400 text-gray-950 px-2 py-1 rounded">Manager</span>
                </td>
                <td class="flex px-4 py-2 space-x-3">
                    <router-link
                    :to="`/managers/${manager.id}`"
                    class="bg-indigo-600 text-white px-1 py-1 rounded-lg text-sm hover:bg-indigo-700"
                    >
                    <Eye class="w-4 h-4" />
                    </router-link>
                    <button
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
    </Layout>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from "vue";
  import axios from "axios";
  import AppDatas from "../../Services/app.js";
  import Layout from "../../Layouts/AppLayout.vue";
  import { Eye, Trash } from "lucide-vue-next";
  import Swal from "sweetalert2";
  
  const baseUrl = AppDatas.baseUrl;
  const managers = ref([]);
  const search = ref("");
  const currentPage = ref(1);
  const itemsPerPage = 5;
  const sortKey = ref("id");
  const sortOrder = ref(1);
  const user = ref({});
  
  // Charger les managers depuis l'API
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
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`
            }
        });
        user.value = response.data;
        console.log("Données de l'utilisateur :", user.value);
    } catch (error) {
        console.error("Erreur lors de la récupération des données de l'utilisateur", error);
    }
};
  
  onMounted(() => {
    fetchManagers();
  });
  
  // Filtrer la liste des clients
  const filteredManagers = computed(() => {
    return managers.value.filter((manager) =>
      manager.name.toLowerCase().includes(search.value.toLowerCase()) ||
      manager.email.toLowerCase().includes(search.value.toLowerCase()) ||
      manager.phone.toLowerCase().includes(search.value.toLowerCase()) 
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
  
  </script>
  
  <style scoped>
  th {
    cursor: pointer;
  }
  th:hover {
    background-color: #f3f4f6;
  }
  </style>
  