<template>
    <Layout>
        <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Modifier le profil</h2>

            <!-- Section : Informations générales -->
            <div class="bg-gray-100 p-4 rounded-md">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Informations personnelles</h3>

                <!-- Avatar -->
                <div class="flex items-center space-x-4 mb-4">
                    <img :src="Person" class="w-20 h-20 rounded-full border" alt="Avatar">
                </div>

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
                    <button @click="updateProfile" :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded">
                        {{ loading ? 'Enregistrement...' : 'Enregistrer' }}
                    </button>
                </div>
            </div>

            <!-- Section : Changer le mot de passe -->
            <div class="bg-gray-100 p-4 mt-6 rounded-md">
                <h3 class="text-lg font-medium text-gray-700 mb-3">Changer le mot de passe</h3>

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
                    <button @click="updatePassword" :disabled="loading" class="px-4 py-2 bg-blue-600 text-white rounded">
                        {{ loading ? 'Modification...' : 'Modifier' }}
                    </button>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
import { ref, watchEffect } from 'vue'
import axios from 'axios'
import Layout from '../../Layouts/AppLayout.vue'
import AppDatas from '../../Services/app.js'
import Person from '../../Assets/Images/person.png'

// État utilisateur
const authUser = ref({})
const user = ref({ name: '', email: '', address: '' })
const password = ref({ current: '', new: '', confirm: '' })
const loading = ref(false)

// Récupérer l'utilisateur connecté
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

// Mettre à jour `user` avec les données récupérées
watchEffect(() => {
    if (authUser.value && Object.keys(authUser.value).length > 0) {
        user.value = {
            name: authUser.value.name || '',
            email: authUser.value.email || '',
            address: authUser.value.address || ''
        }
    }
})

// Mettre à jour les informations du profil
const updateProfile = async () => {
    loading.value = true
    try {
        await axios.put(`${AppDatas.baseUrl}/managers`, {
            name: user.value.name,
            email: user.value.email,
            address: user.value.address
        })

        alert('Profil mis à jour avec succès ! 🚀')
    } catch (error) {
        console.error('Erreur lors de la mise à jour du profil', error)
        alert('Une erreur est survenue lors de la mise à jour.')
    } finally {
        loading.value = false
    }
}

// Mettre à jour le mot de passe
const updatePassword = async () => {
    loading.value = true
    try {
        await axios.put(`${AppDatas.baseUrl}/managers/password`, {
            current_password: password.value.current,
            new_password: password.value.new,
            password_confirmation: password.value.confirm
        })

        alert('Mot de passe mis à jour ! 🔒')
        password.value = { current: '', new: '', confirm: '' }
    } catch (error) {
        console.error('Erreur lors de la mise à jour du mot de passe', error)
        alert('Erreur lors de la mise à jour du mot de passe.')
    } finally {
        loading.value = false
    }
}

// Charger les infos utilisateur au démarrage
getUserInfo()
</script>
    