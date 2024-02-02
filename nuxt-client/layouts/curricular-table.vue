<template>
    <div class="flex flex-col gap-4 mb-96">
        <h1 class="text-2xl font-bold mb-8">
            <slot name="title"></slot>    
        </h1>

        <!-- Search and Filter Bars -->
        <div class="flex flex-row justify-between">
            <div class="flex flex-row gap-4 items-end">
                <slot name="search-bars"></slot>
                <div>
                    <UButton size="md" class="btn-maroon" @click="applyFilter">Apply Filter</UButton>
                </div>
            </div>
            <div>
                <Dropdown title="Number of Rows" :items="numRows" :label="dropdownLabel"/>
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
                <UPagination v-model="page" :page-count="pageCount" :total="filteredCourses.length"/>
            </div>
        </div>
    </div>
</template>

<script setup>
    const {tableData, tableColMeta, filterData} = defineProps(['tableData', 'tableColMeta', 'filterData'])

    const filteredCourses = ref([...tableData])
    const emits = defineEmits()
    
    // table pagination
    const page = ref(1)
    const pageCount = ref(5)
    const rows = computed(() => {
        return filteredCourses.value.slice((page.value - 1) * pageCount.value, (page.value) * pageCount.value)
    })

    watch(filteredCourses, () => {
        page.value = 1
    })
    
    // number of rows dropdown functionality
    const dropdownLabel = ref(5)
    const numRows = [[]]
    for(let i = 0; i < 4; i++){
        numRows[0].push({
            label: (i+1) * 5,
            click: () => {
                page.value = 1,
                pageCount.value = (i+1) * 5,
                dropdownLabel.value = (i+1) * 5
            }
        })
    }

    const applyFilter = () => {
        filteredCourses.value = tableData.filter((course) => {

            if (filterData.dropdownLabel == "--"){
                console.log("true")
                return course.course_code.toLowerCase().includes(filterData.courseCodeContent.toLowerCase()) &&
                course.title.toLowerCase().includes(filterData.titleContent.toLowerCase())
            }

            return course.status.value === filterData.dropdownLabel && 
            course.course_code.toLowerCase().includes(filterData.courseCodeContent.toLowerCase()) &&
            course.title.toLowerCase().includes(filterData.titleContent.toLowerCase())
        })
    }
    
</script>

<style scoped>

</style>