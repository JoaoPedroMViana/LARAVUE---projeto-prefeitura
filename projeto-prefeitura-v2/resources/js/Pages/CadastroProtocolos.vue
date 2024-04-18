<script setup>
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import MainLayout from '../Layouts/MainLayout.vue'
    import { ref } from 'vue';
    import FormProtocolo from '../Components/FormProtocolo.vue'

    const props = defineProps({
        pessoas_select: Object,
        departamentos_select: Object
    });

    let limparForm = ref(false);
</script>

<template>
    <section><Head title="Cadastrar Protocolos" />
        <main-layout paginaAtual="Cadastrar Protocolos" class="w-full">
            <v-app class="w-full mt-2"> 
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-5/6 rounded-lg">
                        <FormProtocolo :pessoas_select="pessoas_select" :departamentos_select="departamentos_select" :clear="limparForm" :values='null' route="/protocolos/store" method="post" text_button_submit="Cadastrar">
                             <v-btn
                              rounded="md" prepend-icon="mdi-delete-outline" variant="flat"
                              @click="limparForm = !limparForm"
                            >
                                Limpar formulário 
                            </v-btn>
                        </FormProtocolo>
                    </v-card>    
                </div>
            </v-app>
        </main-layout>
    </section>
</template>