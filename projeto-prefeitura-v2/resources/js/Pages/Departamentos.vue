<script setup>
    import { Head, Link } from '@inertiajs/vue3'
    import MainLayout from '../Layouts/MainLayout.vue'

    const props = defineProps({
        departamentos: Object
    });
</script>

<template>
    <main-layout paginaAtual="Departamentos" class="w-full">
    <Head title="Departamentos"/>
        <v-app class="w-full my-3">
            <div class="w-full flex justify-center">
                <v-card elevation="4" class="w-5/6 rounded-lg">
                    <div class="w-100 flex items-center justify-between m-0 py-2">
                        <p class="text-sm py-0 my-0 ml-8 opacity-45">Total de Departamentos: {{departamentos.total}}</p>
                        <v-btn class="mr-8" rounded="lg" variant="text">
                            <Link href="/departamentos/cadastro" ><v-icon icon="mdi-plus-circle-outline" class="mr-3"></v-icon> Cadastrar</Link>
                        </v-btn>
                    </div> 
                    <v-table  density="comfortable" hover class="p-5 pt-0">
                        <thead class="text-base">
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Ações</th>
                            </tr>
                        </thead> 
                        <tbody v-if="departamentos.data.length != 0">
                            <tr v-for="departamento in departamentos.data" :key="departamento.id">
                                <td>{{departamento.id}}</td>
                                <td>{{departamento.nome}}</td>
                                <td>
                                    <v-btn class="mr-6 h-75" rounded="lg" color="#7CB342" prepend-icon="mdi-eye-outline" variant="flat" @click.once="console.log(departamento.nome)">
                                        Visualizar 
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>   
                    </v-table>
                </v-card>
            </div>
        </v-app>
    </main-layout>
</template>