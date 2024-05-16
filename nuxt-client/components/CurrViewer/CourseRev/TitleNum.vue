<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >
        <template #title>
            <div v-if="subproposal.details.new_code && subproposal.details.new_title">
                REVISION OF {{ `${subproposal.curr_course_code} (${subproposal.curr_course_title})` }} CODE AND TITLE
            </div>
            <div v-else-if="subproposal.details.new_code">
                REVISION OF {{ `${subproposal.curr_course_code} (${subproposal.curr_course_title})` }} CODE
            </div>
            <div v-else-if="subproposal.details.new_title">
                REVISION OF {{ `${subproposal.curr_course_code} (${subproposal.curr_course_title})` }} TITLE
            </div>
        </template>

        <template #content>
            <CurrViewerGenDetails>
                <template v-slot:title>Main Details</template>
                <template v-slot:firstHalf>
                    <p class="font-bold text-center">Previous Information</p>
                    <DetailSpan v-if="subproposal.details.new_code" title="Previous Code: " :content="subproposal.details.prev_code"/>
                    <DetailSpan v-if="subproposal.details.new_title" title="Previous Title: " :content="subproposal.details.prev_title"/>
                </template>
                <template v-slot:secondHalf>
                    <p class="font-bold text-center">Proposed Changes</p>
                    <DetailSpan v-if="subproposal.details.new_code" title="Proposed Code: " :content="subproposal.details.new_code"/>
                    <DetailSpan v-if="subproposal.details.new_title" title="Proposed Title: " :content="subproposal.details.new_title"/>
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