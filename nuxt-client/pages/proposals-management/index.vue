<template>
    <div>
        <NuxtLayout name="curricular-table" :data="courses" :meta="meta" :uri="uri">
            <template v-slot:title>Proposals Management</template>

            <!-- <template v-slot:search-bars>
                <SearchBar label="Course Code" @textbox="updCodeCont"/>
                <SearchBar label="Title" @textbox="updTitleCont"/>
                <Dropdown label="Status" :items="dropdownItems" :value="filterData.dropdownLabel"/>
            </template> -->
            
        </NuxtLayout>
    </div>
</template>

<script setup>
    const uri = "/proposals-management/"
    const { data: proposals } = await useFetch('http://localhost:8000/api/get-proposals', {
        lazy: false,
        server: false
    })
    console.log("proposals = ", proposals.value)
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