<template>
    <p class="font-bold text-center text-lg text-black">Course Crosslisting</p>
    <SingleCourseSelect 
        inputFieldLabel="Course to Edit" 
        @input="formContent.selectedCourse=$event"
    />

    <!-- Course to be crosslisted -->
    <div v-if="formContent.selectedCourse"
        class="flex flex-col"
    >
        <label class="text-sm font-bold">
            Course to be crosslisted
        </label>

        <PrimeInputGroup>
            <PrimeInputText
                disabled 
                id="text-input" 
                variant="filled" 
                type="text" 
                v-model="formContent.crosslistCourse.code" 
                class="text-input p-2 text-base border-2"
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
                        <p v-if="formContent.crosslistCourse === slotProps.data.code"
                            class="italic font-normal"
                        >
                            Selected
                        </p>
                        <PrimeButton 
                            v-else 
                            class="btn-maroon" 
                            label="Select" 
                            @click="selectItem(slotProps)"
                        />
                    </template>
                </PrimeColumn>

            </template>
        </Table>
    </div>

    <FormInput 
        type="text-area"
        label="Rationale"
        @input="formContent.rationale=$event"
    />
</template>

<script setup>
    const {data: fetchedCourses} = await useFetch("http://localhost:8000/api/get-courses")
    const emit = defineEmits(['inputValue'])
    const formContent = reactive({
        selectedCourse: "",
        crosslistCourse: "",
        rationale: ""
    })

    const courses = computed(() => {
        return fetchedCourses.value.filter(course => course.id !== formContent.selectedCourse.id)
    })

    const viewTable = ref(true)

    watchEffect(() => {
        emit('inputValue', formContent)
    })

    const showTable = () => {
        viewTable.value = !viewTable.value
    }    

    const selectItem = (item) => {
        showTable()
        formContent.crosslistCourse = item.data
    }

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
</script>

<style scoped>
</style>