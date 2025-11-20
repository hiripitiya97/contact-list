import { createRouter, createWebHistory } from 'vue-router'
import ContactList from './Pages/Contact/List.vue'
import ContactCreate from './Pages/Contact/Create.vue'
import AppLayout from "@/Layouts/AppLayout.vue";
import Login from "@/Pages/Login.vue";
import axios from './axios'

const routes = [

    {
        path: '/',
        name: 'login',
        component: Login,
        meta: { guest: true }
    },

    {
        path: '/admin',
        component: AppLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '/contacts',
                name: 'contact.list',
                component: ContactList
            },
            {
                path: '/contacts/create',
                name: 'contact.create',
                component: ContactCreate
            },
            {
                path: "/contacts/edit/:id",
                name: "contacts.edit",
                component: ContactCreate,
                props: true,
            }
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})


router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem('token')

    if (to.meta.guest && token) {
        return next('/admin')
    }

    if (to.meta.requiresAuth) {
        if (!token) return next('/')

        try {
            await axios.get('/user', {
                headers: { Authorization: `Bearer ${token}` }
            })
            next()
        } catch (err) {
            localStorage.removeItem('token')
            return next('/')
        }
    } else {
        next()
    }
})
export default router
