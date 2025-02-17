<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Liste des reservations de vehicule</h1>

    <div class="bg-white p-4 rounded-lg shadow-md">
      <!-- Barre de recherche -->
      <div class="flex justify-between mb-4">
        <input
          v-model="search"
          type="text"
          placeholder="Rechercher un vehicule..."
          class="border p-2 rounded-lg w-1/3"
        />
      </div>

      <!-- Tableau des réservations -->
      <table class="w-full border-collapse border rounded-lg">
        <thead>
          <tr class="bg-gray-200">
            <th class="p-3 text-left">#</th>
            <th class="p-3 text-left">Client</th>
            <th class="p-3 text-left">Lieu de reception</th>
            <th class="p-3 text-left">Date de début</th>
            <th class="p-3 text-left">Date de fin</th>
            <th class="p-3 text-left">Statut</th>
            <th class="p-3 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(car, index) in filteredCars" :key="car.id" class="border-b">
            <td class="p-3">{{ index + 1 }}</td>
            <td class="p-3">{{ car.customer.name }}</td>
            <td class="p-3">{{ car.origin.name }}</td>
            <td class="p-3">
              {{ formatDate(car.started_date) }}
            </td>
            <td class="p-3">
              {{ formatDate(car.ended_date) }}
            </td>
            <td class="p-3">
              <span
                @click="openStatusModal(car)"
                class="px-3 py-1 rounded-full text-white text-sm cursor-pointer"
                :class="{
                  'bg-yellow-500': car.status === 'pending',
                  'bg-green-500': car.status === 'approved',
                  'bg-red-500': car.status === 'rejected'
                }"
                v-text="(car.status === 'pending') ? 'En attente' : (car.status === 'approved') ? 'Validé' : 'Rejeté'"
              >

              </span>
            </td>
            <td class="p-3 flex space-x-2">
              <router-link
                :to="`/bookings/car-location/${car.id}`"
                class="bg-indigo-600 text-white px-1 py-1 rounded-lg text-sm hover:bg-indigo-700"
              >
                <Eye class="w-4 h-4" />
              </router-link>
              <button
                @click="confirmDelete(car)"
                class="bg-red-600 text-white px-1 py-1 rounded-lg text-sm hover:bg-red-700"
              >
                <Trash class="w-4 h-4" />
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div class="flex justify-between mt-4">
        <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 bg-gray-300 rounded-lg">
          Précédent
        </button>
        <span>Page {{ currentPage }} / {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 bg-gray-300 rounded-lg">
          Suivant
        </button>
      </div>
    </div>
    <!-- 🚀 MODALE -->
    <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
        <h2 class="text-xl font-bold mb-4">Modifier le statut du vol :</h2>
        
        <label class="block mb-2">Nouveau Statut</label>
        <select v-model="selectedStatus" class="w-full border p-2 rounded-lg">
          <option value="pending">En attente</option>
          <option value="approved">Validé</option>
          <option value="rejected">Rejeté</option>
        </select>
        <form @submit.prevent="updateStatus" v-if="selectedStatus === 'rejected'" class="mt-4">
          <div class="py-2">
            <label for="reason" class="block mb-2">Raison du rejet :</label>
            <textarea v-model="formRejectedStatus.rejectionReason" id="reason" placeholder="Nous avons pas pu trouver un vol pour la date demandé mais ..." class="w-full border p-2 rounded-lg"></textarea>
          </div>
          <div class="py-2">
            <label for="departure_date">Date de début</label>
            <input type="date" v-model="formRejectedStatus.startedDate" class="w-full border p-2 rounded-lg" />
          </div>
          <div class="py-2">
            <label for="price">Prix</label>
            <input type="number" v-model="formRejectedStatus.price" class="w-full border p-2 rounded-lg" />
          </div>
          <div class="flex justify-end mt-4">
            <button @click="closeModal" class="px-4 py-2 bg-gray-300 rounded-lg mr-2">Annuler</button>
            <button type="submit" :disabled="buttonLoading" class="px-4 py-2 min-w-28 bg-blue-600 text-white rounded-lg">
              <span v-if="!buttonLoading">Enregistrer</span>
              <div v-else class="flex items-center">
                <svg class="mr-3 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="font-medium"> Enregistrement ... </span>
              </div>
            </button>
          </div>
        </form>
        <form @submit.prevent="updateStatus" v-else>
          <div class="my-2" v-if="!takeNote">
            <button
              type="button"
              class="flex py-1 px-2 rounded bg-slate-700 text-white"
              @click="takeNote = !takeNote"
            >
              <div class="flex items-end justify-end">
                <Plus class="w-5" />
              </div>
              <span class="italic ml-2">Ajouter une note</span>
            </button>
          </div>
          <div v-if="takeNote">
            <div class="py-2">
              <label for="note">Note</label>
              <textarea
                id="note"
                name="note"
                v-model="note"
                class="w-full border p-2 rounded-lg"
                placeholder="Saisissez votre note ici..."
              ></textarea>
            </div>
          </div>
          <div class="flex justify-end mt-4">
            <button @click="closeModal" type="button" class="px-4 py-2 bg-gray-300 rounded-lg mr-2">
              Annuler
            </button>
            <button type="submit" :disabled="buttonLoading" class="px-4 py-2 min-w-28 bg-blue-600 text-white rounded-lg">
              <span v-if="!buttonLoading">Enregistrer</span>
              <div v-else class="flex items-center">
                <svg class="mr-3 h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="font-medium"> Enregistrement ... </span>
              </div>
            </button>
          </div>
        </form>


      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed, defineProps } from "vue";
  import { Eye, Trash, Plus } from "lucide-vue-next";
  import axios from "axios";
  import AppDatas from "../../Services/app.js";
  import Swal from 'sweetalert2';

  const props = defineProps({
    carLocations: {
      type: Array,
      required: true,
    },
  });

  const search = ref("");
  const errorMessage = ref("");
  const itemsPerPage = 5;
  const currentPage = ref(1);
  const takeNote = ref(false);

  // 🔹 État pour gérer la modale
  const isModalOpen = ref(false);
  const selectedCar = ref(null);
  const selectedStatus = ref("");
  const note = ref("");
  const buttonLoading = ref(false);

  const formRejectedStatus = ref({
    newStatus: "rejected",
    rejectionReason: "",
    startedDate: "",
    price: "",
  });

  // 🔹 Ouvrir la modale avec les infos du vehicule sélectionné
  const openStatusModal = (car) => {
    selectedCar.value = car;
    selectedStatus.value = car.status;
    isModalOpen.value = true;
    note.value = car.note;

    if (car.status === "rejected") {
      formRejectedStatus.value.rejectionReason = car.note;
      formRejectedStatus.value.startedDate = car.started_date;
      formRejectedStatus.value.price = car.price;
    }
  };

  // 🔹 Fermer la modale
  const closeModal = () => {
    isModalOpen.value = false;
    selectedCar.value = null;
    selectedStatus.value = "";
  };

  // Ici on accède aux données et applique un filtre de recherche
  const filteredCars = computed(() => {
    const searchTerm = search.value.toLowerCase();

    return props.carLocations
      .filter(car => {
        return (
          car.customer?.name?.toLowerCase().includes(searchTerm) ||
          car.origin.name.toLowerCase().includes(searchTerm) ||
          car.status.toLowerCase().includes(searchTerm)
        );
      })
      .slice((currentPage.value - 1) * itemsPerPage, currentPage.value * itemsPerPage);
  });

  // Fonction pour formater la date
  const formatDate = (dateString) => {
    if (!dateString) return "Date invalide";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", { day: "2-digit", month: "long", year: "numeric" });
  };

  // Pagination
  const totalPages = computed(() => Math.ceil(props.carLocations.length / itemsPerPage));

  const nextPage = () => {
    if (currentPage.value < totalPages.value) currentPage.value++;
  };

  const prevPage = () => {
    if (currentPage.value > 1) currentPage.value--;
  };

  //Changement de statut
  const updateStatus = async () => {
    if (!selectedCar.value) return;

    // Vérifier que si le statut est "rejected", la raison est renseignée
    if (selectedStatus.value === "rejected" && !formRejectedStatus.value.rejectionReason) {
      errorMessage.value = "La raison du rejet est requise.";
      return;
    }

    // Construire le payload selon le statut choisi
    let payload = {};
    if (selectedStatus.value === "rejected") {
      payload = {
        newStatus: "rejected",
        note: formRejectedStatus.value.rejectionReason,
        startedDate: formRejectedStatus.value.startedDate,
        price: formRejectedStatus.value.price,
      };
    } else {
      payload = {
        newStatus: selectedStatus.value,
        note: note.value,
      };
    }

    try {
      buttonLoading.value = true;
      const response = await axios.put(
        `${AppDatas.baseUrl}/car-locations/${selectedCar.value.id}/status`,
        payload
      );
      console.log(response.data);
      
      // Mettre à jour localement le statut du vehicule
      selectedCar.value.status = selectedStatus.value;
      
      // Fermer la modale après la mise à jour
      closeModal();
    } catch (error) {
      console.error("Erreur lors de la mise à jour du statut", error);
      errorMessage.value = "Erreur lors de la mise à jour du statut. Veuillez réessayer.";
    } finally {
      buttonLoading.value = false;
      toastFunction("success", "Statut mis à jour avec succès !");
    }
  };

  // ⚡ Fonction pour supprimer un vol+hotel
  const confirmDelete = async (car) => {
    Swal.fire({
        title: "Êtes-vous sûr ?",
        text: `Voulez-vous vraiment supprimer la réservation de ${car.customer.name} ?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Oui, supprimer !",
        cancelButtonText: "Annuler",
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`${AppDatas.baseUrl}/car-locations/${car.id}`);

                Swal.fire({
                    title: "Supprimé !",
                    text: "La réservation a été supprimée avec succès.",
                    icon: "success",
                    timer: 2000,
                    showConfirmButton: false,
                });

                // Rafraîchir la liste après suppression
                props.carLocations.splice(props.carLocations.indexOf(car), 1);
            } catch (error) {
                Swal.fire({
                    title: "Erreur",
                    text: "Une erreur s'est produite lors de la suppression.",
                    icon: "error",
                });
                console.error("Erreur lors de la suppression", error);
            }
        }
    });
  };

  const toastFunction = (type, message) => {
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Toast.fire({
      icon: type,
      title: message
    });
  };
</script>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 10px;
  text-align: left;
}

th {
  background-color: #f3f4f6;
}

button:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
