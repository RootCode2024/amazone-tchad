<template>
    <form @submit.prevent="submitForm()" class="p-6 flex flex-col space-y-4 max-w-4xl mx-auto w-full">
        
        <!-- Étape 1 : Détails du vol -->
        <div v-if="step === 1">                
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 py-5">
                <h2 class="col-span-5 text-sm italic text-center">Veuillez remplir les informations du vol pour continuer.</h2>
            </div>
            <hr>
            <div class="flex flex-col md:flex-row justify-between space-x-0 md:space-x-4 mb-4">
                <!-- Sélection du type de vol -->
                <div class="flex flex-col space-y-2 text-sm italic w-full">
                    <label class="font-medium">Type de vol</label>
                    <div class="flex flex-wrap items-center space-x-4">
                        <label for="one_way" class="flex items-center space-x-2">
                            <input type="radio" v-model="form.flight_type" value="one_way" id="one_way">
                            <span>Aller simple</span>
                        </label>
                        
                        <label for="round_trip" class="flex items-center space-x-2">
                            <input type="radio" v-model="form.flight_type" value="round_trip" id="round_trip">
                            <span>Aller - Retour</span>
                        </label>
                        
                        <label for="multi_destination" class="flex items-center space-x-2">
                            <input type="radio" v-model="form.flight_type" value="multi_destination" id="multi_destination">
                            <span>Multi Destination</span>
                        </label>
                    </div>
                </div>

                <!-- Nombre de passagers et classe -->
                <div class="flex flex-col space-y-2 text-sm italic w-full">
                    <label class="font-medium">Passagers et classe</label>
                    <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2">
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
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                    </select>
                    <p v-if="form.departure_city_id === form.destination_city_id && form.departure_city_id" class="text-red-500 text-sm">La ville de départ et d’arrivée doivent être différentes.</p>
                </div>

                <div>
                    <label for="departure_date" class="block text-sm font-medium">Date de départ</label>
                    <input type="date" id="departure_date" v-model="form.departure_date" class="border p-2 rounded w-full">
                </div>

                <div>
                    <label for="return_date" class="block text-sm font-medium">Date de retour</label>
                    <input type="date" id="return_date" v-model="form.return_date" class="border p-2 rounded w-full" :disabled="form.flight_type === 'one_way'">
                </div>
                <div class="flex items-end">
                    <button type="button" @click="changeStep(2)" class="bg-indigo-500 text-white px-6 py-2 rounded w-full">Suivant</button>
                </div>
            </div>
        </div>

        <!-- Étape 2 : Informations du voyageur -->
        <div v-if="step === 2">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 py-5">
                <h2 class="col-span-5 text-sm italic text-center">Veuillez remplir les informations du voyageur pour valider.</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="name" v-model="form.name" placeholder="Nom" class="border p-2 rounded w-full">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" v-model="form.email" placeholder="Email" class="border p-2 rounded w-full">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="tel" id="phone" v-model="form.phone" placeholder="Téléphone" class="border p-2 rounded w-full">
                </div>
            </div>

            <NavigationButton 
                :buttonLoading="buttonLoading" 
                submitLabel="Envoyer"
                @go-back="changeStep(1)" 
            />
        </div>
    </form>
</template>


<script setup>
import { ref } from "vue";
import Swal from "sweetalert2";
import { onMounted } from "vue";
import axios from 'axios';
import AppDatas from '../../../Services/app.js';
import NavigationButton from "../../NavigationButton.vue";

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
    name: "",
    email: "",
    phone: "",
});

const formErrors = ref({
    departure_date: { error: false, message: "Veuillez sélectionner une date de départ." },
    return_date: { error: false, message: "Veuillez sélectionner une date de retour." },
    name: { error: false, message: "Veuillez saisir votre nom." },
    email: { error: false, message: "Veuillez saisir une adresse email valide." },
    phone: { error: false, message: "Veuillez saisir un numéro de téléphone valide." },
    dates: { error: false, message: "La date du départ doit être inferieur à la date de retour." },
    customerInfo: { error: false, message: "Verifier que le nom, l'email et le numero sont bien entrées." },
});

// Changer de step
const changeStep = (newStep) => {
    if (newStep === 2) {
        if (!form.value.departure_date) {
            formErrors.value.departure_date.error = true;
            showError(formErrors.value.departure_date.message);
            step.value = 1;
            return;
        }
        
        if (form.value.flight_type !== "one_way" && !form.value.return_date) {
            formErrors.value.return_date.error = true;
            showError(formErrors.value.return_date.message);
            step.value = 1;
            return;
        }

        
        if (form.value.flight_type !== 'one_way' && form.value.return_date <= form.value.departure_date) {
            showError(formErrors.value.dates.message);
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
        await axios.post(`${baseUrl}/flights`, form.value);
    } catch (error) {
        showError("Échec de la soumission. Vérifiez votre connexion.");
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
