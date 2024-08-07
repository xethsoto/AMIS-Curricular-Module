<template>
    <div class="flex flex-col gap-4 mb-8">
        <h1 class="page-title mb-8">
            <slot name="title"></slot>    
        </h1>
        
        <!-- Table -->
        <Table
            :data="data"
            :searchLabel=searchLabel
            :rowsOption="true"
            :proposalTable="proposalTable"
        >
            <template #loading> Loading Data. Please Wait...</template>
            <template v-slot:columns>
                <PrimeColumn
                    v-for="entry in meta"
                    sortable
                    :key="entry.field"
                    :field="entry.field"
                    :header="entry.header"
                >
                </PrimeColumn>
                <PrimeColumn v-if="!noStatus" sortable key="status" field="status" header="Status">
                    <template #body="slotProps">
                        <PrimeTag :severity="slotProps.data.status === 'Active' ? 'success' : 'danger'" :value="slotProps.data.status"/>
                    </template>
                </PrimeColumn>
                <PrimeColumn key="action" field="action" header="Action">
                    <template #body="slotProps">
                        <PrimeButton v-if="selectLabel" class="btn-maroon" :label=selectLabel @click="selectItem(slotProps)"/>
                        <PrimeButton v-else class="btn-maroon" label="Select" @click="selectItem(slotProps)"/>
                    </template>
                </PrimeColumn>
            </template>
        </Table>
    </div>
</template>

<script setup>
    const {
        data,
        meta,
        uri,
        noStatus,
        searchLabel,
        proposalTable,
        selectLabel,
    } = defineProps([
        'data',
        'meta',
        'uri',
        'noStatus',
        'searchLabel',
        'proposalTable',
        'selectLabel'
    ])

    const selectItem = async (item) => {
        await navigateTo({ path: uri + item.data.id })
    }
</script>

<style scoped>

</style>