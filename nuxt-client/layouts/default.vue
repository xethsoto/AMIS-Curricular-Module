<template>
    <div>
        <header>
            <nav class="flex h-16 flex-row justify-between items-center px-6">
                <!-- Logo and Hamburger Icon -->
                <div class="flex items-center">
                    <button class="mr-2">
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8">
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
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
                    <button>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="h-6 w-6 inline-block cursor-pointer">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </nav>
        </header>

        <div class="relative overflow-x-hidden items-top justify-center min-h-screen h-full bg-gray-100 pt-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <slot></slot>
            </div>
        </div>

    </div>
</template>

<script setup>
    definePageMeta({
        colorMode:'dark'
    })

    const name = ref('USER')
</script>

<style scoped>

</style>