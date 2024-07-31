<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >
        <template #title>
            CROSSLISTING OF {{ `${subproposal.course.code} AND ${subproposal.crosslist.code}` }}
        </template>

        <template #content>
            <CurrViewerGenDetails>
                <template v-slot:title>Main Details</template>
                <template v-slot:firstHalf>
                    <p class="font-bold text-center">Course 1 Crosslisted</p>
                    <NuxtLink
                        :to="`/courses-management/${subproposal.course.id}`"
                        class="nav-link"
                    >
                        {{ `${subproposal.course.code}: ${subproposal.course.title}` }}
                    </NuxtLink>
                </template>
                <template v-slot:secondHalf>
                    <p class="font-bold text-center">Course 2 Crosslisted</p>
                    <NuxtLink
                        :to="`/courses-management/${subproposal.crosslist.id}`"
                        class="nav-link"
                    >
                        {{ `${subproposal.crosslist.code}: ${subproposal.crosslist.title}` }}
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
</script>

<style scoped>

</style>