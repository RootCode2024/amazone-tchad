<template>
    <div class="flex flex-col items-center py-10 bg-no-repeat bg-center bg-cover min-h-screen"
        style="background-image: url('../Assets/Images/background.jpg')">
        <img :src="Logo" alt="Logo" class="h-32">
        <router-link to="/dashboard">...</router-link>

        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                <!-- Main Content -->
                <div class="md:col-span-2 bg-white shadow-lg p-6 rounded-lg">

                    <!-- Tabs -->
                    <div class="flex justify-center mb-4">
                        <ul class="flex space-x-4 border-b">
                            <li v-for="tab in tabs" :key="tab.key">
                                <a class="cursor-pointer px-4 py-2 border-b-2"
                                    :class="activeTab === tab.key ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-600'"
                                    @click="activeTab = tab.key">
                                    <img :src="tab.icon" alt="" class="h-6 w-6 inline-block">
                                    {{ tab.label }}</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Form Content -->
                    <div class="mt-4">
                        <transition mode="out-in">
                            <component :is="activeComponent"></component>
                        </transition>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { RouterLink } from "vue-router";

import FlightComponent from "../Components/Forms/Reservations/FlightComponent.vue";
import HotelComponent from "../Components/Forms/Reservations/HotelComponent.vue";
import CarLocationComponent from "../Components/Forms/Reservations/CarLocationComponent.vue";
import FlightHotelComponent from "../Components/Forms/Reservations/FlightHotelComponent.vue";


import PlaneIcon from "../Assets/svg/plane.png";
import HotelIcon from "../Assets/svg/hotel.png";
import PlaneHotelIcon from "../Assets/svg/planehotel.png";
import CarLocationIcon from "../Assets/svg/car.png";

import Logo from '../Assets/Images/logo.png';

const activeTab = ref("vols");

const tabs = [
    { key: "vols", label: "Vols", icon: PlaneIcon, component: FlightComponent },
    { key: "hotels", label: "Hôtels", icon: HotelIcon, component: HotelComponent },
    { key: "volhotel", label: "Vol + Hôtel", icon: PlaneHotelIcon, component: FlightHotelComponent },
    { key: "voiture", label: "Location de voiture", icon: CarLocationIcon, component: CarLocationComponent }
];

const activeComponent = computed(() => {
    return tabs.find(tab => tab.key === activeTab.value)?.component || VolForm;
});
</script>

<style>
/* Ajoute des animations */
.v-enter-active, .v-leave-active {
    transition: opacity 0.3s ease;
}
.v-enter-from, .v-leave-to {
    opacity: 0;
}
</style>

