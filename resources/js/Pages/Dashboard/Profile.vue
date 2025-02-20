<template>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-md">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Modifier le profil</h2>

        <!-- Section : Informations générales -->
        <div class="bg-gray-100 p-4 rounded-md">
            <h3 class="text-lg font-medium text-gray-700 mb-3">Informations personnelles</h3>

            <!-- Avatar -->
            <div class="flex items-center space-x-4 mb-4">
                <img :src="Person" class="w-20 h-20 rounded-full border" alt="Avatar">
            </div>

            <form @submit.prevent="updateProfile">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nom</label>
                    <input type="text" v-model="user.name" class="w-full px-4 py-2 border rounded-md">
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-600">Email</label>
                    <input type="email" v-model="user.email" class="w-full px-4 py-2 border rounded-md">
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-600">Adresse</label>
                    <input type="text" v-model="user.address" class="w-full px-4 py-2 border rounded-md">
                </div>

                <div class="mt-3 flex justify-end">
                    <button type="submit" :disabled="loadingBtnProfile" class="px-4 py-2 bg-blue-600 text-white rounded">
                        {{ loadingBtnProfile ? 'Enregistrement...' : 'Enregistrer' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Section : Changer le mot de passe -->
        <div class="bg-gray-100 p-4 mt-6 rounded-md">
            <h3 class="text-lg font-medium text-gray-700 mb-3">Changer le mot de passe</h3>

            <form @submit.prevent="updatePassword">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Mot de passe actuel</label>
                    <input type="password" v-model="password.current" class="w-full px-4 py-2 border rounded-md">
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-600">Nouveau mot de passe</label>
                    <input type="password" v-model="password.new" class="w-full px-4 py-2 border rounded-md">
                </div>

                <div class="mt-3">
                    <label class="block text-sm font-medium text-gray-600">Confirmer le mot de passe</label>
                    <input type="password" v-model="password.confirm" class="w-full px-4 py-2 border rounded-md">
                </div>

                <div class="mt-3 flex justify-end">
                    <button type="submit" :disabled="loadingBtnPassword" class="px-4 py-2 bg-blue-600 text-white rounded">
                        {{ loadingBtnPassword ? 'Modification...' : 'Modifier' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
    import Person from '../../Assets/Images/person.png'
    import AppDatas from '../../Services/app.js'
    import { ref, watchEffect } from 'vue'
    import axios from 'axios'
    import Swal from "sweetalert2"


    const authUser = ref({})
    const user = ref({ id: '',name: '', email: '', address: '' })
    const password = ref({ current: '', new: '', confirm: '' })
    const loadingBtnProfile = ref(false)
    const loadingBtnPassword = ref(false)


    const getUserInfo = async () => {
        try {
            const response = await axios.get(`${AppDatas.baseUrl}/user`, {
                headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
            })
            authUser.value = response.data
        } catch (error) {
            console.error("Erreur lors de la récupération des données de l'utilisateur", error)
        }
    }


    watchEffect(() => {
        if (authUser.value && Object.keys(authUser.value).length > 0) {
            user.value = {
                id: authUser.value.id || '',
                name: authUser.value.name || '',
                email: authUser.value.email || '',
                address: authUser.value.address || ''
            }
        }
    })


    const updateProfile = async () => {
        loadingBtnProfile.value = true;
        const datas = {
            id: user.value.id,
            name: user.value.name,
            email: user.value.email,
            address: user.value.address
        }
        
        try {
            await axios.put(`${AppDatas.baseUrl}/managers`, datas)
            getUserInfo();
            toastFunction('success', 'Profil mis à jour avec succès ! 🚀')
        } catch (error) {
            console.error('Erreur lors de la mise à jour du profil', error)
        } finally {
            loadingBtnProfile.value = false
        }
    }


    const updatePassword = async () => {
        loadingBtnPassword.value = true
        try {
            await axios.put(`${AppDatas.baseUrl}/managers/password`, {
                id: authUser.value.id,
                current_password: password.value.current,
                new_password: password.value.new,
                new_password_confirmation: password.value.confirm
            })

            toastFunction('success', 'Mot de passe mis à jour ! 🔒')
            password.value = { current: '', new: '', confirm: '' }
        } catch (error) {
            console.error('Erreur lors de la mise à jour du mot de passe', error)
            alert('Erreur lors de la mise à jour du mot de passe.')
        } finally {
            loadingBtnPassword.value = false
        }
    }

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

    // Charger les infos utilisateur au démarrage
    getUserInfo()
</script>
    