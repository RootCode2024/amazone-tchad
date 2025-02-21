<template>
    <div class="flex flex-col items-center bg-repeat bg-center bg-cover min-h-screen px-4 relative"
        style="background-image: url('../Assets/Images/background.jpg')">

        <div class="absolute inset-0 bg-black opacity-50"></div>
        <!-- Overlay for darkening the background -->

        <img :src="Logo" alt="Logo" class="h-24 sm:h-36 z-50">
        <p class="w-full sm:w-3/4 md:w-1/2 flex z-50 justify-center text-center italic font-bold px-2 text-white">
            Chaque Voyageur est unique et a sa propre vision du voyage. Que ce soit pour partir quelques heures de chez vous ou à l’autre bout du monde pour découvrir, visiter, rencontrer, vous émerveiller…..
        </p>

        <div class="container mx-auto px-4 z-50">
            <div class="grid grid-cols-1 gap-6">
                <!-- Main Content -->
                <div class="bg-white shadow-lg p-2 sm:p-4 rounded-lg">

                    <!-- Tabs -->
                    <div class="flex justify-center mb-4 overflow-x-auto">
                        <ul class="flex flex-wrap space-x-2 sm:space-x-4 border-b">
                            <li v-for="tab in tabs" :key="tab.key" class="flex-shrink-0">
                                <a class="cursor-pointer px-2 sm:px-4 py-2 border-b-2 whitespace-nowrap"
                                    :class="activeTab === tab.key ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-600'"
                                    @click="activeTab = tab.key">
                                    <img :src="tab.icon" alt="" class="h-5 w-5 sm:h-6 sm:w-6 inline-block">
                                    {{ tab.label }}
                                </a>
                            </li>
                        </ul>
                    </div>


                    <!-- Form Content -->
                    <div class="mt-2">
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
    return tabs.find(tab => tab.key === activeTab.value)?.component || FlightComponent;
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
