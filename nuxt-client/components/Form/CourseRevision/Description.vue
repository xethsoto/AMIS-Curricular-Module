<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course description
        </template>
        <template #main-fields>
            <!-- Course Description -->
            <FormInput 
                type="text-area" 
                label="New Course Description" 
                @input="formContent.newDescription = $event"
            />
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newDescription: "",
        rationale: ""
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>