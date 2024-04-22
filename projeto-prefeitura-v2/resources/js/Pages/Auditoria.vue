<script setup>
    import { Head, router } from '@inertiajs/vue3'
    import { ref, watch } from 'vue'
    import MainLayout from '../Layouts/MainLayout.vue'
    
    const props = defineProps({
        audit: Object
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

    let pagina_atual = ref(props.audit.current_page);
    let itens_por_pag = ref(props.audit.per_page);

    watch(pagina_atual, () => {
        router.get(`/auditorias?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`);
    })

    watch(itens_por_pag, () => {
        let ultima_pagina = Math.ceil(parseInt(props.audit.total) / itens_por_pag.value);
        // calcula a última página disponivel com base no total de pessoas e os itens por página
        if(ultima_pagina < pagina_atual.value){
            pagina_atual.value = ultima_pagina;
            // verifica se a ultima pagina disponivel é menor que a página atual
        }
        router.get(`/auditorias?page=${pagina_atual.value}&itens_pag=${itens_por_pag.value}`);
    })
</script>

<template>
    <main-layout paginaAtual="Auditorias" class="w-full">
        <Head title="Auditorias"/>    
       <v-app class="w-full my-3">
            <div class="w-full flex justify-center">
                <v-card elevation="4" class="w-11/12 rounded-lg  pt-4">
                    <div class="w-100 flex items-center justify-between m-0 py-2">
                            <p class="text-sm py-0 my-0 ml-8 opacity-45">Total de Auditorias: {{audit.total}}</p>
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
                        <tbody v-if="audit.data.length != 0">
                            <tr v-for="auditoria in audit.data" :key="auditoria.id">
                                <td class="text-center">{{auditoria.id}}</td>
                                <td class="text-left">{{auditoria.user.name}}</td>
                                <td class="text-left">{{auditoria.event}}</td>
                                <td class="text-left">{{dataFormatada(auditoria.created_at)}}</td>
                                <td class="text-left">{{auditoria.auditable_type.replace("App\\Models\\", "")+"s"}}</td>
                                <td class="text-left">{{auditoria.auditable_id}}</td>
                                <td class="text-center">
                                    <v-btn class="mr-6 h-75" rounded="lg" color="#7CB342" prepend-icon="mdi-eye-outline" variant="flat" @click.once="console.log('visualizar')">
                                        Visualizar 
                                    </v-btn>
                                </td>
                            </tr>
                        </tbody>   
                    </v-table>
                    <p v-if="audit.total == 0" ><v-icon icon="mdi-alert-circle" class="ml-6 m-4"></v-icon> Nenhuma auditoria realizada"</p>
                    <div class="flex w-100 justify-center relative">
                        <div v-if="audit.total != 0"  class="w-40 absolute left-8 flex gap-4 items-center justify-center">    
                            <v-select
                                v-model="itens_por_pag"
                                variant="outlined"
                                rounded="md"
                                density="comfortable"
                                :items="[audit.total, '20', '10', '5']"
                                base-color="#7CB342"
                                label="Itens por página"
                                color="#7CB342"
                            ></v-select>
                        </div>
                        <v-pagination
                            v-if="audit.total != 0" 
                            v-model="pagina_atual"
                            :length="audit.last_page"
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
