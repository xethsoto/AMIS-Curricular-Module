<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course content
        </template>
        <template #main-fields>
            <!-- Course Description -->
            <FormInput 
                type="text-area" 
                label="New Course Goal" 
                @input="formContent.newGoal = $event"
            />

            <FormInput 
                type="text-area" 
                label="New Course Outline" 
                @input="formContent.newOutline = $event"
            />
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newGoal: "",
        newOutline: "",
        rationale: ""
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>