<template>
    <p class="font-bold text-center text-lg text-black">Course Crosslisting</p>
    <SingleCourseSelect 
        inputFieldLabel="Course to Edit" 
        @input="formContent.selectedCourse=$event"
    />
    <SingleCourseSelect
        inputFieldLabel="Course to be crosslisted" 
        @input="formContent.crosslistCourse = $event"
    />
    <FormInput 
        type="text-area"
        label="Rationale"
        @input="formContent.rationale=$event"
    />
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        crosslistCourse: "",
        rationale: ""
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>