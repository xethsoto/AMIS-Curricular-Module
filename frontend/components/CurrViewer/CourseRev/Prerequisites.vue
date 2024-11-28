<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >
        <template #title>
            REVISION OF {{ `${subproposal.curr_course_code} (${subproposal.curr_course_title})` }} PREREQUISITES
        </template>

        <template #content>
            <CurrViewerGenDetails>
                <template v-slot:title>Main Details</template>
                
                <template v-slot:firstHalf>
                    <p class="font-bold text-center">Previous Changes</p>
                    <p class="font-semibold">Previous Prerequisites: </p>
                    <div v-if="subproposal.details.prev_prereqs.length > 0"
                    v-for="prereq in subproposal.details.prev_prereqs">
                        <NuxtLink
                            :to="`/courses-management/${prereq.id}`"
                            class="nav-link"
                        >
                        {{ `${prereq.code} (${prereq.title})` }}
                        </NuxtLink>
                    </div>
                    <div v-else>
                        <p class="italic">No Prerequisites</p>
                    </div>
                </template>

                <template v-slot:secondHalf>
                    <p class="font-bold text-center">Proposed Changes</p>
                    <p class="font-semibold">Proposed Prerequisites: </p>
                    <div v-if="subproposal.details.curr_prereqs.length > 0" 
                    v-for="prereq in subproposal.details.curr_prereqs">
                        <NuxtLink
                            :to="`/courses-management/${prereq.id}`"
                            class="nav-link"
                        >
                        {{ `${prereq.code} (${prereq.title})` }}
                        </NuxtLink>
                    </div>
                    <div v-else>
                        <p class="italic">No Prerequisites</p>
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
</script>

<style scoped>

</style>