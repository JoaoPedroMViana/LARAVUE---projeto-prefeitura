<script setup>
    import { Head, Link, router } from '@inertiajs/vue3';
    import { computed, ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";

    const props = defineProps({
        pessoas: Object,
        pesquisa: String
    });

    const toggleNextPrevius = (index, arr) => {
        if(index == 0 || index == arr.length -1) return false
        else return true
    }

    const valueSearch = () => {
        if(props.pesquisa == 'null' ) return ''
        else return props.pesquisa
    }

    let search = ref(valueSearch());

    watch(search, () => {
        router.get(`/pessoas/pesquisar?search=${search.value}`)
    });
    //fazer um watcher no search pra rota de pesquisa
</script>

<template>

    <div>
        <Head title="Pessoas" />
        <main-layout paginaAtual="Pessoas" class="w-full">

            <v-app class="bg-red-400 w-full mt-12">
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
                                        <v-btn class="mr-6" rounded="lg" color="#7CB342" prepend-icon="mdi-pencil" variant="flat">
                                            Editar 
                                        </v-btn>
                                        <v-btn rounded="lg" color="#B71C1C" prepend-icon="mdi-delete-outline" variant="tonal">
                                            Apagar  
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                                
                        </v-table>
                        <p v-if="pessoas.data.length == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhuma pessoa encontrada com: "{{ pesquisa }}"</p>
                        <div clas="flex" v-if="pessoas.length != 0">
                            <Link 
                                :href="pessoas.prev_page_url"
                                :disabled="pessoas.current_page == 1"
                                as="button"
                                preserve-scroll="true"
                            >
                                Anterior
                            </Link>
                            <div class="flex" v-for="(link, index) in pessoas.links"
                                :key="index"
                            >
                                <Link
                                    v-if="toggleNextPrevius(index, pessoas.links)"
                                    as="button"
                                    :href="link.url"
                                    :disabled="pessoas.current_page == link.label"
                                    class="p-3"
                                    preserve-scroll="true"
                                >
                                    {{ link.label }}
                                </Link>                   
                            </div>
                        </div>
                        <Link 
                                :href="pessoas.next_page_url"
                                :disabled="pessoas.current_page == pessoas.last_page"
                                as="button"
                                preserve-scroll="true"
                            >
                                Proxima
                        </Link><br/>
                    </v-card>
                </div>
            </v-app>
           
        </main-layout>
    </div>
</template>

<style scoped>
    [type="text"]:focus {
        box-shadow: none;
        /* por algum motivo os inputs tavam com um box-shadow azul no focus  que parecia um border */
    }
</style>


