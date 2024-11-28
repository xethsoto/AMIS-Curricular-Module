<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course prerequisites
        </template>
        <template #main-fields>

            <div v-if="formContent.selectedCourse"
                class="flex flex-col gap-4"
            >
                <!-- Current prerequisites list -->
                <div>
                    <label class="text-base font-bold">Current Course Prerequisites</label>
                    <div class="ml-4">
                        <p v-if="prereqsView">{{ prereqsView }}</p>
                        <p v-else class="italic">No prerequisites</p>
                    </div>
                </div>   

                <!-- New prerequisites list and table -->
                <TablePrereqs
                    :selectedCourse="formContent.selectedCourse"
                    label="New Prerequisites" 
                    @inputValue="formContent.newPrereqs=$event"
                />
            </div>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])
    const prereqsView = ref(null)
    
    const formContent = reactive({
        selectedCourse: "",
        newPrereqs: [],
        rationale: ""
    })
        
    watch(() => formContent.selectedCourse, (newSelectedCourse) => {
        formContent.newPrereqs = newSelectedCourse?.prereqs
        prereqsView.value = newSelectedCourse?.prereqs.join(", ")
    })

    watch(formContent, (newVal) => {
        emit('inputValue', newVal)
    }, {deep: true})
</script>

<style scoped>
</style>