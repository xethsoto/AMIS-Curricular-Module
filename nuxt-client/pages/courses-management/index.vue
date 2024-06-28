<template>
    <div>
        <NuxtLayout name="curricular-table"
            :data="fetchedCourses"
            :meta="meta"
            :uri="uri"
            searchLabel="Filter By Course Code or Title"
            :proposalTable = false
        >
            <template v-slot:title>Courses Management</template>
        </NuxtLayout>
    </div>
</template>

<script setup>
    const uri = "/courses-management/"
    const config = useRuntimeConfig()
    const apiUrl = config.public.api_url

    definePageMeta({
        middleware: ['auth']
    })

    const { data: fetchedCourses } = await useFetch(`${apiUrl}/api/get-courses`, {
        lazy: false,
        server: false
    })

    watchEffect(() => {
        if (fetchedCourses.value) {
            fetchedCourses.value.forEach(course => {
                course.sem_offered = course.sem_offered.join(', ');
            });
        }
    });

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