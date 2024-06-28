<template>
    <FormInput type="text-field" label="Name" @input="formContent.name = $event"/>
    <FormInput type="text-field" label="Career" @input="formContent.career = $event"/>
    <FormInput type="text-field" label="College" @input="formContent.college = $event"/>
    <FormInput type="text-field" label="Number of Units" @input="formContent.numOfUnits = $event"/>
    <FormInput type="text-area" label="Description" @input="formContent.desc = $event"/>

    <!-- Majors -->
    <label class="text-left text-base font-bold">New Majors and Curriculum</label>
    <div v-if="formContent.majors.length">
        <PrimeAccordion>
            <PrimeAccordionTab 
                v-for="(major,index) in formContent.majors"
                :key="index"
                :header="`Major #${index+1}`"
            >
                <div class="flex flex-col gap-4">
                    <!-- Major -->
                    <div class="flex flex-col">
                        <label>
                            <span class="text-sm">Major Name</span>
                        </label>
                        <div class="flex flex-row gap-4">
                            <PrimeInputText
                                variant="filled" 
                                type="text" 
                                v-model="formContent.majors[index]"
                                class="text-input p-2 text-base flex-1"
                            />
                            <PrimeButton 
                                label="Remove Major"
                                style="color: white" 
                                class="bg-maroon p-2 w-fit"
                                @click="removeMajor(index)">
                            </PrimeButton>
                        </div>
                        <!-- Remove Major Button -->
                    </div>
                    <PrimeButton
                        label="Add Curriculum"
                        style="color: white"
                        class="bg-maroon p-2 w-fit"
                        @click="addCurriculum(formContent.majors[index])"
                    />

                    <!-- Curricula of Major -->
                    <PrimeAccordion>
                        <PrimeAccordionTab 
                            v-for="(curriculum, currIndex) in formContent.curricula"
                            :key="currIndex"
                            :header="`Curriculum #${currIndex+1}`"
                        >
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col">
                                    <label>
                                        <span class="text-sm">Curriculum Name</span>
                                    </label>
                                    <div class="flex flex-row gap-4">
                                        <PrimeInputText
                                            variant="filled" 
                                            type="text" 
                                            v-model="formContent.curricula[currIndex].name"
                                            class="text-input p-2 text-base flex-1"
                                        />
                                        <PrimeButton 
                                            label="Remove Curriculum"
                                            style="color: white" 
                                            class="bg-maroon p-2 w-fit"
                                            @click="removeCurriculum(currIndex)">
                                        </PrimeButton>
                                    </div>
                                </div>

                                <!-- Year of a Curriculum -->
                                <PrimeAccordion v-if="formContent.curricula[currIndex].year!=[]">
                                    <PrimeAccordionTab
                                        v-for="(year, yearIndex) in formContent.curricula[currIndex].year"
                                        :key="yearIndex"
                                        :header="`Year ${yearIndex+1}`"
                                    >
                                        <PrimeButton 
                                            label="Remove Year"
                                            style="color: white" 
                                            class="bg-maroon p-2 w-fit"
                                            @click="removeYear(yearIndex, year.currIndex)">
                                        </PrimeButton>

                                        <PrimeAccordion>
                                            <!-- <PrimeAccordionTab
                                                v-for="(value, key) in formContent.curricula[currIndex].year[yearIndex]"
                                                :header="key"
                                            >

                                                <template v-slot:input-field>
                                                    <PrimeChips 
                                                        v-model="semesterCourses"
                                                        class="w-full p-2 text-base"
                                                    />
                                                </template>
                                                <TableCoursePicker
                                                    :searchLabel="searchLabel"
                                                    :customAction="true"
                                                    :courses="courses"
                                                    :semesterCourses="formContent.curricula[currIndex].year[yearIndex].key"
                                                >
                                                    <template #custom-action>
                                                        <PrimeColumn 
                                                            key="action"
                                                            field="action"
                                                            header="Action"
                                                        >
                                                            <template #body="slotProps">
                                                                <p
                                                                    v-if="formContent.curricula[currIndex].year[yearIndex].key.includes(slotProps.data.code)"
                                                                    class="italic font-normal"
                                                                >
                                                                    Selected
                                                                </p>
                                                                <PrimeButton
                                                                    v-else
                                                                    class="btn-maroon"
                                                                    label="Select"
                                                                    @click="selectItem(slotProps.data.code, formContent.curricula[currIndex].year[yearIndex].key)"
                                                                />
                                                            </template>
                                                        </PrimeColumn>
                                                    </template>

                                                </TableCoursePicker>
                                            </PrimeAccordionTab> -->

                                            <!-- Semester 1 -->
                                            <PrimeAccordionTab header="Semester 1">
                                                <TableCoursePicker
                                                    :searchLabel="searchLabel"
                                                    :customAction="true"
                                                    :courses="courses"
                                                >
                                                    <template v-slot:input-field>
                                                        <PrimeChips 
                                                            v-model="formContent.curricula[currIndex].year[yearIndex].sem1"
                                                            class="w-full p-2 text-base"
                                                        />
                                                    </template>

                                                    <template #custom-action>
                                                        <PrimeColumn key="action" field="action" header="Action">

                                                            <template #body="slotProps">
                                                                <p
                                                                    v-if="condition(slotProps.data.code, formContent.curricula[currIndex].year[yearIndex].sem1)"
                                                                    class="italic font-normal"
                                                                >
                                                                    Selected
                                                                </p>
                                                                <PrimeButton
                                                                    v-else
                                                                    class="btn-maroon"
                                                                    label="Select"
                                                                    @click="selectItem(slotProps.data.code, formContent.curricula[currIndex].year[yearIndex].sem1)"
                                                                />

                                                            </template>
                                                        </PrimeColumn>
                                                    </template>
                                                </TableCoursePicker>
                                            </PrimeAccordionTab>

                                            <!-- Semester 2 -->
                                            <PrimeAccordionTab header="Semester 2">
                                                <TableCourse
                                                    :searchLabel="searchLabel"
                                                    :customAction="true"
                                                    :courses="courses"
                                                >
                                                    <template v-slot:input-field>
                                                        <PrimeChips 
                                                            v-model="formContent.curricula[currIndex].year[yearIndex].sem2"
                                                            class="w-full p-2 text-base"
                                                        />
                                                    </template>

                                                    <template #custom-action>
                                                        <PrimeColumn key="action" field="action" header="Action">

                                                            <template #body="slotProps">
                                                                <p
                                                                    v-if="condition(slotProps.data.code, formContent.curricula[currIndex].year[yearIndex].sem2)"
                                                                    class="italic font-normal"
                                                                >
                                                                    Selected
                                                                </p>
                                                                <PrimeButton
                                                                    v-else
                                                                    class="btn-maroon"
                                                                    label="Select"
                                                                    @click="selectItem(slotProps.data.code, formContent.curricula[currIndex].year[yearIndex].sem2)"
                                                                />

                                                            </template>
                                                        </PrimeColumn>
                                                    </template>
                                                </TableCourse>
                                            </PrimeAccordionTab>

                                            <!-- Mid Semester -->
                                            <PrimeAccordionTab header="Mid Semester">
                                                <TableCourse
                                                    :searchLabel="searchLabel"
                                                    :customAction="true"
                                                    :courses="courses"
                                                >
                                                    <template v-slot:input-field>
                                                        <PrimeChips 
                                                            v-model="formContent.curricula[currIndex].year[yearIndex].midSem"
                                                            class="w-full p-2 text-base"
                                                        />
                                                    </template>

                                                    <template #custom-action>
                                                        <PrimeColumn key="action" field="action" header="Action">

                                                            <template #body="slotProps">
                                                                <p
                                                                    v-if="condition(slotProps.data.code, formContent.curricula[currIndex].year[yearIndex].midSem)"
                                                                    class="italic font-normal"
                                                                >
                                                                    Selected
                                                                </p>
                                                                <PrimeButton
                                                                    v-else
                                                                    class="btn-maroon"
                                                                    label="Select"
                                                                    @click="selectItem(slotProps.data.code, formContent.curricula[currIndex].year[yearIndex].midSem)"
                                                                />

                                                            </template>
                                                        </PrimeColumn>
                                                    </template>
                                                </TableCourse>
                                            </PrimeAccordionTab>

                                        </PrimeAccordion>
                                    </PrimeAccordionTab>
                                </PrimeAccordion>

                                <!-- Button for adding a year to a curriculum -->
                                <PrimeButton
                                    label="Add Year"
                                    style="color: white"
                                    class="bg-maroon p-2 w-fit"
                                    @click="addYear(currIndex)"
                                />
                            
                            </div>
                        </PrimeAccordionTab>
                    </PrimeAccordion>
                </div>
            </PrimeAccordionTab>
        </PrimeAccordion>
    </div>
    <PrimeButton
        label="Add Major" 
        class="p-2 w-fit bg-maroon text-white"
        @click="addMajor">
    </PrimeButton>

    <!-- Rationale -->
    <FormInput type="text-area" label="Rationale" @input="formContent.rationale = $event"/>
