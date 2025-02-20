<template>
  <AuthLayout>
    <div class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md" v-if="step === 1">
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
            :readonly="true"
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
    <div class="max-w-md mx-auto bg-white p-6 rounded-md shadow-md" v-if="step === 2">
      <p class="my-5">Mot de passe réinitialisé avec succès</p>
      <router-link to="/login" class="my-5 bg-indigo-600 hover:bg-indigo-700 py-2 px-4 text-lg rounded text-white">Se connecter</router-link>
    </div>
  </AuthLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthLayout from "../../Layouts/AuthLayout.vue";
import axios from 'axios';
import AppDatas from "../../Services/app.js";
import { RouterLink } from 'vue-router';

const email = ref(null);
const password = ref("");
const passwordConfirmation = ref("");
const loading = ref(false);
const errorMessage = ref(null);
const successMessage = ref(null);
const token = ref(null);
const step = ref(1);

// Récupérer le token et l'email depuis la query string
onMounted(() => {
  token.value = new URLSearchParams(window.location.search).get('token');
  email.value = new URLSearchParams(window.location.search).get('email');

  console.log('Token:', token.value);
  console.log('Email:', email.value);
});

const resetPassword = async () => {
  loading.value = true;
  errorMessage.value = null;
  successMessage.value = null;

  try {
    const response = await axios.post(`${AppDatas.baseUrl}/password/reset/save`, {
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
      token: token.value,
    }, {
      headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content,
      },
      withCredentials: true,
    });

    successMessage.value = "Mot de passe réinitialisé avec succès.";
    loading.value = false;
  } catch (error) {
    if (error.response && error.response.data) {
      errorMessage.value = error.response.data.message || "Une erreur est survenue.";
    } else {
      errorMessage.value = "Erreur réseau ou serveur.";
    }
  } finally {
    step.value = 2;
    loading.value = false;
    email.value = '';
    password.value = '';
    passwordConfirmation.value = '';
  }
}
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
