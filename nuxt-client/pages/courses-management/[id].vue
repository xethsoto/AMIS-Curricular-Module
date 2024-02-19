<template>
    <div>
        <NuxtLayout name="curricular-viewer" prevLink="\courses-management" :course="course">
            <template v-slot:viewer-title>Course Viewer</template>
            <template v-slot:title>{{ `${course.code} (${course.title})` }}</template>
            <template v-slot:contents>

                <!-- General Details -->
                <CurrViewerGenDetails>
                    <template v-slot:title>General Details</template>
                    <template v-slot:firstHalf>
                        <DetailSpan title="Code: " :content="course.code"/>
                        <DetailSpan title="Title: " :content="course.title"/>
                        <DetailSpan title="Description: " :content="course.desc"/>
                    </template>
                    <template v-slot:secondHalf>
                        <DetailSpan title="Semesters Offered: " :content="semOffered"/>
                        <DetailSpan title="Credit: " :content="course.credit"/>
                        <DetailSpan title="Number of Hours: " :content="course.num_of_hours"/>
                        <DetailSpan title="Goal: " :content="course.goal"/>
                        <DetailSpan title="Status: " :content="course.status"/>
                    </template>
                </CurrViewerGenDetails>
                <hr class="h-px border-0 bg-black dark:bg-gray-700">
                
                <!-- Prerequisites and Requisites -->
                <div class="flex flex-row">
                    <div class="flex flex-col flex-1 gap-2">
                        <p class="font-semibold">Prerequisites:</p>
                        <div v-if="prereqs">
                            <div v-for="prereq in prereqs">
                                <ULink
                                    :to="`/courses-management/${prereq.id}`"
                                    class="text-blue-900 hover:text-blue-300"
                                >
                                {{ `${prereq.code}: ${prereq.title}` }}
                                </ULink>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col flex-1 gap-2">
                        <p class="font-semibold">Requisites:</p>
                        <div v-if="reqs">
                            <div v-for="req in reqs">
                                <ULink
                                    :to="`/courses-management/${req.id}`"
                                    class="text-blue-900 hover:text-blue-300"
                                >
                                {{ `${req.code}: ${req.title}` }}
                                </ULink>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="font-semibold">Course Outline:</p>
                <div v-if="course">
                    <p>{{ course.outline }}</p>
                </div>

                <div v-else>
                    <p class="font-bold italic">No Course Outline</p>
                </div>

                <hr class="h-px border-0 bg-black dark:bg-gray-700">

            </template>
        </NuxtLayout>
    </div>
</template>

<script setup>
    // Functions
    // const customSort = (array) => {
    //     console.log("array = ", array)
    //     array.sort((a, b) => {
    //         const numA = parseInt(a.match(/\d+/));
    //         const numB = parseInt(b.match(/\d+/));

    //         // If both strings have numbers, compare them numerically
    //         if (!isNaN(numA) && !isNaN(numB)) {
    //             return numA - numB;
    //         }

    //         return a.localeCompare(b);
    //      });
    // }

    const { id } = useRoute().params

    // fetching course
    const courseURI = 'http://localhost:3001/courses/' + id
    const { data: course } = await useFetch(courseURI, {key: id})
    if (!course.value){
        throw createError({ statusCode: 404, statusMessage: "Course not found", fatal: true })
    }

    //fetching courses
    const coursesURI = 'http://localhost:3001/courses'
    const { data: courses } = await useFetch(coursesURI)

    // course - semester offerings
    const semOfferedURI = 'http://localhost:3001/sem_offered?course_id=' + id 
    const { data: semOfferedData } = await useFetch(semOfferedURI)
    const semOffered = ref("")
    semOfferedData.value.forEach((entry) => {
        semOffered.value += `${entry.sem_offered} `
    })

    // course prerequisites and requisites
    const prereqURI = 'http://localhost:3001/course_prereq?course_id=' + id
    const { data: prereqData } = await useFetch(prereqURI)
    const prereqs = courses.value.filter((course) => {
            return prereqData.value.some((prereq) => {
                return Number(course.id) === prereq.prereq_id
            })
        })

    // customSort(prereqs)
    
    const reqURI = 'http://localhost:3001/course_prereq?prereq_id=' + id
    const { data: reqData } = await useFetch(reqURI)
    const reqs = courses.value.filter((course) => {
            return reqData.value.some((req) => {
                return Number(course.id) === req.course_id
            })
        })

    // customSort(reqs)

</script>

<style scoped>

</style>