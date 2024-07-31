<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course description
        </template>
        <template #main-fields>

            <div v-if="formContent.selectedCourse"
                class="flex flex-col gap-4"
            >
                <!-- Current Course Description -->
                <label class="text-base font-bold">Current Course Description</label>
                <div class="ml-4">
                    <p v-if="currDesc">{{ currDesc }}</p>
                    <p v-else class="italic">No course description indicated</p>
                </div>

                <!-- New Course Description -->
                <label class="text-base font-bold">New Course Description</label>
                <FormInput 
                    type="text-area" 
                    label="New Course Description" 
                    @input="formContent.newDescription = $event"
                />
            </div>
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
    const currDesc = ref("")

    watch(() => formContent.selectedCourse, (newSelectedCourse) => {
        currDesc.value = newSelectedCourse?.desc
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>