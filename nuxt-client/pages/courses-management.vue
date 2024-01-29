<template>
    <div>
        <h1>Courses Management</h1>
        <div class="container h-12">

        </div>
        <div v-if="courses.length > 0">
            <UTable :rows="courses" :columns="columns" class="bg-white rounded-lg p-4">
                <template #actions-data>
                    <UButton color="red" label="Click Here"/>
                </template>

                <template #title-header class="font-bold">
                </template>
            </UTable>
        </div>
        <div v-else>
            <p>Loading...</p>
        </div>
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