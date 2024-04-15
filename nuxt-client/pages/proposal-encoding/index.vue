<template>
    <div class="flex flex-col gap-4">
        <PrimeToast />
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
                        
                        <FormCourseInstitution 
                            v-if="propAction[num-1].propTarget==='Course' && propAction[num-1].propType==='Institution'"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                        <FormCourseAbolition
                            v-else-if="propAction[num-1].propTarget==='Course' && propAction[num-1].propType==='Abolition'"
                            @inputValue="formContent[num-1]=$event"
                            class="flex flex-col gap-4"
                        />

                        <FormDegProgInstitution
                            v-else-if="propAction[num-1].propTarget==='Degree Program' && propAction[num-1].propType==='Institution'"
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

    const toast = useToast()    //notification

    const proposalTitle = ref("Proposal Test Title")
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
        const proposalData = {
            "title": proposalTitle.value,
            "action": propAction,
            "content": formContent
        }
        
        try {
            const { data, error } = await useFetch('http://localhost:8000/api/save-proposal',{
                method: 'POST',
                body: JSON.stringify(proposalData)
            })
            
            // console.log("response = ", response)
            // const responseData = response.error
            // console.log("responseData = ", responseData)
            
            if (error.value) {
                console.log("Error in uploading data: ", error.value.data?.message)

                toast.add({
                    severity: 'error',
                    summary: "Error in uploading data",
                    detail: error.value.data.message,
                    life: 3000
                })
            } else {
                console.log("Data uploaded successfully")

                toast.add({
                    severity: 'success',
                    summary: data.message,
                    life: 3000
                })
                await navigateTo({ path: '/proposal-encoding' });
            }

        } catch (error) {
            console.error('Critical error in uploading file: ', error)
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