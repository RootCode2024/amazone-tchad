<template>
    <div class="p-6">
      <!-- Titre principal centré -->
      <h1 class="text-4xl font-extrabold text-center mb-8 text-gray-800">
        Détails de la réservation de l'hotel
      </h1>
  
      <!-- Loader (affiché pendant le chargement des données) -->
      <div v-if="isLoading" class="flex justify-center items-center min-h-[300px]">
        <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-blue-500"></div>
      </div>
  
      <!-- Message d'erreur -->
      <div v-else-if="error" class="text-center text-red-600 text-lg font-semibold">
        {{ error }}
      </div>
  
      <!-- Contenu de la réservation -->
      <div v-else class="bg-white p-4 rounded-xl shadow-xl mx-auto border border-gray-100">
        <!-- En-tête avec un séparateur -->
        <div class="border-b pb-4 mb-6">
          <h2 class="text-3xl font-bold text-gray-700">
            Réservation #{{ hotel.id }}
          </h2>
        </div>
  
        <!-- Détails de la réservation en deux colonnes sur écran moyen et plus -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-gray-700">
          <div>
            <div class="flex items-center mb-4">
              <User class="w-5 h-5 text-indigo-600 mr-2" />
              <span class="font-semibold mr-2">Client : </span>
              <p class="font-bold italic text-gray-950">{{ hotel.customer.name }}</p>
            </div>
            <div class="flex items-center mb-4">
              <Mail class="w-5 h-5 text-indigo-600 mr-2" />
              <span class="font-semibold mr-2">Email :</span>
              <p class="font-bold italic text-gray-950">{{ hotel.customer.email }}</p>
            </div>
            <div class="flex items-center mb-4">
              <Phone class="w-5 h-5 text-indigo-600 mr-2" />
              <span class="font-semibold mr-2">Numéro de téléphone :</span>
              <p class="font-bold italic text-gray-950">{{ hotel.customer.phone }}</p>
            </div>
            <div class="flex items-center mb-4">
              <MapPin class="w-5 h-5 text-indigo-600 mr-2" />
              <span class="font-semibold mr-2">Destination :</span>
              <p class="font-bold italic text-gray-950">{{ hotel.country.name }}</p>
            </div>
          </div>
  
          <div>
            <div class="flex items-center mb-4">
              <Calendar class="w-5 h-5 text-indigo-600 mr-2" />
              <span class="font-semibold mr-2">Date de début :</span>
              <p class="font-bold italic text-gray-950">{{ formatDate(hotel.arrival_date) }}</p>
            </div>
            <div class="flex items-center mb-4" v-if="hotel.return_date">
              <Calendar class="w-5 h-5 text-indigo-600 mr-2" />
              <span class="font-semibold mr-2">Date de fin :</span>
              <p class="font-bold italic text-gray-950">{{ hotel.return_date ? formatDate(hotel.return_date) : "Non renseignée" }}</p>
            </div>
            <div class="flex items-center mb-4">
              <Users class="w-5 h-5 text-indigo-600 mr-2" />
              <span class="font-semibold mr-2">Nombre de chambre :</span>
              <p class="font-bold italic text-gray-950">{{ hotel.number_of_room }}</p>
            </div>
            <div class="flex items-center mb-4">
                <Star class="w-5 h-5 text-indigo-600 mr-2" />
                <span class="font-semibold mr-2">Statut :</span>
                <span class="font-bold" :class="{
                    'text-yellow-600': hotel.status === 'pending',
                    'text-green-600': hotel.status === 'approved',
                    'text-red-600': hotel.status === 'rejected'
                    }">
                    <span v-if="hotel.status === 'pending'">En attente</span>
                    <span v-if="hotel.status === 'approved'">Validé</span>
                    <span v-if="hotel.status === 'rejected'">Rejeté</span>
                </span>
            </div>
          </div>
        </div>
  
        <!-- Détails supplémentaires pour un vol rejeté -->
        <div v-if="hotel.status === 'rejected'" class="mt-6 p-4 border-l-4 border-red-500 bg-red-50 rounded">
          <div class="flex items-center mb-4">
            <XCircle class="w-5 h-5 text-red-600 mr-2" />
            <span class="font-semibold">Raison du rejet :</span>
          </div>
          <p>{{ hotel.note }}</p>
          <div class="flex items-center mb-4">
            <Calendar class="w-5 h-5 text-red-600 mr-2" />
            <span class="font-semibold">Date d'arriver modifiée :</span>
            <p>{{ formatDate(hotel.arrival_date) }}</p>
          </div>
          <div class="flex items-center mb-4">
            <DollarSign class="w-5 h-5 text-red-600 mr-2" />
            <span class="font-semibold">Prix :</span>
          </div>
          <p>{{ hotel.price }}</p>
        </div>
  
        <!-- Notes associées au vol -->
        <div v-if="hotel.note" class="mt-6 p-4 bg-yellow-50 rounded border-l-4 border-yellow-500">
          <div class="flex items-center mb-4">
            <Edit class="w-5 h-5 text-yellow-500 mr-2" />
            <span class="font-semibold">Note :</span>
          </div>
          <p>{{ hotel.note }}</p>
        </div>
  
        <!-- Bouton de retour -->
        <div class="mt-8 text-center">
          <button
            @click="goBack"
            class="px-6 py-3 bg-gray-700 text-white font-semibold rounded-lg hover:bg-gray-800 transition duration-300"
          >
            Retour
          </button>
        </div>
      </div>
    </div>
  </template>
<script setup>
  import axios from 'axios';
  import { ref, onMounted } from 'vue';
  import AppDatas from '../../Services/app.js';
  import { useRoute, useRouter } from 'vue-router';
  import { Calendar, User, Mail, Phone, Plane, Edit, Users, Briefcase, XCircle, DollarSign, MapPin, Type, Star } from 'lucide-vue-next';
  const route = useRoute();
  const router = useRouter();
  
  const hotel = ref(null);
  const isLoading = ref(true);
  const error = ref("");
  
  // Récupérer l'ID depuis l'URL (/bookings/hotel/:id)
  const id = route.params.id;
  
  // Fonction pour formater la date en français
  const formatDate = (dateString) => {
    if (!dateString) return "Date invalide";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
      day: "2-digit",
      month: "long",
      year: "numeric",
    });
  };
  
  const fetchHotel = async () => {
    try {
      isLoading.value = true;
      const response = await axios.get(`${AppDatas.baseUrl}/bookings/hotel/${id}`);
      hotel.value = response.data;
      console.log("Détails de la reservation :", hotel.value);
    } catch (err) {
      error.value = "Erreur lors du chargement des détails de la reservation.";
      console.error(err);
    } finally {
      isLoading.value = false;
    }
  };
  
  const goBack = () => {
    router.back();
  };
  
  onMounted(() => {
    fetchHotel();
  });
  </script>
  
  <style scoped>
  /* Vous pouvez ajouter ici des styles personnalisés supplémentaires si nécessaire */
  </style>
  