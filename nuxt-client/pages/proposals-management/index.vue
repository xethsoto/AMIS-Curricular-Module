<template>
    <div>
        <NuxtLayout name="curricular-table" :data="proposals" :meta="meta" :uri="uri" :noStatus="true">
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
    const { data: proposals } = await useFetch('http://localhost:8000/api/get-proposals-basic-info', {
        lazy: false,
        server: false,
    })
    
    /* converting classification array to string
    *  for table displaying
    */

    watchEffect(() => {
        console.log("proposals = ", proposals)
        console.log("proposals.value = ", proposals.value)

        if (proposals.value){
            proposals.value.forEach(proposal => {
                proposal.target = proposal.target.join(', ')
                proposal.type = proposal.type.join(', ')
                // excluding null sub types to join
                proposal.sub_type = proposal.sub_type.filter((subType) => subType !== null).join(', ')
            })
        }
    })
    
    const meta = [
        {
            field: 'name',
            header: "Name",
        },
        {
            field: 'date_created',
            header: "Date",
        },
        {
            field: 'target',
            header: "Target",
        },
        {
            field: 'type',
            header: "Type",
        },
        {
            field: 'sub_type',
            header: "Sub-Type",
            render: (data) => {
                return data.sub_type ? data.sub_type : "N/A"
            }
        }
    ]
</script>

<style scoped>

</style>