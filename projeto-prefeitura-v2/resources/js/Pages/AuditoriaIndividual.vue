<script setup>
    import MainLayout from '../Layouts/MainLayout.vue'
    import { Head } from '@inertiajs/vue3';

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
</script>

<template>
    <main-layout paginaAtual="Visualizar Auditoria" class="w-full">
        <Head title="Visualizar auditoria">
            <link rel="icon" type="image/x-icon" href="/logo.ico" />
        </Head> 
        <div class="w-full my-3">
                <div class="w-full flex justify-center">
                    <v-card elevation="4" class="w-11/12 rounded-lg">
                        <div class="p-12 flex
                         flex-column gap-4">
                            <p><b class="text-lime-900 text-lg">Id: </b>{{audit.id}}</p>
                            <div class="flex justify-between">
                                <p><b class="text-lime-600 font-normal">Usuário: </b>{{audit.user.name}}</p>
                                <p><b class="text-lime-600 font-normal">Data e Horário: </b>{{dataFormatada(audit.created_at)}}</p>
                                <p><b class="text-lime-600 font-normal">URL: </b>{{audit.url}}</p>
                                <p><b class="text-lime-600 font-normal">IP: </b>{{audit.ip_address}}</p>
                            </div>
                            <div class="flex gap-16">
                                <p><b class="text-lime-600 font-normal">Evento: </b>{{audit.event}}</p>
                                <p><b class="text-lime-600 font-normal">Tabela: </b>{{audit.auditable_type.replace("App\\Models\\", "")+"s"}}</p>
                                <p><b class="text-lime-600 font-normal">Id Auditado: </b>{{audit.auditable_id}}</p>
                            </div>
                            <div class="flex flex-column gap-4">
                                <h2 class="text-lg font-bold text-lime-900">Dados:</h2>
                                <div v-if="audit.old_values.length > 2" class="flex flex-column gap-3"> 
                                    <p class="text-lime-700 font-bold">Anteriores:</p>
                                    <p class="pl-3 text-justify" v-for="(e, index) in JSON.parse(audit.old_values)" :key="index">
                                        <b class="text-lime-600 font-normal">{{index}}:</b> {{e}}
                                    </p>
                                </div>
                                <div v-if="audit.new_values.length > 2" class="flex flex-column gap-3"> 
                                    <p class="text-lime-700 font-bold">Novos:</p>
                                    <p class="pl-3 text-justify" v-for="(e, index) in JSON.parse(audit.new_values)" :key="index">
                                        <b class="text-lime-600 font-normal">{{index}}:</b> {{e}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </v-card>
                </div>
        </div>
    </main-layout>
</template>
