<template>
    <div class="max-w-xl bg-white border border-gray-200 shadow-sm rounded-xl p-8">

        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <span v-if="isEdit">Edit</span> Contact Information
        </h2>

        <form @submit.prevent="submitForm" class="space-y-6 text-left">

            <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-700">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 transition"
                />
                <p v-if="errors.name" class="text-red-600 text-sm">{{ errors.name }}</p>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-700">Email</label>
                <input
                    v-model="form.email"
                    type="email"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 transition"
                />
                <p v-if="errors.email" class="text-red-600 text-sm">{{ errors.email }}</p>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-700">Phone</label>
                <input
                    v-model="form.phone"
                    type="text"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 transition"
                />
                <p v-if="errors.phone" class="text-red-600 text-sm">{{ errors.phone }}</p>
            </div>

            <button
                type="submit"
                class="w-full py-3 text-white bg-blue-600 rounded-lg font-semibold text-base hover:bg-blue-700 transition disabled:opacity-60 shadow"
                :disabled="loading"
            >
                {{ loading ? 'Submitting...' : 'Submit' }}
            </button>

            <p
                v-if="message"
                :class="messageClass"
                class="mt-4 p-3 rounded-lg text-center font-medium"
            >
                {{ message }}
            </p>

        </form>

    </div>

</template>
<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import axios from '../../axios'

const route = useRoute()
const router = useRouter()

const isEdit = ref(false)
const contactId = route.params.id || null

const form = reactive({
    name: '',
    email: '',
    phone: '',
})

const errors = reactive({})
const loading = ref(false)
const message = ref('')
const messageClass = ref('')

function setMessage(text = '', type = '') {
    message.value = text
    messageClass.value =
        type === 'success'
            ? 'bg-green-100 text-green-700 border border-green-300'
            : type === 'error'
                ? 'bg-red-100 text-red-700 border border-red-300'
                : ''
}

onMounted(async () => {
    if (contactId) {
        isEdit.value = true
        await fetchContact()
    }
})

async function fetchContact() {
    try {
        const token = localStorage.getItem('token')

        const response = await axios.get(`/contacts/show/${contactId}`, {
            headers: { Authorization: `Bearer ${token}` }
        })

        form.name = response.data.contact.name
        form.email = response.data.contact.email
        form.phone = response.data.contact.phone

    } catch (err) {
        console.log(err)
        setMessage(response.data.message)
    }
}

function clearErrors() {
    Object.keys(errors).forEach(k => (errors[k] = ''))
}

async function submitForm() {
    loading.value = true
    clearErrors()
    setMessage('')

    const token = localStorage.getItem('token')

    try {
        let response

        if (isEdit.value) {
            response = await axios.put(`/contacts/update/${contactId}`, form, {
                headers: { Authorization: `Bearer ${token}` }
            })
        } else {
            response = await axios.post('/contacts/store', form, {
                headers: { Authorization: `Bearer ${token}` }
            })
        }

        setMessage(response.data.message, 'success')

        if (isEdit.value) {
            setTimeout(() => router.push('/contacts'), 1000)
        } else {
            form.name = ''
            form.email = ''
            form.phone = ''
        }

    } catch (err) {
        if (err.response?.status === 422) {
            const validationErrors = err.response.data.errors
            for (const key in validationErrors) {
                errors[key] = validationErrors[key][0]
            }
        } else {
            setMessage('Something went wrong', 'error')
        }
    } finally {
        loading.value = false
    }
}

</script>
