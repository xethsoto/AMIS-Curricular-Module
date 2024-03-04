<template>
    <form class="flex flex-col gap-4">
        <FormInput type="text-field" label="Course Code" @input="formContent.courseNum = $event"/>
        <FormInput type="text-field" label="Course Title" @input="formContent.courseTitle = $event"/>
        <FormInput type="text-area" label="Course Description" @input="formContent.courseDesc = $event"/>
        <FormInput type="text-field" label="Course Credit" @input="formContent.courseCredit = $event"/>
        <FormInput type="text-field" label="Number of Hours" @input="formContent.numOfHours = $event"/>
        <FormInput type="text-area" label="Course Goal" @input="formContent.courseGoal = $event"/>
        <FormInput type="text-area" label="Course Outline" @input="formContent.courseOutline = $event"/>

        <label for="prerequisites">Prerequisites</label>
        <PrimeChips v-model="formContent.prereqs" class="w-full"/>

        <label for="sem-offerings">Semester Offering</label>
        <div id="sem-offerings" v-for="offering of semOfferings" :key="offering">
            <PrimeCheckbox inputId="offering" v-model="formContent.sem_offered" name="sem-offering" :value="offering"/>
            <label :for="offering">{{ offering }}</label>
        </div>
    </form>
</template>

<script setup>
    const emit = defineEmits(['inputValue'])

    const formContent = reactive({
        courseNum: "",
        courseTitle: "",
        courseDesc: "",
        courseCredit: null,
        numOfHours: null,
        courseGoal: "",
        courseOutline: "",
        prereqs: [],
        sem_offered: []
    })

    const semOfferings = ["1st Semester", "2nd Semester", "Midyear"]

    watchEffect(() => {
        emit('inputValue', formContent)
    })

</script>

<style scoped>
    .chips {
        border: 2px solid #ccc; /* Example border style */
    }
</style>