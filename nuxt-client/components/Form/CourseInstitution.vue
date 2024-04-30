<template>
    <FormInput type="text-field" label="Course Code" @input="formContent.num = $event"/>
    <FormInput type="text-field" label="Course Title" @input="formContent.title = $event"/>
    <FormInput type="text-area" label="Course Description" @input="formContent.desc = $event"/>
    <FormInput type="text-field" label="Course Credit" @input="formContent.credit = $event"/>
    <FormInput type="text-field" label="Number of Hours" @input="formContent.numOfHours = $event"/>
    <FormInput type="text-area" label="Course Goal" @input="formContent.goal = $event"/>
    <FormInput type="text-area" label="Course Outline" @input="formContent.outline = $event"/>

    <!-- Prerequisites -->
    <label class="text-sm">Prerequisites</label>
    <PrimeInputGroup>
        <PrimeChips 
            v-model="formContent.prereqs" 
            class="w-full p-2 text-base"
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
        :searchLabel="searchLabel" 
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
                    <PrimeButton v-if="!formContent.prereqs.includes(slotProps.data.code)" class="btn-maroon" label="Select" @click="selectItem(slotProps)"/>
                    <p v-else class="italic font-normal">Added</p>
                </template>
            </PrimeColumn>
        </template>
    </Table>

    <!-- Sem Offerings -->
    <label>Semester Offering</label>
    <SemOffering @input="formContent.sem_offered=$event"/>

    <!-- Rationale -->
    <FormInput type="text-area" label="Rationale" @input="formContent.rationale = $event"/>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])
    
    //API calls
    const {data: courses} = await useFetch("http://localhost:8000/api/get-courses")

    const viewTable = ref(true)
    const searchLabel = "Course Prerequisites"
    const formContent = reactive({
        num: "",
        title: "",
        desc: "",
        credit: 1,
        numOfHours: 1,
        goal: "",
        outline: "",
        prereqs: [],
        sem_offered: [],
        rationale: ""
    })
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

    // Adds selected item to prerequisite list
    const selectItem = (item) => {
        const courseCode = item.data.code
        const prereqsList = formContent.prereqs

        if (!prereqsList.includes(courseCode)){
            prereqsList.push(courseCode)
        }
    }

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
    .chips {
        border: 2px solid #ccc;
    }
</style>