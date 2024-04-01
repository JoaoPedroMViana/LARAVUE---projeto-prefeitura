<script setup>
    import { Head, Link, router } from '@inertiajs/vue3';
    import { computed, ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";

    const props = defineProps({
        pessoas: Object,
        pesquisa: String
    });

    // páginação
    let pagina_atual = ref(props.pessoas.current_page);

    watch(pagina_atual, () => {
        if(props.pesquisa != null) router.get(`/pessoas/pesquisar?search=${props.pesquisa}&page=${pagina_atual.value}`);
        else router.get(`/pessoas?page=${pagina_atual.value}`)
    })

    // search
    let search = ref(props.pesquisa);

    watch(search, () => {
        router.get(`/pessoas/pesquisar?search=${search.value}`)
    });

    // página de editar
    const editar = (id) => {
        router.get(`/pessoa/${id}`);
    }

</script>

<template>

    <div>
        <Head title="Pessoas" />
        <main-layout paginaAtual="Pessoas" class="w-full">

            <v-app class="w-full mt-4">
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-5/6 rounded-lg">
                    
                        <v-text-field
                            v-model="search"
                            autofocus
                            class="mx-8 mt-3"
                            clearable
                            label="Pesquisar"
                            prepend-inner-icon="mdi-text-search"
                            variant="underlined"
                        >
                        </v-text-field>
                        
                        <v-table hover class="p-5">
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
                                    <td>{{pessoa.data_nascimento}}</td>
                                    <td>{{pessoa.sexo}}</td>
                                    <td>
                                        <v-btn class="mr-6" rounded="lg" color="#7CB342" prepend-icon="mdi-pencil" variant="flat" @click.once="editar(pessoa.id)">
                                            Editar 
                                        </v-btn>
                                        <v-btn rounded="lg" color="#B71C1C" prepend-icon="mdi-delete-outline" variant="flat">
                                            Apagar  
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                                
                        </v-table>
                        <p v-if="pessoas.data.length == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhuma pessoa encontrada com: "{{ pesquisa }}"</p>
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
                    </v-card>
                </div>
            </v-app>
           
        </main-layout>
    </div>
</template>



