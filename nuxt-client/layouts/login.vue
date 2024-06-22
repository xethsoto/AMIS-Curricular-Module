<template>
    <PrimeToast />
    <div class="flex flex-col gap-2 items-center">
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
                    <PrimeButton label="Login" class="btn-maroon w-full"
                    @click="submitForm"/>
                </div>
            </template>
        </PrimeCard>

        <p>
            Don't have an account? Register
            <NuxtLink to="/register" class="nav-link underline">here</NuxtLink>
        </p>
    </div>
</template>

<script setup>
    import { useToast } from 'primevue/usetoast'
    import { useRouter } from 'vue-router'

    const toast = useToast()
    const router = useRouter()
    const authToken = useCookie('auth-token')
    if (authToken.value) {
        router.push('/')
    }

    const loginForm = reactive({
        email: '',
        password: ''
    })

    const isValidEmail = ref(true)

    const validateEmail = (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    const submitForm = async () => {
        isValidEmail.value = validateEmail(loginForm.email)

        const authToken = useCookie('auth-token')

        if (isValidEmail.value) {
            try {
                const response = await fetch('http://localhost:8000/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(loginForm)
                })

                const data = await response.json()

                if (!response.ok) {
                    throw new Error(data.message)
                }

                if (data.token) {
                    authToken.value = data.token
                    router.push('/courses-management')
                } else {
                    throw new Error('Token not received')
                }
            } catch (error) {
                console.log("Error: ", error)
                toast.add({
                    severity: 'error',
                    summary: error,
                    detail: error.message,
                    life: 3000
                })
            }
        }
    }
</script>