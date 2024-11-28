<template>
    <p class="font-bold text-center text-lg text-black">Course Revision</p>
    <p class="font-bold text-center text-base text-black">
        <slot name="subtype"></slot>    <!-- Subtype of course revision -->
    </p>
        
    <SingleCourseSelect inputFieldLabel="Course to Edit" @input="selectedCourse=$event"/>

    <!-- Main Fields for Revisions -->
    <slot name="main-fields"></slot>

    <!-- Rationale -->
    <FormInput 
        type="text-area"
        label="Rationale"
        @input="rationale=$event"
    />
</template>

<script setup>
    const emit = defineEmits(['selectedCourse', 'rationale'])

    const selectedCourse = ref(null)
    const rationale = ref("")

    watchEffect(() => {
        emit('selectedCourse', selectedCourse.value)
        emit('rationale', rationale.value)
    })
</script>

<style scoped>
</style>