<template>
  <div v-if="loading">
    <Loader />
  </div>
  <div v-else>
    <Layout>
      <!-- Message de bienvenue -->
      <div class="mb-6 p-6 bg-blue-600 text-white rounded-lg shadow-md">
        <h1 class="text-3xl font-bold">👋 Heureux de vous revoir, <span class="italic">Admin</span> !</h1>
        <p class="text-lg mt-2">{{ motivationMessage }}</p>
      </div>

      <!-- Statistiques principales -->
      <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 truncate">
                        Total Réservations
                    </div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ stats.reservations }}
                    </div>
                </div>
                <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 truncate">
                        Total Clients
                    </div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ stats.customers }}
                    </div>
                </div>
                <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 truncate">
                        Total Manager
                    </div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        {{ stats.managers }}
                    </div>
                </div>
            </div>

    </Layout>
  </div>
</template>

<script setup>
import Layout from '../../Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import Loader from '../../Components/Loader.vue';
import { PlaneTakeoff, Hotel, Car, ListChecks, Plus, BarChartBig } from 'lucide-vue-next';
import axios from 'axios';
import AppDatas from '../../Services/app.js'


const motivationMessages = [
  "Chaque réservation est une nouvelle aventure en route ! 🚀",
  "Continuez à offrir des expériences de voyage inoubliables ! 🌍",
  "Votre gestion fait toute la différence. Bravo ! 💪",
  "Le succès est une question de persévérance. Continuez comme ça ! 🔥"
];

const motivationMessage = ref(motivationMessages[Math.floor(Math.random() * motivationMessages.length)]);

const stats = ref(null);
const loading = ref(true);

const fetchDatas = async () => {
  try {
    const response = await axios.get(`${AppDatas.baseUrl}/dashboard`);
    stats.value = response.data;
    console.log(stats.value);
  } catch (error) {
    console.error("Erreur lors de la récupération des données :", error);
  } finally {
    loading.value = false;
  }
};

onMounted(fetchDatas);
</script>
