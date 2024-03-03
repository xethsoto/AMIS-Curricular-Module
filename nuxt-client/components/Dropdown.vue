<template>
    <div>
        <!-- <UFormGroup :label="label">
            <UDropdown :items="items">
                <div class="relative block w-24 disabled:cursor-not-allowed disabled:opacity-75 focus:outline-none border-0 form-input rounded-none placeholder-gray-400 dark:placeholder-gray-500 text-sm px-3 py-2 shadow-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                    {{ val }}
                </div>
                <UButton square icon="i-heroicons-chevron-down-20-solid" class="btn-maroon rounded-none">
                </UButton>
            </UDropdown>
        </UFormGroup> -->
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