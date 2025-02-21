import { defineStore } from "pinia";
import { ref } from "vue";
import axios from "axios";
import AppDatas from "../Services/app.js";

export const useUserStore = defineStore("user", () => {
    const user = ref({});

    // Récupérer les infos de l'utilisateur
    const fetchUser = async () => {
        try {
            const response = await axios.get(`${AppDatas.baseUrl}/user`, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            });
            user.value = response.data;
        } catch (error) {
            console.error("Erreur lors de la récupération des données de l'utilisateur.", error);
        }
    };

    // Mettre à jour les infos de l'utilisateur (utile dans Profile.vue)
    const updateUser = (newUserData) => {
        user.value = { ...user.value, ...newUserData };
    };

    return { user, fetchUser, updateUser };
});
