<script setup>
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import { ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const props = defineProps({
        protocolos: Object,
        searchNumero: String,
        searchDescricao: String,
        searchPessoa: String,
        searchData: String,
        focus: String
    });

    const page = usePage();

    // Modal excluir
    let dialog = ref(false)
    let protocolo_excluir = ref({
        numero: null,
        pessoa_nome: null
    })

    const deletarProtocolo = (numero) => {
        router.delete(`/protocolo/delete/${numero}`, {onSuccess: () => {
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

    // Formatar datas
    const formatarData = (date) => {
        if(date == null || date == undefined) return '';
        const dataString = date;
        const [ano, mes, dia] = dataString.split('-');
        let formatada = `${dia}/${mes}/${ano}`
        return formatada;
    }

    let formatarDataBanco = (date) => {
        if(date == null || date == undefined) return '';
        let data = new Date(date);
        let ano = data.getFullYear();
        let mes = ('0' + (data.getMonth() + 1)).slice(-2);
        let dia = ('0' + data.getDate()).slice(-2);
        let formatada = `${ano}-${mes}-${dia}`
        return formatada;
    };

    // página de editar
    const editar = (numero) => {
        router.get(`/protocolo/${numero}`);
    }

    // páginação
    let pagina_atual = ref(props.protocolos.current_page);
    let itens_por_pag = ref(props.protocolos.per_page)

    watch(pagina_atual, () => {
        if(props.searchNumero != null || props.searchDescricao != null || props.searchPessoa != null || props.searchData != null) router.get(`/protocolos/pesquisar?
        numero=${searchNumero.value}&
        descricao=${searchDescricao.value}&
        pessoa=${searchPessoa.value}&
        data=${searchData.value}&
        page=${pagina_atual.value}&
        itens_pag=${itens_por_pag.value}`)

        else router.get(`/protocolos?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    })

    watch(itens_por_pag, () => {
        let ultima_pagina = Math.ceil(parseInt(props.protocolos.total) / itens_por_pag.value);
        // calcula a última página disponivel com base no total de protocolos e os itens por página
        if(ultima_pagina < pagina_atual.value){
            pagina_atual.value = ultima_pagina;
            // verifica se a ultima pagina disponivel é menor que a página atual
        }

        if(props.searchNumero != null || props.searchDescricao != null  || props.searchPessoa != null || props.searchData != null) router.get(`/protocolos/pesquisar?
        numero=${searchNumero.value}&
        descricao=${searchDescricao.value}&
        pessoa=${searchPessoa.value}&
        data=${searchData.value}&
        page=${pagina_atual.value}&
        itens_pag=${itens_por_pag.value}`)

        else router.get(`/protocolos?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    })

    // search
    let searchNumero = ref(props.searchNumero);
    let searchDescricao = ref(props.searchDescricao);
    let searchPessoa = ref(props.searchPessoa);
    let searchData = ref(props.searchData);

    watch(searchNumero, () => {
        router.get(`/protocolos/pesquisar?
        numero=${searchNumero.value}&
        descricao=${searchDescricao.value}&
        pessoa=${searchPessoa.value}&
        data=${searchData.value}&
        inputFocus=numero&
        itens_pag=${itens_por_pag.value}
        `)
    });

    watch(searchDescricao, () => {
        router.get(`/protocolos/pesquisar?
        numero=${searchNumero.value}&
        descricao=${searchDescricao.value}&
        pessoa=${searchPessoa.value}&
        data=${searchData.value}&
        inputFocus=decricao&
        itens_pag=${itens_por_pag.value}
        `)
    });
    
    watch(searchPessoa, () => {
        router.get(`/protocolos/pesquisar?
        numero=${searchNumero.value}&
        descricao=${searchDescricao.value}&
        pessoa=${searchPessoa.value}&
        data=${searchData.value}&
        inputFocus=pessoa&
        itens_pag=${itens_por_pag.value}
        `)
    });

    watch(searchData, () => {
        router.get(`/protocolos/pesquisar?
        numero=${searchNumero.value}&
        descricao=${searchDescricao.value}&
        pessoa=${searchPessoa.value}&
        data=${searchData.value}&
        itens_pag=${itens_por_pag.value}
        `)
    });

    // mudar autofocus
    let inputFocus = ref(props.focus)

     // Date picker
    const isMenuOpen = ref(false)
    let selectedDate = ref()

    let formated = ref(formatarData(searchData.value));// variavel que é exposta no front
    if(formated.value == 'undefined/undefined/'){
        formated = '';
    }

    watch(selectedDate, (newValue, oldValue) => {
        isMenuOpen.value = false

        if(newValue == null){
            formated.value = '';
            searchData.value = '';
        }else{            
            searchData.value = formatarDataBanco(selectedDate.value)
            formated = formatarData(formatarDataBanco(selectedDate.value))
        }

    })

    // Exibir cor da situação

    const cor = (situacao) => {
        if(situacao == 'A') return {color: 'red', text: 'Aberto'} 
        else if(situacao == 'E') return {color: 'yellow', text: 'Em atendimento'} 
        else return {color: 'green', text: 'Solucionado'} 
    }

    // Data limite

    let dataLimite = ref('00/00/0000');

    const FormatarDataLimite = (date, prazo) => {
        const newDate = new Date(date);
        newDate.setDate(newDate.getDate() + prazo);
        const dataString = formatarDataBanco(newDate);
        const [ano, mes, dia] = dataString.split('-');
        let formatada = `${dia}/${mes}/${ano}`
        return formatada;
    }
</script>

<template>
    <div>
        <Head title="Protocolos">
            <link rel="icon" type="image/x-icon" href="/logo.ico" />
        </Head>
        <main-layout paginaAtual="Protocolos" class="w-full">
            <v-dialog
            v-model="dialog"
            transition="dialog-top-transition"
            class="w-50"
            >   
                <v-card
                    rounded="lg"
                >
                    <v-card-title class="pt-6">
                        <p class="text-2xl p-2 pb-0"><v-icon icon="mdi-delete-circle"></v-icon> Deletar protocolo {{protocolo_excluir.numero}}</p>    
                    </v-card-title>
                    <v-card-text class="pl-8 pt-2">Deseja excluir o protocolo vinculado a pessoa: <b class="text-red-800 underline">{{protocolo_excluir.pessoa_nome}}</b>?</v-card-text>
                    <template v-slot:actions>
                    <v-container class="flex justify-end gap-4">
                        <v-btn
                            class="px-5" size="large" rounded="lg" color="#B71C1C" variant="flat"
                            @click="dialog = false;  deletarProtocolo(protocolo_excluir.numero)"
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
            <div class="w-full mt-4">
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-11/12 rounded-lg">
                     <div class="flex h-12 justify-between mx-8 gap-8">
                        <v-text-field
                            v-model="searchNumero"
                            class="mt-3 mb-2 h-12"
                            clearable 
                            label="Número"
                            variant="underlined"
                            :autofocus="inputFocus == 'numero'"
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            density="comfortable"
                        >
                        
                        </v-text-field>
                        <v-text-field
                            class="mt-3 mb-2 h-12"
                            clearable
                            variant="underlined"
                            rounded="md"
                            v-model="searchPessoa"
                            label="Contruibuinte"
                            :autofocus="inputFocus == 'pessoa'"                         
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            density="comfortable"
                            ></v-text-field>
                            
                     </div>

                        <div class="flex h-12 justify-between mx-8 mt-4 gap-8">

                            <div class="w-25">
                                <v-menu v-model="isMenuOpen" :close-on-content-click="false">
                                    <template v-slot:activator="{ props }">
                                    <div class="flex items-center">
                                        <v-text-field
                                            id="data_nascimento"
                                            label="Data de registro"
                                            :model-value="formated"
                                            type="input"
                                            readonly
                                            v-bind="props"
                                            variant="underlined"
                                            rounded="md"
                                            base-color="#7CB342"
                                            color="#7CB342"
                                            density="comfortable"
                                        >
                                        </v-text-field>
                                        <v-btn size="sm" :disabled="formated == ''" variant="text" color="grey" @click="selectedDate = null"> <v-icon icon="mdi-close-circle"></v-icon> </v-btn>
                                    </div>
                                    </template>
                                    <v-date-picker rounded :max="new Date()" @click.self="isMenuOpen = false" v-model="selectedDate" color="#7CB342"></v-date-picker>
                                </v-menu>
                            </div>
                            <v-text-field
                            class="w-25"
                            clearable
                            variant="underlined"
                            rounded="md"
                            v-model="searchDescricao"
                            label="Descrição"
                            :autofocus="inputFocus == 'decricao'"                         
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            density="comfortable"
                            ></v-text-field>
                            
                        </div>
                
                        <div class="w-100 flex items-center justify-between m-0 py-2">
                            <p class="text-sm py-0 my-0 ml-8 opacity-45">Total de protocolos: {{protocolos.total}}</p>
                            <div>
                                <v-btn rounded="lg" variant="text" color="grey">
                                    <a href="/download/pdf">
                                        <v-icon icon="mdi-download-outline" class="mr-3"></v-icon> Baixar protocolos
                                    </a>
                                </v-btn>
                                <v-btn class="mr-8" rounded="lg" variant="text">
                                    <Link href="/protocolos/cadastro" ><v-icon icon="mdi-plus-circle-outline" class="mr-3"></v-icon> Cadastrar</Link>
                                </v-btn>
                            </div> 
                        </div>
                        <v-table  density="comfortable" hover class="p-5 pt-0">
                            <thead class="text-base">
                                <tr>
                                    <th>Número</th>
                                    <th>Contruibuinte</th>
                                    <th>Departamento</th>
                                    <th>Data de registro</th>
                                    <th>Data limite</th>
                                    <th>Prazo</th>
                                    <th>Situação</th>
                                    <th class="text-center">Acões</th>
                                </tr>
                            </thead>
                            <tbody v-if="protocolos.data.lenght != 0">
                                <tr v-for="protocolo in protocolos.data" :key="protocolo.numero">
                                    <td>{{protocolo.numero}}</td>
                                    <td><Link class="hover:underline text-blue-500" :href="`/pessoas/pesquisar?nome=${protocolo.pessoa.nome}`">{{protocolo.pessoa.nome}}</Link></td> 
                                    <td>{{protocolo.departamento.nome}}</td>
                                    <td>{{formatarData(protocolo.data_registro)}}</td>
                                    <td>{{FormatarDataLimite(protocolo.data_registro, protocolo.prazo)}}</td>
                                    <td>{{protocolo.prazo}}</td>
                                    <td><v-icon :color="cor(protocolo.situacao).color" icon="mdi-circle"></v-icon> {{cor(protocolo.situacao).text}}</td>
                                    <td class="text-center">
                                        <v-btn class="mr-6 h-75" rounded="lg" color="#7CB342" prepend-icon="mdi-pencil" variant="flat" @click.once="editar(protocolo.numero)">
                                            Visualizar
                                        </v-btn>
                                        <v-btn class="h-75" rounded="lg" color="#B71C1C" prepend-icon="mdi-delete-outline" variant="flat" @click="dialog = true; protocolo_excluir.numero = protocolo.numero; protocolo_excluir.pessoa_nome = protocolo.pessoa.nome">
                                            Excluir  
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>     
                        </v-table>
                        <p v-if="protocolos.data.length == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhum protocolo encontrado"</p>
                        <div class="flex w-100 justify-center relative">
                            <div v-if="protocolos.data.length != 0"  class="w-40 absolute left-8 flex gap-4 items-center justify-center">    
                                <v-select
                                    v-model="itens_por_pag"
                                    variant="outlined"
                                    rounded="md"
                                    density="comfortable"
                                    :items="[protocolos.total, '20', '10', '5']"
                                    base-color="#7CB342"
                                    label="Itens por página"
                                    color="#7CB342"
                                ></v-select>
                            </div>
                            <v-pagination
                                v-if="protocolos.data.length != 0" 
                                v-model="pagina_atual"
                                :length="protocolos.last_page"
                                class="mb-2"
                                :total-visible="5"
                                active-color="#1B5E20"
                                color="#7CB342"
                                rounded
                            ></v-pagination>
                        </div>
                    </v-card>  
                </div>    
            </div>
        </main-layout>
    </div>
</template>

<style scoped>
    [type='text']:focus {
        box-shadow: none; 
        /* por algum motivo os inputs tavam com um box-shadow azul no focus  que parecia um border */
    }
</style>