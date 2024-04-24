<script setup>
    import MainLayout from "../../Layouts/MainLayout.vue";
    import FormUsuarios from "../../Components/FormUsuarios.vue";
    import { Head } from "@inertiajs/vue3";
</script>

<template> 
    <main-layout paginaAtual="Cadastrar Usuários">
        <Head title="Cadastrar usuário">
            <link rel="icon" type="image/x-icon" href="/logo.ico" />
        </Head>
        <v-app class="w-full mt-2 mb-2"> 
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-5/6 rounded-lg">
                        <FormUsuarios method="post" route="/register" text_button_submit="Cadastrar"></FormUsuarios>
                    </v-card>
                </div>
        </v-app>
    </main-layout>
</template>

<style scoped>
    #password_confirmation, #password, #email, #name {
        border: 1px solid #7CB342;
    }
</style>
