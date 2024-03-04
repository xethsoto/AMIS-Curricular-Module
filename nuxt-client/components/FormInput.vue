<template>
    <div class="flex flex-col w-full">
        <label for="text-input">
            <span class="text-sm">{{ label }}</span>
        </label>
        <PrimeInputText v-if="type==='text-field'" 
            id="text-input" 
            variant="filled" 
            type="text" 
            v-model="inputValue" 
            class="text-input p-2 text-base"
        />
        <PrimeTextarea v-else-if="type==='text-area'"
            id="text-input"
            variant="filled"
            v-model="inputValue"
            class="text-input p-2 min-h-56 max-h-56"
        />
    </div>
</template>

<script setup>
    const {label, type} = defineProps(['label', 'type'])
    const emit = defineEmits(['input'])

    const inputValue = ref("")
    watchEffect(() => {
        emit("input", inputValue.value)
    })
</script>

<style scoped>
    .text-input {
        border: 2px solid #ccc;
    }
</style>