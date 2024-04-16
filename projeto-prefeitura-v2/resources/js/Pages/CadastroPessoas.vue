<script setup>
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import MainLayout from '../Layouts/MainLayout.vue'
    import FormPessoa from '../Components/FormPessoa.vue'
    import { ref } from 'vue';

    let limparForm = ref(false); 
</script>

<template>
    <section>
        <Head title="Cadastrar Pessoas" />
        <main-layout paginaAtual="Cadastrar Pessoas" class="w-full">
            <v-app class="w-full mt-4">
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-5/6 rounded-lg">
                        <FormPessoa :clear="limparForm" :values='null' route="/pessoas/store" method="post" text_button_submit="Cadastrar">
                             <v-btn
                              rounded="md" prepend-icon="mdi-delete-outline" variant="flat"
                              @click="limparForm = !limparForm; console.log(limparForm)"
                            >
                                Limpar formulário
                            </v-btn>
                        </FormPessoa>
                    </v-card>    
                </div>
            </v-app>
        </main-layout>
    </section>
</template>
