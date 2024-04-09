<template>
    <div>
        <NuxtLayout name="curricular-table" :data="courses" :meta="meta">
            <template v-slot:title>Courses Management</template>

            <!-- <template v-slot:search-bars>
                <SearchBar label="Course Code" @textbox="updCodeCont"/>
                <SearchBar label="Title" @textbox="updTitleCont"/>
                <Dropdown label="Status" :items="dropdownItems" :value="filterData.dropdownLabel"/>
            </template> -->
            
        </NuxtLayout>
    </div>
</template>

<script setup>
    // Get Request (using useFetch doesn't work)
    const promise = useFetch('http://localhost:8000/api/get-courses', { immediate: false })
    await promise.execute({_initial: true})

    const courses = promise.data.value
    
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
        }
    ]

    // // Adding the semester offering info to the each course object
    // courses.value.forEach((course) => {
    //     const tempCont = semOffered.value.filter((entry)=>{
    //         return entry.course_id === Number(course.id)
    //     })

    //     console.log("tempCont = ", tempCont)
        
    //     course.sem_offered = ''
    //     tempCont.forEach((entry) => {
    //         course.sem_offered += `${entry.sem_offered} `
    //     })
    // })

    // // Content = textbox content
    // // Label = label of textbox
    // const updCodeCont = (content) => {
    //     filterData.courseCodeContent = content
    // }

    // const updTitleCont = (content) => {
    //     filterData.titleContent = content
    // }

    // console.log("courses = ", courses.value)
</script>

<style scoped>

</style>