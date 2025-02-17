<template>
    <div class="flex h-screen bg-gray-100">
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
                            <router-link to="/bookings" 
                                         :class="isActive('/bookings')" 
                                         class="flex items-center p-2 space-x-3 rounded-md text-gray-100 hover:bg-gray-700">
                                <ListChecks class="w-6 h-6" />
                                <span v-if="!isOpen">Réservations</span>
                            </router-link>
                        </li>
                        <li class="rounded-sm mb-2">
                            <router-link to="/customers" 
                                         :class="isActive('/customers')" 
                                         class="flex items-center p-2 space-x-3 rounded-md text-gray-100 hover:bg-gray-700">
                                <Users class="w-6 h-6" />
                                <span v-if="!isOpen">Clients</span>
                            </router-link>
                        </li>
                        <hr>
                        <li class="rounded-sm mt-2">
                            <router-link to="/managers" 
                                         :class="isActive('/managers')" 
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
            <!-- Navbar -->
            <nav class="bg-white shadow-md w-full h-16 px-6 flex items-center justify-between fixed top-0 left-0 right-0 z-40 transition-all duration-300">
                <h1 :class="isOpen ? 'pl-40' : 'pl-60'" class="text-lg font-bold text-gray-700">Tableau de bord</h1>

                <!-- Dropdown -->
                <div class="relative">
                    <button @click="openDropDown = !openDropDown" class="text-gray-900 font-bold flex items-center">
                        <span class="mr-2">{{ user.name }}</span>
                        <img :src="Person" class="rounded-full w-10 h-10" alt="Avatar">
                    </button>

                    <!-- Contenu de la dropdown -->
                    <div v-if="openDropDown" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50">
                        <router-link to="/dashboard/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</router-link>
                        <button @click="logout()" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">Déconnexion</button>
                    </div>
                </div>
            </nav>

            <!-- Contenu avec padding pour éviter d'être caché sous la navbar -->
            <div class="container mx-auto mt-20 px-6">
                <main>
                    <slot></slot>
                </main>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { useRouter, useRoute } from "vue-router";
import { Home, ListChecks, Users, LucideLock } from 'lucide-vue-next';
import axios from "axios";
import Logo from '../Assets/Images/logo.png';
import Person from '../Assets/Images/person.png';
import AppDatas from '../Services/app.js';

const isOpen = ref(false);
const openDropDown = ref(false);
const router = useRouter();
const route = useRoute();
const user = ref({});

// Détermine si un lien est actif
const isActive = (path) => route.path === path ? 'bg-gray-500 text-gray-950' : '';

// Récupérer les infos utilisateur
const getUserInfo = async () => {
    try {
        const response = await axios.get(`${AppDatas.baseUrl}/user`, {
            headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        user.value = response.data;
    } catch (error) {
        console.error("Erreur lors de la récupération des données de l'utilisateur", error);
    }
};

// Déconnexion
const logout = async () => {
    try {
        const token = localStorage.getItem("token");
        if (!token) {
            console.error("Aucun token trouvé dans localStorage !");
            return;
        }

        await axios.post(`${AppDatas.baseUrl}/logout`, {}, {
            headers: { Authorization: `Bearer ${token}` }
        });

        localStorage.removeItem("token");
        router.push("/login");
    } catch (error) {
        console.error("Erreur lors de la déconnexion", error);
    }
};

// Initialisation
onMounted(() => {
    getUserInfo();
});
</script>

<style scoped>
/* Animation de la sidebar */
aside {
  transition: width 0.3s ease-in-out;
}
</style>
