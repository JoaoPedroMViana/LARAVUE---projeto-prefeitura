<script setup>
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import { computed, ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage()
 
    const props = defineProps({
        pessoas: Object,
        pesquisa: String,
        itens: Number
    });

    // páginação
    let pagina_atual = ref(props.pessoas.current_page);
    let itens_por_pag = ref(props.pessoas.per_page)

    watch(pagina_atual, () => {
        if(props.pesquisa != null) router.get(`/pessoas/pesquisar?search=${props.pesquisa}&page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`);
        else router.get(`/pessoas?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    })

    watch(itens_por_pag, () => {
        let ultima_pagina = Math.ceil(parseInt(props.pessoas.total) / itens_por_pag.value);
        // calcula a última página disponivel com base no total de pessoas e os itens por página
        if(ultima_pagina < pagina_atual.value){
            pagina_atual.value = ultima_pagina;
            // verifica se a ultima pagina disponivel é menor que a página atual
        }
        if(props.pesquisa != null) router.get(`/pessoas/pesquisar?search=${props.pesquisa}&page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`);
        else router.get(`/pessoas?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    })

    
    // search
    let search = ref(props.pesquisa);

    watch(search, () => {
        router.get(`/pessoas/pesquisar?search=${search.value}&itens_pag=${itens_por_pag.value}`)
    });

    // página de editar
    const editar = (id) => {
        router.get(`/pessoa/${id}`);
    }

    // Formatar data tabela
    const formatarData = (date) => {
        const dataString = date;
        const [ano, mes, dia] = dataString.split('-');
        let formatada = `${dia}/${mes}/${ano}`
        return formatada;
    }

    // Modal excluir
    let dialog = ref(false)
    let pessoa_excluir = ref({
        id: null,
        nome: null
    })

    const deletarPessoa = (id) => {
        router.delete(`/pessoa/delete/${id}`, {onSuccess: () => {
            if(page.props.flash){
                toast(`${page.props.flash}`, {
                    "autoClose": 2500,
                    "type": "success",
                    "dangerouslyHTMLString": true,
                    "position": "top-center",
                });// Mensagem de sucesso
            }
        }});
    }

    // botar o preserveScroll na páginação e no número por página
    // fazer as mensagens de erro do deletar pessoa
</script>

<template>

    <div>
        <Head title="Pessoas" />
        <main-layout paginaAtual="Pessoas" class="w-full">
            <v-dialog
            v-model="dialog"
            transition="dialog-top-transition"
            class="w-50"
            >   
                <v-card
                    rounded="lg"
                >
                    <v-card-title class="pt-6">
                        <p class="text-2xl p-2 pb-0"><v-icon icon="mdi-delete-circle"></v-icon> Deletar pessoa {{pessoa_excluir.id}}</p>    
                    </v-card-title>
                    <v-card-text class="pl-8 pt-2">Deseja excluir a pessoa: <b class="text-red-800 underline">{{pessoa_excluir.nome}}</b>?</v-card-text>
                    <template v-slot:actions>
                    <v-container class="flex justify-end gap-4">
                        <v-btn
                            class="px-5" size="large" rounded="lg" color="#B71C1C" variant="flat"
                            @click="dialog = false;  deletarPessoa(pessoa_excluir.id)"
                        >
                            Deletar
                        </v-btn>

                        <v-btn
                            class="px-5" size="large" rounded="lg" color="#0D47A1" variant="tonal"
                            @click="dialog = false"
                        >
                            Cancelar
                        </v-btn>
                    </v-container>
                    </template>
                </v-card>
            </v-dialog>
            <v-app class="w-full my-4">
                <div class="w-full flex justify-center">
                    
                    <v-card elevation="4" class="w-5/6 rounded-lg">
                        <v-text-field
                            v-model="search"
                            class="mx-8 mt-3"
                            clearable
                            label="Pesquisar"
                            prepend-inner-icon="mdi-text-search"
                            variant="underlined"
                            autofocus
                        >
                        </v-text-field>
                        <div class="w-100 flex items-center justify-between m-0 py-2">
                            <p class="text-sm py-0 my-0 ml-8 opacity-45">Total de pessoas: {{pessoas.total}}</p>
                            <v-btn class="mr-8" rounded="lg" variant="text">
                                <Link href="/pessoas/cadastro" ><v-icon icon="mdi-plus-circle-outline" class="mr-3"></v-icon> Cadastrar</Link>
                            </v-btn>
                        </div>
                        <v-table  density="comfortable" hover class="p-5 pt-0">
                            <thead class="text-base">
                                <tr>
                                    <th>#</th>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Data de nascimento</th>
                                    <th>Sexo</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody v-if="pessoas.data.length != 0">
                                <tr v-for="pessoa in pessoas.data" :key="pessoa.id">
                                    <td>{{pessoa.id}}</td>
                                    <td>{{pessoa.nome}}</td>
                                    <td>{{pessoa.CPF}}</td>
                                    <td>{{formatarData(pessoa.data_nascimento)}}</td>
                                    <td>{{pessoa.sexo}}</td>
                                    <td>
                                        <v-btn class="mr-6 h-75" rounded="lg" color="#7CB342" prepend-icon="mdi-pencil" variant="flat" @click.once="editar(pessoa.id)">
                                            Editar 
                                        </v-btn>
                                        <v-btn class="h-75" rounded="lg" color="#B71C1C" prepend-icon="mdi-delete-outline" variant="flat" @click="dialog = true; pessoa_excluir.id = pessoa.id; pessoa_excluir.nome = pessoa.nome">
                                            Apagar  
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                                
                        </v-table>
                        <p v-if="pessoas.data.length == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhuma pessoa encontrada com: "{{ pesquisa }}"</p>
                        <div class="flex w-100 justify-center relative">
                            <div v-if="pessoas.data.length != 0"  class="w-40 absolute left-8 flex gap-4 items-center justify-center">    
                                <v-select
                                    v-model="itens_por_pag"
                                    variant="outlined"
                                    rounded="md"
                                    density="comfortable"
                                    :items="[pessoas.total, '20', '10', '5']"
                                    base-color="#7CB342"
                                    label="Itens por página"
                                    color="#7CB342"
                                ></v-select>
                                {{itens}}
                            </div>
                            <v-pagination
                                v-if="pessoas.data.length != 0" 
                                v-model="pagina_atual"
                                :length="pessoas.last_page"
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



