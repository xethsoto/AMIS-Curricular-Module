<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course content
        </template>
        <template #main-fields>

            <div v-if="formContent.selectedCourse"
                class="flex flex-col gap-4"
            >
                <!-- Current Course Content -->
                <label class="text-base font-bold">Current Course Content</label>
                <div class="flex flex-row gap-4 ml-4">
                    <div class="flex-1">
                        <label class="text-center font-semibold">Course Goal</label>
                        <p v-if="currGoal">{{ currGoal }}</p>
                        <p v-else class="italic">No course goal indicated</p>
                    </div>
        
                    <div class="flex-1">
                        <label class="text-center font-semibold">Course Outline</label>
                        <p v-if="currOutline">{{ currOutline }}</p>
                        <p v-else class="italic">No course outline indicated</p>
                    </div>
                </div>
        
                <!-- New Course Content -->
                <label class="text-base font-bold">New Course Content</label>
                <div class="flex flex-col gap-4">
                    <FormInput 
                        type="text-area" 
                        label="New Course Goal" 
                        @input="formContent.newGoal = $event"
                    />
        
                    <FormInput 
                        type="text-area" 
                        label="New Course Outline" 
                        @input="formContent.newOutline = $event"
                    />
                </div>
            </div>
            
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newGoal: "",
        newOutline: "",
        rationale: ""
    })
    const currGoal = ref("")
    const currOutline = ref("")

    watch(() => formContent.selectedCourse, (newSelectedCourse) => {
        currGoal.value = newSelectedCourse?.goal
        currOutline.value = newSelectedCourse?.outline
    })

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>