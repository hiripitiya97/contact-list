<template>
    <div>
        <BaseTable
            :modelData="contacts"
            @page-changed="fetchContacts"
            @delete-data="openDeleteModal"
            @search="onSearchInput"
        />
    </div>
    <DeleteModel
        :show="showDeleteModal"
        :message="modalMessage"
        :messageClass="modalMessageClass"
        :loading="deleteLoading"
        @confirm="deleteSelected"
        @cancel="closeDeleteModal"
    />
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from '../../axios.js'
import BaseTable from "@/Componets/Table/BaseTable.vue";
import { useRouter } from 'vue-router'
import DeleteModel from "@/Componets/DeleteModel.vue";

const deleteId = ref(null)
const showDeleteModal = ref(false)

const modalMessage = ref('')
const modalMessageClass = ref('')

const deleteLoading = ref(false)

const router = useRouter()

const contacts = ref({})
let debounceTimeout = null;

const fetchContacts = async (search = "") => {
    try {
        const token = localStorage.getItem("token");
        const response = await axios.get("/contacts", {
            params: { search },
            headers: { Authorization: `Bearer ${token}` },
        });
        contacts.value = response.data.contacts;
    } catch (error) {
        console.error("Error fetching contacts:", error);
    }
};

const onSearchInput = (value) => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
        fetchContacts(value);
    }, 300);
};


function openDeleteModal(id) {
    deleteId.value = id
    modalMessage.value = ''
    modalMessageClass.value = ''
    showDeleteModal.value = true
}

function closeDeleteModal() {
    deleteId.value = null
    showDeleteModal.value = false
    modalMessage.value = ''
    modalMessageClass.value = ''
}


async function deleteSelected() {
    const token = localStorage.getItem('token')
    deleteLoading.value = true

    try {
        const res = await axios.delete(`/contacts/destroy/${deleteId.value}`, {
            headers: { Authorization: `Bearer ${token}` }
        })

        modalMessage.value = res.data.message
        modalMessageClass.value = "bg-green-100 text-green-700 border border-green-300"

        await fetchContacts()

        setTimeout(() => {
            closeDeleteModal()
        }, 1200)

    } catch (error) {
        modalMessage.value = error.response.data.message
        modalMessageClass.value = "bg-red-100 text-red-700 border border-red-300"

    } finally {
        deleteLoading.value = false
    }
}


onMounted(() => {
    fetchContacts()
})
</script>
