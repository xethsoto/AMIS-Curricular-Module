<template>
    <div>
        <div>
            <UFormGroup :label="label">
                <UInput
                    size="md"
                    v-model="value"
                    :ui="{rounded: 'rounded-none'}"
                    @keyup="sendData">
                </UInput>
            </UFormGroup>
        </div>
    </div>
</template>

<script setup>
    const {label} = defineProps(['label'])
    const emits = defineEmits()
    const value = ref('')

    function sendData() {
        emits('textbox', value.value)
    }
</script>

<style lang="scss" scoped>

</style>