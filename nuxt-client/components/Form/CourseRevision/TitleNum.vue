<template>
    <p class="font-bold text-center text-lg text-black">Course Revision</p>
    <p class="font-bold text-center text-base text-black">Change in course number and/or course title</p>
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

    <!-- Course Code and/or Title -->
    <div class="flex flex-col items-center">
        <PrimeSelectButton
            v-model="formType"
            :options="formTypeOptions"
        />
        <FormInput 
            v-if="formType === 'Number only' || formType === 'Number and Title'"
            type="text-field" 
            label="New Course Title" 
            @input="formContent.newCourseTitle = $event"
        />
        <FormInput
            v-if="formType === 'Title only' || formType === 'Number and Title'"
            type="text-field"
            label="New Course Code"
            @input="formContent.newCourseCode = $event"
        />
    </div>

    <!-- Rationale -->
    <FormInput 
        type="text-area"
        label="Rationale"
        @input="formContent.rationale = $event"
    />
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formType = ref("Number only")
    const formTypeOptions = [
        "Number only",
        "Title only",
        "Number and Title"
    ]

    const formContent = reactive({
        selectedCourse: "",
        newCourseTitle: "",
        newCourseCode: "",
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