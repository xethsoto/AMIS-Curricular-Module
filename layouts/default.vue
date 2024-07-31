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

            <div class="flex items-center gap-4">
                <div class="flex items-center">
                    <img src="assets\images\no-picture.webp" alt="No Profile Picture" class="h-8 w-auto md:mr-4">
                    <h1 class="font-semibold text-lg text-gray-900 hidden md:flex items-center">{{ name }}</h1>
                </div>
                <div>
                    <PrimeButton type="button" icon="pi pi-ellipsis-v" @click="ellipsisToggle" aria-haspopup="true" aria-controls="overlay_menu" />
                    <PrimeMenu ref="ellipsisMenu" id="overlay_menu" :model="ellipsisItems" :popup="true" />
                </div>
            </div>
        </nav>
    </header>

    <!-- Drawer -->
    <div v-if="openedDrawer" class="drawer fixed bg-white h-full z-50 w-1/6">
        <PrimeMenu
            :model="menuItems"
            :pt="{
                icon: { class: 'text-black' },
                submenuHeader: { class: 'text-black' },
                label: { class: 'text-black' }
            }"
        />
    </div>

    <div id="main-part" class="relative overflow-x-hidden items-top justify-center min-h-screen h-full pt-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Contents -->
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
    import { useRouter } from 'vue-router'

    const userType = useCookie('user-type')

    definePageMeta({
        middleware: ['auth']
    })

    const openedDrawer = ref(false)
    const ellipsisMenu = ref()
    const router = useRouter()
    
    const authToken = useCookie('auth-token')
    const userTypeCookie = useCookie('user-type')
    const name = useCookie('username')

    const openDrawer = (event) => {
        event.stopPropagation()
        openedDrawer.value = !openedDrawer.value
        document.addEventListener('click', closeDrawer)
    }

    const closeDrawer = (event) => {
        if (!event.target.closest('.drawer')){
            openedDrawer.value = false
            document.removeEventListener('click', closeDrawer)
        }
    }

    const logout = () => {
        authToken.value = null
        userTypeCookie.value = null
        router.push('/login')
    }

    const ellipsisItems = ref([
        {
            items: [
                {
                    label: 'Logout',
                    icon: 'pi pi-sign-out',
                    command: logout
                }
            ]
        }
    ]);

    const ellipsisToggle = (event) => {
        ellipsisMenu.value.toggle(event);
    };

    const menuItems = ref([
        {
            items: [
                {
                    label: 'Home',
                    icon: 'pi pi-home',
                    command: () => router.push('/')
                }
            ]
        },
        {
            label: 'Academic Catalog',
            items: [
                {
                    label: 'Courses Management',
                    icon: 'pi pi-book',
                    command: () => router.push('/courses-management')
                },
                {
                    label: 'Proposals Management',
                    icon: 'pi pi-file',
                    command: () => router.push('/proposals-management')
                },
                {
                    label: 'Proposal Encoding',
                    icon: 'pi pi-pencil',
                    command: () => router.push('/proposal-encoding'),
                    visible: userType.value === 'admin'
                }
            ]
        }
    ])

</script>

<style>
    #main-part{
        background-color: whitesmoke;
    }
</style>