<template>
    <div>
        <div class="flex flex-col">
            <label for="dropdown" class="text-gray-500 text-sm">{{ label }}</label>
            <PrimeDropdown id="dropdown" v-model="dropdownVal" :options="items"/>
        </div>
    </div>
</template>

<script setup>
    const {items, value, label} = defineProps(['items', 'value', 'label'])
    const emit = defineEmits(['dropdownVal'])

    const dropdownVal = ref()
    watchEffect(() => {
        emit("dropdownVal", dropdownVal.value)
    })

</script>

<style scoped>
</style>