</template>

<script setup>
    const config = useRuntimeConfig()
    const apiUrl = config.public.api_url
    const { data: courses } = await useFetch(`${apiUrl}/api/get-courses`)

    const formContent = reactive({
        name: "",
        career: "",
        college: "",
        numOfUnits: 1,
        desc: "",
        majors: [],
        curricula: [
            /*
                {
                    name:
                    majors: []
                }
            */
        ],
        acadYears: [

        ]
    })

    const addMajor = () => {
        formContent.majors.push("")
    }

    const searchLabel = "Course to Add"

    const addCurriculum = (major) => {
        formContent.curricula.push({
            index: formContent.curricula.length,
            name: "",
            major: major,
            year: [
                /*
                    index 0 (year 1) = {
                        currIndex: 0
                        yearIndex:
                        Semester 1: []
                        Semester 2: []
                        Midsemester*: []
                    },
                    index 1 (year 2) = {
                        etc.
                    }

                    *can be omitted
                */
            ]
        })
    }

    const addYear = (currIndex) => {
        formContent.curricula[currIndex].year.push({
            sem1: [],
            sem2: [],
            midSem: []
        })
    }

    const removeMajor = (index) => {
        formContent.majors.splice(index, 1)
    }

    const removeCurriculum = (index) => {
        formContent.curricula.splice(index, 1)
    }

    const removeYear = (index, currIndex) => {
        formContent.curricula[currIndex].year.splice(index, 1)
    }

    const selectItem = (slotProps, courses) => {
        if (!courses.includes(slotProps)){
            courses.push(slotProps)
        }
        console.log(courses)
    }

    // button render condition in adding courses to a sem
    const condition = (slotProps, courses) => {
        return courses.includes(slotProps)
    }

    watchEffect(() => {
        emit('inputValue', formContent)

        console.log("formContent = ", formContent)
        console.log("formContent.majors = ", formContent.majors)
    })
</script>

<style scoped>
</style>