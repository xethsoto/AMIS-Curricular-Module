<template>
    <PrimeDataTable
        :globalFilter="data"
        :value="data"
        paginator
        :rows="5"
        :rowsPerPageOptions="rowsOption ? rowsPerPageOptions : null"
        :globalFilterFields="['code']"
        :pt="{ header: 'table-header', table: 'actual-table' }"
    >   

    <!-- Search Field -->
        <template #header>
            <div class="flex flex-col lg:w-1/4 md:w-1/2">
                <label>
                    <span class="text-sm">{{ searchLabel }}</span>
                </label>
                <PrimeInputText 
                    variant="filled" 
                    type="text" 
                    v-model="globalFilterValue"
                    class="text-input p-2 text-base"
                />
            </div>
        </template>
        <slot name="columns"></slot>

    </PrimeDataTable>
</template>

<script setup>
    /*
    * data = the contents of the table
    * searchLabel = the label of the searchBar of the table
    * rowsOption = if different item number per page is allowed 
    */
    const {data, searchLabel, rowsOption} = defineProps(['data', 'searchLabel', 'rowsOption'])
    const rowsPerPageOptions = [5, 10, 20, 50]

    // filter functionality
    const globalFilterValue = ref('')
    // const filteredData = computed(() => {
    //     const filterText = globalFilterValue.value.toLowerCase()

    //     return data.filter(item => {
    //         return Object.values(item).some(value => {
    //             return String(value).toLowerCase().includes(filterText)
    //         })
    //     })
    // })
</script>

<style>
    .text-input {
        border: 2px solid #ccc;
    }
    
    .table-header{
        background: transparent;
    }

    .actual-table{
        background: black !important;
        padding: 100px;
        border: 100px;
    }
</style>