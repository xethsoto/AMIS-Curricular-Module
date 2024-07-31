<template>
    <div class="flex flex-row gap-4">
        <p class="font-semibold">{{ title }}</p>
        <p>{{ content }}</p>
    </div>
</template>

<script setup>
    const {title, content} = defineProps(['title', 'content'])
</script>

<style scoped>

</style>