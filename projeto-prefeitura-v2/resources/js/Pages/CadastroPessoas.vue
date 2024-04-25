<script setup>
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import MainLayout from '../Layouts/MainLayout.vue'
    import FormPessoa from '../Components/FormPessoa.vue'
    import { ref } from 'vue';

</script>

<template>
    <section>
        <Head title="Cadastrar Pessoas">
            <link rel="icon" type="image/x-icon" href="/logo.ico" />
        </Head>
        <main-layout paginaAtual="Cadastrar Pessoas" class="w-full">
            <div class="w-full mt-4">
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-5/6 rounded-lg">
                        <FormPessoa :values='null' route="/pessoas/store" method="post" text_button_submit="Cadastrar">
                        </FormPessoa>
                    </v-card>    
                </div>
            </div>
        </main-layout>
    </section>
</template>
