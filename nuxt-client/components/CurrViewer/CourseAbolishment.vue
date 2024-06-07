<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >
        <template #title>
            ABOLISHMENT OF {{ `${subproposal.course.code}` }}
        </template>

        <template #content>
            <DetailSpan title="Rationale: "/>
            <p>{{ proposal_classification.rationale }}</p>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const { proposal_classification, subproposal } = defineProps(['proposal_classification', 'subproposal'])
    console.log("subproposal: ", subproposal)
</script>

<style scoped>

</style>