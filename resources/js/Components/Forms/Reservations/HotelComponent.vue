<template>
    <form @submit.prevent="submitForm()" class="p-6 flex flex-col space-y-4 max-w-4xl mx-auto">
        
        <!-- Étape 1 : Détails de la réservation d'hôtel -->
        <div v-if="step === 1">                
            <div class="grid grid-cols-5 gap-4 py-5">
                <h2 class="col-span-5 text-sm italic">Veuillez remplir les informations de la réservation d'hôtel pour continuer.</h2>
            </div>
            <hr>
            
            <!-- Sélection des villes et dates -->
            <div class="grid grid-cols-5 gap-4 my-2">
                <!-- Sélection du nombre de chambres -->
                <div class="">
                    <label class="block text-sm font-medium">Nombre de chambres</label>
                    <input type="number" v-model="form.number_of_room" min="1" max="20" class="border p-2 rounded w-full" placeholder="Nombre de chambres">
                </div>
                <div>
                    <label for="city_id" class="block text-sm font-medium">Ville de destination</label>
                    <select id="city_id" v-model="form.city_id" class="border p-2 rounded w-full">
                        <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                    </select>
                </div>

                <div>
                    <label for="arrival_date" class="block text-sm font-medium">Date d'arrivée</label>
                    <input type="date" id="arrival_date" v-model="form.arrival_date" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.arrival_date.error }">
                </div>

                <div>
                    <label for="return_date" class="block text-sm font-medium">Date de retour</label>
                    <input type="date" id="return_date" v-model="form.return_date" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.return_date.error }">
                </div>
                <div class="flex items-end">
                    <button type="button" @click="changeStep(2)" class="bg-indigo-500 text-white px-6 py-2 rounded">Suivant</button>
                </div>
            </div>

        </div>

        <!-- Étape 2 : Informations du voyageur -->
        <div v-if="step === 2">
            <div class="grid grid-cols-5 gap-4 py-5">
                <h2 class="col-span-5 text-sm italic">Veuillez remplir les informations du voyageur pour valider.</h2>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="name" v-model="form.name" placeholder="Nom" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.name.error }">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" v-model="form.email" placeholder="Email" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.email.error }">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="tel" id="phone" v-model="form.phone" placeholder="Téléphone" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.phone.error }">
                </div>
            </div>

            <div class="mt-4 flex justify-between">
                <button type="button" @click="changeStep(1)" class="bg-gray-500 text-white px-4 py-2 rounded">Retour</button>
                <button type="submit" :disabled="buttonLoading" class="px-4 py-2 min-w-28 bg-blue-600 text-white rounded-lg">
                  <span v-if="!buttonLoading">Valider</span>
                  <div v-else class="flex items-center">
                    <svg class="mr-3 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="font-medium"> Envoie en cours ... </span>
                  </div>
                </button>
            </div>
        </div>
    </form>
</template>

<script setup>
import { ref } from "vue";
import Swal from "sweetalert2";
import { onMounted } from "vue";
import axios from 'axios';
import AppDatas from '../../../Services/app.js';

const baseUrl = AppDatas.baseUrl;

const step = ref(1);
const cities = ref([]);
const buttonLoading = ref(false);

const form = ref({
    city_id: 1,
    arrival_date: "",
    return_date: "",
    number_of_room: 1,
    name: "",
    email: "",
    phone: "",
});

const formErrors = ref({
    arrival_date: { error: false, message: "Veuillez sélectionner une date d'arrivée." },
    return_date: { error: false, message: "Veuillez sélectionner une date de retour." },
    name: { error: false, message: "Veuillez saisir votre nom." },
    email: { error: false, message: "Veuillez saisir une adresse email valide." },
    phone: { error: false, message: "Veuillez saisir un numéro de téléphone valide." }
});

// Changer de step
const changeStep = (newStep) => {
    if (newStep === 2) {
        if (!form.value.arrival_date) {
            formErrors.value.arrival_date.error = true;
            showError(formErrors.value.arrival_date.message);
            step.value = 1;
            return;
        }
        if (!form.value.return_date) {
            formErrors.value.return_date.error = true;
            showError(formErrors.value.return_date.message);
            step.value = 1;
            return;
        }

        step.value = newStep;
    } else if (newStep === 1) {
        step.value = newStep;
    }
};

// Charger les villes depuis l'API
const fetchCities = async () => {
    try {
        const response = await fetch(`${baseUrl}/cities`);
        if (!response.ok) throw new Error("Erreur lors du chargement des villes");
        cities.value = await response.json();
    } catch (error) {
        showError("Impossible de charger les villes.");
    }
};

onMounted(() => {
    fetchCities();
});

// Valider et soumettre le formulaire
const submitForm = async () => {
    if (!form.value.name || !form.value.email || !form.value.phone) {
        formErrors.value.customerInfo.error = true;
        showError(formErrors.value.customerInfo.message);
        return;
    }
    console.log(form.value);

    try {
        buttonLoading.value = true;
        await axios.post(`${baseUrl}/hotels`, form.value);
        showSummary();
    } catch (error) {
        showError("Échec de la soumission. Vérifiez votre connexion.");
    } finally {
        buttonLoading.value = false;
    }
};

const showError = (message) => {
    Swal.fire({
        icon: "error",
        title: "Erreur",
        text: message,
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
    });
};

const showSummary = () => {
    Swal.fire({
        title: "Récapitulatif de la réservation",
        html: `
            <b>Ville de destination :</b> ${form.value.city_id}<br>
            <b>Date d'arrivée :</b> ${form.value.arrival_date}<br>
            <b>Date de retour :</b> ${form.value.return_date || "Non applicable"}<br>
            <b>Nombre de chambres :</b> ${form.value.number_of_room}<br>
            <hr>
            <b>Nom :</b> ${form.value.name}<br>
            <b>Email :</b> ${form.value.email}<br>
            <b>Téléphone :</b> ${form.value.phone}
        `,
        icon: "success",
        confirmButtonText: "OK",
    });
};
</script>
