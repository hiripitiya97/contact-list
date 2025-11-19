<template>
    <div class="flex">
        <SideMenu />

        <div class="flex-1 p-4">
            <router-view></router-view>
        </div>
    </div>
</template>

<script setup>
import SideMenu from "@/Componets/SideMenu.vue";
import {RouterView} from "vue-router";
</script>
