<template>
    <div class="flex flex-col gap-4">
        <PrimeToast />
        <h1 class="font-bold text-lg">Proposal Encoding</h1>

        <!-- Proposal Title -->
        <FormInput type="text-field" label="Proposal Title" @input="proposalTitle = $event"/>

        <!-- Proposal Date -->
        <div class="flex flex-col">
            <label>
                <span class="text-sm">Date Effective</span>
            </label>
            <PrimeCalendar v-model="creationDate"
                dateFormat="dd/mm/yy" 
                showIcon 
                showButtonBar
                class="w-fit"
                :pt="{ 
                    input: {
                        class: 'p-2 text-base text-input'
                    }   
                }"
            />
        </div>

        <!-- Subproposals -->
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
                                @dropdownVal="propAction[num-1].propTarget = $event"
                            />

                            <Dropdown v-if="propAction[num-1].propTarget==='Course'"
                                class="flex-1"
                                :items="courseTypeSelect"
                                label="Type"
                                @dropdownVal="propAction[num-1].propType = $event"
                            />
                            <Dropdown v-else
                                class="flex-1"
                                :items="degProgTypeSelect"
                                label="Type"
                                @dropdownVal="propAction[num-1].propType = $event"
                            />

                            <div class="flex-1" v-if="propAction[num-1].propType==='Revision' && propAction[num-1].propTarget!=='Curriculum'">
                                <Dropdown v-if="propAction[num-1].propTarget==='Degree Program'"
                                    :items="degProgRevTypes"
                                    label="Sub-type"
                                    @dropdownVal="propAction[num-1].propSubType = $event"
                                />
                                <Dropdown v-else
                                    :items="courseRevTypes"
                                    label="Sub-type"
                                    @dropdownVal="propAction[num-1].propSubType = $event"
                                />
                            </div>
                        </div>
    
                        <hr class="hr-temp">
                        
                        <!-- Course Institution Form -->
                        <FormCourseInstitution 
                            v-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Institution'"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                        <!-- Course Abolition Form -->
                        <FormCourseAbolition
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Abolition'"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />
                        
                        <!-- Course Revision Forms -->
                        <FormCourseRevisionTitleNum
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Revision'
                            && propAction[num-1].propSubType===courseRevTypes[0]"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                        <FormCourseRevisionDescription
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Revision'
                            && propAction[num-1].propSubType===courseRevTypes[1]"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                        <FormCourseRevisionPrerequisites
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Revision'
                            && propAction[num-1].propSubType===courseRevTypes[2]"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                        <FormCourseRevisionSemOffering
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Revision'
                            && propAction[num-1].propSubType===courseRevTypes[3]"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4" 
                        />

                        <FormCourseRevisionNumOfHours
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Revision'
                            && propAction[num-1].propSubType===courseRevTypes[4]"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                        <FormCourseRevisionCredit
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Revision'
                            && propAction[num-1].propSubType===courseRevTypes[5]"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                        <FormCourseRevisionContent
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Revision'
                            && propAction[num-1].propSubType===courseRevTypes[6]"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />
                        
                        <!-- Course Crosslisting -->
                        <FormCourseCrosslisting
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Crosslisting'"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />
                        
                        <!-- Course Adoption -->
                        <FormCourseInstitution
                            v-else-if="propAction[num-1].propTarget==='Course'
                            && propAction[num-1].propType==='Adoption'"
                            @inputValue="formContent[num-1]=$event"
                            :adoption="true"
                            class="flex flex-col gap-4"
                        />

                        <FormDegProgInstitution
                            v-else-if="propAction[num-1].propTarget==='Degree Program'
                            && propAction[num-1].propType==='Institution'"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                    </div>

                </PrimeAccordionTab>
            </PrimeAccordion>

        </div>

        <div v-else class="p-4 text-center">
            <p class="font-medium">Nothing encoded yet. Add Proposal to start encoding.</p>
        </div>

        <div class="flex flex-row gap-4 justify-between">
            <PrimeButton class="btn-maroon w-fit" label="Add Proposal" @click="addProposal"/>
            <PrimeButton class="bg-green-500 text-white p-2 w-fit" label="Submit" @click="submitProposal"/>
        </div>
    </div>
</template>

<script setup>
    import { useToast } from 'primevue/usetoast'
    import { useRouter } from 'vue-router'
    import { format } from 'date-fns'

    const router = useRouter()

    const toast = useToast()    //notification

    const proposalTitle = ref("")
    const creationDate = ref(null)
    const numOfProp = ref(0)
    
    const propAction = reactive([])
    const formContent = reactive([])

    const addProposal = () => {
        numOfProp.value++
        propAction.push(reactive(
            {
                propTarget: "",
                propType: "",
                propSubType: ""
            }
        ))
        formContent.push({})
    }

    const submitProposal = async () => {

        validate()

        const proposalData = {
            "title": proposalTitle.value,
            "date": format(creationDate.value, 'yyyy-MM-dd'),
            "action": propAction,
            "content": formContent
        }

        console.log("Submitting Form = ", proposalData);

        try {
            const { data, error } = await useFetch('http://localhost:8000/api/save-proposal',{
                method: 'POST',
                body: JSON.stringify(proposalData)
            })
            
            if (error.value) {
                console.log("Error in uploading data: ", error.value.data?.message)

                error.value.data.message.forEach(msg => {
                    toast.add({
                        severity: 'error',
                        summary: "Error in uploading data",
                        detail: msg,
                        life: 3000
                    })
                })
            } else {
                console.log("Data uploaded successfully")

                toast.add({
                    severity: 'success',
                    summary: "Data uploaded successfully",
                    life: 3000
                })
                router.push('/proposals-management/' + data.value.proposal_id);
            }

        } catch (error) {
            console.error('Critical error in uploading file: ', error)
        }
    }

    const validate = () => {
        if (!proposalTitle.value){
            toast.add({
                severity: 'error',
                summary: "Error in uploading data",
                detail: "'Proposal Title' is empty",
                life: 5000
            });
        }

        if (!creationDate.value){
            toast.add({
                severity: 'error',
                summary: "Error in uploading data",
                detail: "'Date Effective' is empty",
                life: 5000
            });
        }

        if(numOfProp.value <= 0){
            toast.add({
                severity: 'error',
                summary: "Error in uploading data",
                detail: "No subproposals added. Please add a subproposal to continue.",
                life: 5000
            });
        }

    }

    // Dropdown choices
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

    /*
    * NOTE: Changing the values will affect the processing of the forms
    */
    const courseRevTypes = [
        "Change in course number and/or course title",
        "Change in course description",
        "Change in prerequisites",
        "Change in semester offering",
        "Change in number of hours",
        "Change in course credit",
        "Change in course content"
    ]
    
</script>

<style>
    /* Date Calendar Button */
    .p-button.p-component.p-button-icon-only.p-datepicker-trigger{
        background-color: maroon;
        color: white;
        border-top-right-radius: 7px;
        border-bottom-right-radius: 7px;
    }
</style>