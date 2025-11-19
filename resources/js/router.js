import { createRouter, createWebHistory } from 'vue-router'
import ContactList from './Pages/Contact/List.vue'
import ContactCreate from './Pages/Contact/Create.vue'
import AppLayout from "@/Layouts/AppLayout.vue";
import Login from "@/Pages/Login.vue";

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
        ]
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')

    if (to.meta.requiresAuth && !token) {
        return next('/')
    }

    if (to.meta.guest && token) {
        return next('/admin')
    }

    next()
})
export default router
