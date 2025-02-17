<template>
    <Layout>
        <div class="flex flex-col items-center justify-start min-h-screen text-center">
            <h1 class="text-2xl font-semibold text-gray-800">Vérification de l'email</h1>
            <p class="mt-2 text-gray-600">
                Un email de vérification a été envoyé à votre adresse. Veuillez vérifier votre boîte de réception et cliquer sur le lien pour activer votre compte.
            </p>
            <button 
                @click="resendVerificationEmail" 
                :disabled="isLoading"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:bg-gray-400"
            >
                {{ isLoading ? "Envoi en cours..." : "Renvoyer l'email de vérification" }}
            </button>
        </div>
    </Layout>
</template>

<script setup>
import Layout from '../../Layouts/AuthLayout.vue'
import AppDatas from '../../Services/app.js'
import { ref } from 'vue'

const isLoading = ref(false);

const resendVerificationEmail = async () => {
    isLoading.value = true;

    try {
        const response = await axios.post(`${AppDatas.baseUrl}/email/resend`, {}, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('token')}`
            }
        });

        alert(response.data.message || "Un nouvel email de vérification a été envoyé !");
    } catch (error) {
        if (error.response) {
            if (error.response.status === 400) {
                alert("Votre email est déjà vérifié.");
            } else if (error.response.status === 401) {
                alert("Session expirée. Veuillez vous reconnecter.");
                localStorage.removeItem("token");
                router.push("/login");
            } else {
                alert("Une erreur est survenue. Veuillez réessayer.");
            }
        } else {
            console.error("Erreur lors de l'envoi de l'email de vérification", error);
            alert("Impossible d'envoyer l'email.");
        }
    } finally {
        isLoading.value = false; // Désactive l'état de chargement
    }
};

</script>
