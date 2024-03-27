<script setup>
    import { Head, Link } from '@inertiajs/vue3';
    import MainLayout from "../Layouts/MainLayout.vue";

    const props = defineProps({
        pessoas: Object
    });

    const toggleNextPrevius = (index, arr) => {
        if(index == 0 || index == arr.length -1) return false
        else return true
    }
</script>

<template>

    <div>
        <Head title="Pessoas" />
        <main-layout paginaAtual="Pessoas">
            <!-- views vão ser carregadas aqui -->
            <h1>PESSOAS</h1>
            <div v-if="pessoas.length != 0">
                <p v-for="pessoa in pessoas.data" :key="pessoa.id">
                    {{ pessoa.nome }} - {{ pessoa.CPF }} - {{ pessoa.data_nascimento }} - {{ pessoa.sexo }}
                    <br/><br/>
                </p>
                <Link 
                    :href="pessoas.prev_page_url"
                    :disabled="pessoas.current_page == 1"
                    as="button"
                    preserve-scroll="true"
                >
                    Anterior
                </Link>
                <div v-for="(link, index) in pessoas.links"
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
            <div v-else>
                <p>Não há pessoas :(</p>
            </div>
            <Link 
                    :href="pessoas.next_page_url"
                    :disabled="pessoas.current_page == pessoas.last_page"
                    as="button"
                    preserve-scroll="true"
                >
                    Proxima
            </Link><br/>
            {{ pessoas.links }}<br/>
            <!-- <div>
                <v-app>
                    <v-card>
                        <v-table>
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Data de nascimento</th>
                                    <th>Sexo</th>
                                    <th>Config</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pessoa in pessoas" :key="pessoa.id">
                                    <td>{{pessoa.nome}}</td>
                                    <td>{{pessoa.CPF}}</td>
                                    <td>{{pessoa.data_nascimento}}</td>
                                    <td>{{pessoa.sexo}}</td>
                                    <td>
                                        <v-btn rounded="lg" color="#B71C1C" prepend-icon="mdi-close" variant="tonal">
                                            Apagar  
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-card>
                </v-app>
            </div> -->
        </main-layout>
    </div>
</template>



