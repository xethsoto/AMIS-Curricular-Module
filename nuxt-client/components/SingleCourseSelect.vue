<template>
    <div class="flex flex-col">
        <label class="text-sm font-bold">
            {{ inputFieldLabel }}
        </label>

        <!-- Courses Table -->
        <TableCourse
            :searchLabel="searchLabel"
            :selectItem="selectItem"
            :condition="condition"
        >
            <template v-slot:input-field>
                <PrimeInputText
                    disabled 
                    id="text-input" 
                    variant="filled" 
                    type="text" 
                    v-model="selectedCourse.code" 
                    class="text-input p-2 text-base border-2"
                />
            </template>
        </TableCourse>
    </div>
</template>

<script setup>
    const {inputFieldLabel} = defineProps(['inputFieldLabel'])
    const emit = defineEmits(['input'])
    const searchLabel = "Filter by Course Code"
    const selectedCourse = ref({code: null})

    const selectItem = (item, showTable) => {
        showTable()
        selectedCourse.value = item.data
    }

    // condition for item selecting in course table
    const condition = (slotProps) => {
        return selectedCourse.value === slotProps.data.code
    }

    watch(selectedCourse, (newVal) => {
        emit('input', newVal)
    }, {deep: true});
</script>

<style scoped>

</style>