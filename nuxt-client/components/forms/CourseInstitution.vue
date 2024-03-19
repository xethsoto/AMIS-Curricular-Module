<template>
    <FormInput type="text-field" label="Course Code" @input="formContent.courseNum = $event"/>
    <FormInput type="text-field" label="Course Title" @input="formContent.courseTitle = $event"/>
    <FormInput type="text-area" label="Course Description" @input="formContent.courseDesc = $event"/>
    <FormInput type="text-field" label="Course Credit" @input="formContent.courseCredit = $event"/>
    <FormInput type="text-field" label="Number of Hours" @input="formContent.numOfHours = $event"/>
    <FormInput type="text-area" label="Course Goal" @input="formContent.courseGoal = $event"/>
    <FormInput type="text-area" label="Course Outline" @input="formContent.courseOutline = $event"/>

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
    <div class="flex flex-row gap-4">
        <div v-for="offering of semOfferings" :key="offering">
            <PrimeCheckbox inputId="offering" v-model="formContent.sem_offered" name="sem-offering" :value="offering" class="checkbox"/>
            <label :for="offering" class="text-sm">{{ offering }}</label>
        </div>
    </div>

    <!-- Rationale -->
    <FormInput type="text-area" label="Rationale" @input="formContent.rationale = $event"/>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])
    
    //API calls
    const {data: courses} = await useFetch("http://localhost:3001/courses")

    const viewTable = ref(true)
    const searchLabel = "Course Prerequisites"
    const semOfferings = ["1st Semester", "2nd Semester", "Midyear"]
    const formContent = reactive({
        courseNum: "CMSC 199",
        courseTitle: "Undergraduate Seminar",
        courseDesc: "Undergraduate Seminar for Computer Science",
        courseCredit: 1,
        numOfHours: 1,
        courseGoal: "To have computer science students be acquainted to Seminars",
        courseOutline: "This is the outline",
        prereqs: ["CMSC 100", "CMSC 127"],
        sem_offered: ["1st Semester", "2nd Semester"],
        rationale: "Lorem Ipusm Dolor"
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

    .checkbox {
        border: 2px solid #ccc;
        border-radius: 5px;
    }
</style>