<template>
    <div class="flex flex-col bg-white p-7 gap-4">
        <!-- Title -->
        <h2 class="font-bold text-xl">
            <slot name="title"></slot>
        </h2>
    
        <hr class="hr-temp">
        <!-- Proposal Classification -->
        <div class="flex flex-row justify-between">
            <div class="flex-grow">
                <DetailSpan title="Target: " :content="proposal_classification.target"/>
            </div>
            <div class="flex-grow">
                <DetailSpan title="Type: " :content="proposal_classification.type"/>
            </div>
            <div v-if="proposal_classification.sub_type" class="flex-grow">
                <DetailSpan title="Sub-Type: " :content="proposal_classification.sub_type"/>
            </div>
        </div>
        <hr class="hr-temp"> 
    
        <slot name="content"></slot>
    </div>
</template>

<script setup>
    const { proposal_classification } = defineProps(['proposal_classification'])
</script>

<style scoped>

</style>