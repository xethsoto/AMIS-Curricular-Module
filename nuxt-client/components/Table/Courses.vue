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
    <PrimeDataTable
        :globalFilter="courses"
        :value="courses"
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
    </PrimeDataTable>
</template>

<script setup>
    const {
        searchLabel,
        selectItem,
        condition,
        customAction,
        courses
    } = defineProps([
        'searchLabel',
        'selectItem', 
        'condition',
        'customAction',
        'courses'
    ])
    
    if (!courses) {
        const {data: courses} = await useFetch("http://localhost:8000/api/get-courses")
    }   
    
    const viewTable = ref(true)

    const rowsPerPageOptions = [5, 10, 20, 50]
    const globalFilterValue = ref('')
    
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