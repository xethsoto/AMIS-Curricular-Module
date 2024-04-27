<template>
    <p class="font-bold text-center text-lg text-black">Course Revision</p>
    <p class="font-bold text-center text-base text-black">Change in prerequisites</p>
    <div class="flex flex-col">
        <label class="text-sm font-bold">Course to Edit</label>

        <!-- Course to be edited -->
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
                    v-model="formContent.selectedCourse" 
                    class="text-input p-2 text-base border-2"
                />
            </template>
        </TableCourse>

        <label class="text-sm font-bold">New Prerequisites</label>
        <!-- New prerequisites list and table -->
        <TableCourse
            :searchLabel="searchLabel"
            :selectItem="addPrereqs"
            :condition="addPrereqsCondition"
        >
            <template v-slot:input-field>
                <PrimeChips 
                    v-model="formContent.newPrereqs" 
                    class="w-full p-2 text-base"
                />
            </template>
        </TableCourse>
    </div>
    <FormInput type="text-area" label="Rationale" @input="formContent.rationale = $event"/>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newPrereqs: [],
        rationale: ""
    })

    const searchLabel = "Filter by Course Code"

    const selectItem = (item, showTable) => {
        showTable()
        return formContent.selectedCourse = item.data.code
    }

    // condition for selecting an already selected course in 'Course to Edit' table
    const condition = (slotProps) => {
        return formContent.selectedCourse === slotProps.data.code
    }

    const addPrereqs = (item) => {
        return formContent.newPrereqs.push(item.data.code)
    }

    // condition for selecting an already selected course in 'New Prerequisites' table
    const addPrereqsCondition = (slotProps) => {
        return formContent.newPrereqs.includes(slotProps.data.code)
    }

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>