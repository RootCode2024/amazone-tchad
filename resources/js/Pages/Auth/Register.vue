<template>
  <AuthLayout>
    <div class="text-center">
      <h2 class="text-3xl font-bold">Bienvenue !</h2>
      <p class="text-gray-600 mt-2 italic">Rejoignez-nous et profitez d'une expérience exceptionnelle.</p>
    </div>

    <form class="flex flex-col pt-6" @submit.prevent="register()">
      <div class="flex flex-col">
        <label class="text-lg font-medium">Nom</label>
        <input type="text" v-model="form.name" placeholder="John Doe" class="input-field" />
        <span v-if="errors.name" class="error">{{ errors.name }}</span>
      </div>

      <div class="flex flex-col mt-4">
        <label class="text-lg font-medium">Email</label>
        <input type="email" v-model="form.email" placeholder="your@email.com" class="input-field" />
        <span v-if="errors.email" class="error">{{ errors.email }}</span>
      </div>

      <div class="flex space-x-4">
        <div class="flex flex-col mt-4">
          <label class="text-lg font-medium">Mot de passe</label>
          <input type="password" v-model="form.password" placeholder="********" class="input-field" />
          <span v-if="errors.password" class="error">{{ errors.password }}</span>
        </div>

        <div class="flex flex-col mt-4">
          <label class="text-lg font-medium">Confirmez le mot de passe</label>
          <input type="password" v-model="form.confirmPassword" placeholder="********" class="input-field" />
          <span v-if="errors.confirmPassword" class="error">{{ errors.confirmPassword }}</span>
        </div>
      </div>

      <button type="submit" class="btn-submit mt-6" :disabled="loading">
        {{ loading ? "Inscription..." : "S'inscrire" }}
      </button>
    </form>

    <div class="text-center pt-4">
      <p>Vous avez déjà un compte ? 
        <router-link to="/login" class="text-blue-600 font-semibold underline">Connectez-vous ici</router-link>
      </p>
    </div>
  </AuthLayout>
</template>

<script setup>
  import { ref } from "vue";
  import { useRouter } from "vue-router";
  import axios from "axios";
  import AuthLayout from "../../Layouts/AuthLayout.vue";
  import AppDatas from "../../Services/app.js";

  const baseUrl = AppDatas.baseUrl;

  console.log(baseUrl);
  const router = useRouter();
  const loading = ref(false);
  const form = ref({
    name: "",
    email: "",
    password: "",
    confirmPassword: "",
  });

const errors = ref({});
const generalError = ref("");

const register = async () => {
  errors.value = {};
  generalError.value = "";

  // Validation basique des champs vides
  if (!form.value.name) errors.value.name = "Le nom est requis.";
  if (!form.value.email) errors.value.email = "L'email est requis.";
  if (!form.value.password) errors.value.password = "Le mot de passe est requis.";
  if (!form.value.confirmPassword) errors.value.confirmPassword = "Veuillez confirmer votre mot de passe.";

  // Vérification du mot de passe
  if (form.value.password && form.value.password !== form.value.confirmPassword) {
    errors.value.confirmPassword = "Les mots de passe ne correspondent pas.";
    return;
  }

  if (Object.keys(errors.value).length > 0) return;

  try {
    loading.value = true;
    const response = await axios.post(`${baseUrl}/register`, {
      name: form.value.name,
      email: form.value.email,
      password: form.value.password,
      password_confirmation: form.value.confirmPassword,
    });

    console.log("✅ Inscription réussie:", response.data);

    if (response.data.token) {
        localStorage.setItem("token", response.data.token);
        console.log("Token enregistré :", response.data.token);
    }
    router.push("/email-verify");

  } catch (error) {
    if (error.response) {
      console.error("Erreurs API:", error.response.data);
      errors.value = error.response.data.errors || {};
    } else {
      generalError.value = "Une erreur est survenue. Veuillez réessayer.";
    }
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
  @apply bg-black text-white font-bold text-lg py-2 rounded hover:bg-gray-700 transition;
}

.error {
  @apply text-red-500 text-sm mt-1;
}
</style>