<template>
    <!-- Course Institution Form -->
    <FormCourseInstitution 
        v-if="target==='Course'
        && type==='Institution'"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />

    <!-- Course Abolition Form -->
    <FormCourseAbolition
        v-else-if="target==='Course'
        && type==='Abolition'"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />
    
    <!-- Course Revision Forms -->
    <FormCourseRevisionTitleNum
        v-else-if="target==='Course'
        && type==='Revision'
        && subType===courseRevTypes[0]"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />

    <FormCourseRevisionDescription
        v-else-if="target==='Course'
        && type==='Revision'
        && subType===courseRevTypes[1]"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />

    <FormCourseRevisionPrerequisites
        v-else-if="target==='Course'
        && type==='Revision'
        && subType===courseRevTypes[2]"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />

    <FormCourseRevisionSemOffering
        v-else-if="target==='Course'
        && type==='Revision'
        && subType===courseRevTypes[3]"
        @inputValue="content=$event"
        class="flex flex-col gap-4" 
    />

    <FormCourseRevisionNumOfHours
        v-else-if="target==='Course'
        && type==='Revision'
        && subType===courseRevTypes[4]"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />

    <FormCourseRevisionCredit
        v-else-if="target==='Course'
        && type==='Revision'
        && subType===courseRevTypes[5]"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />

    <FormCourseRevisionContent
        v-else-if="target==='Course'
        && type==='Revision'
        && subType===courseRevTypes[6]"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />
    
    <!-- Course Crosslisting -->
    <FormCourseCrosslisting
        v-else-if="target==='Course'
        && type==='Crosslisting'"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />
    
    <!-- Course Adoption -->
    <FormCourseAdoption
        v-else-if="target==='Course'
        && type==='Adoption'"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />

    <FormDegProgInstitution
        v-else-if="target==='Degree Program'
        && type==='Institution'"
        @inputValue="content=$event"
        class="flex flex-col gap-4"
    />
</template>

<script setup>
    const emit = defineEmits(['content'])
    const {target, type, subType} = defineProps(['target', 'type', 'subType'])

    const content = ref(null)

    const courseRevTypes = [
        "Change in course number and/or course title",
        "Change in course description",
        "Change in prerequisites",
        "Change in semester offering",
        "Change in number of hours",
        "Change in course credit",
        "Change in course content"
    ]

    watchEffect(() => {
        emit('content', content.value)
    })
</script>

<style scoped>

</style>