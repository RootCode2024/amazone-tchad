<template>
<!-- component -->
<div class="lg:px-24 lg:py-24 md:py-20 md:px-44 px-4 py-24 items-center flex justify-center flex-col-reverse lg:flex-row md:gap-28 gap-16">
            <div class="xl:pt-24 w-full xl:w-1/2 relative pb-12 lg:pb-0">
                <div class="relative">
                    <div class="absolute">
                        <div class="">
                            <h1 class="my-2 text-gray-800 font-bold text-2xl">
                                <span class="text-4xl">O</span>n dirait que vous avez trouvé la
                                porte vers le grand néant
                            </h1>
                            <p class="my-5 text-gray-800">Désolé pour cela ! Veuillez visiter notre page d'accueil pour trouver ce que vous cherchez.</p>
                            <router-link to="{{ baseUrl }}" class="sm:w-full lg:w-auto my-2 border rounded md py-4 px-8 text-center bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-700 focus:ring-opacity-50">Ramenez-moi là-bas !</router-link>
                        </div>
                    </div>
                    <div>
                        <img src="https://i.ibb.co/G9DC8S0/404-2.png" />
                    </div>
                </div>
            </div>
            <div>
                <img src="https://i.ibb.co/ck1SGFJ/Group.png" />
            </div>
        </div>
</template>
  
<script setup>
import { RouterLink } from 'vue-router';
import AppDatas from '../Services/app.js';

const baseUrl = AppDatas.baseUrl;

</script>
  