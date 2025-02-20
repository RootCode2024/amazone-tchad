import { createRouter, createWebHistory } from 'vue-router';
import axios from "axios";

// Importation des composants
import Home from '../Pages/Home.vue';
import Dashboard from '../Pages/Dashboard/Index.vue';
import Register from '../Pages/Auth/Register.vue';
import Login from '../Pages/Auth/Login.vue';
import Bookings from '../Pages/Dashboard/Booking.vue';
import Customers from '../Pages/Dashboard/Customers.vue';
import Profile from '../Pages/Dashboard/Profile.vue';
import BookingDetails from '../Pages/Dashboard/BookingDetails.vue';
import CustomerDetails from '../Pages/Dashboard/CustomerDetails.vue';
import Managers from '../Pages/Dashboard/Managers.vue';
import ManagerDetails from '../Pages/Dashboard/ManagerDetails.vue';
import EmailVerify from '../Pages/Auth/EmailVerify.vue';
import NotFound from '../Pages/NotFound.vue';
import AppLayout from '../Layouts/AppLayout.vue';
import ForgotPassword from '../Pages/Auth/ForgotPassword.vue';
import ResetPassword from "../Pages/Auth/ResetPassword.vue";

const routes = [
    { path: '/', component: Home },
    { path: "/register", name: "Register", component: Register, meta: { guestOnly: true } },
    { path: "/login", name: "Login", component: Login, meta: { guestOnly: true } },
    { path: "/email-verify", name: "EmailVerify", component: EmailVerify },
    { path: "/forgot_password", name: "ForgotPassword", component: ForgotPassword },
    { path: "/password/reset/:token", name: "ResetPassword", component: ResetPassword },

    // Routes protégées
    {
        path: '/dashboard',
        component: AppLayout,  // Utilisation d'un layout global
        meta: { requiresAuth: true },
        children: [
            { path: '', component: Dashboard },
            { path: 'profile', component: Profile },
            { path: 'bookings', component: Bookings },
            { path: 'bookings/:type/:id', component: BookingDetails },
            { path: 'customers', component: Customers },
            { path: 'customers/:id', component: CustomerDetails },
            { path: 'managers', component: Managers },
            { path: 'managers/:id', component: ManagerDetails },
        ]
    },

    { path: '/:pathMatch(.*)*', component: NotFound }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

// ✅ Fonction pour vérifier l'authentification
const isAuthenticated = async () => {
    const token = localStorage.getItem("token");
    
    if (!token) return false;

    try {
        const response = await axios.get(`${import.meta.env.VITE_API_BASE_URL}/user`, {
            headers: { Authorization: `Bearer ${token}` }
        });

        return response.status === 200;
    } catch (error) {
        console.error("Authentication failed:", error);
        localStorage.removeItem("token"); // Supprime le token expiré
        return false;
    }
};

// ✅ Middleware de navigation
router.beforeEach(async (to, from, next) => {
    const isLoggedIn = await isAuthenticated();

    if (to.meta.requiresAuth && !isLoggedIn) {
        next("/login"); // Redirection vers la page de connexion
    } else if (to.meta.guestOnly && isLoggedIn) {
        next("/dashboard"); // Empêcher les utilisateurs connectés d’accéder à /login ou /register
    } else {
        next();
    }
});

export default router;
