<template>
  <AuthLayout>
    <div class="text-center">
      <h2 class="text-3xl font-bold">Mot de passe oublié ?</h2>
      <p class="text-gray-600 mt-2 italic">Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>
    </div>

    <form class="flex flex-col pt-6" @submit.prevent="sendResetLink">
      <div class="flex flex-col">
        <label class="text-lg font-medium">Email</label>
        <input
          type="email"
          v-model="email"
          placeholder="your@email.com"
          class="input-field"
          required
        />
      </div>

      <p v-if="errorMessage" class="text-red-500 text-sm mt-2">{{ errorMessage }}</p>
      <p v-if="successMessage" class="text-green-500 text-sm mt-2">{{ successMessage }}</p>

      <button
        type="submit"
        class="btn-submit mt-4"
        :disabled="loading"
      >
        <span v-if="loading">Envoi en cours...</span>
        <span v-else>Envoyer le lien</span>
      </button>
    </form>

    <div class="text-center pt-6">
      <p>Vous vous rappelez de votre mot de passe ?  
        <router-link to="/login" class="text-blue-600 font-semibold underline">Retour à la connexion</router-link>
      </p>
    </div>
  </AuthLayout>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import AuthLayout from "../../Layouts/AuthLayout.vue";
import  AppDatas from '../../Services/app.js'

const email = ref('');
const loading = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const router = useRouter();

const sendResetLink = async () => {
  if (!email.value) {
    errorMessage.value = 'L\'email est requis.';
    return;
  }

  loading.value = true;
  errorMessage.value = '';
  successMessage.value = '';

  try {
    const response = await axios.post(`${AppDatas.baseUrl}/password/email`, { email: email.value });

    successMessage.value = 'Un lien de réinitialisation a été envoyé à votre adresse email.';
    email.value = '';
  } catch (error) {
    errorMessage.value = 'Une erreur est survenue. Veuillez réessayer.';
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* Styles personnalisés pour la page de mot de passe oublié */
.container {
  max-width: 600px;
  margin: auto;
}

.input-field {
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.btn-submit {
  background-color: #4CAF50;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-submit:disabled {
  background-color: #bdbdbd;
}

.text-blue-600 {
  color: #3182ce;
}

.text-gray-600 {
  color: #4a5568;
}
</style>
