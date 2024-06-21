<template>
    <div class="flex justify-center">
        <PrimeCard style="width: 25rem; overflow: hidden">
            <template #header>
                <div class="flex justify-center mt-4">
                    <img alt="UPLB Official Logo" src="assets\images\uplb-logo.webp" class="size-24"/>
                </div>
            </template>
            <template #title>
                <p class="text-center text-2xl">Login</p>
            </template>
            <template #subtitle>
                <p class="text-lg text-center">Welcome to UPLB Academic Management Information System</p>
            </template>
            <template #content>
                <p class="m-0 text-center text-sm">
                    Note: This is a demo application to be used for testing a potential feature for AMIS. THIS IS NOT THE ACTUAL AMIS WEB APPLICAITON.
                </p>
    
                <!-- Login Form -->
                <div class="flex flex-col gap-4">
    
                    <!-- Email -->
                    <div class="flex flex-col">
                        <FormInput label="Email" type="text-field" @input="loginForm.email=$event"/>
                        <p v-if="!isValidEmail" class="text-sm text-red-500">
                            Please enter a valid email address
                        </p>
                    </div>
                    
                    <!-- Password -->
                    <div class="flex flex-col">
                        <p class="text-sm">Password</p>
                        <PrimePassword
                            toggleMask
                            v-model="loginForm.password"
                            :pt="{
                                input: {class: 'border-2'}
                            }"
                        />
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex gap-3 mt-1">
                    <PrimeButton label="Save" class="btn-maroon w-full"
                    @click="submitForm"/>
                </div>
            </template>
        </PrimeCard>
    </div>
</template>

<script setup>
    const loginForm = reactive({
        email: '',
        password: ''
    })

    const isValidEmail = ref(true)

    const validateEmail = (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    const submitForm = () => {
        isValidEmail.value = validateEmail(loginForm.email)

        if (isValidEmail.value) {
            console.log("Submitting Form")
        }
    }
</script>