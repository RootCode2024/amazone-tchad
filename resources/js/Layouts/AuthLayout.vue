<template>
  <div class="w-full flex flex-wrap h-screen bg-white font-sans">
    <!-- Section Auth -->
    <div class="w-full md:w-1/2 flex flex-col justify-center px-4 md:px-12 lg:px-24">
      <div class="flex justify-center md:justify-center mb-2">
        <div class="w-1/2">
          <img :src="Logo" alt="logo Amazone Tchad" />
        </div>
      </div>

      <slot></slot>
    </div>

    <!-- Section Image -->
    <div class="w-1/2 hidden md:block">
      <img class="object-cover w-full h-full" :src="RightImage" alt="Background" />
    </div>
  </div>
</template>


<script setup>
  import Logo from '../Assets/Images/logo.png';
  import RightImage from '../Assets/Images/authImage.jpg';
</script>