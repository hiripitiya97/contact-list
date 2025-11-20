<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
    >
        <div class="bg-white p-6 rounded-xl shadow-lg max-w-sm w-full">

            <h2 class="text-lg font-bold mb-3 text-gray-800">Confirm Delete</h2>
            <p class="text-gray-600 mb-4">Are you sure you want to delete this item?</p>

            <!-- Status Message -->
            <div
                v-if="message"
                :class="messageClass"
                class="p-3 rounded mb-4 text-sm font-medium"
            >
                {{ message }}
            </div>

            <div class="flex justify-end gap-3">
                <button
                    @click="$emit('cancel')"
                    class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400"
                    :disabled="loading"
                >
                    Cancel
                </button>

                <button
                    @click="$emit('confirm')"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="loading"
                >
                    {{ loading ? 'Deleting...' : 'Yes, Delete' }}
                </button>
            </div>

        </div>
    </div>
</template>

<script setup>
defineProps({
    show: Boolean,
    message: String,
    messageClass: String,
    loading: Boolean
})

defineEmits(["confirm", "cancel"])
</script>
