import { useCookie } from '#app'

// redirect to login if not authenticated
export default defineNuxtRouteMiddleware((to, from) => {
    const authToken = useCookie('auth-token');

    // If trying to access other pages without being authenticated, redirect to login
    if (!authToken.value && (to.path !== '/login' || to.path !== '/register')) {
        return navigateTo('/login');
    }
});