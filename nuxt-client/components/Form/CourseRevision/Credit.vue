<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course credit
        </template>
        <template #main-fields>

            <div v-if="formContent.selectedCourse"
                class="flex flex-col gap-4"
            >
                <!-- Current Course Credit -->
                <label class="text-base font-bold">Current Course Credit</label>
                <div class="ml-4">
                    <p v-if="currCredit">{{ currCredit }}</p>
                    <p v-else class="italic">No course credit indicated</p>
                </div>

                <!-- New Course Credit -->
                <label class="text-base font-bold">New Course Credit</label>
                <FormInput 
                    type="text-field" 
                    label="New Course Credit" 
                    @input="formContent.newCredit = $event"
                />
            </div>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newCredit: "",
        rationale: ""
    })
    const currCredit = ref("")

    watch(() => formContent.selectedCourse, (newSelectedCourse) => {
        currCredit.value = newSelectedCourse?.credit
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>