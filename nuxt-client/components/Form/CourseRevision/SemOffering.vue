<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in semester offered
        </template>
        <template #main-fields>

            <div v-if="formContent.selectedCourse"
                class="flex flex-col gap-4"
            >
                <!-- Current Semester Offering/s -->
                <label class="text-base font-bold">Current Semester Offerings</label>
                <div class="ml-4">
                    <p v-if="currSemOffering">{{ currSemOffering }}</p>
                    <p v-else class="italic">No semester offerings indicated</p>
                </div>

                <!-- Current Semester Offering/s -->
                <label class="text-base font-bold">New Semester Offerings</label>
                <label class="text-sm italic text-red-500">
                    <span class="font-bold">NOTE: </span>
                    <span>Please also tick current semester offering/s if they are still needed</span>
                </label>
                <SemOffering
                    @input="formContent.newSemOffering = $event"
                    :initVal="formContent.currSemOffering"
                />
            </div>
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
    const currSemOffering = ref("")

    watch(() => formContent.selectedCourse, (newSelectedCourse) => {
        currSemOffering.value = newSelectedCourse?.sem_offered.map(sem => {
            if (sem === '1') return "1st Semester"
            else if (sem === '2') return "2nd Semester"
            else if (sem === 'M') return "Midyear"
            else if (sem === 'S') return "Summer"
        }).join(", ")
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>