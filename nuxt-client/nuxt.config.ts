// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  modules: ['@nuxt/ui', 'nuxt-primevue'],
  colorMode: {
    preference: 'light'
  },
  css: ['primevue/resources/themes/aura-light-green/theme.css']
})
