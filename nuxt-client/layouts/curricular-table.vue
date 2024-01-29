<template>
    <div>
        <h1>
             <slot name="title"/>
        </h1>
        <div class="container h-12">

        </div>
        <div v-if="tableData.length > 0">
       </div>
        <div v-else>
            <p>No Data</p>
        </div>
    </div>
</template>

<script setup>
    // const {tableData} = defineProps(['tableData'])
    const tableData = ref([])
</script>

<style lang="scss" scoped>

</style>