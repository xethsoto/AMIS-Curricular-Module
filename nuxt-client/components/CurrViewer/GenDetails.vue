<template>
    <div class="flex flex-col gap-4">
        <h3 class="font-bold text-center text-lg"><slot name="title"></slot></h3>
        <div class="flex flex-row">
            <div class="flex flex-1 flex-col">
                <slot name="firstHalf">
                </slot>
            </div>
            <div class="flex flex-1 flex-col">
                <slot name="secondHalf">
                </slot>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>

</style>