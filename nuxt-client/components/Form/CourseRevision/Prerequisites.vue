<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course description
        </template>
        <template #main-fields>
            <!-- New prerequisites list and table -->
            <label class="text-sm font-bold">New Prerequisites</label>
            <TableCourse
                :searchLabel="searchLabel"
                :selectItem="addPrereqs"
                :condition="addPrereqsCondition"
            >
                <template v-slot:input-field>
                    <PrimeChips 
                        v-model="formContent.newPrereqs" 
                        class="w-full p-2 text-base"
                    />
                </template>
            </TableCourse>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newPrereqs: [],
        rationale: ""
    })

    const searchLabel = "Filter by Course Code"

    const addPrereqs = (item) => {
        return formContent.newPrereqs.push(item.data.code)
    }

    // condition for selecting an already selected course in 'New Prerequisites' table
    const addPrereqsCondition = (slotProps) => {
        return formContent.newPrereqs.includes(slotProps.data.code)
    }

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>