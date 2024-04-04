<template>
    <header class="sticky inset-x-0 top-0 z-50 bg-white">
        <nav class="flex h-16 flex-row justify-between items-center px-6">
            <!-- Logo and Hamburger Icon -->
            <div class="flex items-center">
                <PrimeButton class="mr-2" @click="openDrawer">
                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8">
                        <path d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </PrimeButton>
                <div class="flex items-center m-auto md:mx-4 gap-4">
                    <img src="assets\images\uplb-official-logo.webp" alt="UPLB Official Logo" class="w-auto h-12"> 
                    <h1 class="font-semibold text-xl text-gray-900 hidden md:flex items-center">AMIS</h1>
                </div>
            </div>

            <!-- Account Name and Three-dots Icon -->
            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <img src="assets\images\no-picture.webp" alt="No Profile Picture" class="h-8 w-auto md:mr-4">
                    <h1 class="font-semibold text-lg text-gray-900 hidden md:flex items-center">{{ name }}</h1>
                </div>
                <PrimeButton>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-6 w-6 inline-block cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                </PrimeButton>
            </div>
        </nav>
    </header>

    <!-- Drawer -->
    <div v-if="openedDrawer" class="drawer absolute bg-white h-full z-50 w-1/6">
        <div class="">
            <PrimeAccordion>
                <PrimeAccordionTab class="font-medium" header="Academic Catalog">
                    <NuxtLink to="/courses-management">
                        <PrimeButton class="font-semibold p-1" label="Courses Management"/>
                    </NuxtLink>
                    <NuxtLink to="/proposal-encoding">
                        <PrimeButton class="font-semibold p-1" label="Proposal Encoding"/>
                    </NuxtLink>
                </PrimeAccordionTab>
            </PrimeAccordion>
        </div>
    </div>

    <div class="relative overflow-x-hidden items-top justify-center min-h-screen h-full bg-gray-100 pt-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contents -->
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
    definePageMeta({
        colorMode:'dark'
    })

    const openedDrawer = ref(false)
    const name = ref('USER')

    const openDrawer = (event) => {
        event.stopPropagation()
        openedDrawer.value = !openedDrawer.value
        document.addEventListener('click', closeDrawer)
    }

    const closeDrawer = (event) => {
        if (!event.target.closest('.drawer')){
            openedDrawer.value = false
            document.removeEventListener('click', handleOutsideClick)
        }
    }

</script>

<style scoped>
</style>