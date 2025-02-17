<template>
  <AuthLayout>
    <div class="text-center">
      <h2 class="text-3xl font-bold">Heureux de vous revoir !</h2>
      <p class="text-gray-600 mt-2 italic">Connectez-vous pour accéder à votre compte.</p>
    </div>

    <form class="flex flex-col pt-6" @submit.prevent="login">
      <div class="flex flex-col">
        <label class="text-lg font-medium">Email</label>
        <input type="email" v-model="email" placeholder="your@email.com" class="input-field" required />
      </div>

      <div class="flex flex-col mt-4">
        <label class="text-lg font-medium">Mot de passe</label>
        <input type="password" v-model="password" placeholder="********" class="input-field" required />
      </div>

      <div class="flex justify-between items-center mt-4">
        <label class="flex items-center">
          <input type="checkbox" v-model="rememberMe" class="form-checkbox text-blue-600" />
          <span class="ml-2 text-gray-600">Se souvenir de moi</span>
        </label>
        <a href="#" class="text-blue-600 font-semibold text-sm">Mot de passe oublié ?</a>
      </div>

      <p v-if="errorMessage" class="text-red-500 text-sm mt-2">{{ errorMessage }}</p>

      <button type="submit" class="btn-submit mt-4" :disabled="loading">
        <span v-if="loading">Connexion...</span>
        <span v-else>Se connecter</span>
      </button>
    </form>

    <div class="text-center pt-6">
      <p>Vous n'avez pas encore de compte ?  
        <router-link to="/register" class="text-blue-600 font-semibold underline">Inscrivez-vous ici</router-link>
      </p>
    </div>
  </AuthLayout>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import AuthLayout from "../../Layouts/AuthLayout.vue";
import AppDatas from "../../Services/app.js"

const email = ref("");
const password = ref("");
const rememberMe = ref(false);
const errorMessage = ref("");
const loading = ref(false);
const router = useRouter();

const login = async () => {
  errorMessage.value = "";
  loading.value = true;

  try {
    const response = await axios.post(`${AppDatas.baseUrl}/login`, {
      email: email.value,
      password: password.value,
      remember: rememberMe.value,
    });

    const token = response.data.token;
    localStorage.setItem("token", token);
    axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

    router.push("/dashboard");
  } catch (error) {
    const err = error.response.status;
    if (err == 305)
    {
      router.push('/email-verify');
      return;
    }
    errorMessage.value = "Email ou mot de passe incorrect.";
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
.input-field {
  @apply shadow border rounded w-full py-2 px-3 text-gray-700 mt-1 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-400;
}

.btn-submit {
  @apply bg-black text-white font-bold text-lg py-2 rounded hover:bg-gray-700 transition disabled:bg-gray-500;
}
</style>
