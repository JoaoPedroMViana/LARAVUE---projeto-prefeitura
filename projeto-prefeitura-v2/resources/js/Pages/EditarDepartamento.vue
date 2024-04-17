<script setup>
    import { Head, usePage } from '@inertiajs/vue3'
    import { useForm } from 'laravel-precognition-vue-inertia'
    import { ref, watch } from 'vue'
    import MainLayout from '../Layouts/MainLayout.vue'
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    import PermissoesDepartamento from '../Components/PermissoesDepartamento.vue'
    
    const page = usePage();

    const props = defineProps({
        departamento: Object,
        usuarios: Object,
        permitidos: Array
    });

    let data = {
        id: props.departamento.id,
        nome: props.departamento.nome,
    }; 

    const form = useForm('put', '/departamento/update', data);

    const submit = () => {
        form.submit({onSuccess: () => {
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
</script>

<template>
    <main-layout paginaAtual="Editar Departamento" class="w-full">
        <Head title="Editar Departamento"/>
        <v-app class="w-full mt-4">
            <div class="w-full flex flex-column justify-center items-center gap-6">
                <v-card elevation="4" class="w-5/6 rounded-lg">
                    <form action="post" @submit.prevent="submit" class="p-8 pt-3">
                        <v-container class=" flex flex-column justify-between">  
                            <v-text-field
                                variant="outlined"
                                rounded="md"
                                v-model="form.nome"
                                :counter="255"
                                label="Nome"
                                required 
                                maxlength="255"
                                clearable
                                type="input"
                                base-color="#7CB342"
                                color="#7CB342"
                                id="nome"
                                @change="form.validate('nome')"
                                @mouseout="form.validate('nome')"
                                :error-messages="form.errors.nome"
                            >

                            </v-text-field>
                            <v-btn
                                class="mt-3" rounded="md" color="#7CB342" prepend-icon="mdi-content-save-outline" variant="flat" :disabled="!form.isDirty || form.processing || form.hasErrors" @click.prevent="submit"
                            >
                                    Salvar
                                    <v-icon v-if="form.processing" icon="mdi-loading" class="animate-spin h-5 w-5 mr-3"></v-icon>
                            </v-btn>
                        </v-container>
                    </form>
                </v-card>
                <PermissoesDepartamento :usuarios="usuarios" :departamento_id="departamento.id" :permitidos="permitidos"/>
            </div>
        </v-app>
    </main-layout>
</template>
