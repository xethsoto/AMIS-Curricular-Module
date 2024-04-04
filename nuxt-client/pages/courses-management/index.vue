<template>
    <div>
        <NuxtLayout name="curricular-table" :data="courses" :meta="meta" :filterData="filterData">
            <template v-slot:title>Courses Management</template>

            <template v-slot:search-bars>
                <SearchBar label="Course Code" @textbox="updCodeCont"/>
                <SearchBar label="Title" @textbox="updTitleCont"/>
                <Dropdown label="Status" :items="dropdownItems" :value="filterData.dropdownLabel"/>
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
    
    const meta = [
        {
            field: 'code',
            header: "Course Code",
        },
        {
            field: 'title',
            header: "Title",
        },
        {
            field: 'desc',
            header: "Description",
        },
        {
            field: 'sem_offered',
            header: "Sem Offered",
        },
        {
            field: 'credit',
            header: "Units",
        },
        {
            field: 'status',
            header: "Status",
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
        // courses.value.forEach((course) => {

        //     // Used for styling the status data in tables
        //     if (course.status === "Active"){
        //         course.status = "Active"
        //     } else {
        //         course.status = "Abolished"
        //     }

        // })
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