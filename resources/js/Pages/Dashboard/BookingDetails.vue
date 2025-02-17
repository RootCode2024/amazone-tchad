<template>
  <Layout>
    <FlightDetails v-if="type === 'flight'" />
    <FlightHotelDetails v-else-if="type === 'flighthotel'" />
    <HotelDetails v-else-if="type === 'hotel'" />
    <CarLocationDetails v-else />
  </Layout>
</template>

<script setup>
import Layout from "../../Layouts/AppLayout.vue";
import FlightDetails from "../../Components/DetailsBookingComponents/FlightDetails.vue";
import FlightHotelDetails from "../../Components/DetailsBookingComponents/FlightHotelDetails.vue";
import HotelDetails from "../../Components/DetailsBookingComponents/HotelDetails.vue";
import CarLocationDetails from "../../Components/DetailsBookingComponents/CarLocationDetails.vue";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

// Récupérer l'ID depuis l'URL (/bookings/flight/:id)
const id = route.params.id;
const type = route.params.type;

</script>

<style scoped>
/* Vous pouvez ajouter ici des styles personnalisés supplémentaires si nécessaire */
</style>
