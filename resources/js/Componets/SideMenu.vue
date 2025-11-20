<template>
    <div class="flex h-screen flex-col justify-between border-e border-gray-100 bg-white">
        <div class="px-4 py-6">
    <span class="grid h-10 w-32 place-content-center rounded-lg bg-gray-100 text-xs text-gray-600">
      Logo
    </span>

            <ul class="mt-6 space-y-1">
                <li>
                    <router-link to="/" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">Home</router-link>
                </li>

                <li>
                    <details class="group [&amp;_summary::-webkit-details-marker]:hidden">
                        <summary class="flex cursor-pointer items-center justify-between rounded-lg px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                            <span class="text-sm font-medium"> Contacts </span>

                            <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                              <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                              </svg>
                            </span>
                        </summary>

                        <ul class="mt-2 space-y-1 px-4">
                            <li>
                                <router-link to="/contacts" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                                    List
                                </router-link>
                            </li>

                            <li>
                                <router-link to="/contacts/create" class="block rounded-lg px-4 py-2 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700">
                                    Create
                                </router-link>
                            </li>
                        </ul>
                    </details>
                </li>

            </ul>
        </div>

        <div class="sticky inset-x-0 bottom-0 border-t border-gray-100">
            <button
                @click="logout"
                class="px-3 py-1 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200"
            >
                Logout
            </button>
        </div>
    </div>

</template>

<script setup >
import { useRouter } from 'vue-router'
import axios from "../axios.js";

const router = useRouter();
const logout = async () => {
    try {
        const token = localStorage.getItem("token");

        await axios.post(
            "/logout",
            {},
            {
                headers: { Authorization: `Bearer ${token}` },
            }
        );

    } catch (error) {
        console.error("Logout failed:", error);
    } finally {
        localStorage.removeItem("token");
        router.push({ name: "login" });
    }
};

</script>

<style scoped>

</style>
