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
            <div v-for="(subproposal,index) in proposal.subproposals" class="flex flex-col bg-white p-7 gap-4">
                <CurrViewerCourseInstitution 
                    v-if="proposal.proposal_classification[index].target == 'Course' && proposal.proposal_classification[index].type == 'Institution'"
                    :proposal_classification="proposal.proposal_classification[index]"
                    :subproposal="subproposal"
                />
            </div>
        </div>


    </div>
</template>

<script setup>
import { watchEffect } from 'vue';

    const { id } = useRoute().params

    // fetching course and course requisites
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