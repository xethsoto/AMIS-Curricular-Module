<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in number of hours
        </template>
        <template #main-fields>
            <FormInput 
                type="text-field" 
                label="New Number of Hours" 
                @input="formContent.newNumOfHours = $event"
            />
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

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>