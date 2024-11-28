<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in number of hours
        </template>
        <template #main-fields>

            <div v-if="formContent.selectedCourse"
                class="flex flex-col gap-4"
            >
                <!-- Current Number of Hours -->
                <label class="text-base font-bold">Current Number of Hours</label>
                <div class="ml-4">
                    <p v-if="currNumOfHours">{{ currNumOfHours }}</p>
                    <p v-else class="italic">No number of hours indicated</p>
                </div>

                <!-- New Number of Hours -->
                <label class="text-base font-bold">New Number of Hours</label>
                <FormInput 
                    type="text-field" 
                    label="New Number of Hours" 
                    @input="formContent.newNumOfHours = $event"
                />
            </div>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newNumOfHours: "",
        rationale: ""
    })
    const currNumOfHours = ref("")

    watch(() => formContent.selectedCourse, (newSelectedCourse) => {
        currNumOfHours.value = newSelectedCourse?.num_of_hours
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>