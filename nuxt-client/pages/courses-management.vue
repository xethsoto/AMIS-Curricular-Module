<template>
    <div>
        <NuxtLayout name="curricular-table" :tableData="courses" :tableColMeta="columns">
            <template v-slot:title>Courses Management</template>

            <template v-slot:search-bars>
                <SearchBar>Course Code</SearchBar>
                <SearchBar>Title</SearchBar>
                <Dropdown :items="dropdownItems" :label="dropdownLabel">Status</Dropdown>
                <div>
                    <UButton size="md" class="btn-maroon">Apply Filter</UButton>
                </div>
            </template>
        </NuxtLayout>
    </div>
</template>

<script setup>
    let dropdownLabel = ref('Active')
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

    const dropdownItems = [
        [{
            label: 'Active',
            click: () => {
                dropdownLabel.value = 'Active'
                this.$emit('end', )
            }
        },
        {
            label: 'Abolished',
            click: () => {
                dropdownLabel.value = 'Abolished'
            }
        }],
    ]

    onMounted(() => {
        courses.value.forEach((course) => {
            if (course.status === "Active"){
                course.status = { value: "Active", class: "bg-green-500"}
            } else {
                course.status = { value: "Abolished", class: "bg-red-500"}
            }
        })
    })
</script>

<style scoped>

</style>