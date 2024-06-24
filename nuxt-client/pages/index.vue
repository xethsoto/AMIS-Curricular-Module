<template>
    <div class="flex justify-center self-center mt-24">
        <PrimeCard
            class="w-2/3 text-center" 
        >
            <template #title>Greetings, {{ username }}</template>
            <template #subtitle>Welcome to the Academic Management Information System (AMIS)</template>
            <template #content>
                <p>
                    To access the courses and course proposals management feature of this application. Click the hamburger icon at the top-left corner of the app to open the directory.
                    Click the ellipsis icon at the top-right corner of the website to logout.
                </p>
                <p class="italic m-4 text-xs">
                    Note: This is a prototype application to be used for testing and showcasing a potential feature for AMIS. THIS IS NOT THE ACTUAL AMIS WEB APPLICATION.
                </p>
            </template>
        </PrimeCard>
    </div>
</template>

<script setup>
    definePageMeta({
        middleware: 'auth'
    })

    const username = useCookie('username')
</script>