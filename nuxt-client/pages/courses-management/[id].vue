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
                <hr class="hr-temp">
                
                <!-- Prerequisites and Requisites -->
                <!-- <div class="flex flex-row">
                    <div class="flex flex-col flex-1 gap-2">
                        <p class="font-semibold">Prerequisites:</p>
                        <div v-if="prereqs">
                            <div v-for="prereq in prereqs">
                                <ULink
                                    :to="`/courses-management/${prereq.id}`"
                                    class="nav-link"
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
                </div> -->

                <!-- <p class="font-semibold">Course Outline:</p>
                <div v-if="course">
                    <p>{{ course.outline }}</p>
                </div>
                <div v-else>
                    <p class="font-bold italic">No Course Outline</p>
                </div>

                <hr class="hr-temp"> -->

                <!-- Degree Programs Requiring This Course -->

                <!-- <p class="font-bold text-lg text-center">Degree Programs Requiring This Course</p>
                <PrimeAccordion v-if="filteredDegProgs" :multiple="true">
                    <PrimeAccordionTab v-for="degProg in filteredDegProgs" :header="degProg.name">
                        <div v-if="degProg.has_majors" class="grid grid-cols-2 gap-4">
                            <div v-for="degMajorObj in degProgMajors" class="text-center"> -->

                                <!-- Filters appropriate deg_prog - major relationship -->
                                <!-- <div v-if="degMajorObj.deg_prog_id == degProg.id">

                                    <div v-for="major in filteredMajors">
                                        <div v-if="major.id == degMajorObj.major_id">
                                            <p class="font-semibold">{{ major.name }}</p>
                                        </div>
                                    </div> -->

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

                                <!-- </div>
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
                    <p class="text-semibold">No Degree Programs require this course</p>
                </div> -->

                <hr class="hr-temp">
                
                <!-- History -->
                <p class="font-bold text-lg text-center">Course History</p>
                <!-- <div class="timeline-container">
                    <PrimeTimeline :value="events" layout="horizontal" class="w-full md:w-20rem overflow-x-auto" @wheel="handleScroll">
                        <template #marker="slotProps">
                            <div class="border-2 border-solid border-black">
                                <div v-for="testing in slotProps">
                                    {{ testing.item }}
                                </div>
                            </div>
                        </template>
                        <template #content="slotProps">
                            <div class="container w-96">
                                
                            </div>
                        </template>
                    </PrimeTimeline>
                </div> -->
                
            </template>
        </NuxtLayout>
    </div>
</template>

<script setup>
    const { id } = useRoute().params

    const events = [
        {
            item: "Institution"
        },
        {
            item: "Revision"
        },
        {
            item: "Revision"
        },
        {
            item: "Revision"
        },
        {
            item: "Revision"
        },
        {
            item: "Abolition"
        },
   ]

//    const events = [
//     "Institution", "Revision", "Revision", "Revision", "Revision", "Revision", "Abolition"
//     ]

    // fetching course
    console.log("id = ", id)
    const promise = useFetch('http://localhost:8000/api/course/' + id, { immediate: false })
    await promise.execute({_initial: true})

    const course = promise.data.value

    console.log("course = ", course)

    // // fetching course
    // // const courseURI = 'http://localhost:3001/courses/' + id
    // // const { data: course } = await useFetch(courseURI, {key: id})
    // // if (!course.value){
    // //     throw createError({ statusCode: 404, statusMessage: "Course not found", fatal: true })
    // // }

    // //fetching courses
    // const coursesURI = 'http://localhost:3001/courses'
    // const { data: courses } = await useFetch(coursesURI)

    // // course - semester offerings
    // const semOfferedURI = 'http://localhost:3001/sem_offered?course_id=' + id 
    // const { data: semOfferedData } = await useFetch(semOfferedURI)
    // const semOffered = ref("")
    // semOfferedData.value.forEach((entry) => {
    //     semOffered.value += `${entry.sem_offered} `
    // })

    // // course prerequisites and requisites
    // const prereqURI = 'http://localhost:3001/course_prereq?course_id=' + id
    // const { data: prereqData } = await useFetch(prereqURI)
    // const prereqs = courses.value.filter((course) => {
    //         return prereqData.value.some((prereq) => {
    //             return Number(course.id) === prereq.prereq_id
    //         })
    //     })
    
    // const reqURI = 'http://localhost:3001/course_prereq?prereq_id=' + id
    // const { data: reqData } = await useFetch(reqURI)
    // const reqs = courses.value.filter((course) => {
    //         return reqData.value.some((req) => {
    //             return Number(course.id) === req.course_id
    //     })
    // })

    // const { data: curricula } = await useFetch('http://localhost:3001/curriculum')
    // const { data: degProgs } = await useFetch('http://localhost:3001/deg_prog')
    // const { data: majors } = await useFetch('http://localhost:3001/majors')
    // const { data: degProgMajors } = await useFetch('http://localhost:3001/deg_prog_majors')
    
    // const currCourseURI = 'http://localhost:3001/curr_course?course_id=' + id
    // const { data: currCourse } = await useFetch(currCourseURI)

    // // curricula that require said courses
    // const curriculaIds = [...new Set(currCourse.value.map(obj => obj.curr_id))] // eliminates duplicate values
    // const filteredCurricula = curricula.value.filter((curriculum) => {
    //     return curriculaIds.includes(Number(curriculum.id))
    // })

    // // degree programs of the curriculum that requires the course
    // const degProgIds = [...new Set(filteredCurricula.map(obj => obj.deg_prog_id))]
    // const filteredDegProgs = degProgs.value.filter((degProg) => {
    //     return degProgIds.includes(Number(degProg.id))
    // })

    // // majors that require specific course
    // const majorsIds = [...new Set(filteredCurricula.map(obj => obj.major_id))]
    // const filteredMajors = majors.value.filter((major) => {
    //     return majorsIds.includes(Number(major.id))
    // })
    
    // // Timeline
    // const scrollableElement = ref(null);

    // const handleScroll = ((event) => {
    //     // Calculate the horizontal scroll amount based on the deltaX property
    //     const delta = event.deltaX || event.deltaY;
    //     // Adjust the scrollLeft property of the scrollable element
    //     event.currentTarget.scrollLeft += delta;

    //     if (Math.abs(event.deltaX) > Math.abs(event.deltaY)) {
    //         event.preventDefault();
    //         // Prevent the window from scrolling
    //         window.scrollTo({
    //         left: window.scrollX,
    //         top: window.scrollY,
    //         behavior: 'instant'
    //         });
    //     }
    // })

</script>

<style scoped>

</style>