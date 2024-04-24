<script setup>
    import { Head, router } from '@inertiajs/vue3'
    import { ref, watch } from 'vue'
    import MainLayout from '../Layouts/MainLayout.vue'
    
    const props = defineProps({
        audits: Object,
        searchUsuario: String,
        searchEvent: String,
        searchTabela: String,
        searchData: String,
        inputFocus: String
    });

    const dataFormatada = (data) => {
        let newData = new Date(data);
        let dia = ('0' + newData.getDate()).slice(-2);
        let mes = ('0' + (newData.getMonth() + 1)).slice(-2);
        let ano = newData.getFullYear();
        let hora = newData.getHours();
        let min = newData.getMinutes();
        return `${dia}/${mes}/${ano} - ${hora.toString().padStart(2, '0')}h:${min} min`;
    }

    let pagina_atual = ref(props.audits.current_page);
    let itens_por_pag = ref(props.audits.per_page);

    watch(pagina_atual, () => {
        if(props.searchUsuario != null || props.searchEvent != null || props.searchTabela != null || props.searchData != null){
            router.get(`/auditorias/pesquisar?
            page=${pagina_atual.value}&
            usuario=${searchUsuario.value}&
            evento=${searchEvent.value}&
            tabela=${searchTabela.value}&
            data=${searchData.value}&
            itens_pag=${itens_por_pag.value}`);
        } 
        else router.get(`/auditorias?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
    })

    watch(itens_por_pag, () => {
        let ultima_pagina = Math.ceil(parseInt(props.audits.total) / itens_por_pag.value);
        // calcula a última página disponivel com base no total de pessoas e os itens por página
        if(ultima_pagina < pagina_atual.value){
            pagina_atual.value = ultima_pagina;
            // verifica se a ultima pagina disponivel é menor que a página atual
        }

        if(props.searchUsuario != null || props.searchEvent != null || props.searchTabela != null || props.searchData != null){
            router.get(`/auditorias/pesquisar?
            page=${pagina_atual.value}&
            usuario=${searchUsuario.value}&
            evento=${searchEvent.value}&
            tabela=${searchTabela.value}&
            data=${searchData.value}&
            itens_pag=${itens_por_pag.value}`);
        } 
        else router.get(`/auditorias?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`)
        
    })

    const visualizarAudit = (id) => {
        router.get(`/auditoria/${id}`);
    }

    // search
    let searchUsuario = ref(props.searchUsuario);
    let searchEvent = ref(props.searchEvent);
    let searchTabela = ref(props.searchTabela);
    let searchData = ref(props.searchData);

    watch(searchUsuario, () => {
        router.get(`/auditorias/pesquisar?usuario=${searchUsuario.value}&evento=${searchEvent.value}&tabela=${searchTabela.value}&data=${searchData.value}&itens_pag=${itens_por_pag.value}&inputFocus=usuario`)
    });

    watch(searchEvent, () => {
        router.get(`/auditorias/pesquisar?usuario=${searchUsuario.value}&evento=${searchEvent.value}&tabela=${searchTabela.value}&data=${searchData.value}&itens_pag=${itens_por_pag.value}&inputFocus=evento`)
    });

    watch(searchTabela, () => {
        router.get(`/auditorias/pesquisar?usuario=${searchUsuario.value}&evento=${searchEvent.value}&tabela=${searchTabela.value}&data=${searchData.value}&itens_pag=${itens_por_pag.value}`)
    });

    watch(searchData, () => {
        router.get(`/auditorias/pesquisar?usuario=${searchUsuario.value}&evento=${searchEvent.value}&tabela=${searchTabela.value}&data=${searchData.value}&itens_pag=${itens_por_pag.value}`)
    });

    let tabelas = [
        {title: 'Pessoas', value: 'Pessoa'},
        {title: 'Protocolos', value: 'Protocolo'},
        {title: 'Departamentos', value: 'Departamento'},
        {title: 'Arq_anexados', value: 'Arq_anexado'},
        {title: 'Users', value: 'User'},
        {title: 'Permissões', value: 'Permissoe'},
        {title: 'Acompanhamentos', value: 'Acompanhamento'}
    ];

    let eventos = [
        'created',
        'updated',
        'deleted'
    ]

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

    // Data picker
    const isMenuOpen = ref(false)
    let selectedDate = ref()

    let formated = ref(formatarData(searchData.value))
    if(formated.value == 'undefined/undefined/'){
        formated = '';
    }

    watch(selectedDate, (newValue, oldValue) => {
        isMenuOpen.value = false

        if(newValue == null){
            formated = '';
            searchData.value = '';
        }else{            
            searchData.value = formatarDataBanco(selectedDate.value)
            formated = formatarData(formatarDataBanco(selectedDate.value))
        }

    })
</script>

<template>
    <main-layout paginaAtual="Auditorias" class="w-full">
        <Head title="Auditorias">
            <link rel="icon" type="image/x-icon" href="/logo.ico" />
        </Head>    
       <v-app class="w-full my-3">
            <div class="w-full flex justify-center">
                <v-card elevation="4" class="w-11/12 rounded-lg  pt-4">
                    <v-text-field
                            v-model="searchUsuario"
                            class="mx-8 mt-3 mb-2 h-12"
                            clearable 
                            label="Usuário"
                            variant="underlined"
                            :autofocus="inputFocus == 'usuario'"
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                        >
                    </v-text-field>
                    <div class="flex mx-8 mt-3 mb-2 h-12 gap-12 justify-between">
                        <v-select
                            v-model="searchTabela"
                            variant="underlined"
                           
                            rounded="md"
                            :items="tabelas"
                            label="Tabela"
                            base-color="#7CB342"
                            color="#7CB342"
                            density="comfortable"
                            clearable
                        ></v-select>
                        <v-select
                            v-model="searchEvent"
                            variant="underlined"
                           
                            rounded="md"
                            :items="eventos"
                            label="Evento"
                            base-color="#7CB342"
                            color="#7CB342"
                            density="comfortable"
                            clearable
                        ></v-select>
                        <div class="w-25">
                            <v-menu v-model="isMenuOpen" :close-on-content-click="false">
                                <template v-slot:activator="{ props }">
                                <div class="flex items-center">
                                    <v-text-field
                                        label="Data"
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
                    </div>
            
                    <div class="w-100 flex items-center justify-between m-0 py-2">
                            <p class="text-sm py-0 my-0 ml-8 opacity-45">Total de Auditorias: {{audits.total}}</p>
                    </div> 
                    <v-table  density="comfortable" hover class="p-5 pt-0">
                        <thead class="text-base">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-left">Usuário</th>
                                <th class="text-left">Evento</th>
                                <th class="text-left">Data e Horário</th>
                                <th class="text-left">Tabela</th>
                                <th class="text-left">Id Auditado</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead> 
                        <tbody v-if="audits.data.length != 0">
                            <tr v-for="auditoria in audits.data" :key="auditoria.id">
                                <td class="text-center">{{auditoria.id}}</td>
                                <td class="text-left">{{auditoria.user.name}}</td>
                                <td class="text-left">{{auditoria.event}}</td>
                                <td class="text-left">{{dataFormatada(auditoria.created_at)}}</td>
                                <td class="text-left">{{auditoria.auditable_type.replace("App\\Models\\", "")+"s"}}</td>
                                <td class="text-left">{{auditoria.auditable_id}}</td>
                                <td class="text-center">
                                    <v-btn class="mr-6 h-75" rounded="lg" color="#7CB342" prepend-icon="mdi-file-plus-outline" variant="flat" @click.once="visualizarAudit(auditoria.id)">
                                        Detalhes 
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>   
                    </v-table>
                    <p v-if="audits.total == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhuma auditoria realizada"</p>
                    <div class="flex w-100 justify-center relative">
                        <div v-if="audits.total != 0"  class="w-40 absolute left-8 flex gap-4 items-center justify-center">    
                            <v-select
                                v-model="itens_por_pag"
                                variant="outlined"
                                rounded="md"
                                density="comfortable"
                                :items="[audits.total, '20', '10', '5']"
                                base-color="#7CB342"
                                label="Itens por página"
                                color="#7CB342"
                            ></v-select>
                        </div>
                        <v-pagination
                            v-if="audits.total != 0" 
                            v-model="pagina_atual"
                            :length="audits.last_page"
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
