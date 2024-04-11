<template>
    <div class="flex flex-col gap-5">
        <!-- Title -->
        <div class="flex flex-row gap-4">
            <NuxtLink to="/courses-management">
                <PrimeButton label="Close" size="lg" class="btn-maroon"/>
            </NuxtLink>
            <h1 class="page-title">
                <slot name="viewer-title"></slot>
            </h1>
        </div>

        <!-- Body -->
        <div v-if="pending">
            <p class="text-center font-bold">Loading data. Please wait...</p>
        </div>
        
        <div v-else class="flex flex-col bg-white p-7 gap-4">
            <!-- Title -->
            <h2 class="font-bold text-xl">
                <slot name="title"></slot>
            </h2>
            <slot name="date-created"></slot>

            <hr class="h-px border-0 bg-black dark:bg-gray-700">

            <!-- Content -->
            <slot name="contents"></slot>
        </div>
    </div>
</template>

<script setup>
    const {prevLink, pending} = defineProps(['prevLink', 'pending'])
</script>

<style scoped>

</style>