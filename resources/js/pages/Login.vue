<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
                Login to Your Account
            </h2>

            <form @submit.prevent="login" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        v-model="email"
                        type="email"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        v-model="password"
                        type="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full py-2 text-white bg-blue-600 rounded-lg font-semibold hover:bg-blue-700 transition"
                >
                    Login
                </button>
            </form>

            <div v-if="message" :class="responseMessageClass" class="mt-2 p-3 rounded-lg text-center text-sm">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const email = ref("");
const password = ref("");

const message = ref("");
const responseMessageClass = ref("");

async function login() {
    setMessage();

    try {
        const response = await axios.post("/api/login", {
            email: email.value,
            password: password.value,
        });

        localStorage.setItem("token", response.data.token);

        setMessage(response.data.message || "Login successful", "success");

        router.push("/admin");

    } catch (error) {

        const backendMessage =
            error.response?.data?.message ||
            "Something went wrong. Please try again.";

        setMessage(backendMessage, "error");
    }
}

function setMessage(text = '', type = '') {
    message.value = text

    if (type === 'success') {
        responseMessageClass.value =
            'bg-green-100 text-green-700 border border-green-300 p-3 rounded mt-3'
    } else if (type === 'error') {
        responseMessageClass.value =
            'bg-red-100 text-red-700 border border-red-300 p-3 rounded mt-3'
    } else {
        responseMessageClass.value = ''
    }
}

</script>
