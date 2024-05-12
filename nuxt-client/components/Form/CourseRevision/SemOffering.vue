<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in semester offered
        </template>
        <template #main-fields>
            <label>New Semester Offering</label>
            <label class="text-sm italic text-red-500">
                <span class="font-bold">NOTE: </span>
                <span>Include ALL semester offering. All offerings selected here will replace all the currently existing offerings.</span>
            </label>
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