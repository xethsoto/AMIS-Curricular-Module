<template>
    <FormInput type="text-field" label="Name" @input="formContent.name = $event"/>
    <FormInput type="text-field" label="Career" @input="formContent.career = $event"/>
    <FormInput type="text-field" label="College" @input="formContent.college = $event"/>
    <FormInput type="text-field" label="Number of Units" @input="formContent.numOfUnits = $event"/>
    <FormInput type="text-area" label="Description" @input="formContent.desc = $event"/>

    <!-- Majors -->
    <label class="text-left text-base font-bold">New Majors</label>
    <div v-if="formContent.majors.length">
        <PrimeAccordion 
            v-for="(major,index) in formContent.majors"
            :key="index"
        >
            <PrimeAccordionTab :header="`Major #${index}`">
                <div class="flex flex-col gap-4">

                    <!-- Remove Major Button -->
                    <PrimeButton 
                        label="Remove Major"
                        style="color: white" 
                        class="bg-maroon p-2 w-fit"
                        @click="removeInputField(index)">
                    </PrimeButton>
                    
                    <!-- Major -->
                    <div class="flex flex-col">
                        <label>
                            <span class="text-sm">Major Name</span>
                        </label>
                        <PrimeInputText
                        variant="filled" 
                        type="text" 
                        v-model="formContent.majors[index]"
                        class="text-input p-2 text-base"
                        />
                    </div>
                    <PrimeButton
                        label="Add Curriculum"
                        style="color: white"
                        class="bg-maroon p-2 w-fit"
                        @click="addCurriculum"
                    />

                    <!-- Curricula of Major -->
                    <PrimeAccordion
                        v-for="(curriculum, currIndex) in formContent.curricula"
                        :key="currIndex"
                    >
                        <PrimeAccordionTab :header="`Curriculum #${currIndex+1}`">
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col">
                                    <label>
                                        <span class="text-sm">Curriculum Name</span>
                                    </label>
                                    <PrimeInputText
                                        variant="filled" 
                                        type="text" 
                                        v-model="curriculum.name"
                                        class="text-input p-2 text-base"
                                    />
                                </div>

                                <!-- Year of a Curriculum -->
                                <PrimeAccordion 
                                    v-for="(year, yearIndex) in curriculum.year"
                                    :key="yearIndex"
                                >
                                    <PrimeAccordionTab :header="`Year ${yearIndex+1}`">
                                        <!-- Semesters of a Year -->
                                        <PrimeAccordion
                                            v-for="(value, key) in year"
                                            :key="key"
                                        >   
                                            <PrimeAccordionTab v-if="key!='curriculumIndex'" :header="key">
                                                <TableCourse
                                                    :searchLabel="searchLabel"
                                                    :customAction="true"
                                                >
                                                    <template v-slot:input-field>
                                                        <!-- <PrimeChips 
                                                            v-model="formContent.curricula[currIndex].year[yearIndex].semName"
                                                            class="w-full p-2 text-base"
                                                        /> -->
                                                        {{value}}
                                                    </template>

                                                    <template #custom-action>
                                                        <PrimeColumn key="action" field="action" header="Action">

                                                            <template #body="slotProps">
                                                                <p
                                                                    v-if="condition(slotProps.data.code, value)"
                                                                    class="italic font-normal"
                                                                >
                                                                    Selected
                                                                </p>
                                                                <PrimeButton
                                                                    v-else
                                                                    class="btn-maroon"
                                                                    label="Select"
                                                                    @click="selectItem(slotProps.data.code, value)"
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
                                    @click="addYear(curriculum)"
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
    const emit = defineEmits(['inputValue'])
    
    const formContent = reactive({
        name: "",
        career: "",
        college: "",
        numOfUnits: 1,
        desc: "",
        majors: [],
        curricula: []
    })

    const addMajor = () => {
        formContent.majors.push("")
    }

    const searchLabel = "Course to Add"

    const addCurriculum = () => {
        formContent.curricula.push({
            index: formContent.curricula.length,
            name: "",
            //major: major
            //degProg: degProg
            year: [
                /*
                    index 0 (year 1) = {
                        curriculumIndex: 0
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

        console.log("formContent.curricula = ", formContent.curricula)
    }

    const removeInputField = (index) => {
        formContent.majors.splice(index, 1)
    }

    const addYear = (curricula) => {
        curricula.year.push({
            "curriculumIndex": curricula.index,
            "Semester 1": [],
            "Semester 2": [],
            "Midsemester" : []
        })
    }

    const selectItem = (slotProps, courses) => {
        if (!courses.includes(slotProps)){
            courses.push(slotProps)
        }
    }

    // button render condition in adding courses to a sem
    const condition = (slotProps, courses) => {
        return courses.includes(slotProps)
    }

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>