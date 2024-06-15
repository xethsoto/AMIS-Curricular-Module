<template>
    <NuxtLayout name="subproposal-view"
        :proposal_classification = proposal_classification
    >   
        <template #title>
            ADOPTION OF {{ `${subproposal.code} (${subproposal.title})` }}
        </template>

        <!-- Content -->
        <template #content>
            <CurrViewerGenDetails>
                <template v-slot:title>Main Details</template>
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
                    <DetailSpan title="Semester Offered: " :content="semOfferedView"/>
                    <DetailSpan title="Course Credit: " :content="subproposal.credit"/>
                    <DetailSpan title="Number of Hours: " :content="subproposal.num_of_hours"/>
                    <DetailSpan title="Course Goal: " :content="subproposal.goal"/>
                    <DetailSpan title="Adopted From: " :content="subproposal.univ_origin"/>
                </template>
            </CurrViewerGenDetails>
        
            <DetailSpan title="Course Outline: "/>
            <p>{{ subproposal.goal }}</p>
            <DetailSpan title="Rationale: "/>
            <p>{{ proposal_classification.rationale }}</p>
        </template>
    </NuxtLayout>
</template>

<script setup>
    const { proposal_classification, subproposal } = defineProps(['proposal_classification', 'subproposal'])

    // Converting sem offered array to string
    const semOfferedView = ref('No semester offering indicated')
    watchEffect(() => {
        if (subproposal) {
            semOfferedView.value = subproposal.sem_offered.join(', ');
        }
    });
</script>

<style scoped>

</style>