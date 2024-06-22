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
                <p class="text-center text-2xl">Register</p>
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
    
                    <!-- Name -->
                    <div class="flex flex-col">
                        <FormInput label="Name" type="text-field" @input="registerForm.name=$event"/>
                        <p v-if="!isValidName" class="text-sm text-red-500">
                            Name is required
                        </p>
                    </div>
    
                    <!-- Email -->
                    <div class="flex flex-col">
                        <FormInput label="Email" type="text-field" @input="registerForm.email=$event"/>
                        <p v-if="!isValidEmail" class="text-sm text-red-500">
                            Please enter a valid email address
                        </p>
                    </div>
                    
                    <!-- Password -->
                    <div class="flex flex-col">
                        <p class="text-sm">Password</p>
                        <PrimePassword
                            v-model="registerForm.password"
                            toggleMask
                        />
                        <p v-if="!isValidPassword.value" class="text-sm text-red-500">
                            {{ isValidPassword.message }}
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="flex flex-col">
                        <p class="text-sm">Confirm Password</p>
                        <PrimePassword
                            v-model="confirmPassword"
                            toggleMask
                        />
                        <p v-if="!passwordMatch" class="text-sm text-red-500">
                            Password and Confirm Password do not match
                        </p>
                    </div>

                </div>
            </template>
            <template #footer>
                <div class="flex gap-3 mt-1">
                    <PrimeButton label="Save" class="btn-maroon w-full"
                        @click="submitForm"
                    />
                </div>
            </template>
        </PrimeCard>

        <p>
            Already have an account? Login
            <NuxtLink to="/login" class="nav-link underline">here</NuxtLink>
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

    const registerForm = reactive({
        name: '',
        email: '',
        password: '',
    })
    const confirmPassword = ref('')

    // Validation Flags
    const isValidName = ref(true)
    const isValidEmail = ref(true)
    const isValidPassword = ref({value: true, message: "Password is valid"})
    const passwordMatch = ref(true)

    const validateEmail = (email) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    const validatePassword = (password) => {
        const lengthCheck = password.length > 8;
        const numberCheck = /\d/.test(password);
        const uppercaseCheck = /[A-Z]/.test(password);
        const lowercaseCheck = /[a-z]/.test(password);

        if (!lengthCheck){
            return {value: false, message: "Password must be at least 8 characters long"}
        }

        if (!(numberCheck && uppercaseCheck && lowercaseCheck)){
            return {value: false, message: "Password must contain at least a number, an uppercase letter, and a lowercase letter"}
        }

        return {value: true, message: "Password is valid"}
    }

    const submitForm = async () => {
        isValidName.value = registerForm.name.length > 0
        isValidEmail.value = validateEmail(registerForm.email)
        isValidPassword.value = validatePassword(registerForm.password)
        passwordMatch.value = registerForm.password === confirmPassword.value

        if (isValidName.value && isValidEmail.value && isValidPassword.value.value && passwordMatch) {

            console.log("registerForm = ", registerForm)
            try {
                const responseObj = {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(registerForm)
                }

                console.log("responseObj = ", responseObj)
                const response = await fetch('http://localhost:8000/api/register', responseObj)


                console.log("response = ", response)

                if (!response.ok) {
                    throw new Error('Network error')
                }

                const data = await response.json()

                console.log("data = ", data)

                if (data.token) {
                    localStorage.setItem('auth_token', data.token)
                    router.push('/courses-management')
                } else {
                    throw new Error('Token not received')
                }
            } catch (error) {
                console.log(error)
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