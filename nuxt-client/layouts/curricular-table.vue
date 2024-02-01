<template>
    <div class="flex flex-col gap-4 mb-96">
        <h1 class="text-2xl font-bold mb-8">
            <slot name="title"></slot>    
        </h1>

        <!-- Search and Filter Bars -->
        <div class="flex flex-row justify-between">
            <div class="flex flex-row gap-4 items-end">
                <slot name="search-bars"></slot>
            </div>
            <div>
                <Dropdown :items="numRows" :label="numRows">Number of Items</Dropdown>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg p-4 justify-center">
            <UTable
                :rows="rows"
                :columns="tableColMeta"
                :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }"
                :empty-state="{ icon: 'i-heroicons-no-symbol-20-solid', label: 'No courses.' }">
                    <template #status-data="{row}">
                        {{ row.status.value }}
                    </template>
    
                    <template #actions-data>
                        <UButton class="btn-maroon" size="md">View</UButton>
                    </template>
    
            </UTable>

            <div class="flex justify-center px-3 py-3.5 border-t border-gray-200 dark:border-gray-700">
                <UPagination v-model="page" :page-count="pageCount" :total="tableData.length"/>
            </div>
        </div>

    </div>
</template>

<script setup>
    const {tableData, tableColMeta} = defineProps(['tableData', 'tableColMeta'])

    // table pagination
    const page = ref(1)
    const pageCount = ref(5)
    const rows = computed(() => {
        return tableData.slice((page.value - 1) * pageCount.value, (page.value) * pageCount.value)
    })

    // number of rows
    const numRows = [
        [{
            label: '5',
            click: () => {
                pageCount.value = 5
            }
        },
        {
            label: '10',
            click: () => {
                pageCount.value = 10
            }
        },
        {
            label: '15',
            click: () => {
                pageCount.value = 15
            }
        },
        {
            label: '20',
            click: () => {
                pageCount.value = 20
            }
        }]
    ]
</script>

<style scoped>

</style>