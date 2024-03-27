<script setup>
    import { ref, watch } from "vue";
    import NavbarVue from "../Components/Navbar.vue";
    import SideBarVue from "../Components/SideBar.vue"

    defineProps({
        paginaAtual: String
    })

    let toggleModal = ref(false);
    


</script>

<template>
    <main>
        <NavbarVue :pag="paginaAtual" @sidebar="toggleModal = !toggleModal"/>
        <SideBarVue :pag="paginaAtual" @fecharside="toggleModal = false" :active="toggleModal"/>
        <slot></slot>
    </main>
</template>

<style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
</style>