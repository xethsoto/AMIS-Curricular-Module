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
                    v-model="selectedCourse" 
                    class="text-input p-2 text-base border-2"
                />
            </template>
        </TableCourse>
    </div>
</template>

<script setup>
    const {inputFieldLabel} = defineProps(['inputFieldLabel'])
    const emit = defineEmits(['input'])
    const selectedCourse = ref("")
    const searchLabel = "Filter by Course Code"

    const selectItem = (item, showTable) => {
        showTable()
        return selectedCourse.value = item.data.code
    }

    // condition for item selecting in course table
    const condition = (slotProps) => {
        return selectedCourse.value === slotProps.data.code
    }

    watchEffect(() => {
        emit('input', selectedCourse.value)
    })
</script>

<style lang="scss" scoped>

</style>