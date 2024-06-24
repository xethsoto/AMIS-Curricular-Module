<template>
    <div>Home</div>
</template>

<script setup>
    definePageMeta({
        middleware: 'auth'
    })

    const userType = useCookie('user-type')
    console.log("userType = ", userType)
</script>