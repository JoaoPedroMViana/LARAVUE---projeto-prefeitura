<script setup>
    import { Head, Link, router } from '@inertiajs/vue3'
    import { ref, watch } from 'vue';
    import MainLayout from '../Layouts/MainLayout.vue'

    const props = defineProps({
        departamentos: Object,
    });

    let itens_por_pag = ref(props.departamentos.per_page);
    let pagina_atual = ref(props.departamentos.current_page);

    watch(itens_por_pag, () => {
        let ultima_pagina = Math.ceil(parseInt(props.departamentos.total) / itens_por_pag.value);
        // calcula a última página disponivel com base no total de pessoas e os itens por página
        if(ultima_pagina < pagina_atual.value){
            pagina_atual.value = ultima_pagina;
            // verifica se a ultima pagina disponivel é menor que a página atual
        }
        router.get(`/departamentos?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    });

    watch(pagina_atual, () => {
        router.get(`/departamentos?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    });

    const editarDepartamento = (id) => {
        router.get(`/departamento/${id}`);
    }
</script> 

<template>
    <main-layout paginaAtual="Departamentos" class="w-full">
    <Head title="Departamentos"/>
        <v-app class="w-full my-3">
            <div class="w-full flex justify-center">
                <v-card elevation="4" class="w-11/12 rounded-lg  pt-4">
                    <div class="w-100 flex items-center justify-between m-0 py-2">
                        <p class="text-sm py-0 my-0 ml-8 opacity-45">Total de Departamentos: {{departamentos.total}}</p>
                        <v-btn class="mr-8" rounded="lg" variant="text">
                            <Link href="/departamentos/cadastro" ><v-icon icon="mdi-plus-circle-outline" class="mr-3"></v-icon> Cadastrar</Link>
                        </v-btn>
                    </div> 
                    <v-table  density="comfortable" hover class="p-5 pt-0">
                        <thead class="text-base">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Nome</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead> 
                        <tbody v-if="departamentos.data.length != 0">
                            <tr v-for="departamento in departamentos.data" :key="departamento.id">
                                <td class="text-center">{{departamento.id}}</td>
                                <td class="text-left">{{departamento.nome}}</td>
                                <td class="text-center">
                                    <v-btn class="mr-6 h-75" rounded="lg" color="#7CB342" prepend-icon="mdi-eye-outline" variant="flat" @click.once="editarDepartamento(departamento.id)">
                                        Visualizar 
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>   
                    </v-table>
                    <p v-if="departamentos.total == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhum departamento cadastrado"</p>
                    <div class="flex w-100 justify-center relative">
                        <div v-if="departamentos.total != 0"  class="w-40 absolute left-8 flex gap-4 items-center justify-center">    
                            <v-select
                                v-model="itens_por_pag"
                                variant="outlined"
                                rounded="md"
                                density="comfortable"
                                :items="[departamentos.total, '20', '10', '5']"
                                base-color="#7CB342"
                                label="Itens por página"
                                color="#7CB342"
                            ></v-select>
                        </div>
                        <v-pagination
                            v-if="departamentos.total != 0" 
                            v-model="pagina_atual"
                            :length="departamentos.last_page"
                            class="mb-2"
                            :total-visible="5"
                            active-color="#1B5E20"
                            color="#7CB342"
                            rounded
                        ></v-pagination>
                    </div>
                </v-card>
            </div>
        </v-app>
    </main-layout>
</template>