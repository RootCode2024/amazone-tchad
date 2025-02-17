<template>
    <Layout>
      <div class="p-6 max-w-4xl mx-auto">
        <div class="text-center mb-6">
          <h1 class="text-2xl font-semibold">{{ customer.name }}</h1>
          <p class="text-sm text-gray-500">{{ customer.email }}</p>
        </div>
  
        <!-- Informations du client -->
        <div class="bg-white p-6 shadow rounded-lg">
          <h2 class="text-xl font-semibold mb-4">Informations du client</h2>
          <p><strong>Nom:</strong> {{ customer.name }}</p>
          <p><strong>Email:</strong> {{ customer.email }}</p>
          <p><strong>Téléphone:</strong> {{ customer.phone }}</p>
          <p><strong>Adresse:</strong> {{ customer.address ?? 'Non Spécifié' }}</p>
        </div>
  
        <!-- Réservations du client -->
        <div class="bg-white p-6 mt-6 shadow rounded-lg">
          <h2 class="text-xl font-semibold mb-4">Réservations</h2>
  
          <!-- Vols -->
          <div v-if="customer.flights.length > 0" class="text-center mt-4">
            <h3 class="text-lg font-medium mb-2">Vols</h3>
            <table class="w-full table-auto border-collapse">
              <thead>
                <tr>
                  <th class="border p-2">Type</th>
                  <th class="border p-2">Ville de départ</th>
                  <th class="border p-2">Ville d'arrivée</th>
                  <th class="border p-2">Date de départ</th>
                  <th class="border p-2">Date de retour</th>
                  <th class="border p-2">Classe</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="flight in customer.flights" :key="flight.id">
                  <td class="border p-2">
                    <span v-if="flight.flight_type === 'one_way'">Aller Simple</span>
                    <span v-else-if="flight.flight_type === 'round_trip'">Aller Retour</span>
                    <span v-else>Multi Destination</span>
                  </td>
                  <td class="border p-2">{{ getCityName(flight.departure_city_id) }}</td>
                  <td class="border p-2">{{ getCityName(flight.destination_city_id) }}</td>
                  <td class="border p-2">{{ flight.departure_date }}</td>
                  <td class="border p-2">{{ flight.return_date || '----------' }}</td>
                  <td class="border p-2">
                    <span v-if="flight.flight_class === 'first_class'">Première</span>
                    <span v-else-if="flight.flight_class === 'business'">Affaire</span>
                    <span v-else>Economie</span>
                  </td>
                </tr>
              </tbody>
            </table>
        </div>
        <p v-else class="mt-4 text-center">Aucune réservation de vol disponible</p>
  
          <!-- Hôtels -->
          <div v-if="customer.hotels.length > 0">
          <h3 class="text-lg font-medium mb-2 mt-6">Hôtels</h3>
          <table class="w-full table-auto border-collapse">
            <thead>
              <tr>
                <th class="border p-2">Nom de l'hôtel</th>
                <th class="border p-2">Pays</th>
                <th class="border p-2">Date d'arrivée</th>
                <th class="border p-2">Date de départ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="hotel in customer.hotels" :key="hotel.id">
                <td class="border p-2">{{ hotel.name }}</td>
                <td class="border p-2">{{ hotel.country.name }}</td>
                <td class="border p-2">{{ hotel.arrival_date }}</td>
                <td class="border p-2">{{ hotel.return_date || 'Non applicable' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p v-else class="text-center mt-4">Aucune Reservation d'hotel disponible</p>
  
          <!-- Locations de voitures -->
        <div v-if="customer.carLocations == []">
          <h3 class="text-lg font-medium mb-2 mt-6">Locations de voitures</h3>
          <table class="w-full table-auto border-collapse">
            <thead>
              <tr>
                <th class="border p-2">Lieu de location</th>
                <th class="border p-2">Date de début</th>
                <th class="border p-2">Date de fin</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="car in customer.carLocations" :key="car.id">
                <td class="border p-2">{{ car.place_of_location }}</td>
                <td class="border p-2">{{ car.started_date }}</td>
                <td class="border p-2">{{ car.ended_date }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p v-else class="text-center mt-4">Aucune location de voiture disponible</p>
      </div>
    </div>
  </Layout>
</template>

<script setup>
  import { ref, onMounted, computed } from "vue";
  import axios from "axios";
  import { useRoute } from "vue-router";
  import AppDatas from "../../Services/app.js";
  import Layout from "../../Layouts/AppLayout.vue";
  
  // Variable de base URL de l'API
  const baseUrl = AppDatas.baseUrl;
  
  // Paramètre dynamique du routeur (id du client)
  const route = useRoute();
  const customerId = route.params.id;
  
  // Initialisation des données du client
  const customer = ref({
    name: "",
    email: "",
    phone: "",
    address: "",
    flights: [],
    hotels: [],
    flightHotels: [],
    carLocations: [],
  });
  const cities = ref([]);
  
  const loading = ref(true);
  const error = ref(null);
  
  // Récupérer les données du client
  const fetchCustomer = async () => {
    try {
      const response = await axios.get(`${baseUrl}/customers/${customerId}`);
        customer.value.address = response.data.data.address;
        customer.value.name = response.data.data.name;
        customer.value.phone = response.data.data.phone;
        customer.value.email = response.data.data.email;
        customer.value.flights = response.data.data.flights;
        customer.value.hotels = response.data.data.hotels;
        customer.value.carLocations = response.data.data.carLocations;
        customer.value.flightHotels = response.data.data.flightHotels;
        console.log(customer.value)
    } catch (err) {
      error.value = "Échec du chargement des données du client";
      console.error("Erreur lors de la récupération des données du client:", err);
    } finally {
      loading.value = false;
    }
  };

  const fetchCities = async () => {
    try {
      const response = await axios.get(`${baseUrl}/cities`);
      if (!response.data) throw new Error("Erreur lors du chargement des villes");
      cities.value = response.data;
    } catch (error) {
      console.error("Impossible de charger les villes:", error);
    }
  };

  const getCityName = (id) => {
    return computed(() => cities.value.find(city => city.id === id)?.name || "Ville non trouvée");
  };


  
  // Charger les données lors du montage du composant
  onMounted(async () => {
    await fetchCities();  // S'assurer que les villes sont chargées
    fetchCustomer();
  });

  </script>
  
<style scoped>
  /* Style personnalisé pour la page de détails du client */
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    padding: 8px;
    border: 1px solid #ddd;
  }
  th {
    text-align: left;
    background-color: #f4f4f4;
  }
</style>
  