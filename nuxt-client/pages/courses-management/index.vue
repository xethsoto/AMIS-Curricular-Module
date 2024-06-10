<template>
    <div>
        <NuxtLayout name="curricular-table"
            :data="courses"
            :meta="meta"
            :uri="uri"
            searchLabel="Filter By Course Code or Title"
            :globalFilterFields="['code', 'title']"
        >
            <template v-slot:title>Courses Management</template>
        </NuxtLayout>
    </div>
</template>

<script setup>
    const uri = "/courses-management/"

    const { data: courses } = await useFetch('http://localhost:8000/api/get-courses', {
        lazy: false,
        server: false
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
        }
    ]
</script>

<style scoped>

</style>