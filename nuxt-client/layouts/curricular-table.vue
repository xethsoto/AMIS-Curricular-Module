<template>
    <div class="flex flex-col gap-4 mb-96">
        <h1 class="page-title mb-8">
            <slot name="title"></slot>    
        </h1>

        <!-- Search and Filter Bars -->
        <!-- <div class="flex flex-row justify-between">
            <div class="flex flex-row gap-4 items-end">
                <slot name="search-bars"></slot>
                <div>
                    <PrimeButton label="Apply Filter" class="btn-maroon" @click="applyFilter"/>
                </div>
            </div>
            <div>
                <PrimeDropdown title="Number of Rows" :items="numRows" :value="dropdownLabel"/>
                <Dropdown v-model="selectedCity" :options="cities" optionLabel="name" placeholder="Select a City" class="w-full md:w-14rem" />
            </div>
        </div> -->
        
        <!-- Table -->
        <!-- <div class="bg-white rounded-lg p-4 justify-center"> -->
            <Table
                :data="data"
                searchLabel="Filter By Course"
                :rowsOption="true"
            >
                <template v-slot:columns>
                    <PrimeColumn
                        v-for="entry in meta"
                        sortable
                        :key="entry.field"
                        :field="entry.field"
                        :header="entry.header"
                    >
                    </PrimeColumn>
                    <PrimeColumn sortable key="status" field="status" header="Status">
                        <template #body="slotProps">
                            <PrimeTag :severity="slotProps.data.status === 'Active' ? 'success' : 'danger'" :value="slotProps.data.status"/>
                        </template>
                    </PrimeColumn>
                    <PrimeColumn key="action" field="action" header="Action">
                        <template #body="slotProps">
                            <PrimeButton class="btn-maroon" label="Select" @click="selectItem(slotProps)"/>
                        </template>
                    </PrimeColumn>
                </template>
            </Table>
    </div>
</template>

<script setup>
    // FIXME: BUILT-IN SORT only sorts rows on the current page

    const {data, meta, uri, filterData} = defineProps(['data', 'meta', 'uri', 'filterData'])


    console.log("data = ", data)
    // const filteredCourses = ref(data)
    // const emits = defineEmits()
    
    // // table pagination
    // const page = ref(1)
    // const pageCount = ref(5)
    // const rows = computed(() => {
    //     return filteredCourses.value.slice((page.value - 1) * pageCount.value, (page.value) * pageCount.value)
    // })

    // watch(filteredCourses, () => {
    //     page.value = 1
    // })
    
    // // number of rows dropdown functionality
    // const dropdownLabel = ref(5)
    // const numRows = [[]]
    // for(let i = 0; i < 4; i++){
    //     numRows[0].push({
    //         label: (i+1) * 5,
    //         click: () => {
    //             page.value = 1,
    //             pageCount.value = (i+1) * 5,
    //             dropdownLabel.value = (i+1) * 5
    //         }
    //     })
    // }

    // const applyFilter = () => {
    //     filteredCourses.value = data.filter((course) => {

    //         if (filterData.dropdownLabel == "--"){
    //             console.log("true")
    //             return course.course_code.toLowerCase().includes(filterData.courseCodeContent.toLowerCase()) &&
    //             course.title.toLowerCase().includes(filterData.titleContent.toLowerCase())
    //         }

    //         return course.status.value === filterData.dropdownLabel && 
    //         course.course_code.toLowerCase().includes(filterData.courseCodeContent.toLowerCase()) &&
    //         course.title.toLowerCase().includes(filterData.titleContent.toLowerCase())
    //     })
    // }

    const selectItem = async (item) => {
        console.log("item = ", item.data)
        await navigateTo({ path: uri + item.data.id })
    }
    
</script>

<style scoped>

</style>