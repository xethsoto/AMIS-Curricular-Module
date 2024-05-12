<template>
    <!-- Title -->
    <h2 class="font-bold text-xl">
        {{ `${subproposal.title} (${subproposal.code})` }}
    </h2>

    <hr class="hr-temp">
    <!-- Proposal Classification -->
    <div class="flex flex-row justify-between">
        <div class="flex-grow">
            <DetailSpan title="Target: " :content="proposal_classification.target"/>
        </div>
        <div class="flex-grow">
            <DetailSpan title="Type: " :content="proposal_classification.type"/>
        </div>
        <div v-if="proposal_classification.subType" class="flex-grow">
            <DetailSpan title="Sub-Type: " :content="proposal_classification.subType"/>
        </div>
    </div>
    <hr class="hr-temp"> 

    <!-- Content -->
    <CurrViewerGenDetails>
        <template v-slot:title>General Details</template>
        <template v-slot:firstHalf>
            <DetailSpan title="Course Number: " :content="subproposal.code"/>
            <DetailSpan title="Course Description: " :content="subproposal.title"/>
            <DetailSpan title="Prerequisites: "/>
                <NuxtLink v-if="subproposal.prereqs"
                    v-for="prereq in subproposal.prereqs" 
                    :to="`/courses-management/${prereq.id}`"
                    class="nav-link"
                >
                    <DetailSpan
                        :title="`${prereq.code}:`"
                        :content="prereq.title"
                    />
                </NuxtLink>
                <p v-else class="italic"> No Prerequisites </p>
        </template>
        <template v-slot:secondHalf>
            <DetailSpan title="Semester Offered: " :content="subproposal.sem_offered"/>
            <DetailSpan title="Course Credit: " :content="subproposal.credit"/>
            <DetailSpan title="Number of Hours: " :content="subproposal.num_of_hours"/>
            <DetailSpan title="Course Goal: " :content="subproposal.goal"/>
        </template>
    </CurrViewerGenDetails>

    <DetailSpan title="Course Outline: "/>
    <p>{{ subproposal.goal }}</p>
    <DetailSpan title="Rationale: "/>
    <p>{{ proposal_classification.rationale }}</p>
</template>

<script setup>
    const { proposal_classification, subproposal } = defineProps(['proposal_classification', 'subproposal'])

    watchEffect(() => {
        console.log("subproposal = ", subproposal)
        console.log("proposal_classification = ", proposal_classification)
    })
</script>

<style scoped>

</style>