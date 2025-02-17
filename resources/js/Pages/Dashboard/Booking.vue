<template>
  <Layout>
    <div class="p-6">
      <h1 class="text-3xl font-bold mb-6">📋 Gestion des Réservations</h1>

      <!-- Onglets -->
      <div class="flex space-x-4 border-b mb-6">
        <button
          v-for="tab in tabs"
          :key="tab.name"
          @click="activeTab = tab.name"
          class="px-6 py-3 text-lg font-semibold border-b-4 transition"
          :class="activeTab === tab.name ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-600'"
        >
          <component :is="tab.icon" class="inline-block w-5 h-5 mr-2" />
          {{ tab.label }}
        </button>
      </div>

      <!-- Contenu des Onglets -->
      <div class="bg-white p-6 rounded-lg shadow-md">
        <Flights v-if="activeTab === 'flights'" :flights="flightsDatas" />
        <Hotels v-if="activeTab === 'hotels'" :hotels="hotelsDatas" />
        <FlightHotel v-if="activeTab === 'flightHotel'" :flighthotels="flightHotelsDatas" />
        <CarLocation v-if="activeTab === 'carLocation'" :carLocations="carLocationsDatas" />
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Layout from "../../Layouts/AppLayout.vue";
import Flights from "../../Components/BookingComponents/Flight.vue";
import Hotels from "../../Components/BookingComponents/Hotel.vue";
import FlightHotel from "../../Components/BookingComponents/FlightHotel.vue";
import CarLocation from "../../Components/BookingComponents/CarLocation.vue";
import { Plane, Hotel, Package, Car } from "lucide-vue-next";
import axios from "axios";
import AppDatas from "../../Services/app.js";

// Définition des onglets avec icônes
const tabs = [
  { name: "flights", label: "Vols", icon: Plane },
  { name: "hotels", label: "Hôtels", icon: Hotel },
  { name: "flightHotel", label: "Vol + Hôtel", icon: Package },
  { name: "carLocation", label: "Location Voiture", icon: Car }
];

const activeTab = ref("flights");

const flightsDatas = ref([]);
const hotelsDatas = ref([]);
const flightHotelsDatas = ref([]);
const carLocationsDatas = ref([]);

// Fonction pour récupérer les données des réservations
const fetchData = async () => {
  try {
    const flightResponse = await axios.get(`${AppDatas.baseUrl}/flights`);
    const hotelResponse = await axios.get(`${AppDatas.baseUrl}/hotels`);
    const flightHotelResponse = await axios.get(`${AppDatas.baseUrl}/flight-hotel`);
    const carLocationResponse = await axios.get(`${AppDatas.baseUrl}/car-locations`);

    flightsDatas.value = flightResponse.data;
    hotelsDatas.value = hotelResponse.data;
    flightHotelsDatas.value = flightHotelResponse.data;
    carLocationsDatas.value = carLocationResponse.data;
    console.log("Données des vols :", flightsDatas.value);
  } catch (error) {
    console.error("Erreur lors de la récupération des données", error);
  }
};

// Charger les données au moment où le composant est monté
onMounted(() => {
  try {
   fetchData();
  } catch (error) {
    console.error('Erreur lors de la récupération des vols', error);
  }
});
</script>

<style scoped>
/* Style supplémentaire si nécessaire */
</style>
