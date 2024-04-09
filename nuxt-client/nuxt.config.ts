// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  plugins: [
    '~/plugins/primevue-toast.js'
  ],
  modules: ['nuxt-primevue', "@nuxtjs/tailwindcss"],
  primevue: {
    components: {
      prefix: 'Prime',
    }
  },
  css: ['primevue/resources/themes/aura-light-green/theme.css'],
})