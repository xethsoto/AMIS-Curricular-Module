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
        </NuxtLayout>
    </div>
</template>

<script setup>
    const uri = "/proposals-management/"
    const config = useRuntimeConfig()
    const apiUrl = config.public.api_url

    const { data: proposals } = await useFetch(`${apiUrl}/api/get-proposals-basic-info`, {
        lazy: false,
        server: false,
    })

    definePageMeta({
        middleware: ['auth']
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
</script>

<style scoped>

</style>