<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course number and/or course title
        </template>
        <template #main-fields>

            <div v-if="formContent.selectedCourse"
                class="flex flex-col gap-4"
            >
                <!-- Current course title / code -->
                <label class="text-base font-bold">Current Course Title / Code</label>
                <div class="flex flex-row gap-4 ml-4">
                    <div class="flex-1">
                        <label class="text-center font-semibold">Course Code</label>
                        <p v-if="currCode">{{ currCode }}</p>
                        <p v-else class="italic">No course code indicated</p>
                    </div>

                    <div class="flex-1">
                        <label class="text-center font-semibold">Course Title</label>
                        <p v-if="currTitle">{{ currTitle }}</p>
                        <p v-else class="italic">No course title indicated</p>
                    </div>
                </div>

                <!-- New course title / code -->
                <div class="flex flex-col">
                    <label class="text-base font-bold">New Course Title / Code</label>
                    <PrimeSelectButton
                        v-model="formContent.formType"
                        :options="formTypeOptions"
                        class="self-center"
                    />
                    <FormInput
                        v-if="formContent.formType === 'Code only' || formContent.formType === 'Code and Title'"
                        type="text-field"
                        label="New Course Code"
                        @input="formContent.newCourseCode = $event"
                    />
                    <FormInput 
                        v-if="formContent.formType === 'Title only' || formContent.formType === 'Code and Title'"
                        type="text-field" 
                        label="New Course Title" 
                        @input="formContent.newCourseTitle = $event"
                    />
                </div>
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

    const currCode = ref("")
    const currTitle = ref("")

    watch(() => formContent.selectedCourse, (newSelectedCourse) => {
        currCode.value = newSelectedCourse?.code
        currTitle.value = newSelectedCourse?.title
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>