<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course description
        </template>
        <template #main-fields>
            <label>New Semester Offering</label>
            <SemOffering @input="formContent.newSemOffering = $event"/>
        </template>
    </NuxtLayout>
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