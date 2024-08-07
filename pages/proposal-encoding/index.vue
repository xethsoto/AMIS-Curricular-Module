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

        <div class="bg-white p-4 rounded-md">
            <p class="text-xl font-bold">Notes:</p>
            <p>To add a subproposal, click the "Add Subproposal" button below.</p>
            <p>To delete a subproposal, click the "x" button at the left side of the subproposal to be deleted.</p>
            <p>Change the type of subproposal by clicking the dropdown box and selecting the desired type.</p>
            <p>Fill out the form/s and submit to create a proposal.</p>
            <p class="italic">Creating <span class="font-bold">institutions and/or adoption</span> proposals <span class="font-bold">creates a new course </span> alongside the proposal.</p>
            <p class="italic">Creating <span class="font-bold">revisions, abolition, and/or crosslisting</span> will <span class="font-bold">only</span> update <span class="font-bold">EXISTING</span> course.</p>

        </div>

        <!-- Subproposals -->
        <div v-if="proposalData.subproposals" class="flex flex-col gap-4">
            <!-- Render multiple tabs depending on the number of proposals -->
            <PrimeAccordion class="flex flex-col gap-4">
                <PrimeAccordionTab
                    v-for="(item, index) in proposalData.subproposals" :key="item.id"
                >
                    <template #header>
                        <p class="text-gray-500 text-sm font-medium">Subproposal #{{ index+1 }}</p>
                        <PrimeButton
                            icon="pi pi-times"
                            class="btn-maroon m-0"
                            @click.stop="removeProposal(item.id)"
                        />
                    </template>

                    <div class="flex flex-col gap-4">
                        <!-- Determines the type of form that should be rendered -->
                        <div class="flex flex-start gap-4 w-full">

                            <!-- Target -->
                            <div class="flex flex-col w-full">
                                <label for="dropdown" class="text-gray-500">
                                    <span class="text-sm">Target</span>
                                </label>
                                <PrimeDropdown 
                                    id="dropdown" 
                                    v-model="item.action.propTarget" 
                                    variant="filled" 
                                    :options="targetSelection" 
                                    class="dropdown text-base"
                                />
                            </div>

                            <!-- Type -->
                            <div class="flex flex-col w-full">
                                <label for="dropdown" class="text-gray-500">
                                    <span class="text-sm">Type</span>
                                </label>
                                <PrimeDropdown 
                                    id="dropdown" 
                                    v-model="item.action.propType" 
                                    variant="filled" 
                                    :options="courseTypeSelect" 
                                    class="dropdown text-base"
                                />
                            </div>

                            <!-- Subtype -->
                            <div 
                                class="flex flex-col w-full"
                                v-if="item.action.propType==='Revision'"
                            >
                                <label for="dropdown" class="text-gray-500">
                                    <span class="text-sm">Sub-type</span>
                                </label>
                                <PrimeDropdown 
                                    id="dropdown" 
                                    v-model="item.action.propSubType" 
                                    variant="filled"
                                    :options="courseRevTypes" 
                                    class="dropdown text-base"
                                />
                            </div>

                        </div>
    
                        <hr class="hr-temp">
                        
                        <!-- Render a subproposal form based on the the proposal classification -->
                        <SubproposalForm
                            :target="item.action.propTarget"
                            :type="item.action.propType"
                            :subType="item.action.propSubType"
                            @content="item.content=$event"
                        />
                    </div>

                </PrimeAccordionTab>
            </PrimeAccordion>

        </div>

        <div v-else class="p-4 text-center">
            <p class="font-medium">Nothing encoded yet. Add Proposal to start encoding.</p>
        </div>

        <div class="flex flex-row gap-4 justify-between">
            <PrimeButton class="btn-maroon w-fit" label="Add Subproposal" @click="addProposal"/>
            <PrimeButton class="bg-green-500 text-white p-2 w-fit" label="Submit" @click="submitProposal"/>
        </div>
    </div>
</template>

<script setup>
    import { useToast } from 'primevue/usetoast'
    import { useRouter } from 'vue-router'
    import { format } from 'date-fns'

    definePageMeta({
        middleware: ['auth']
    })

    const router = useRouter()
    const toast = useToast()    //validation notification

    const proposalData = reactive({
        "title": ref(""),
        "date": ref(null),
        "subproposals": []
    })

    let idCounter = 0

    const addProposal = () => {
        proposalData.subproposals.push({
            id: idCounter++,
            action: {
                propTarget: "Course",
                propType: "Institution",
                propSubType: ""
            },
            content: {}
        })
    }

    onMounted(() => {
        addProposal()
    })

    const removeProposal = (itemId) => {
        const index = proposalData.subproposals.findIndex(item => item.id === itemId)

        if (index !== -1) {
            proposalData.subproposals.splice(index, 1)
        }
    }

    const submitProposal = async () => {

        const config = useRuntimeConfig()
        const apiUrl = config.public.api_url
        const valid = validate()

        if (valid){
            proposalData.date = format(proposalData.date, 'yyyy-MM-dd')

            const dataToSend = {
                "title": proposalData.title,
                "date": proposalData.date,
                "action": proposalData.subproposals.map(subproposal => subproposal.action),
                "content": proposalData.subproposals.map(subproposal => subproposal.content)
            }
                
            try {
                const { data, error } = await useFetch(`${apiUrl}/api/save-proposal`,{
                    method: 'POST',
                    body: JSON.stringify(dataToSend)
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
        if(proposalData.subproposals.length <= 0){
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
    const targetSelection = ["Course"]

    const courseTypeSelect = [
        "Institution",
        "Revision",
        "Abolition",
        "Crosslisting",
        "Adoption"
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

    .dropdown {
        border: 2px solid #ccc;
    }
</style>