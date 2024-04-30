<template>
    <p class="font-bold text-center text-lg text-black">Course Revision</p>
    <p class="font-bold text-center text-base text-black">Change in course number and/or course title</p>
        
    <CourseToEdit @input="formContent.selectedCourse=$event"/>

    <!-- Course Code and/or Title -->
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

    <!-- Rationale -->
    <FormInput 
        type="text-area"
        label="Rationale"
        @input="formContent.rationale = $event"
    />
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

    const searchLabel = "Filter by Course Code"

    const selectItem = (item, showTable) => {
        showTable()
        return formContent.selectedCourse = item.data.code
    }

    // condition for item selecting in course table
    const condition = (slotProps) => {
        return formContent.selectedCourse === slotProps.data.code
    }

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>