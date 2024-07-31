<template>
    <FormInput type="text-field" label="Course Code" @input="formContent.num = $event"/>
    <FormInput type="text-field" label="Course Title" @input="formContent.title = $event"/>
    <FormInput type="text-area" label="Course Description" @input="formContent.desc = $event"/>
    <FormInput type="text-field" label="Course Credit" @input="formContent.credit = $event"/>
    <FormInput type="text-field" label="Number of Hours" @input="formContent.numOfHours = $event"/>
    <FormInput type="text-area" label="Course Goal" @input="formContent.goal = $event"/>
    <!-- <FormInput type="text-area" label="Course Outline" @input="formContent.outline = $event"/> -->

    <!-- Course Outline -->
    <label>
        <span class="text-sm">Course Outline</span>
    </label>    
    <PrimeEditor 
        v-model="formContent.outline"
        :modules="{ toolbar: false }"
        editorStyle="height: 320px"
        :pt="{
            toolbar: {
                class: 'hidden'
            }
        }"
        class="border-2 border-gray-300 rounded-md"
    >
    </PrimeEditor>

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
    import PrimeEditor from 'primevue/editor'
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