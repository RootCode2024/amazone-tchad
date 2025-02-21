<script setup>
import { onMounted, onUnmounted, ref } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useUserStore } from "@/stores/userStore";
import { Home, ListChecks, Users, LucideLock } from "lucide-vue-next";
import Logo from "../Assets/Images/logo.png";
import Person from "../Assets/Images/person.png";

const isOpen = ref(false);
const openDropDown = ref(false);
const dropdownContainer = ref(null);
const router = useRouter();
const route = useRoute();

const userStore = useUserStore();

const isActive = (path) => {
    return route.path === path ? 'bg-gray-100 text-gray-950' : '';
};

// Fermer le dropdown si on clique en dehors
const handleClickAway = (event) => {
    if (dropdownContainer.value && !dropdownContainer.value.contains(event.target)) {
        openDropDown.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickAway);
    if (localStorage.getItem("token")) {
        userStore.fetchUser(); // Récupération des données utilisateur
    } else {
        userStore.isLoading = false; // Pas de token = pas de chargement
    }
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickAway);
});
</script>

<template>
    <!-- Affichage du loader tant que les données ne sont pas chargées -->
    <div v-if="userStore.isLoading" class="h-screen flex items-center justify-center">
        <div class="animate-spin rounded-full h-12 w-12 border-t-4 border-blue-500"></div>
    </div>

    <!-- Affichage du layout uniquement lorsque les données sont prêtes -->
    <div v-else class="flex h-screen bg-white">
        <!-- Sidebar -->
        <div 
            :class="isOpen ? 'w-40' : 'w-60'" 
            class="flex flex-col h-screen md:z-50 p-3 duration-300 bg-indigo-600 shadow overflow-hidden fixed">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <router-link to="/dashboard" class="text-xl font-bold text-white">
                        <img :src="Logo" alt="Logo">
                    </router-link>
                    <button @click="isOpen = !isOpen">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
                <hr>
                <div class="flex-1">
                    <ul class="pt-2 pb-4 space-y-1 text-sm">
                        <li class="rounded-sm">
                            <router-link to="/dashboard" 
                                         :class="isActive('/dashboard')" 
                                         class="flex items-center p-2 space-x-3 rounded-md text-gray-100 hover:bg-gray-700">
                                <Home class="w-6 h-6" />
                                <span v-if="!isOpen">Tableau de bord</span>
                            </router-link>
                        </li>
                        <li class="rounded-sm">
                            <router-link to="/dashboard/bookings" 
                                         :class="isActive('/dashboard/bookings')" 
                                         class="flex items-center p-2 space-x-3 rounded-md text-gray-100 hover:bg-gray-700">
                                <ListChecks class="w-6 h-6" />
                                <span v-if="!isOpen">Réservations</span>
                            </router-link>
                        </li>
                        <li class="rounded-sm mb-2">
                            <router-link to="/dashboard/customers" 
                                         :class="isActive('/dashboard/customers')" 
                                         class="flex items-center p-2 space-x-3 rounded-md text-gray-100 hover:bg-gray-700">
                                <Users class="w-6 h-6" />
                                <span v-if="!isOpen">Clients</span>
                            </router-link>
                        </li>
                        <hr>
                        <li class="rounded-sm mt-2">
                            <router-link to="/dashboard/managers" 
                                         :class="isActive('/dashboard/managers')" 
                                         class="flex items-center p-2 space-x-3 rounded-md text-gray-100 hover:bg-gray-700">
                                <LucideLock class="w-6 h-6" />
                                <span v-if="!isOpen">Mon équipe</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div :class="isOpen ? 'ml-40' : 'ml-60'" class="flex flex-col flex-1 transition-all duration-300">
            <nav class="bg-white shadow-md w-full h-16 px-6 flex items-center justify-between fixed top-0 left-0 right-0 z-40">
                <h1 :class="isOpen ? 'pl-40' : 'pl-60'" class="text-lg font-bold text-gray-700">Tableau de bord</h1>

                <!-- Dropdown -->
                <div class="relative" ref="dropdownContainer">
                    <button @click="openDropDown = !openDropDown" class="text-gray-900 font-bold flex items-center">
                        <span class="mr-2">{{ userStore.user.name }}</span>
                        <img :src="Person" class="rounded-full w-10 h-10" alt="Avatar">
                    </button>

                    <div v-if="openDropDown" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50">
                        <router-link to="/dashboard/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</router-link>
                        <button @click="logout()" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Déconnexion</button>
                    </div>
                </div>
            </nav>

            <div class="container mx-auto mt-20 px-6">
                <main>
                    <router-view></router-view>
                </main>
            </div>
        </div>
    </div>
</template>
