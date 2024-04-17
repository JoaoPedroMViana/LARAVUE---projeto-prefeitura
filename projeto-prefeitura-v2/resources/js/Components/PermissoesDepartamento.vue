<script setup>
    import { router, usePage } from "@inertiajs/vue3";
    import { useForm } from "laravel-precognition-vue-inertia";
    import { ref } from "vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';
    
    const page = usePage();

    const props = defineProps({
        usuarios: Object,
        departamento_id: Number,
        permitidos: Array,
    });

    const items = [
      
    ]
    for(let usuario of props.usuarios){
        items.push({title: `${usuario.name}`, value: `${usuario.id}`})
    }

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

    let content = {
        departamento_id: props.departamento_id,
        user_id: null,
        data_liberacao: formatarDataBanco(new Date())
    }

    const form = useForm(
        'post', 
        '/permissoes/liberar',
        content
    ); 

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
            form.reset();
        }})
    }

    const removerPermissao = (id) => {
        router.delete(`/permissoes/remover/${id}`, {onSuccess: () => {
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
    }
</script>

<template>
    <v-card elevation="4" class="w-5/6 rounded-lg mb-4">
        <h1 class="pt-8 pl-10 text-lg">Permissão de Usuários</h1>
        <form @submit.prevent="submit" class="p-10 pt-3">
            <div class="flex gap-4 items-center justify-center">
                <v-select
                    id="pessoa_id"
                    v-model="form.user_id"
                    variant="outlined"
                    rounded="md"
                    :items="items"
                    label="Selecione um usuário"
                    required
                    base-color="#7CB342"
                    color="#7CB342"
                    density="comfortable"
                ></v-select>
                <v-btn 
                    class="mb-5"
                    rounded="md"
                    type="submit"
                >
                    <v-icon class="pr-3" icon="mdi-link-variant-plus"></v-icon> Liberar acesso
                </v-btn>
            </div>
        </form>
        <section class="p-10 pt-0">
            <h2 class="text-lg">Usuários com acesso:</h2>
            <p class="pt-2 opacity-55" v-if="permitidos.length == 0">Nenhum usuário com acesso</p>
            <v-table v-if="permitidos.length != 0" hover>
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th class="text-left">Nome</th>
                        <th class="text-center">Data</th>
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="permitido in permitidos" :key="permitido.id">
                        <td class="text-center">{{permitido.user.id}}</td>
                        <td class="text-left ">{{permitido.user.name}}</td>
                        <td class="text-center">{{formatarData(permitido.data)}}</td>
                        <td class="text-center"><v-btn @click="removerPermissao(permitido.id)" color="#B71C1C" prepend-icon="mdi-link-variant-off" rounded="md">Remover acesso</v-btn></td>
                    </tr>
                </tbody>
            </v-table>
        </section>
    </v-card>
</template>
