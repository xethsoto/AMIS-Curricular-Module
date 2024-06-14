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
                <div>
                    <label class="text-base font-bold">New Prerequisites</label>
                    <div class="flex flex-col gap-4">
                        <p class="text-sm italic text-red-500">
                            <span class="font-bold">NOTE: </span>
                            <span>Leaving the field empty means </span>
                            <span class="font-bold">NO PREREQUISITES</span>
                        </p>
    
                        <!-- Input Field -->
                        <PrimeInputGroup>
                            <PrimeInputText
                                disabled
                                v-model="formContent.newPrereqs" 
                                class="border-2 p-2 text-input"
                            />
                            <PrimeButton
                                icon="pi pi-calendar" 
                                style="color: white" 
                                class="bg-maroon" 
                                @click="showTable"
                            />
                        </PrimeInputGroup>
                        <Table v-if="viewTable"
                            :data="courses" 
                            searchLabel="Filter by Course Code" 
                            :rowsOption="false" 
                            class="border-2"
                        >
                            <template v-slot:columns>
                                <PrimeColumn 
                                    v-for="entry in meta"
                                    sortable 
                                    :key="entry.field" 
                                    :field="entry.field" 
                                    :header="entry.header"
                                >
                                </PrimeColumn>
    
                                <PrimeColumn key="action" field="action" header="Action">
                                    <template #body="slotProps">
                                        <PrimeButton v-if="formContent.newPrereqs.includes(slotProps.data.code)"
                                            class="btn-maroon w-20"
                                            label="Remove"
                                            @click="removePrereq(slotProps.data.code)"
                                        />
                                        <PrimeButton v-else
                                            class="btn-green w-20"
                                            label="Select"
                                            @click="formContent.newPrereqs.push(slotProps.data.code)"
                                        />
                                    </template>
                                </PrimeColumn>
    
                            </template>
                        </Table>
                    </div>
                </div>

            </div>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const {data: fetchedCourses} = await useFetch("http://localhost:8000/api/get-courses")
    const courses = computed(() => {
        return fetchedCourses.value.filter(course => course.id !== formContent.selectedCourse.id)
    })
    
    const viewTable = ref(true)
    
    const meta = [
        {
            field: "code",
            header: "Course Code",
        },
        {
            field: "title",
            header: "Title",
        },
        {
            field: "credit",
            header: "Units",
        },
        {
            field: "status",
            header: "Status",
        }
    ]

    const showTable = () => {
        viewTable.value = !viewTable.value
    }
    
    const emit = defineEmits(['inputValue'])
    
    const formContent = reactive({
        selectedCourse: "",
        newPrereqs: [],
        rationale: ""
    })
    const prereqsView = ref(null)

    const removePrereq = (code) => {
        const index = formContent.newPrereqs.indexOf(code)
        if (index !== -1) {
            formContent.newPrereqs.splice(index, 1);
        }
    }
        
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