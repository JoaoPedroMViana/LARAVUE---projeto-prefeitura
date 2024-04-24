<script setup>
    import MudarSenha from '../Components/MudarSenha.vue';
    import { Head, Link, router, usePage} from '@inertiajs/vue3';
    import MainLayout from "../Layouts/MainLayout.vue";
    import FormUsuarios from "../Components/FormUsuarios.vue"
    import { ref } from 'vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage()

    const props = defineProps({
        user: Object,
        canEdit: Boolean
    })

    // Modal desativar/ativar
    let dialog = ref(false)

    const desativarUsuario = (id) => {
        router.post(`/user/desativar/${id}`, {},{onSuccess: () => {
           
            if(page.props.flash){
                toast(`${page.props.flash}`, {
                    "autoClose": 2500,
                    "type": "success",
                    "dangerouslyHTMLString": true,
                    "position": "top-center",
                });// Mensagem de sucesso
            }
        }})
    }

    const ativarUsuario = (id) => {
        router.post(`/user/ativar/${id}`, {},{onSuccess: () => {
           
            if(page.props.flash){
                toast(`${page.props.flash}`, {
                    "autoClose": 2500,
                    "type": "success",
                    "dangerouslyHTMLString": true,
                    "position": "top-center",
                });// Mensagem de sucesso
            }
        }})
    }
</script>

<template>
    <main-layout paginaAtual="Editar Usuário" class="w-full">
        <Head title="Editar Usuário"/>
        <v-dialog
            v-model="dialog"
            transition="dialog-top-transition"
            class="w-50"
            >   
                <v-card
                    rounded="lg"
                >
                    <v-card-title class="pt-6">
                        <p v-if="user.ativo == 'S'" class="text-2xl p-2 pb-0"><v-icon icon="mdi-delete-circle"></v-icon> Desativar usuário {{user.id}}</p>    
                        <p v-if="user.ativo == 'N'" class="text-2xl p-2 pb-0"><v-icon icon="mdi-account-reactivate-outline"></v-icon> Ativar usuário {{user.id}}</p>    
                    </v-card-title>
                    <v-card-text v-if="user.ativo == 'S'" class="pl-8 pt-2">Deseja desativar o usuário: <b class="text-red-800 underline">{{user.name}}</b>?</v-card-text>
                    <v-card-text v-if="user.ativo == 'N'" class="pl-8 pt-2">Deseja ativar o usuário: <b class="text-blue-800 underline">{{user.name}}</b>?</v-card-text>
                    <template v-slot:actions>
                    <v-container class="flex justify-end gap-4">
                        <v-btn
                            v-if="user.ativo == 'S'" class="px-5" size="large" rounded="lg" color="#B71C1C" variant="flat"
                            @click="dialog = false;  desativarUsuario(user.id)"
                        >
                            Desativar
                        </v-btn>
                        <v-btn
                                v-if="user.ativo == 'N'" class="px-5" size="large" @click="dialog = false; ativarUsuario(user.id)" rounded="lg" prepend-icon="mdi-account-check" variant="flat"
                            > 
                                Ativar
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
        <v-app class="w-full mt-4">
            <div class="w-full flex flex-column justify-center items-center">
                <v-card elevation="4" class="w-5/6 rounded-lg">
                    <FormUsuarios :canEdit="canEdit" :ativo="user.ativo" :values='user' route="/user/update" method="put" text_button_submit="Salvar">
                             <v-btn
                                v-if="user.ativo == 'S' && canEdit" @click="dialog = true" rounded="md" color="#B71C1C" prepend-icon="mdi-account-off" variant="flat"
                            > 
                                Desativar
                            </v-btn>
                             <v-btn
                                v-if="user.ativo == 'N' && canEdit" @click="dialog = true" rounded="md" prepend-icon="mdi-account-check" variant="flat"
                            > 
                                Ativar
                            </v-btn>
                        </FormUsuarios>
                </v-card>
                <v-card v-if="canEdit" elevation="4" class="w-5/6 rounded-lg mt-4">
                    <MudarSenha :user_id="user.id" :user_ativo="user.ativo"/>
                </v-card>
            </div>
        </v-app>
    </main-layout>
</template>