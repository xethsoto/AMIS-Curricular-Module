<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >
        <template #title>
            REVISION OF {{ `${subproposal.curr_course_code} (${subproposal.curr_course_title})` }} SEMESTER OFFERED
        </template>

        <template #content>
            <CurrViewerGenDetails>
                <template v-slot:title>Main Details</template>
                <template v-slot:firstHalf>
                    <p class="font-bold text-center">Previous Changes</p>
                    <p class="font-semibold">Previous Semester Offered: </p>
                    <p v-if="subproposal.details.prev_sem_offered.length > 0">{{ subproposal.details.prev_sem_offered }}</p>
                    <p v-else class="italic">No Semester Offerings</p>
                </template>
                <template v-slot:secondHalf>
                    <p class="font-bold text-center">Proposed Changes</p>
                    <p class="font-semibold">Proposed Semester Offered: </p>
                    <p v-if="subproposal.details.curr_sem_offered">{{ subproposal.details.curr_sem_offered }}</p>
                    <p v-else class="italic">No Semester Offerings</p>
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