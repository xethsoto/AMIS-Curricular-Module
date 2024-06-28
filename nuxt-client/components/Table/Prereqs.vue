<template>
    <!-- New prerequisites list and table -->
    <div>
        <label class="text-base font-bold">
            {{ label }}
        </label>
        <div class="flex flex-col gap-4">
            <p class="text-sm italic text-red-500">
                <span class="font-bold">NOTE: </span>
                <span>Leaving the field empty means </span>
                <span class="font-bold">NO PREREQUISITES</span>
            </p>

            <!-- Input Field -->
            <PrimeInputGroup>
                <PrimeInputText
                    disabled
                    v-model="prereqs" 
                    class="border-2 p-2 text-input"
                />
                <PrimeButton
                    icon="pi pi-calendar" 
                    style="color: white" 
                    class="bg-maroon" 
                    @click="showTable"
                />
            </PrimeInputGroup>
            <Table v-if="viewTable"
                :data="courses" 
                searchLabel="Filter by Course Code" 
                :rowsOption="false" 
                class="border-2"
            >
                <template v-slot:columns>
                    <PrimeColumn 
                        v-for="entry in meta"
                        sortable 
                        :key="entry.field" 
                        :field="entry.field" 
                        :header="entry.header"
                    >
                    </PrimeColumn>

                    <PrimeColumn key="action" field="action" header="Action">
                        <template #body="slotProps">
                            <PrimeButton v-if="prereqs.includes(slotProps.data.code)"
                                class="btn-maroon w-20"
                                label="Remove"
                                @click="removePrereq(slotProps.data.code)"
                            />
                            <PrimeButton v-else
                                class="btn-green w-20"
                                label="Select"
                                @click="addPrereq(slotProps.data.code)"
                            />
                        </template>
                    </PrimeColumn>

                </template>
            </Table>
        </div>
    </div>
</template>

<script setup>
    const props = defineProps(['selectedCourse', 'label'])
    const emit = defineEmits(['inputValue'])

    const apiUrl = process.env.VUE_APP_API_URL
    const {data: fetchedCourses} = await useFetch(`${apiUrl}/api/get-courses`)

    const selectedCourseRef = toRef(props, 'selectedCourse')
    const label = props.label
    
    const courses = computed(() => {
        if(selectedCourseRef.value === undefined) return fetchedCourses.value
        return fetchedCourses.value.filter(course => course.id !== selectedCourseRef.value.id)
    })
    const emptyArray = ref([])
    const viewTable = ref(true)

    const prereqs = computed(() => {
        if (selectedCourseRef.value === undefined) return emptyArray.value
        return selectedCourseRef.value.prereqs
    })

    const showTable = () => {
        viewTable.value = !viewTable.value
    }

    const removePrereq = (code) => {
        const index = prereqs.value.indexOf(code)
        if (index !== -1) {
            prereqs.value.splice(index, 1);
        }
    }

    const addPrereq = (code) => {
        if (!prereqs.value.includes(code)) {
            prereqs.value.push(code)
        }
    }

    watchEffect(() => {
        emit('inputValue', prereqs.value)
    })

    const meta = [
        {
            field: "code",
            header: "Course Code",
        },
        {
            field: "title",
            header: "Title",
        },
        {
            field: "credit",
            header: "Units",
        },
        {
            field: "status",
            header: "Status",
        }
    ]
</script>

<style scoped>

</style>