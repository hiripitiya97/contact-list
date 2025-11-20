<template>
    <div class="overflow-x-auto">
        <div class="flex items-center justify-between mt-6 mb-5 mx-2">
            <h1 class="text-2xl font-semibold text-gray-800">Contact List</h1>

            <div class="relative w-full max-w-md">
                <input
                    type="text"
                    placeholder="Search..."
                    class="w-full py-2 pl-4 pr-10 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                    v-model="searchTerm"
                    @input="onInput"
                />
                <button class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto bg-white shadow-md rounded-xl border border-gray-100">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100/60">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700 tracking-wider">Actions</th>
                </tr>
                </thead>

                <tbody class="bg-white divide-y divide-gray-100">
                <tr
                    v-for="singleData in modelData.data"
                    :key="singleData.id"
                    class="hover:bg-gray-50 transition-all duration-200"
                >
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-medium">
                        {{ singleData.name }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ singleData.email }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        {{ singleData.phone }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <button
                            class="inline-flex items-center px-3 py-1.5 text-sm bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded-lg mr-2 transition"
                            @click="$router.push(`/contacts/edit/${singleData.id}`)"
                        >
                            ✏ Edit
                        </button>

                        <button
                            class="inline-flex items-center px-3 py-1.5 text-sm bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded-lg transition"
                            @click="$emit('delete-data', singleData.id)"
                        >
                            🗑 Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-center mt-4 gap-2" v-if="modelData.last_page > 1">
            <button
                :disabled="modelData.current_page === 1"
                @click="$emit('page-changed', modelData.current_page - 1)"
                class="px-3 py-1 bg-gray-200 rounded disabled:opacity-50"
            >
                Previous
            </button>

            <span class="px-3 py-1">
             Page {{ modelData.current_page }} of {{ modelData.last_page }}
            </span>

            <button
                :disabled="modelData.current_page === modelData.last_page"
                @click="$emit('page-changed', modelData.current_page + 1)"
                class="px-3 py-1 bg-gray-200 rounded disabled:opacity-50"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script setup>
import { defineProps,ref } from 'vue'

import { useRouter } from 'vue-router'

const searchTerm = ref("");

const router = useRouter()

const emit = defineEmits(['edit-data', 'delete-data', 'search'])

function onInput() {
    emit("search", searchTerm.value);
    console.log(searchTerm.value)
}

const props = defineProps({
    modelData: {
        type: Object,
        default: () => [],
    },
})

</script>
