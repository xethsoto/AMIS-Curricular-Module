<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >
        <template #title>
        REVISION OF {{ `${subproposal.curr_course_code} (${subproposal.curr_course_title})` }} CREDIT
        </template>

        <template #content>
            <CurrViewerGenDetails>
                <template v-slot:title>Main Details</template>
                <template v-slot:firstHalf>
                    <p class="font-bold text-center">Previous Changes</p>
                    <p class="font-semibold">Previous Course Credit: </p>
                    <p>{{ subproposal.details.prev_credit }}</p>
                </template>
                <template v-slot:secondHalf>
                    <p class="font-bold text-center">Proposed Changes</p>
                    <p class="font-semibold">Proposed Course Credit: </p>
                    <p>{{ subproposal.details.new_credit }}</p>
                </template>
            </CurrViewerGenDetails>
        
            <DetailSpan title="Rationale: "/>
            <p>{{ proposal_classification.rationale }}</p>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const { proposal_classification, subproposal } = defineProps(['proposal_classification', 'subproposal'])
</script>

<style scoped>

</style>