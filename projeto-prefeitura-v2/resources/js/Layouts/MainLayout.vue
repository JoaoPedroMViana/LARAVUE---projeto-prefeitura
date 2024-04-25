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
    [type='text']:focus, [type='password']:focus, [type='email']:focus{
        box-shadow: none;
        border: none;
        outline: none;
        /* por algum motivo os inputs tavam com um box-shadow azul no focus  que parecia um border */
    }

    ::selection {
        color: white;
        background-color: rgb(26, 185, 26);
    }
</style>
