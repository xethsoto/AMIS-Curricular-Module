<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >
        <template #title>
            REVISION OF {{ `${subproposal.curr_course_code} (${subproposal.curr_course_title})` }} CONTENT
        </template>

        <template #content>
            <CurrViewerGenDetails>
                <template v-slot:title>Main Details</template>
                <template v-slot:firstHalf>
                    <p class="font-bold text-center">Previous Information</p>
                    <div class="flex flex-col gap-4">
                        <div>
                            <p class="font-semibold">Previous Goal: </p>
                            <p>{{ subproposal.details.prev_goal }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Previous Outline: </p>
                            <p v-html="prevOutline"></p>
                        </div>
                    </div>
                </template>

                <template v-slot:secondHalf>
                    <p class="font-bold text-center">Proposed Changes</p>
                    <div class="flex flex-col gap-4">
                        <div>
                            <p class="font-semibold">Proposed Goal: </p>
                            <p>{{ subproposal.details.new_goal }}</p>
                        </div>
                        <div>
                            <p class="font-semibold">Proposed Outline: </p>
                            <p v-html="newOutline"></p>
                        </div>
                    </div>
                </template>
            </CurrViewerGenDetails>
        
            <DetailSpan title="Rationale: "/>
            <p>{{ proposal_classification.rationale }}</p>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const { proposal_classification, subproposal } = defineProps(['proposal_classification', 'subproposal'])

    const prevOutline = subproposal.details.prev_outline.replace(/\n/g, '<br>')
    const newOutline = subproposal.details.new_outline.replace(/\n/g, '<br>')

</script>

<style scoped>

</style>