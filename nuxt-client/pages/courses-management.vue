<template>
    <div>
        <NuxtLayout name="curricular-table" :tableData="courses" :tableColMeta="columns">
            <template v-slot:title>Courses Management</template>
        </NuxtLayout>
    </div>
</template>

<script setup>
    const {data: courses} = await useFetch("http://localhost:3001/courses")

    const columns = [
        {
            key: 'course_code',
            label: "Course Code",
            sortable: true,
        },
        {
            key: 'title',
            label: "Title",
            sortable: true,
        },
        {
            key: 'description',
            label: "Description",
            sortable: true
        },
        {
            key: 'sem_offered',
            label: "Sem Offered",
            sortable: true
        },
        {
            key: 'units',
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

    onMounted(() => {
        courses.value.forEach((course) => {
            console.log(course)
            console.log(course.status)
            if (course.status === "Active"){
                console.log("true")
                course.status = { value: "Active", class: "bg-green-500"}
            } else {
                console.log("false")
                course.status = { value: "Abolished", class: "bg-red-500"}
            }
        })
    })
</script>

<style scoped>

</style>