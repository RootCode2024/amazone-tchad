<template>
    <form @submit.prevent="submitForm()" class="p-6 flex flex-col space-y-4 max-w-4xl mx-auto">
        
        <!-- Étape 1 : Détails du vol -->
        <div v-if="step === 1">                
            <div class="grid grid-cols-5 gap-4 py-5">
                <h2 class="col-span-5 text-sm italic">Veuillez remplir les informations du vol pour continuer.</h2>
            </div>
            <hr>
            <div class="flex justify-between space-x-4 mb-4">
                <!-- Sélection du type de vol -->
                <div class="flex flex-col space-y-2 text-sm italic">
                    <label class="font-medium">Type de vol</label>
                    <div class="flex items-center space-x-4">
                        <input type="radio" v-model="form.flight_type" value="one_way" id="one_way">
                        <label for="one_way">Aller simple</label>
                        
                        <input type="radio" v-model="form.flight_type" value="round_trip" id="round_trip">
                        <label for="round_trip">Aller - Retour</label>
                        
                        <input type="radio" v-model="form.flight_type" value="multi_destination" id="multi_destination">
                        <label for="multi_destination">Multi Destination</label>
                    </div>
                </div>

                <!-- Nombre de passagers et classe -->
                <div class="flex flex-col space-y-2 text-sm italic">
                    <label class="font-medium">Passagers et classe</label>
                    <div class="flex space-x-2">
                        <input type="number" v-model="form.passengers" min="1" max="10" class="border p-2 rounded w-full" placeholder="Nombre de passagers">

                        <select v-model="form.flight_class" class="border p-2 rounded w-full">
                            <option value="economy">Économie</option>
                            <option value="business">Affaires</option>
                            <option value="first_class">Première classe</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Sélection des villes et dates -->
            <div class="grid grid-cols-5 gap-4">
                <div>
                    <label for="departure_city_id" class="block text-sm font-medium">Origine</label>
                    <select id="departure_city_id" v-model="form.departure_city_id" class="border p-2 rounded w-full">
                        <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                    </select>
                </div>

                <div>
                    <label for="destination_city_id" class="block text-sm font-medium">Destination</label>
                    <select id="destination_city_id" v-model="form.destination_city_id" class="border p-2 rounded w-full">
                        <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                    </select></div>

                <div>
                    <label for="departure_date" class="block text-sm font-medium">Date de départ</label>
                    <input type="date" id="departure_date" v-model="form.departure_date" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.departure_date.error }">
                </div>

                <div>
                    <label for="return_date" class="block text-sm font-medium">Date de retour</label>
                    <input type="date" id="return_date" v-model="form.return_date" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.return_date.error || form.flight_type == 'round_trip' }" :disabled="form.flight_type === 'one_way'">
                </div>
                
                <div>
                    <label for="number_of_room" class="block text-sm font-medium">Nombre de chambre</label>
                    <input type="number" id="number_of_room" min="1" max="20" v-model="form.number_of_room" class="border p-2 rounded w-full">
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
    flight_type: "one_way",
    departure_city_id: 1,
    destination_city_id: 2,
    departure_date: "",
    return_date: "",
    passengers: 1,
    flight_class: "economy",
    number_of_room: 1,
    name: "",
    email: "",
    phone: "",
});

const formErrors = ref({
    departure_date: { error: false, message: "Veuillez sélectionner une date de départ." },
    return_date: { error: false, message: "Veuillez sélectionner une date de retour." },
    name: { error: false, message: "Veuillez saisir votre nom." },
    email: { error: false, message: "Veuillez saisir une adresse email valide." },
    phone: { error: false, message: "Veuillez saisir un numéro de téléphone valide." }
});


const changeStep = (newStep) => {
    if (newStep === 2) {
        if (!form.value.departure_date) {
            formErrors.value.departure_date.error = true;
            toastFunction('error', formErrors.value.departure_date.message);
            step.value = 1;
            return;
        }
        
        if (form.value.flight_type !== "one_way" && !form.value.return_date) {
            formErrors.value.return_date.error = true;
            toastFunction('error', formErrors.value.return_date.message);
            step.value = 1;
            return;
        }

        step.value = newStep;
    } else if (newStep === 1) {
        step.value = newStep;
    }
};



    const fetchCities = async () => {
        try {
            const response = await fetch(`${baseUrl}/cities`);
            if (!response.ok) throw new Error("Erreur lors du chargement des villes");
            cities.value = await response.json();
        } catch (error) {
            toastFunction('error', 'Impossible de charger les villes.');
        }
    };

    onMounted(() => {
        fetchCities();
    });



const submitForm = async () => {
    if (!form.value.name || !form.value.email || !form.value.phone) {
        formErrors.value.customerInfo.error = true;
        toastFunction('error', formErrors.value.customerInfo.message);
        return;
    }

    try {
        buttonLoading.value = true;
        await axios.post(`${baseUrl}/flight-hotel`, form.value);
        
    } catch (error) {
        toastFunction('error', 'Échec lors de la soumission. Vérifiez votre connexion.');
    } finally {
        buttonLoading.value = false;
        step.value = 1;

        form.value.flight_type = "one_way";
        form.value.departure_city_id =  1;
        form.value.destination_city_id =  2;
        form.value.departure_date =  "";
        form.value.return_date =  "";
        form.value.passengers =  1;
        form.value.flight_class =  "economy";
        form.value.name =  "";
        form.value.email =  "";
        form.value.phone =  "";

        toastFunction('success', 'Votre reservation a été soumise avec succès.')
    }
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
</script>
