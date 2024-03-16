<template>
    <p class="font-bold text-center text-lg text-black">Course Abolition</p>
    <div class="flex flex-col">
        <label for="course-to-abolish" class="text-sm font-bold">Course to Abolish</label>
        <PrimeInputGroup iconPosition="right" class="course-to-abolish w-full">
            <PrimeInputText
                disabled 
                id="text-input" 
                variant="filled" 
                type="text" 
                v-model="selectedCourse" 
                class="text-input p-2 text-base border-2"
            />
            <PrimeButton 
                icon="pi pi-calendar"
                style="color: white" 
                class="bg-maroon" 
                @click="showTable"
            />
        </PrimeInputGroup>
    </div>
    <Table v-if="viewTable" :data="courses" :searchLabel="searchLabel" :rowsOption="false" class="border-2">
        <template v-slot:columns>
            <PrimeColumn v-for="entry in meta" sortable :key="entry.field" :field="entry.field" :header="entry.header"></PrimeColumn>
            <PrimeColumn key="action" field="action" header="Action">
                <template #body="slotProps">
                    <PrimeButton class="btn-maroon" label="Select" @click="selectItem(slotProps)"/>
                </template>
            </PrimeColumn>
        </template>
    </Table>
    <FormInput type="text-area" label="Rationale" @inputVal="rationale=$event"/>
</template>

<script setup>
    import 'primeicons/primeicons.css'

    const selectedCourse = ref("")
    const rationale = ref("")
    const viewTable = ref(true)
    const searchLabel = "Filter by Course Code"

    const {data: courses} = await useFetch('http://localhost:3001/courses')
    
    const coursesCode = courses.value.map(course => course.code)
    // TODO: the back-end should complete the fields needed for the table

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

    const selectItem = (item) => {
        selectedCourse.value = item.data.code
        showTable()
    }

</script>

<style scoped>
</style>