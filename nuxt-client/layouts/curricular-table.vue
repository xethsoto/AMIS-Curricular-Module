<template>
    <div>
        <h1>
            <slot name="title"></slot>    
        </h1>

        <!-- Search and Filter Bars -->
        <div class="container h-12">
            
        </div>

        <!-- Table -->
        <UTable
            :rows="tableData"
            :columns="tableColMeta"
            :loading-state="{ icon: 'i-heroicons-arrow-path-20-solid', label: 'Loading...' }"
            :empty-state="{ icon: 'i-heroicons-no-symbol-20-solid', label: 'No courses.' }"
            class="bg-white rounded-lg p-4 justify-center">
                <template #status-data="{row}">
                    {{ row.status.value }}
                </template>

                <template #actions-data>
                    <UButton class="bg-maroon hover:bg-red-700" size="md">View</UButton>
                </template>
        </UTable>
    </div>
</template>

<script setup>
    const {tableData, tableColMeta} = defineProps(['tableData', 'tableColMeta'])

    console.log("tableData = ", tableData)
</script>

<style scoped>

</style>