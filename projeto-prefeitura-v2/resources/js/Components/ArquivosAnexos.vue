<script setup>
    import { router, usePage } from "@inertiajs/vue3";
    import { ref, watch } from "vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage();

    const props = defineProps({
        method: String,
        anexados: Array
    });

    // deletar arquivo
    const anexo_deletado = ref('');

    const deletar_anexo = (id) => {
        router.delete(`/anexo/delete/${id}`, {onSuccess: () => {
            if(page.props.flash){
                toast(`${page.props.flash}`, {
                    "autoClose": 2500,
                    "type": "success",
                    "dangerouslyHTMLString": true,
                    "position": "top-center",
                });// Mensagem de sucesso
            }
            if(page.props.error_msg){
                toast(`${page.props.error_msg}`, {
                    "autoClose": 2500,
                    "type": "error",
                    "dangerouslyHTMLString": true,
                    "position": "top-center",
                });// Mensagem de erro
            }
        }});
    };

    let dialog_deletar = ref(false);

    // dialog visualizar arq
    let dialog = ref(false);

    let anexo_vizu = '';

    const pdfOrPhoto = (path) => {
        if(path.endsWith('.pdf')) return 'w-full h-full overscroll-x-none p-2'
        else return 'h-full overscroll-x-none p-8'
    }// verifica se é um pdf ou outro tipo de arquivo, para ajustar o embed
</script>

<template>
    <section class="flex flex-column justify-center py-0 mt-2 relative h-full p-5">   
        <h2 class="py-2 ml-2">Arquivos Anexados: </h2>
        <p class="mb-4 ml-4 opacity-50 flex items-center gap-2" v-if="anexados.length == 0"><v-icon icon="mdi-information"></v-icon> O protocolo não possui anexos</p>
        <div v-if="props.method == 'put'" class="flex flex-row">
            <div v-for="anexo in anexados" :key="anexo.id">
                <v-card elevation="4" class="m-4 my-6 overscroll-x">
                    <div class="flex justify-between">
                        <v-btn class="m-2" rounded variant="plain" size="lg" color="grey"><v-icon class="m-3 hover:bg-lime" icon="mdi-delete" @click="anexo_deletado = anexo.id; dialog_deletar = true"></v-icon></v-btn>
                        <v-btn class="m-2" rounded variant="plain" size="lg" color="grey">
                            <a :href="`/anexo/download/${anexo.path.replace('/storage/','')}`">
                                <v-icon class="m-3 hover:bg-lime" icon="mdi-download"></v-icon>
                            </a>
                        </v-btn>
                        <v-btn class="m-2" rounded variant="plain" size="lg" color="grey"><v-icon class="m-3 hover:bg-lime" icon="mdi-eye" @click="dialog = true; anexo_vizu = anexo.path"></v-icon></v-btn>
                    </div>
                    <embed :src="anexo.path" class="w-44 overscroll-x-none p-2">
                </v-card>
            </div>
            <v-dialog
            v-model="dialog"
            transition="dialog-top-transition"
            class="w-75"
            >   
                <v-card
                    rounded="lg"
                    class="relative h-96"
                >
                    <template v-slot:actions>
                    <v-container class="flex justify-end gap-4">
                        <v-btn
                            class="px-5" size="large" rounded="lg" color="#B71C1C" variant="flat"
                            @click="dialog = false;"
                        >
                            Fechar
                        </v-btn>
                    </v-container>
                    </template>
                    <div class="flex items-center justify-center h-full w-full absolute">
                        <embed :src="anexo_vizu" :class="pdfOrPhoto(anexo_vizu)">
                    </div>
                </v-card>
            </v-dialog>
            
            <v-dialog
            v-model="dialog_deletar"
            transition="dialog-top-transition"
            class="w-50"
            >   
                <v-card
                    rounded="lg"
                >   
                    <v-card-title class="pt-6">
                        <p class="text-2xl p-2 pb-0"><v-icon icon="mdi-delete-circle"></v-icon> Deletar anexo</p>    
                    </v-card-title>
                    <v-card-text class="pl-8 pt-2">Deseja excluir este arquivo?</v-card-text>
                    <template v-slot:actions>
                    <v-container class="flex justify-end gap-4">
                        <v-btn
                            class="px-5" size="large" rounded="lg" color="#B71C1C" variant="flat"
                            @click="deletar_anexo(anexo_deletado); dialog_deletar = false"
                        >
                            Deletar
                        </v-btn>
                        <v-btn
                            class="px-5" size="large" rounded="lg" variant="tonal"
                            @click="dialog_deletar = false;"
                        >
                            Fechar
                        </v-btn>
                    </v-container>
                    </template>
                </v-card>
            </v-dialog>
        </div>
    </section>
</template>
