<template>
    <div>
        <div>
            <p class="text-xs"><slot></slot></p>
            <UInput
                size="md"
                v-model="value"
                :ui="{
                    rounded: 'rounded-none'
                }"
            />
        </div>
    </div>
</template>

<script setup>
    const value = ref('')
</script>

<style lang="scss" scoped>

</style>