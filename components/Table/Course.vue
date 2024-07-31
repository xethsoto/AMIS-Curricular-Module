<template>
    <PrimeInputGroup>
        <slot name="input-field"></slot>
        <PrimeButton
            icon="pi pi-calendar" 
            style="color: white" 
            class="bg-maroon" 
            @click="showTable"
        />
    </PrimeInputGroup>
    <Table v-if="viewTable"
        :data="courses" 
        :searchLabel="searchLabel" 
        :rowsOption="false" 
        class="border-2"
    >
        <template v-slot:columns>
            <PrimeColumn 
                v-for="entry in meta"
                sortable 
                :key="entry.field" 
                :field="entry.field" 
                :header="entry.header"
            >
            </PrimeColumn>

            <PrimeColumn v-if="!customAction" key="action" field="action" header="Action">
                <template #body="slotProps">
                    <p v-if="condition(slotProps)" class="italic font-normal">Selected</p>
                    <PrimeButton v-else class="btn-maroon" label="Select" @click="selectItem(slotProps, showTable)"/>
                </template>
            </PrimeColumn>
            <slot v-else name="custom-action"></slot>

        </template>
    </Table>
</template>

<script setup>
    const {searchLabel, selectItem, condition, customAction} = defineProps(['searchLabel', 'selectItem', 'condition', 'customAction'])

    const config = useRuntimeConfig()
    const apiUrl = config.public.api_url
    const {data: courses} = await useFetch(`${apiUrl}/api/get-courses`)
    
    const viewTable = ref(true)
    
    const meta = [
        {
            field: "code",
            header: "Course Code",
        },
        {
            field: "title",
            header: "Title",
        },
        {
            field: "credit",
            header: "Units",
        },
        {
            field: "status",
            header: "Status",
        }
    ]

    const showTable = () => {
        viewTable.value = !viewTable.value
    }    
</script>

<style scoped>

</style>