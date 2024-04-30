<template>
    <p class="font-bold text-center text-lg text-black">Course Revision</p>
    <p class="font-bold text-center text-base text-black">Change in course description</p>

    <CourseToEdit @input="formContent.selectedCourse=$event"/>

    <label>New Semester Offering</label>
    <SemOffering @input="formContent.newSemOffering = $event"/>  

    <!-- Rationale -->
    <FormInput
        type="text-area"
        label="Rationale"
        @input="formContent.rationale = $event"
    />
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newSemOffering: "",
        rationale: ""
    })

    const searchLabel = "Filter by Course Code"

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>