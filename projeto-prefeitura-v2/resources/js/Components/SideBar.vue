<script setup>
    import { Link } from "@inertiajs/vue3";
    import { computed } from "vue";
    import ButtonLinkVue from "./ButtonLink.vue"

    const props = defineProps({
        pag: String,
        active: Boolean // prop para definir se a side aparece ou não
    });

    const emits = defineEmits(['fecharside'])
    
    const Allclasses = computed (() => {
        if(props.active == false) return "flex flex-col justify-between absolute top-0 -inset-1/4 w-1/4 bg-lime-300 min-h-screen transition-all z-50"
        else return 'flex flex-col justify-between absolute top-0 left-0 w-1/5 bg-lime-200 min-h-screen transition-all shadow-2xl z-50'
    });// classe pra mostrar/esconder side

    const selected = (pagLink) => {
        if(props.pag == pagLink) return "m-4 p-3 w-5/6 flex rounded transition-all text-lime-200 bg-lime-500"
        else return "m-4 p-3 bg-lime-100 w-5/6 flex rounded transition-all hover:text-lime-200 hover:bg-lime-500"
    };// função para botar classe de select nos botões

    const fecharSide = () => {
        emits('fecharside');// evento que avisa pro main o click no fechar
    }

</script>

<template>
    <section :class="Allclasses">
        <div>    
            <button class="m-4 p-1 w-8 rounded-full transition-all hover:bg-lime-300 hover:text-white" @click="fecharSide"><v-icon icon="mdi-close" size="medium"></v-icon></button>
            <button-link-vue :pag="pag" :selectedClass="selected('Pessoas')" nome="Pessoas" route="/pessoas">
                <v-icon icon="mdi-account-multiple" class="mr-2"></v-icon>Pessoas
            </button-link-vue>
            <button-link-vue :pag="pag" :selectedClass="selected('Protocolos')" nome="Protocolos" route="/protocolos">
                <v-icon icon="mdi-file-sign" class="mr-2"></v-icon>Protocolos
            </button-link-vue>
        </div>
        <Link as="button"
            method="post"
            href="/logout" 
            class="text-lime-700 m-4 p-3 w-5/6 flex transition-all hover:text-lime-900">
            <v-icon icon="mdi-logout" class="mr-2" size="small"></v-icon>Logout
         </Link>
    </section>
</template>