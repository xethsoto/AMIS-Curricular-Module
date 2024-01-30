<template>
    <div>
        <h1>
            <slot name="title"></slot>    
        </h1>
        <div class="container h-12">

        </div>
        <div v-if="tableData.length > 0">
            <UTable
            :rows="tableData"
            :columns="tableColMeta"
            class="bg-white rounded-lg p-4 justify-center"
            :ui="{
                td: { base: 'text-wrap text-black' },
                th: { base: 'text-bolder text-black'}
            }">
                <template #actions-data>
                    <UButton label="View" class="bg-maroon"/>
                </template>
            </UTable>
        </div>
        <div v-else>
            <p>Loading...</p>
        </div>
    </div>
</template>

<script setup>
    const {tableData, tableColMeta} = defineProps(['tableData', 'tableColMeta'])
</script>

<style lang="scss" scoped>

</style>