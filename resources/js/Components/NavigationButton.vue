<template>
    <div class="mt-4 flex justify-between">
      <button 
        type="button" 
        @click="$emit('go-back')" 
        class="bg-gray-500 text-white px-4 py-2 rounded"
      >
        Retour
      </button>
  
      <button 
        type="submit" 
        :disabled="buttonLoading" 
        class="px-4 py-2 min-w-28 bg-blue-600 text-white rounded-lg"
      >
        <span v-if="!buttonLoading">{{ submitLabel }}</span>
        <div v-else class="flex items-center">
          <svg class="mr-3 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="font-medium"> Envoie en cours ... </span>
        </div>
      </button>
    </div>
  </template>
  
<script setup>
    import { defineProps } from 'vue';

    const props = defineProps({
        buttonLoading: {
            type: Boolean,
            default: false
        },
        submitLabel: {
            type: String,
            default: 'Valider'
        }
    });
</script>
  