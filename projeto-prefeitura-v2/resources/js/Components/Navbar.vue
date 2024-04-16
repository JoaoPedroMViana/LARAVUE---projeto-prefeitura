<script setup>
    import { Link } from "@inertiajs/vue3"

    defineProps({
        pag: String
    })

    const emits = defineEmits(['sidebar'])

    const eventSideBar = () => {
        emits('sidebar')// evento que avisa pro main quando o button for clicado
    }   
</script>
 
<template> 
    <section class="w-full bg-lime-400 h-16 flex justify-between items-center drop-shadow-md">
        <Link as="button" method="post" href="/logout" class="m-4 transition-all text-lime-100 hover:underline"><v-icon icon="mdi-logout" class="mr-2" size="small"></v-icon>Logout</Link>
        <div class="w-full flex items-center justify-end"> 

            <Link v-if="pag == 'Editar Pessoa' || pag == 'Cadastrar Pessoas'" class="mr-2 text-lime-100" href="/pessoas">Pessoas > </Link>
            <Link v-else-if="pag == 'Editar Protocolo' || pag == 'Cadastrar Protocolos'" class="mr-2 text-lime-100" href="/protocolos">Protocolos > </Link>     
            <Link v-else-if="pag == 'Editar Usuário' || pag == 'Cadastrar Usuários'" class="mr-2 text-lime-100" href="/usuarios">Usuários > </Link>     
            <Link v-else-if="pag == 'Editar Departamentos' || pag == 'Cadastrar Departamentos'" class="mr-2 text-lime-100" href="/departamentos">Departamentos > </Link> 
                
            <h1 class="font-semibold text-lg text-white">{{ pag }}</h1>
            <button class="m-4 p-2 rounded-full transition-all hover:bg-lime-300" @click="eventSideBar"><v-icon icon="mdi-menu" size="large" color="white"></v-icon></button>
        </div>
    </section>
</template>
