<template>
    <div v-if="pending"> 
        <p class="text-center font-bold">Loading data. Please wait...</p>
    </div>
    <!-- There is an issue where transition from this page to other page (say, proposal management causes an error) -->
    <!-- Above code fixes it -->
    <NuxtLayout v-else name="curricular-viewer" prevLink="/courses-management">
        <template v-slot:viewer-title>Course Viewer</template>
        <template v-slot:title>{{ `${courseInfo.course.code} (${courseInfo.course.title})` }}</template>
        <template v-slot:contents>

            <!-- General Details -->
            <CurrViewerGenDetails>
                <template v-slot:title>General Details</template>
                <template v-slot:firstHalf>
                    <DetailSpan title="Code: " :content="courseInfo.course.code"/>
                    <DetailSpan title="Title: " :content="courseInfo.course.title"/>
                    <DetailSpan title="Description: " :content="courseInfo.course.desc"/>
                </template>
                <template v-slot:secondHalf>
                    <DetailSpan title="Semesters Offered: " :content="semOfferedView"/>
                    <DetailSpan title="Credit: " :content="courseInfo.course.credit"/>
                    <DetailSpan title="Number of Hours: " :content="courseInfo.course.num_of_hours"/>
                    <DetailSpan title="Goal: " :content="courseInfo.course.goal"/>
                    <DetailSpan title="Status: " :content="courseInfo.course.status"/>
                    <div v-if="courseInfo.crosslisted">
                        <div class="flex flex-row gap-4">
                            <p class="font-semibold">Crosslisted with: </p>
                            <NuxtLink
                                    :to="`/courses-management/${courseInfo.crosslisted.id}`"
                                    class="nav-link"
                            >
                                {{ `${courseInfo.crosslisted.code} (${courseInfo.crosslisted.title})` }}
                            </NuxtLink>
                        </div>
                    </div>
                </template>
            </CurrViewerGenDetails>
            <hr class="hr-temp">
            
            <!-- Prerequisites and Requisites -->
            <div class="flex flex-row gap-10">
                <div class="flex flex-col flex-1 gap-2">
                    <p class="font-semibold">Prerequisites:</p>
                    <div v-if="courseInfo.course.prereqs.length">
                        <div v-for="prereq in courseInfo.course.prereqs">
                            <!-- There would be cases where prereq code is present
                            But the course doesn't exist  -->
                            <div v-if="prereq">    
                                <NuxtLink
                                    :to="`/courses-management/${prereq.id}`"
                                    class="nav-link"
                                >
                                {{ `${prereq.code} (${prereq.title})` }}
                                </NuxtLink>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p class="italic">No Prerequisites</p>
                    </div>
                </div>

                <div class="flex flex-col flex-1 gap-2">
                    <p class="font-semibold">Prerequisites to the following courses:</p>
                    <div v-if="courseInfo.requisites.length">
                        <div v-for="req in courseInfo.requisites">
                            <NuxtLink
                                :to="`/courses-management/${req.id}`"
                                class="nav-link"
                            >
                                {{ `${req.code} (${req.title})` }}
                            </NuxtLink>
                        </div>
                    </div>
                    <div v-else>
                        <p class="italic">No courses require this course as a prerequisite</p>
                    </div>
                </div>
            </div>

            <p class="font-semibold">Course Outline:</p>
            <div v-if="courseInfo.course">
                <p>{{ courseInfo.course.outline }}</p>
            </div>
            <div v-else>
                <p class="font-bold italic">No Course Outline</p>
            </div>

            <hr class="hr-temp">

            <!-- History -->
            <p class="font-bold text-lg text-center">Course History</p>
            <div class="h-80">
                <PrimeTimeline 
                    :value="courseInfo.events" 
                    layout="horizontal"
                    class="container w-full flex flex-nowrap"
                    @wheel="handleScroll"
                    :pt="{
                        root: {
                            class: 'overflow-x-auto h-full pb-4'  
                        },
                        event: {
                            class: 'min-w-96'
                        },
                        content: {
                            class: ''
                        },
                        opposite: {
                            class: 'h-2'
                        }
                    }"
                >
                    <template #opposite="slotProps">
                        <div class="flex h-full items-end">
                            <p class="italic text-left text-sm text-slate-500">{{slotProps.item.created_at}}</p>
                        </div>
                    </template>
                    <template #content="slotProps">
                        <NuxtLink
                            :to="`/proposals-management/${slotProps.item.id}`"
                            class="nav-link"
                        >
                            <p class="text-sm break-words">{{slotProps.item.name}}</p>
                        </NuxtLink>
                    </template>
                </PrimeTimeline>
            </div>
            
        </template>
    </NuxtLayout>
</template>

<script setup>
    const { id } = useRoute().params
    const apiUrl = process.env.VUE_APP_API_URL

    definePageMeta({
        middleware: ['auth']
    })

    // fetching course and course requisites
    const { pending, data: courseInfo } = useAsyncData(
        'courseInfo',
        async () => {
            const [course, requisites, events, crosslisted] = await Promise.all([
                $fetch(`${apiUrl}/api/course/` + id),
                $fetch(`${apiUrl}/api/requisites/` + id),
                $fetch(`${apiUrl}/api/get-proposals-by-course/` + id),
                $fetch(`${apiUrl}/api/get-crosslisted/` + id)
            ])

            return {
                course,
                requisites,
                events,
                crosslisted
            }
        },
        {
            lazy: false,
            server: false
        }
    )

    const semOfferedView = ref('No semester offering indicated')

    watchEffect(() => {
        if (courseInfo.value && courseInfo.value.course && courseInfo.value.course.sem_offered) {
            semOfferedView.value = courseInfo.value.course.sem_offered.join(', ');
        }
    });

    
</script>

<style scoped>

</style>