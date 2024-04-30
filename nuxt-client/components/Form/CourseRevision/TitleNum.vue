<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course number and/or course title
        </template>
        <template #main-fields>
            <div class="flex flex-col items-center">
                <PrimeSelectButton
                    v-model="formContent.formType"
                    :options="formTypeOptions"
                />
                <FormInput 
                    v-if="formContent.formType === 'Title only' || formContent.formType === 'Code and Title'"
                    type="text-field" 
                    label="New Course Title" 
                    @input="formContent.newCourseTitle = $event"
                />
                <FormInput
                    v-if="formContent.formType === 'Code only' || formContent.formType === 'Code and Title'"
                    type="text-field"
                    label="New Course Code"
                    @input="formContent.newCourseCode = $event"
                />
            </div>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formTypeOptions = [
        "Code only",
        "Title only",
        "Code and Title"
    ]

    const formContent = reactive({
        selectedCourse: "",
        formType: "Code only", // "Number only", "Code only", "Code and Title
        newCourseTitle: "",
        newCourseCode: "",
        rationale: ""
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>