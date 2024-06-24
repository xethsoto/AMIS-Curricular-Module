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
                    Note: This is a demo application to be used for testing a potential feature for AMIS. THIS IS NOT THE ACTUAL AMIS WEB APPLICATION.
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
                        <input 
                            type="password"
                            v-model="loginForm.password" 
                            class="border-2 p-2 rounded-md"
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

        <PrimeProgressSpinner
            v-if="isPending"
            style="width: 50px;
            height: 50px"
            strokeWidth="8"
            fill="var(--surface-ground)"
            animationDuration=".5s"
            aria-label="Custom ProgressSpinner" 
        />
    </div>
</template>

<script setup>
    import { useToast } from 'primevue/usetoast'
    import { useRouter } from 'vue-router'

    const toast = useToast()
    const router = useRouter()
    const authToken = useCookie('auth-token')
    const userTypeCookie = useCookie('user-type')
    const username = useCookie('username')
    const isPending = ref(false)

    // if user is already logged in
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
        
        if (isValidEmail.value) {
            try {
                isPending.value = true
                const { data, error, pending } = await useFetch('http://localhost:8000/api/login', {
                    method: 'POST',
                    body: JSON.stringify(loginForm)
                })

                watchEffect(() => {
                    console.log("isPending.value = ", isPending.value)
                    isPending.value = pending.value
                })

                if (error.value) {
                    throw new Error(error.value.data?.message)
                }

                if (data.value) {
                    authToken.value = data.value.token
                    userTypeCookie.value = data.value.userType
                    username.value = data.value.name

                    toast.add({
                        severity: 'success',
                        summary: "Successfully Logged In",
                        detail: "Please wait...",
                        life: 3000
                    })

                    router.push('/courses-management')
                } else {
                    throw new Error('Token not received')
                }
            } catch (error) {
                console.log("Error: ", error)
                toast.add({
                    severity: 'error',
                    summary: "Failed to login",
                    detail: error.message,
                    life: 3000
                })
            }
        }
    }
</script>