<script setup>
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import { computed, ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const props = defineProps({
        users: Object
    });

    // Itens por página
    let itens_por_pag = ref(props.users.per_page);
    let pagina_atual = ref(props.users.current_page);

    watch(pagina_atual, () => {
        router.get(`/usuarios?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`);
    });

    watch(itens_por_pag, () => {
        let ultima_pagina = Math.ceil(parseInt(props.users.total) / itens_por_pag.value);
        // calcula a última página disponivel com base no total de pessoas e os itens por página
        if(ultima_pagina < pagina_atual.value){
            pagina_atual.value = ultima_pagina;
            // verifica se a ultima pagina disponivel é menor que a página atual
        }
        router.get(`/usuarios?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`);
    });

    // Página individual
    const editar = (id) => {
        router.get(`/usuario/${id}`);
    };

    // mostrar o nome do perfil e não a letra
</script>

<template>
    <div>
        <Head title="Usuários"/>
        <main-layout paginaAtual="Usuarios" class="w-full">
            <v-app class="w-full my-3">
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-5/6 rounded-lg"> 
                        <div class="w-100 flex items-center justify-between m-0 py-2">
                            <p class="text-sm py-0 my-0 ml-8 opacity-45">Total de Usuários: {{users.total}}</p>
                            <v-btn class="mr-8" rounded="lg" variant="text">
                                <Link href="/usuarios/cadastro" ><v-icon icon="mdi-plus-circle-outline" class="mr-3"></v-icon> Cadastrar</Link>
                            </v-btn>
                        </div>
                        <v-table  density="comfortable" hover class="p-5 pt-0">
                            <thead class="text-base">
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Perfil</th>
                                    <th>Ativo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead> 
                            <tbody v-if="users.data.length != 0">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>{{user.id}}</td>
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.perfil}}</td>
                                    <td>{{user.ativo}}</td>
                                    <td>
                                        <v-btn class="mr-6 h-75" rounded="lg" color="#7CB342" prepend-icon="mdi-eye-outline" variant="flat" @click.once="editar(user.id)">
                                            Visualizar 
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>   
                        </v-table>
                        <p v-if="users.total == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhum usuário cadastrado"</p>
                        <div class="flex w-100 justify-center relative">
                            <div v-if="users.total != 0"  class="w-40 absolute left-8 flex gap-4 items-center justify-center">    
                                <v-select
                                    v-model="itens_por_pag"
                                    variant="outlined"
                                    rounded="md"
                                    density="comfortable"
                                    :items="[users.total, '20', '10', '5']"
                                    base-color="#7CB342"
                                    label="Itens por página"
                                    color="#7CB342"
                                ></v-select>
                            </div>
                            <v-pagination
                                v-if="users.total != 0" 
                                v-model="pagina_atual"
                                :length="users.last_page"
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
    </div>
</template>
