<template>
    <PrimeDataTable
        v-model:filters="filters"
        :value="data"
        paginator
        :rows="5"
        :rowsPerPageOptions="rowsOption ? rowsPerPageOptions : null"
        :pt="{ header: 'table-header', table: 'actual-table' }"
    >  

    <!-- Search Field -->
        <template #header>
            <div class="flex flex-row">
                <!-- Global Search -->
                <div class="flex flex-col lg:w-1/4 md:w-1/2">
                    <label>
                        <span class="text-sm">{{ searchLabel }}</span>
                    </label>
                    <PrimeInputText 
                        variant="filled" 
                        type="text" 
                        v-model="filters['global'].value"
                        class="text-input p-2 text-base"
                    />
                    </div>
                <slot name="more-filters"></slot>
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
    const {
        data,
        searchLabel,
        rowsOption,
        proposalTable
    } = defineProps([
        'data',
        'searchLabel',
        'rowsOption',
        'proposalTable'
    ])
    const rowsPerPageOptions = [5, 10, 20, 50]
    const filters = ref({
        global: {
            value: null,
            matchMode: 'contains'
        }
    })

    watchEffect(() => {

        if (proposalTable){
            // globalFilterFields.value = ['name']
            filters.name = {
                value: null,
                matchMode: 'contains'
            }
        } else {
            // globalFilterFields.value = ['code', 'title']
            filters.code = {
                value: null,
                matchMode: 'contains'
            }

            filters.title = {
                value: null,
                matchMode: 'contains'
            }
        }      
    })
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