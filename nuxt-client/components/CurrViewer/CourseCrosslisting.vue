<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >
        <template #title>
            CROSSLISTING OF {{ `${subproposal.course_id.code} (${subproposal.crosslist_id.code})` }}
        </template>

        <template #content>
            <CurrViewerGenDetails>
                <template v-slot:title>Main Details</template>
                <template v-slot:firstHalf>
                    <p class="font-bold text-center">Course 1 Crosslisted</p>
                    <NuxtLink
                        :to="`/courses-management/${subproposal.course_id.id}`"
                        class="nav-link"
                    >
                        {{ `${subproposal.course_id.code}: ${subproposal.course_id.title}` }}
                    </NuxtLink>
                </template>
                <template v-slot:secondHalf>
                    <p class="font-bold text-center">Course 2 Crosslisted</p>
                    <NuxtLink
                        :to="`/courses-management/${subproposal.crosslist_id.id}`"
                        class="nav-link"
                    >
                        {{ `${subproposal.crosslist_id.code}: ${subproposal.crosslist_id.title}` }}
                    </NuxtLink>
                </template>
            </CurrViewerGenDetails>
        
            <DetailSpan title="Rationale: "/>
            <p>{{ proposal_classification.rationale }}</p>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const { proposal_classification, subproposal } = defineProps(['proposal_classification', 'subproposal'])

    console.log("proposal_classification = ", proposal_classification)
    console.log("subproposal = ", subproposal)
</script>

<style scoped>

</style>