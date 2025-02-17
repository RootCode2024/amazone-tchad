import { createRouter, createWebHistory } from 'vue-router';

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

const routes = [
    { 
        path: '/', 
        component: Home 
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
        meta: { guestOnly: true }  // 🚀 Rediriger un utilisateur connecté
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: { guestOnly: true }  // 🚀 Rediriger un utilisateur connecté
    },
    {
        path: "/email-verify",
        name: "EmailVerify",
        component: EmailVerify,
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }  // 🔒 Protection par authentification
    },
    {
        path: '/dashboard/profile',
        component: Profile,
        meta: { requiresAuth: true }  // 🔒 Protection par authentification
    },
    {
        path: '/bookings',
        component: Bookings,
        meta: { requiresAuth: true }  // 🔒 Protection par authentification
    },
    {
        path: '/bookings/:type/:id',
        component: BookingDetails,
        meta: { requiresAuth: true }  // 🔒 Protection par authentification
    },
    {
        path: '/customers/:id',
        component: CustomerDetails,
        meta: { requiresAuth: true }  // 🔒 Protection par authentification
    },
    {
        path: '/customers',
        component: Customers,
        meta: { requiresAuth: true }  // 🔒 Protection par authentification
    },
    {
        path: '/managers',
        component: Managers,
        meta: { requiresAuth: true } // 🔒 Protection par authentification
    },
    {
        path: '/managers/:id',
        component: ManagerDetails,
        meta: { requiresAuth: true } // 🔒 Protection par authentification
    },
    {
        path: '/:pathMatch(.*)*',
        component: NotFound,
    }
    
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("token");

    // 🔒 Rediriger les utilisateurs non connectés vers la page de login
    if (to.meta.requiresAuth && !token) {
        next("/login");
    }
    // 🚀 Rediriger les utilisateurs connectés s'ils tentent d'accéder à /login ou /register
    else if (to.meta.guestOnly && token) {
        next("/dashboard");
    }
    else {
        next();
    }
});

export default router;
