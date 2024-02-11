<template>
    <div>
        <NuxtLayout name="curricular-table" :tableData="courses" :tableColMeta="columns" :filterData="filterData">
            <template v-slot:title>Courses Management</template>

            <template v-slot:search-bars>
                <SearchBar label="Course Code" @textbox="updCodeCont"/>
                <SearchBar label="Title" @textbox="updTitleCont"/>
                <Dropdown title="Status" :items="dropdownItems" :label="filterData.dropdownLabel"/>
            </template>
            
        </NuxtLayout>
    </div>
</template>

<script setup>
    const {data: courses} = await useFetch("http://localhost:3001/courses")
    const {data: semOffered} = await useFetch("http://localhost:3001/sem_offered")

    const filterData = reactive({
        courseCodeContent: '',
        titleContent: '',
        dropdownLabel: 'Active'
    })
    
    const columns = [
        {
            key: 'code',
            label: "Course Code",
            sortable: true,
        },
        {
            key: 'title',
            label: "Title",
            sortable: true,
        },
        {
            key: 'desc',
            label: "Description",
            sortable: true
        },
        {
            key: 'sem_offered',
            label: "Sem Offered",
            sortable: true
        },
        {
            key: 'credit',
            label: "Units",
            sortable: true
        },
        {
            key: 'status',
            label: "Status",
            sortable: true
        },
        {
            key: 'actions',
            label: "Action",
        }
    ]

    const dropdownItems = [
        [{
            label: '--',
            click: () => {
                filterData.dropdownLabel = '--'
            }
        },
        {
            label: 'Active',
            click: () => {
                filterData.dropdownLabel = 'Active'
            }
        },
        {
            label: 'Abolished',
            click: () => {
                filterData.dropdownLabel = 'Abolished'
            }
        }]
    ]

    // Adding the semester offering info to the each course object
    courses.value.forEach((course) => {
        const tempCont = semOffered.value.filter((entry)=>{
            return entry.course_id === Number(course.id)
        })

        console.log("tempCont = ", tempCont)
        
        course.sem_offered = ''
        tempCont.forEach((entry) => {
            course.sem_offered += `${entry.sem_offered} `
        })
    })

    onMounted(() => {
        courses.value.forEach((course) => {

            // Used for styling the status data in tables
            if (course.status === "Active"){
                course.status = { value: "Active", class: "bg-green-500"}
            } else {
                course.status = { value: "Abolished", class: "bg-red-500"}
            }

        })
    })

    // Content = textbox content
    // Label = label of textbox
    const updCodeCont = (content) => {
        filterData.courseCodeContent = content
    }

    const updTitleCont = (content) => {
        filterData.titleContent = content
    }

    console.log("courses = ", courses.value)
</script>

<style scoped>

</style>