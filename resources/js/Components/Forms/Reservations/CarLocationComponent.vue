<template>
    <form @submit.prevent="submitForm()" class="p-6 flex flex-col space-y-4 max-w-4xl mx-auto w-full">
        
        <!-- Étape 1 : Détails de la location de voiture -->
        <div v-if="step === 1">                
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 py-5">
                <h2 class="col-span-1 md:col-span-5 text-sm italic text-center">Veuillez remplir les informations de la location de voiture pour continuer.</h2>
            </div>
            <hr>
            
            <!-- Sélection des dates -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 py-2">
                <div>
                    <label for="place_of_location" class="block text-sm font-medium">Lieu de location</label>
                    <select id="place_of_location" v-model="form.place_of_location" class="border p-2 rounded w-full">
                        <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                    </select>
                </div>
                <div>
                    <label for="started_date" class="block text-sm font-medium">Date de début</label>
                    <input type="date" id="started_date" v-model="form.started_date" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.started_date.error }">
                </div>

                <div>
                    <label for="ended_date" class="block text-sm font-medium">Date de fin</label>
                    <input type="date" id="ended_date" v-model="form.ended_date" class="border p-2 rounded w-full" :class="{ 'border-red-500 text-red-500': formErrors.ended_date.error }">
                </div>

                <div>
                    <label for="age" class="block text-sm font-medium">Âge</label>
                    <input type="number" v-model="form.age" min="18" class="border p-2 rounded w-full" placeholder="Âge du conducteur">
                </div>
                <div class="flex items-end">
                    <button type="button" @click="changeStep(2)" class="bg-indigo-500 text-white px-6 py-2 rounded w-full">Suivant</button>
                </div>
            </div>
        </div>

        <!-- Étape 2 : Informations du voyageur -->
        <div v-if="step === 2">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 py-5">
                <h2 class="col-span-1 md:col-span-5 text-sm italic text-center">Veuillez remplir les informations du voyageur pour valider.</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

            <NavigationButton 
                :buttonLoading="buttonLoading" 
                submitLabel="Envoyer"
                @go-back="changeStep(1)" 
            />
        </div>
    </form>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Swal from "sweetalert2";
import axios from 'axios';
import AppDatas from '../../../Services/app.js';
import NavigationButton from "../../NavigationButton.vue";

const baseUrl = AppDatas.baseUrl;

const cities = ref({});
const step = ref(1);
const buttonLoading = ref(false);

const form = ref({
    place_of_location: 5,
    started_date: "",
    ended_date: "",
    age: 16,
    name: "",
    email: "",
    phone: "",
});

const formErrors = ref({
    started_date: { error: false, message: "Veuillez sélectionner une date de début." },
    ended_date: { error: false, message: "Veuillez sélectionner une date de fin." },
    age: { error: false, message: "L'âge du conducteur doit être supérieur ou égal à 18 ans." },
    name: { error: false, message: "Veuillez saisir votre nom." },
    email: { error: false, message: "Veuillez saisir une adresse email valide." },
    phone: { error: false, message: "Veuillez saisir un numéro de téléphone valide." }
});

// Changer de step
const changeStep = (newStep) => {
    if (newStep === 2) {
        if (!form.value.started_date) {
            formErrors.value.started_date.error = true;
            toastFunction('error', formErrors.value.started_date.message);
            step.value = 1;
            return;
        }

        if (!form.value.ended_date) {
            formErrors.value.ended_date.error = true;
            toastFunction('error', formErrors.value.ended_date.message);
            step.value = 1;
            return;
        }

        if (form.value.age < 16) {
            formErrors.value.age.error = true;
            toastFunction('error', formErrors.value.age.message);
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

// Valider et soumettre le formulaire
const submitForm = async () => {
    if (!form.value.name || !form.value.email || !form.value.phone) {
        formErrors.value.customerInfo.error = true;
        toastFunction('error', formErrors.value.customerInfo.message);
        return;
    }

    try {
        buttonLoading.value = true;
        await axios.post(`${baseUrl}/car-locations`, form.value);
        
    } catch (error) {
        toastFunction('error', 'Échec lors de la soumission. Vérifiez votre connexion.');
    } finally {
        buttonLoading.value = false;
        step.value = 1;

        form.value.place_of_location =  5;
        form.value.started_date =  "";
        form.value.ended_date =  "";
        form.value.age =  16;
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
