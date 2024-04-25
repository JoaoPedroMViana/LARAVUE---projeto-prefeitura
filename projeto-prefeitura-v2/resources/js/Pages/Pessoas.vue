<script setup>
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import { computed, ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage()
 
    const props = defineProps({
        pessoas: Object,
        searchNome: String,
        itens: Number,
        searchCpf: String,
        searchData: String,
        focus: String,
        searchSexo: String
    });

    // páginação
    let pagina_atual = ref(props.pessoas.current_page);
    let itens_por_pag = ref(props.pessoas.per_page)

    watch(pagina_atual, () => {
        if(props.searchNome != null || props.searchSexo != null || props.searchCpf != null || props.searchData != null) router.get(`/pessoas/pesquisar?
            nome=${searchNome.value}&
            cpf=${searchCpf.value}&
            data=${searchData.value}&
            page=${pagina_atual.value}&
            itens_pag=${itens_por_pag.value}&
            sexo=${searchSexo.value}`);
        else router.get(`/pessoas?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    })

    watch(itens_por_pag, () => {
        let ultima_pagina = Math.ceil(parseInt(props.pessoas.total) / itens_por_pag.value);
        // calcula a última página disponivel com base no total de pessoas e os itens por página
        if(ultima_pagina < pagina_atual.value){
            pagina_atual.value = ultima_pagina;
            // verifica se a ultima pagina disponivel é menor que a página atual
        }
        if(props.searchNome != null || props.searchSexo != null || props.searchCpf != null || props.searchData != null) router.get(`/pessoas/pesquisar?
            nome=${searchNome.value}&
            cpf=${searchCpf.value}&
            data=${searchData.value}&
            page=${pagina_atual.value}&
            itens_pag=${itens_por_pag.value}&
            sexo=${searchSexo.value}`);
        else router.get(`/pessoas?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    })

    
    // search
    let searchNome = ref(props.searchNome);
    let searchCpf = ref(props.searchCpf);
    let searchSexo = ref(props.searchSexo);
    let searchData = ref(props.searchData);

    watch(searchNome, () => {
        router.get(`/pessoas/pesquisar?
        nome=${searchNome.value}&
        cpf=${searchCpf.value}&
        data=${searchData.value}&
        itens_pag=${itens_por_pag.value}&
        inputFocus=nome&
        sexo=${searchSexo.value}`)
    });

    watch(searchCpf, () => {
        router.get(`/pessoas/pesquisar?
        nome=${searchNome.value}&
        cpf=${searchCpf.value}&
        data=${searchData.value}&
        itens_pag=${itens_por_pag.value}&
        inputFocus=cpf&
        sexo=${searchSexo.value}`)
    });

    watch(searchSexo, () => {
        router.get(`/pessoas/pesquisar?
        nome=${searchNome.value}&
        cpf=${searchCpf.value}&
        data=${searchData.value}&
        itens_pag=${itens_por_pag.value}&
        sexo=${searchSexo.value}`)
    })

    watch(searchData, () => {
        router.get(`/pessoas/pesquisar?
        nome=${searchNome.value}&
        cpf=${searchCpf.value}&
        data=${searchData.value}&
        itens_pag=${itens_por_pag.value}&
        sexo=${searchSexo.value}`)
    })

    // página de editar
    const editar = (id) => {
        router.get(`/pessoa/${id}`);
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

    // formatação do cpf
    const formatarCpf = (event) => {
        if(event.key == 'Backspace'){
            // se for backspace faz nada
        }else if(searchCpf.value != null && /^[\d]+$/.test(event.key) ){
            // testa de a tecla clicada é um numero/-/.
            if(searchCpf.value.length == 3 || searchCpf.value.length == 7){    
            // add o ponto/traço dinamicamente
                searchCpf.value += '.'
            }else if(searchCpf.value.length == 11) {
                searchCpf.value += '-';
            }
        }else if(searchCpf.value == null && (/^[\d]+$/.test(event.key))){
            return;
        }else{
            // se for alguma letra não escreve
            event.preventDefault()
        }
    }

    // mudar autofocus
    let inputFocus = ref(props.focus);

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

</script>

<template>
    <div>
        <Head title="Pessoas">
            <link rel="icon" type="image/x-icon" href="/logo.ico" />
        </Head>
        <main-layout paginaAtual="Pessoas" class="w-full h-fit" >
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
            <div class="w-full mt-3">
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-11/12 rounded-lg"> 
                       <v-text-field
                            v-model="searchNome"
                            class="mx-8 mt-3 mb-2 h-12"
                            clearable 
                            label="Nome"
                            variant="underlined"
                            :autofocus="inputFocus == 'nome'"
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                        >
                        </v-text-field>
                        <div class="flex h-12 justify-between mx-8 mb-2 gap-8">
                            <v-text-field
                            class="w-25"
                            clearable
                            variant="underlined"
                            rounded="md"
                            v-model="searchCpf"
                            label="CPF"
                            maxlength="14"
                            @keydown="formatarCpf($event)"
                            :autofocus="inputFocus == 'cpf'"
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>
                            <div class="w-25">
                                <v-menu v-model="isMenuOpen" :close-on-content-click="false">
                                    <template v-slot:activator="{ props }">
                                    <div class="flex items-center">
                                        <v-text-field
                                            id="data_nascimento"
                                            label="Data de nascimento"
                                            :model-value="formated"
                                            type="input"
                                            readonly
                                            v-bind="props"
                                            variant="underlined"
                                            rounded="md"
                                            base-color="#7CB342"
                                            color="#7CB342"
                                        >
                                        </v-text-field>
                                        <v-btn size="sm" :disabled="formated == ''" variant="text" color="grey" @click="selectedDate = null"> <v-icon icon="mdi-close-circle"></v-icon> </v-btn>
                                    </div>
                                    </template>
                                    <v-date-picker rounded :max="new Date()" @click.self="isMenuOpen = false" v-model="selectedDate" color="#7CB342"></v-date-picker>
                                </v-menu>
                            </div>
                            <div class="flex mt-2">
                                <v-checkbox
                                    v-model="searchSexo"
                                    label="Masculino"
                                    value="masculino"
                                    color="green"
                                ></v-checkbox>
                                <v-checkbox
                                    v-model="searchSexo"
                                    label="Feminino"
                                    value="feminino"
                                    color="green"
                                ></v-checkbox>
                                <v-checkbox
                                    v-model="searchSexo"
                                    label="Outro"
                                    value="outro"
                                    color="green"
                                ></v-checkbox>
                            </div>
                        </div>
                
                        
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
                                            Visualizar
                                        </v-btn>
                                        <v-btn class="h-75" rounded="lg" color="#B71C1C" prepend-icon="mdi-delete-outline" variant="flat" @click="dialog = true; pessoa_excluir.id = pessoa.id; pessoa_excluir.nome = pessoa.nome">
                                            Excluir  
                                        </v-btn>
                                    </td>
                                </tr>
                            </tbody>
                                
                        </v-table>
                        <p v-if="pessoas.data.length == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhuma pessoa encontrada"</p>
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
            </div>
           
        </main-layout>
    </div>
</template>



