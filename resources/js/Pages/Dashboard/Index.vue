<template>
  <div v-if="isLoading" class="flex justify-center items-center min-h-[300px]">
    <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-blue-500"></div>
  </div>
  <div v-else>
      <!-- Message de bienvenue -->
      <div class="mb-6 p-6 bg-blue-600 text-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold">👋 Heureux de vous revoir, <span class="italic">Admin</span> !</h1>
        <p class="text-lg mt-2">{{ motivationMessage }}</p>
      </div>

      <!-- Statistiques principales -->
      <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
          <div class="w-full px-4 py-5 bg-white rounded-lg shadow-xl">
              <div class="text-sm font-medium text-gray-500 truncate">
                  Réservations
              </div>
              <div class="mt-1 text-3xl font-semibold text-gray-900">
                  <!-- Vérification si stats est défini avant d'y accéder -->
                  {{ stats?.reservations || 0 }}
              </div>
          </div>
          <div class="w-full px-4 py-5 bg-white rounded-lg shadow-xl">
              <div class="text-sm font-medium text-gray-500 truncate">
                  Clients
              </div>
              <div class="mt-1 text-3xl font-semibold text-gray-900">
                  {{ stats?.customers || 0 }}
              </div>
          </div>
          <div class="w-full px-4 py-5 bg-white rounded-lg shadow-xl">
              <div class="text-sm font-medium text-gray-500 truncate">
                  Managers
              </div>
              <div class="mt-1 text-3xl font-semibold text-gray-900">
                  {{ stats?.managers || 0 }}
              </div>
          </div>
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { PlaneTakeoff, Hotel, Car, ListChecks, Plus, BarChartBig } from 'lucide-vue-next';
import axios from 'axios';
import AppDatas from '../../Services/app.js'

const isLoading = ref(false)

const motivationMessages = [
  "Chaque réservation est une nouvelle aventure en route ! 🚀",
  "Continuez à offrir des expériences de voyage inoubliables ! 🌍",
  "Votre gestion fait toute la différence. Bravo ! 💪",
  "Le succès est une question de persévérance. Continuez comme ça ! 🔥"
];

const motivationMessage = ref(motivationMessages[Math.floor(Math.random() * motivationMessages.length)]);

// Initialisation de stats avec un objet vide au lieu de null
const stats = ref({
  reservations: 0,
  customers: 0,
  managers: 0
});

const fetchDatas = async () => {
  isLoading.value = true
  try {
    const response = await axios.get(`${AppDatas.baseUrl}/dashboard`);
    stats.value = response.data;
  } catch (error) {
    console.error("Erreur lors de la récupération des données :", error);
  } finally {
    isLoading.value = false
  }
};

onMounted(fetchDatas);
</script>
