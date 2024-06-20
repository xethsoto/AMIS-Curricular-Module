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
            <div class="flex flex-row gap-4">
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

                <!-- Course Status Filter -->
                <div
                    v-if="proposalTable === false" 
                    class="flex flex-col w-full"
                >
                    <label class="text-gray-500">
                        <span class="text-sm">Status</span>
                    </label>
                    <PrimeDropdown 
                        variant="filled"
                        v-model="filters['status'].value" 
                        :options="status"
                        :showClear="true"
                        class="dropdown text-base w-64"
                    />
                </div>
                
                <!-- Proposal Type Filter -->
                <div 
                    v-if="proposalTable === true"
                    class="flex flex-col w-64"
                >
                    <label class="text-gray-500">
                        <span class="text-sm">Type</span>
                    </label>
                    <PrimeDropdown 
                        variant="filled"
                        v-model="filters['type'].value" 
                        :options="types"
                        :showClear="true"
                        class="dropdown text-base"
                    />
                </div>

                <!-- Proposal Sub Type Filter -->
                <div 
                    v-if="proposalTable === true
                    && filters['type'].value === 'Revision'"
                    class="flex flex-col w-96"
                >
                    <label class="text-gray-500">
                        <span class="text-sm">Sub-Type</span>
                    </label>
                    <PrimeDropdown 
                        variant="filled" 
                        v-model="filters['sub_type'].value" 
                        :options="subTypes"
                        :showClear="true"
                        class="dropdown text-base"
                    />
                </div>

            </div>
        </template>

        <template #empty>
            <div class="text-center">
                Nothing found.
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
            filters.value.name = {
                value: null,
                matchMode: 'contains'
            },
            filters.value.type = {
                value: null,
                matchMode: 'contains'
            },
            filters.value.sub_type = {
                value: null,
                matchMode: 'contains'
            }
        } else {
            filters.value.code = {
                value: null,
                matchMode: 'contains'
            }
            filters.value.title = {
                value: null,
                matchMode: 'contains'
            }
            filters.value.status = {
                value: null,
                matchMode: 'contains'
            }
        }      
    })
    console.log("proposalTable in Table.vue = ", proposalTable)

    const types = [
        "Institution",
        "Revision",
        "Abolition",
        "Crosslisting",
        "Adoption"
    ]
    const subTypes = [
        "Change in course number and/or course title",
        "Change in course description",
        "Change in prerequisites",
        "Change in semester offering",
        "Change in number of hours",
        "Change in course credit",
        "Change in course content"
    ]

    const status = [
        "Active",
        "Abolished"
    ]
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

    .dropdown {
        border: 2px solid #ccc;
    }
</style>