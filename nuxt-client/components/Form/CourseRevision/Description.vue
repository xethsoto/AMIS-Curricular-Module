<template>
    <p class="font-bold text-center text-lg text-black">Course Revision</p>
    <p class="font-bold text-center text-base text-black">Change in course description</p>
    <div class="flex flex-col">
        <label class="text-sm font-bold">Course to Edit</label>

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
                    v-model="formContent.selectedCourse" 
                    class="text-input p-2 text-base border-2"
                />
            </template>
        </TableCourse>
    </div>

    <!-- Course Description -->
    <FormInput 
        type="text-area" 
        label="New Course Description" 
        @input="formContent.newDescription = $event"
    />

    <!-- Rationale -->
    <FormInput 
        type="text-area"
        label="Rationale"
        @input="formContent.rationale = $event"
    />
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        selectedCourse: "",
        newDescription: "",
        rationale: ""
    })

    const searchLabel = "Filter by Course Code"

    const selectItem = (item, showTable) => {
        showTable()
        return formContent.selectedCourse = item.data.code
    }

    // condition for item selecting in course table
    const condition = (slotProps) => {
        return formContent.selectedCourse === slotProps.data.code
    }

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>