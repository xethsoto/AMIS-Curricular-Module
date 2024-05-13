<template>
    <div class="flex flex-col gap-5">
        <!-- Title -->
        <div class="flex flex-row gap-4">
            <NuxtLink to="/proposals-management">
                <PrimeButton label="Close" size="lg" class="btn-maroon"/>
            </NuxtLink>
            <h1 class="page-title">
                Proposal Viewer
            </h1>
        </div>

        <!-- Body -->
        <div v-if="pending">
            <p class="text-center font-bold">Loading data. Please wait...</p>
        </div>
        <div v-else>
            <h1>{{ proposal.name }}</h1>
            <h2>{{ proposal.date_created }}</h2>
            <!-- {{ proposal.subproposals }} -->
            <div v-for="(propClassify,index) in proposal.proposal_classification" class="flex flex-col bg-white p-7 gap-4">
                <CurrViewerCourseInstitution 
                    v-if="propClassify.target == 'Course'
                    && propClassify.type == 'Institution'"
                    :proposal_classification="propClassify"
                    :subproposal="proposal.subproposals[index]"
                />

                <CurrViewerCourseRevTitleNum
                    v-else-if="propClassify.target == 'Course'
                    && propClassify.type == 'Revision'
                    && propClassify.sub_type == 'Change in course number and/or course title'"
                    :proposal_classification="propClassify"
                    :subproposal="proposal.subproposals[index]"
                />
            </div>
        </div>


    </div>
</template>

<script setup>
import { watchEffect } from 'vue';

    const { id } = useRoute().params

    // fetching proposal data
    const {data: proposal, pending} = await useFetch(`http://localhost:8000/api/proposal/${id}`, {
        lazy: false,
        server: false
    })

    watchEffect(() => {
        console.log("proposal = ", proposal)
    })
</script>

<style scoped>

</style>