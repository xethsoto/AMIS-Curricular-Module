<template>
    <div>
        <NuxtLayout name="curricular-table"
            :data="proposals"
            :meta="meta"
            :uri="uri"
            searchLabel="Filter By Proposal Name"
            :noStatus="true"
            :proposalTable="true"
        >
            <template v-slot:title>Proposals Management</template>

            <!-- <template v-slot:more-filters>
                <Dropdown
                    :items="type"
                    label="Type"
                    class="w-1/2"
                />
                <Dropdown
                    :items="subType"
                    label="Sub-Type"
                    class="w-1/2"
                />
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
        if (proposals.value){
            proposals.value.forEach(proposal => {
                // convert to Set to remove duplicates
                proposal.target = [...new Set(proposal.target)].join(', ')
                proposal.type = [...new Set(proposal.type)].join(', ')
                // excluding null sub types to join
                proposal.sub_type = [...new Set(proposal.sub_type.filter((subType) => subType !== null))].join(', ')
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

    const type = [
        "Institution",
        "Revision",
        "Abolition",
        "Crosslisting",
        "Adoption"
    ]
    const subType = [
        "Change in course number and/or course title",
        "Change in course description",
        "Change in prerequisites",
        "Change in semester offering",
        "Change in number of hours",
        "Addition of laboratory/recitation/computation",
        "Deletion of laboratory/recitation/computation",
        "Change in course content"
    ]
</script>

<style scoped>

</style>