<template>
  <div class="container mx-auto p-6">
    <!-- En-tête de la page -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
      <h2 class="text-3xl font-semibold text-gray-800">Détails du Manager</h2>
      <div class="flex items-center mt-4">
        <img :src="Person" alt="Avatar" class="rounded-full w-16 h-16 object-cover mr-4" />
        <div>
          <h3 class="text-2xl font-semibold text-gray-800">{{ manager.name }}</h3>
          <p class="text-lg text-gray-600">{{ manager.email }}</p>
          <p class="text-sm text-gray-500">{{ manager.phone }}</p>
        </div>
      </div>
    </div>

    <!-- Section des informations supplémentaires -->
    <div class="bg-white shadow-md rounded-lg p-6">
      <h3 class="text-xl font-semibold text-gray-800 mb-4">Informations supplémentaires</h3>
      <p class="text-lg text-gray-700"><strong>Adresse :</strong> {{ manager.address ?? 'Non spécifiée' }}</p>
      <p class="text-lg text-gray-700"><strong>Rôle :</strong> {{ manager.role }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppDatas from '../../Services/app.js'
import { useRoute } from 'vue-router';
import Person from '../../Assets/Images/person.png'

const manager = ref({
  name: '',
  email: '',
  phone: '',
  address: null,
  role: '',
});

const route = useRoute();

const formatDate = (date) => {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  return new Date(date).toLocaleDateString('fr-FR', options);
};

// Récupération des détails du manager depuis l'API
const fetchManagerDetails = async (managerId) => {
  try {
    const response = await axios.get(`${AppDatas.baseUrl}/managers/${managerId}`);
    manager.value = response.data;
    console.log(manager.value)
  } catch (error) {
    console.error("Erreur lors de la récupération des détails du manager", error);
  }
};

onMounted(() => {
  const managerId = route.params.id; 
  fetchManagerDetails(managerId);
});
</script>

<style scoped>
/* Styles personnalisés pour la page de détails du manager */
.container {
  max-width: 1200px;
}
</style>
