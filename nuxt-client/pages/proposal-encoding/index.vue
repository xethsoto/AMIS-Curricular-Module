<template>
    <div class="flex flex-col gap-4">
        <h1 class="font-bold text-lg">Proposal Encoding</h1>
        <FormInput type="text-field" label="Proposal Title" @input="proposalTitle = $event"/>
        <div v-if="numOfProp" class="flex flex-col gap-4">
            
            <!-- Render multiple tabs depending on the number of proposals -->
            <PrimeAccordion v-for="num in numOfProp" :key="num">
                <PrimeAccordionTab>
                    <template #header>
                        <span class="text-gray-500 text-sm font-medium">Proposal #{{ num }}</span>
                    </template>

                    <div class="flex flex-col gap-4">
                        <!-- Determines the type of form that should be rendered -->
                        <div class="flex flex-start gap-4 w-full">
                            <Dropdown class="flex-1"
                                :items="targetSelection"
                                label="Target"
                                @dropdownVal="propTarget[num-1] = $event"
                            />
                            <Dropdown v-if="propTarget[num-1]==='Course'"
                                class="flex-1"
                                :items="courseTypeSelect"
                                label="Type"
                                @dropdownVal="propType[num-1] = $event"
                            />
                            <Dropdown v-else
                                class="flex-1"
                                :items="degProgTypeSelect"
                                label="Type"
                                @dropdownVal="propType[num-1] = $event"
                            />
                            <div class="flex-1" v-if="propType[num-1]==='Revision' && propTarget[num-1]!=='Curriculum'">
                                <Dropdown v-if="propTarget[num-1]==='Degree Program'"
                                    :items="degProgRevTypes"
                                    label="Sub-type"
                                    @dropdownVal="propSubType[num-1] = $event"
                                />
                                <Dropdown v-else
                                    :items="courseRevTypes"
                                    label="Sub-type"
                                    @dropdownVal="propSubType[num-1] = $event"
                                />
                            </div>
                        </div>
    
                        <hr class="hr-temp">
                        
                        <FormsCourseInstitution 
                            v-if="propTarget[num-1]==='Course' && propType[num-1]==='Institution'"
                            @inputValue="formContent=$event"
                            class="flex flex-col gap-4"
                        />

                        <FormsCourseAbolition
                            v-else-if="propTarget[num-1]==='Course' && propType[num-1]==='Abolition'"
                            @inputValue="formContent=$event"
                            class="flex flex-col gap-4"
                        />
                    </div>

                </PrimeAccordionTab>
            </PrimeAccordion>

        </div>

        <div v-else class="p-4 text-center">
            <p class="font-medium">Nothing encoded yet. Add Proposal to start encoding.</p>
        </div>

        <PrimeButton class="btn-maroon w-fit" label="Add Proposal" @click="addProposal"/>
    </div>
</template>

<script setup>
    const proposalTitle = ref("")
    const numOfProp = ref(0)

    const addProposal = () => {
        numOfProp.value++
    }

    const propTarget = ref([])
    const propType = ref([])
    const propSubType = ref([])

    const formContent = reactive({
        courseNum: "",
        courseTitle: "",
        courseDesc: "",
        courseCredit: null,
        numOfHours: null,
        courseGoal: "",
        courseOutline: "",
        prereqs: [],
        sem_offered: []
    })

    watchEffect(() => {
        console.log("formContent = ", formContent)
    })

    // for every new proposal, add a new empty string to accommodate
    watchEffect(() => {
        propTarget.value.push("Course")
        propType.value.push("Institution")
        propSubType.value.push("")
    })

    const targetSelection = ["Course", "Curriculum", "Degree Program"]
    const degProgTypeSelect = [
        "Institution",
        "Revision",
        "Abolition"
    ]
    const courseTypeSelect = [
        "Institution",
        "Revision",
        "Abolition",
        "Crosslisting",
        "Adoption"
    ]
    const degProgRevTypes = [
        "Addition of Major/Area of Specialization",
        "Deletion of Major/Area of Specialization",
        "Inclusion of courses",
        "Deletion of courses",
        "Change in course sequencing",
        "Change in the number of units"
    ]
    const courseRevTypes = [
        "Change in course number and/or course title",
        "Change in course description",
        "Change in prerequisites",
        "Change in semester offering",
        "Change in number of hours",
        "Addition of laboratory/recitation/computation",
        "Deletion of laboratory/recitation/computation",
        "Change in course content"
    ]
    
</script>

<style scoped>

</style>