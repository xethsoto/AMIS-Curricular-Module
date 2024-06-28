<template>
    <div class="flex flex-col gap-5 mb-8">
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
            <div class="flex flex-col gap-2">
                
                <!-- Proposal Title -->
                <h1 class="font-bold text-xl text-center">{{ proposal.name }}</h1>
                <h2 class="text-center">
                    <span class="font-semibold">Date effective: </span>
                    <span class="italic">{{ proposal.date_created }}</span>
                </h2>

                <!-- Subproposals -->
                <div class="flex flex-col gap-4">
                    <div v-for="(propClassify,index) in proposal.proposal_classification">
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
        
                        <CurrViewerCourseRevDescription
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Revision'
                            && propClassify.sub_type == 'Change in course description'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />
                        
                        <CurrViewerCourseRevPrerequisites
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Revision'
                            && propClassify.sub_type == 'Change in prerequisites'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />

                        <CurrViewerCourseRevSemOffering
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Revision'
                            && propClassify.sub_type == 'Change in semester offering'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />

                        <CurrViewerCourseRevNumOfHours
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Revision'
                            && propClassify.sub_type == 'Change in number of hours'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />

                        <CurrViewerCourseRevContent
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Revision'
                            && propClassify.sub_type == 'Change in course content'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />

                        <CurrViewerCourseRevCredit
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Revision'
                            && propClassify.sub_type == 'Change in course credit'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />

                        <CurrViewerCourseCrosslisting
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Crosslisting'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />

                        <CurrViewerCourseAbolishment
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Abolition'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />

                        <CurrViewerCourseAdoption
                            v-else-if="propClassify.target == 'Course'
                            && propClassify.type == 'Adoption'"
                            :proposal_classification="propClassify"
                            :subproposal="proposal.subproposals[index]"
                        />
                    </div>
                </div>
            </div>

        </div>


    </div>
</template>

<script setup>
    import { watchEffect } from 'vue';

    definePageMeta({
        middleware: ['auth']
    })

    const { id } = useRoute().params
    const apiUrl = process.env.NEXT_PUBLIC_API_URL

    // fetching proposal data
    const {data: proposal, pending} = await useFetch(`${apiUrl}/api/proposal/${id}`, {
        lazy: false,
        server: false
    })

    watchEffect(() => {
        console.log("proposal = ", proposal)
    })
</script>

<style scoped>

</style>