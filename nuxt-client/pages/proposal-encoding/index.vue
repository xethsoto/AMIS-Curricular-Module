<template>
    <div class="flex flex-col gap-4">
        <PrimeToast />
        <h1 class="font-bold text-lg">Proposal Encoding</h1>

        <!-- Proposal Title -->
        <FormInput type="text-field" label="Proposal Title" @input="proposalData.title = $event"/>

        <!-- Proposal Date -->
        <div class="flex flex-col">
            <label>
                <span class="text-sm">Date Effective</span>
            </label>
            <PrimeCalendar v-model="proposalData.date"
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
        <div v-if="proposalData.action" class="flex flex-col gap-4">
            <!-- Render multiple tabs depending on the number of proposals -->
            <PrimeAccordion class="flex flex-col gap-4">
                <PrimeAccordionTab
                    v-for="(item, index) in proposalData.action" :key="item.id"
                >
                    <template #header>
                        <p class="text-gray-500 text-sm font-medium">Proposal #{{ index+1 }}</p>
                        <PrimeButton
                            icon="pi pi-times"
                            class="btn-maroon m-0"
                            @click.stop="removeProposal(item.id)"
                        />
                    </template>

                    <div class="flex flex-col gap-4">
                        <!-- Determines the type of form that should be rendered -->
                        <div class="flex flex-start gap-4 w-full">
                            <Dropdown class="flex-1"
                                :items="targetSelection"
                                label="Target"
                                @dropdownVal="proposalData.action[index].propTarget = $event"
                            />

                            <Dropdown v-if="proposalData.action[index].propTarget==='Course'"
                                class="flex-1"
                                :items="courseTypeSelect"
                                label="Type"
                                @dropdownVal="proposalData.action[index].propType = $event"
                            />
                            <Dropdown v-else
                                class="flex-1"
                                :items="degProgTypeSelect"
                                label="Type"
                                @dropdownVal="proposalData.action[index].propType = $event"
                            />

                            <div class="flex-1" v-if="proposalData.action[index].propType==='Revision'
                                && proposalData.action[index].propTarget!=='Curriculum'"
                            >
                                <Dropdown v-if="proposalData.action[index].propTarget==='Degree Program'"
                                    :items="degProgRevTypes"
                                    label="Sub-type"
                                    @dropdownVal="proposalData.action[index].propSubType = $event"
                                />
                                <Dropdown v-else
                                    :items="courseRevTypes"
                                    label="Sub-type"
                                    @dropdownVal="proposalData.action[index].propSubType = $event"
                                />
                            </div>
                        </div>
    
                        <hr class="hr-temp">
                        
                        <!-- Render a subproposal form based on the the proposal classification -->
                        <SubproposalForm
                            :target="proposalData.action[index].propTarget"
                            :type="proposalData.action[index].propType"
                            :subType="proposalData.action[index].propSubType"
                            @content="proposalData.content[index]=$event"
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
    const toast = useToast()    //validation notification

    const proposalData = reactive({
        "title": ref(""),
        "date": ref(null),
        "action": [],
        "content": []
    })

    let idCounter = 0

    const addProposal = () => {
        proposalData.action.push(reactive(
            {
                id: idCounter++,
                propTarget: "",
                propType: "",
                propSubType: ""
            }
        ))
        proposalData.content.push({})

        console.log("Proposal Data Action = ", proposalData.action)
        console.log("Proposal Data Content = ", proposalData.content)
    }

    const removeProposal = (itemId) => {
        const index = proposalData.action.findIndex(item => item.id === itemId)

        if (index !== -1) {
            proposalData.action.splice(index, 1)
            proposalData.content.splice(index, 1)
        }

        console.log("Proposal Data Action = ", proposalData.action)
        console.log("Proposal Data Content = ", proposalData.content)
    }

    const submitProposal = async () => {

        const valid = validate()

        if (valid){
            proposalData.date = format(proposalData.date, 'yyyy-MM-dd')

            console.log("Submitting Form = ", proposalData);
            
            proposalData = toRaw(proposalData)
    
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
    }

    const validate = () => {
        let valid = true
    
        if (!proposalData.title){
            toast.add({
                severity: 'error',
                summary: "Error in uploading data",
                detail: "'Proposal Title' is empty. Please input a proposal title.",
                life: 5000
            });
            valid = false
        }

        if (!proposalData.date){
            toast.add({
                severity: 'error',
                summary: "Error in uploading data",
                detail: "'Date Effective' is empty. Please input a date.",
                life: 5000
            });
            valid = false
        }

        // if there are no subproposals added
        if(proposalData.action.length <= 0){
            toast.add({
                severity: 'error',
                summary: "Error in uploading data",
                detail: "No subproposals added. Please add a subproposal.",
                life: 5000
            });
            valid = false
        }

        return valid
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