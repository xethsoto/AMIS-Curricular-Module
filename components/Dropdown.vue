<template>
    <div class="flex flex-col w-full">
        <label for="dropdown" class="text-gray-500">
            <span class="text-sm">{{ label }}</span>
        </label>
        <PrimeDropdown 
            id="dropdown" 
            v-model="dropdownVal" 
            variant="filled" 
            :options="items" 
            class="dropdown text-base"
        />
    </div>
</template>

<script setup>
    const {items, label} = defineProps(['items', 'label'])
    const emit = defineEmits(['dropdownVal'])

    const dropdownVal = ref(items[0])
    watchEffect(() => {
        emit("dropdownVal", dropdownVal.value)
    })

</script>

<style scoped>
    .dropdown {
        border: 2px solid #ccc;
    }
</style>