import { useCookie } from '#app'

// redirect to login if not authenticated
export default defineNuxtRouteMiddleware((to, from) => {
    const authToken = useCookie('auth-token');
    const userType = useCookie('user-type')

    // If trying to access other pages without being authenticated, redirect to login
    if (!authToken.value && (to.path !== '/login' || to.path !== '/register')) {
        return navigateTo('/login');
    }

    // prevent non-admin users from accessing proposal encoding page
    if (authToken.value && to.path === '/proposal-encoding' && userType.value !== 'admin') {
        return navigateTo('/forbidden');
    }
});