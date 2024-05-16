<template>
    <NuxtLayout name="course-revision" 
        @selectedCourse="formContent.selectedCourse=$event"
        @rationale="formContent.rationale=$event"
    >
        <template #subtype>
            Change in course prerequisites
        </template>
        <template #main-fields>
            <!-- New prerequisites list and table -->
            <div v-if="formContent.selectedCourse">
                <label class="text-base font-bold">New Prerequisites</label>
                <p class="text-sm italic text-red-500">
                    <span class="font-bold">NOTE: </span>
                    <span>Include ALL required prerequisites. All prerequisites listed here will replace all the currently existing prerequisites.</span>
                </p>
                <TableCourse
                    :searchLabel="searchLabel"
                    :selectItem="addPrereqs"
                    :condition="addPrereqsCondition"
                    :excludeCourse = "formContent.selectedCourse.id"
                >
                    <template v-slot:input-field>
                        {{ formContent.newPrereqs }}
                        <PrimeChips 
                            v-model="formContent.newPrereqs" 
                            class="w-full p-2 text-base"
                        />
                    </template>
                </TableCourse>
            </div>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: null,
        newPrereqs: [],
        rationale: ""
    })
    
    // FIXME: Getting current prerequisites of the selected course
    // watch(() => formContent.selectedCourse, async (courseCode) => {
    //     console.log("here")
    //     const {data: courseResult} = await useFetch(`/api/code-to-course/${courseCode}`)
    //     formContent.newPrereqs = courseResult.prereqs
    //     console.log("formContent.newPrereqs = ", formContent.newPrereqs);
    // })

    const searchLabel = "Filter by Course Code"

    const addPrereqs = (item) => {
        return formContent.newPrereqs.push(item.data.code)
    }

    // condition for selecting an already selected course in 'New Prerequisites' table
    const addPrereqsCondition = (slotProps) => {
        return formContent.newPrereqs.includes(slotProps.data.code)
    }

    watch(formContent, (newVal) => {
        emit('inputValue', newVal)
    }, {deep: true});
</script>

<style scoped>
</style>