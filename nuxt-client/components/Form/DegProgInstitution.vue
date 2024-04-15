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
                        v-for="(curriculum, index) in formContent.curricula"
                        :key="index"
                    >
                        <PrimeAccordionTab :header="`Curriculum #${index}`">
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
                                    v-for="(year, index) in curriculum.year"
                                    :key="index"
                                >
                                    <PrimeAccordionTab :header="`Year ${index+1}`">
                                        <!-- Semesters of a Year -->
                                        <PrimeAccordion
                                            v-for="(courses, semName) in year"
                                            :key="semName"
                                        >   
                                            <PrimeAccordionTab :header="semName">
                                                <TableCourse
                                                    :searchLabel="searchLabel"
                                                    :selectItem="selectItem"
                                                    :condition="condition"
                                                >
                                                    <template v-slot:input-field>
                                                        <PrimeChips 
                                                            v-model="courses" 
                                                            class="w-full p-2 text-base"
                                                        />
                                                    </template>
                                                </TableCourse>
                                            </PrimeAccordionTab>

                                        </PrimeAccordion>

                                        <!-- Button for adding semesters to a year -->
                                        <!-- Index to be used for semester number indicator -->
                                       <PrimeButton
                                           label="Add Semester"
                                           style="color: white"
                                           class="bg-maroon p-2 w-fit"
                                           @click="addSemester(year)"
                                       />
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
            name: "",
            //major: major
            //degProg: degProg
            year: [
                /*
                    index 0 (year 1) = {
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

    const removeInputField = (index) => {
        formContent.majors.splice(index, 1)
    }

    const addYear = (curricula) => {
        curricula.year.push({
            "Semester 1": [],
            "Semester 2": [],
            "Midsemester" : []
        })
    }

    const selectItem = (slotProps) => {
        // semester = formContent.

        // if (!semester.includes(slotProps)){
        //     semester.push(slotProps)
        // }
    }

    const condition = (slotProps) => {
        // <PrimeButton v-if="!formContent.prereqs.includes(slotProps.data.code)" class="btn-maroon" label="Select" @click="selectItem(slotProps)"/>
        // return formContent.curricula.year[]
        // const semester = 
        // return semester.includes(slotProps)
        
    }

    watchEffect(() => {
        emit('inputValue', formContent)
    })
</script>

<style scoped>
</style>