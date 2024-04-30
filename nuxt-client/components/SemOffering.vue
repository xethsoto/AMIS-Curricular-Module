<template>
    <div class="flex flex-row gap-4">
        <div v-for="offering of semOfferings" :key="offering" class="flex flex-row gap-2">
            <PrimeCheckbox 
                inputId="offering"
                v-model="semOffered"
                name="sem-offering"
                :value="offering[0]"
                class="checkbox"
                :pt = "{
                    box: {class:'border-2 border-gray-300 rounded'}
                }"
            />
            <label :for="offering" class="text-sm">{{ offering }}</label>
        </div>
    </div>
</template>

<script setup>
    const emit = defineEmits(['input'])
    const semOfferings = ["1st Semester", "2nd Semester", "Midyear"]

    const semOffered = ref([])

    watchEffect(() => {
        emit("input", semOffered.value)
    })
</script>

<style scoped>

</style>