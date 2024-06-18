<template>
    <FormInput type="text-field" label="Course Code" @input="formContent.num = $event"/>
    <FormInput type="text-field" label="Course Title" @input="formContent.title = $event"/>
    <FormInput type="text-area" label="Course Description" @input="formContent.desc = $event"/>
    <FormInput type="text-field" label="Course Credit" @input="formContent.credit = $event"/>
    <FormInput type="text-field" label="Number of Hours" @input="formContent.numOfHours = $event"/>
    <FormInput type="text-area" label="Course Goal" @input="formContent.goal = $event"/>
    <FormInput type="text-area" label="Course Outline" @input="formContent.outline = $event"/>

    <!-- Prerequisites -->
    <label class="text-sm">Prerequisites</label>
    <TablePrereqs
        @inputValue="formContent.prereqs=$event"
    />

    <!-- Sem Offerings -->
    <label>Semester Offering</label>
    <SemOffering @input="formContent.sem_offered=$event"/>

    <!-- Rationale -->
    <FormInput v-if="adoption" type="text-field" label="University Origin" @input="formContent.univ_origin = $event"/>
    <FormInput type="text-area" label="Rationale" @input="formContent.rationale = $event"/>
</template>

<script setup>
    const {adoption} = defineProps(['adoption'])
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        num: "",
        title: "",
        desc: "",
        credit: 1,
        numOfHours: 1,
        goal: "",
        outline: "",
        prereqs: [],
        sem_offered: [],
        rationale: ""
    })

    if (adoption) {
        formContent.univ_origin = ""
    }

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>