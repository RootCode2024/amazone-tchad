<template>
  <AuthLayout>
    <div class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md">
      <h2 class="text-2xl font-semibold text-center">Réinitialiser le mot de passe</h2>
      
      <!-- Message de succès ou d'erreur -->
      <p v-if="errorMessage" class="text-red-500 text-sm mt-2">{{ errorMessage }}</p>
      <p v-if="successMessage" class="text-green-500 text-sm mt-2">{{ successMessage }}</p>

      <form @submit.prevent="resetPassword">
        <div class="mt-4">
          <label for="email" class="block text-sm">Email</label>
          <input
            type="email"
            v-model="email"
            placeholder="Email"
            class="w-full px-4 py-2 border rounded-md mt-1"
            required
          />
        </div>

        <div class="mt-4">
          <label for="password" class="block text-sm">Nouveau mot de passe</label>
          <input
            type="password"
            v-model="password"
            placeholder="Nouveau mot de passe"
            class="w-full px-4 py-2 border rounded-md mt-1"
            required
          />
        </div>

        <div class="mt-4">
          <label for="password_confirmation" class="block text-sm">Confirmer le mot de passe</label>
          <input
            type="password"
            v-model="passwordConfirmation"
            placeholder="Confirmer le mot de passe"
            class="w-full px-4 py-2 border rounded-md mt-1"
            required
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="btn-submit mt-4 w-full py-2 px-4 bg-blue-600 text-white rounded-md"
        >
          <span v-if="loading">Réinitialisation...</span>
          <span v-else>Réinitialiser le mot de passe</span>
        </button>
      </form>
    </div>
  </AuthLayout>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import AppDatas from '../../Services/app.js'


const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;



// Définition des variables réactives
const email = ref("");
const password = ref("");
const passwordConfirmation = ref("");
const loading = ref(false);
const errorMessage = ref(null);
const successMessage = ref(null);

// Récupérer token et email depuis l'URL
const urlParams = new URLSearchParams(window.location.search);
const token = window.location.pathname.split("/")[4]; // Récupérer le token depuis le chemin de l'URL
const userEmail = urlParams.get("email");  // Récupérer l'email à partir des paramètres de l'URL

email.value = userEmail;  // Remplir automatiquement le champ email dans le formulaire

const resetPassword = async () => {
  loading.value = true;
  errorMessage.value = null;
  successMessage.value = null;

  try {
    const response = await axios.post(`${AppDatas.baseUrl}/password/reset`, {
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
      token: token,
    }, {
      headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
      },
      withCredentials: true,
    });

    successMessage.value = "Mot de passe réinitialisé avec succès.";
    loading.value = false;
    console.log(response);
  } catch (error) {
    loading.value = false;
    if (error.response && error.response.data) {
      errorMessage.value = error.response.data.message || "Une erreur est survenue.";
    } else {
      errorMessage.value = "Erreur réseau ou serveur.";
    }
  }
};


</script>



<style scoped>
/* Style spécifique à cette page */
.btn-submit {
  background-color: #3490dc;
  transition: background-color 0.3s;
}

.btn-submit:hover {
  background-color: #2779bd;
}
</style>
