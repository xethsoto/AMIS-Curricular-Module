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
                    Note: This is a demo application to be used for testing a potential feature for AMIS. THIS IS NOT THE ACTUAL AMIS WEB APPLICATION.
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
                        <!-- <PrimePassword
                            v-model="registerForm.password"
                            toggleMask
                        /> -->
                        <input 
                            type="password"
                            v-model="registerForm.password" 
                            class="border-2 p-2 rounded-md"
                        />
                        <p v-if="!isValidPassword.value" class="text-sm text-red-500">
                            {{ isValidPassword.message }}
                        </p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="flex flex-col">
                        <p class="text-sm">Confirm Password</p>
                        <!-- <PrimePassword
                            v-model="confirmPassword"
                            toggleMask
                        /> -->
                        <input 
                            type="password"
                            v-model="confirmPassword" 
                            class="border-2 p-2 rounded-md"
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

            try {
                isPending.value = true
                const { data, error, pending } = await useFetch('http://localhost:8000/api/register', {
                    method: 'POST',
                    body: JSON.stringify(registerForm)
                })

                watchEffect(() => {
                    isPending.value = pending.value
                })

                if (error.value) {
                    throw new Error (error.value.data?.message)
                }

                if (data.value) {
                    authToken.value = data.value.token
                    userTypeCookie.value = data.value.userType
                    username.value = registerForm.name

                    toast.add({
                        severity: 'success',
                        summary: "Successfully Registered",
                        detail: "Please wait...",
                        life: 3000
                    })

                    router.push('/')
                } else {
                    throw new Error('Token not received')
                }
            } catch (error) {
                console.log(error)
                toast.add({
                    severity: 'error',
                    summary: "Failed to Register",
                    detail: error.message,
                    life: 3000
                })
            }
        }
    }
</script>