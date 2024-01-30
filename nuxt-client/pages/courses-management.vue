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
            class: 'text-2xl'
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
            label: "Action"
        }
    ]
</script>

<style scoped>

</style>