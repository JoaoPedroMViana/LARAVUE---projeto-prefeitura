<script setup>
    import { Head, Link, router, usePage} from '@inertiajs/vue3';
    import MainLayout from "../Layouts/MainLayout.vue";
    import FormPessoa from "../Components/FormPessoa.vue"
    import { ref } from 'vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage()

    const props = defineProps({
        pessoa: Object
    })

    // Modal excluir
    let dialog = ref(false)

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

</script>
 
<template>
    <main-layout paginaAtual="Editar Pessoa" class="w-full">
        <Head title="Editar pessoa">
            <link rel="icon" type="image/x-icon" href="/logo.ico" />
        </Head>
        <v-dialog
            v-model="dialog"
            transition="dialog-top-transition"
            class="w-50"
            >   
                <v-card
                    rounded="lg"
                >
                    <v-card-title class="pt-6">
                        <p class="text-2xl p-2 pb-0"><v-icon icon="mdi-delete-circle"></v-icon> Deletar pessoa {{pessoa.id}}</p>    
                    </v-card-title>
                    <v-card-text class="pl-8 pt-2">Deseja excluir a pessoa: <b class="text-red-800 underline">{{pessoa.nome}}</b>?</v-card-text>
                    <template v-slot:actions>
                    <v-container class="flex justify-end gap-4">
                        <v-btn
                            class="px-5" size="large" rounded="lg" color="#B71C1C" variant="flat"
                            @click="dialog = false;  deletarPessoa(pessoa.id)"
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
                <v-card elevation="4" class="w-5/6 rounded-lg">
                    <FormPessoa :values='pessoa' route="/pessoa/update" method="put" text_button_submit="Salvar">
                             <v-btn
                                @click="dialog = true" rounded="md" color="#B71C1C" prepend-icon="mdi-delete-outline" variant="flat"
                            >
                                Excluir
                            </v-btn>
                        </FormPessoa>
                </v-card>
            </div>
        </div>
    </main-layout>
</template>
