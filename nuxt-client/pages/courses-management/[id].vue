<template>
    <div v-if="pending"> 
        <p class="text-center font-bold">Loading data. Please wait...</p>
    </div>
    <!-- There is an issue where transition from this page to other page (say, proposal management causes an error) -->
    <!-- Above code fixes it -->
    <NuxtLayout name="curricular-viewer" prevLink="/courses-management" :pending="pending">
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
                    <DetailSpan title="Semesters Offered: " :content="courseInfo.course.sem_offered"/>
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
                                {{ `${courseInfo.crosslisted.code}: ${courseInfo.crosslisted.title}` }}
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
                                {{ `${prereq.code}: ${prereq.title}` }}
                                </NuxtLink>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <p class="italic">No Prerequisites</p>
                    </div>
                </div>

                <div class="flex flex-col flex-1 gap-2">
                    <p class="font-semibold">Requisites:</p>
                    <div v-if="courseInfo.requisites.length">
                        <div v-for="req in courseInfo.requisites">
                            <NuxtLink
                                :to="`/courses-management/${req.id}`"
                                class="nav-link"
                            >
                                {{ `${req.code}: ${req.title}` }}
                            </NuxtLink>
                        </div>
                    </div>
                    <div v-else>
                        <p class="italic">No Requisites</p>
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

            <!-- Degree Programs Requiring This Course -->

            <p class="font-bold text-lg text-center">Degree Programs Requiring This Course</p>
            <PrimeAccordion v-if="filteredDegProgs" :multiple="true">
                <PrimeAccordionTab v-for="degProg in filteredDegProgs" :header="degProg.name">
                    <div v-if="degProg.has_majors" class="grid grid-cols-2 gap-4">
                        <div v-for="degMajorObj in degProgMajors" class="text-center">

                            <!-- Filters appropriate deg_prog - major relationship -->
                            <div v-if="degMajorObj.deg_prog_id == degProg.id">

                                <div v-for="major in filteredMajors">
                                    <div v-if="major.id == degMajorObj.major_id">
                                        <p class="font-semibold">{{ major.name }}</p>
                                    </div>
                                </div>

                                <!-- Fills in the curriculum that requires current course -->
                                <!-- <div v-for="curriculum in filteredCurricula">
                                    <div v-if="curriculum.major_id == degMajorObj.major_id" class="flex flex-row justify-around">
                                        <ULink class="nav-link">{{ curriculum.name }}</ULink>
                                        <div v-for="obj in currCourse">
                                            <div v-if="obj.curr_id == curriculum.id" class="font-bold">
                                                {{ obj.course_type }}
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                            <</div>
                        </div>
                    </div>
                    <div v-else>
                        <div v-for="curriculum in filteredCurricula">
                            <div v-if="curriculum.deg_prog_id == degProg.id" class="flex flex-row justify-around">
                                <ULink class="nav-link">{{ curriculum.name }}</ULink>
                                <div v-for="obj in currCourse">
                                    <div v-if="obj.curr_id == curriculum.id" class="font-bold">
                                        <p>{{ obj.course_type }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </PrimeAccordionTab>
            </PrimeAccordion>
            <div v-else>
                <p class="text-semibold text-center italic">No Degree Programs require this course</p>
            </div>

            <hr class="hr-temp">
            
            <!-- History -->
            <p class="font-bold text-lg text-center">Course History</p>
            <div class="timeline-container">
                <PrimeTimeline 
                    :value="courseInfo.events" 
                    layout="horizontal"
                    align="alternate"
                    class="container w-full overflow-x-auto"
                    @wheel="handleScroll"
                >
                    <template #content="slotProps">
                        <div class="container w-full">
                            <NuxtLink
                                :to="`/proposals-management/${slotProps.item.id}`"
                                class="nav-link"
                            >
                                {{slotProps.item.name}}
                            </NuxtLink>
                        </div>
                    </template>
                </PrimeTimeline>
            </div>
            
        </template>
    </NuxtLayout>
</template>

<script setup>
    const { id } = useRoute().params

    // fetching course and course requisites
    const { pending, data: courseInfo } = useAsyncData(
        'courseInfo',
        async () => {
            const [course, requisites, events, crosslisted] = await Promise.all([
                $fetch('http://localhost:8000/api/course/' + id),
                $fetch('http://localhost:8000/api/requisites/' + id),
                $fetch('http://localhost:8000/api/get-proposals-by-course/' + id),
                $fetch('http://localhost:8000/api/get-crosslisted/' + id)
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
</script>

<style scoped>

</style>