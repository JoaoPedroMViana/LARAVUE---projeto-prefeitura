<script setup>
    import { Head, Link, router, usePage} from '@inertiajs/vue3';
    import { useForm } from 'laravel-precognition-vue-inertia';
    import { computed, ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage()

    const props = defineProps({
        pessoa: Object
    })

    // Date picker
    const isMenuOpen = ref(false)
    let selectedDate = ref()

    const formatarData = (date) => {
        const dataString = date;
        const [ano, mes, dia] = dataString.split('-');
        let formatada = `${dia}/${mes}/${ano}`
        return formatada;
    }// formata a data do date picker para mostrar no front (dd/mm/yyyy)

    let formatarDataBanco = (date) => {
        let data = new Date(date);
        let ano = data.getFullYear();
        let mes = ('0' + (data.getMonth() + 1)).slice(-2);
        let dia = ('0' + data.getDate()).slice(-2);
        let formatada = `${ano}-${mes}-${dia}`
        return formatada;
    };// formata a data do picker para mandar pro banco de dados (yyyy-mm-dd)

    let formated = ref(formatarData(props.pessoa.data_nascimento));// variavel que é exposta no front

    watch(selectedDate, (newValue, oldValue) => {
        isMenuOpen.value = false
        formated = formatarData(formatarDataBanco(selectedDate.value))
        console.log(selectedDate.value);
    })

    // valores do select
    const items = [
        'masculino',
        'feminino',
        'outro'
    ]

    // número só aceita números
    const onlyNumbers = (event) => {
        if(/^[\d\-.]+$/.test(event.key) || event.key == 'Backspace') return
        else event.preventDefault();
    }

    // nome só aceita letras
    const onlyLetras = (event) => {
        if(!/^[A-Za-zÀ-ú\s]+$/.test(event.key)){
            event.preventDefault()
        }
    }
    
    // enviar form
    const form = useForm('put','/pessoa/update', {
        id: props.pessoa.id,
        nome: props.pessoa.nome,
        sexo: props.pessoa.sexo,
        CPF: props.pessoa.CPF,
        data_nascimento: props.pessoa.data_nascimento,
        cidade: props.pessoa.cidade,
        bairro: props.pessoa.bairro,
        rua: props.pessoa.rua,
        numero: props.pessoa.numero,
        complemento: props.pessoa.complemento}
    )

    const submit = () => {
        form.submit({onSuccess: () => {
            if(page.props.flash){
                console.log(page.props.flash)
                toast(`${page.props.flash}`, {
                    "autoClose": 2500,
                    "type": "success",
                    "dangerouslyHTMLString": true,
                    "position": "top-center",
                });// Mensagem de sucesso
            }
        }})
    }

    watch(selectedDate, (newValue, oldValue) => {
        form.data_nascimento = formatarDataBanco(selectedDate.value)
    })

    // formatação do cpf
    const formatarCpf = (event) => {
        if(event.key == 'Backspace'){
            // se for backspace faz nada
        }else if(form.CPF != null && /^[\d]+$/.test(event.key) ){
            // testa de a tecla clicada é um numero/-/.
            if(form.CPF.length == 3 || form.CPF.length == 7){    
            // add o ponto/traço dinamicamente
                form.CPF += '.'
            }else if(form.CPF.length == 11) {
                form.CPF += '-';
            }
        }else if(form.CPF == null && (/^[\d]+$/.test(event.key))){
            return;
        }else{
            // se for alguma letra não escreve
            event.preventDefault()
        }
    }

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

    // fechar modal quando clica fora dele
    // são 3 formatos de data diferente: br(front), en(data base) e string(data picker)
    // botar o preserveScroll na páginação e no número por página

</script>

<template>
    <main-layout paginaAtual="Editar Pessoa" class="w-full">
        <Head title="Editar pessoa"/>
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
                            size="large" rounded="lg" color="#B71C1C" variant="flat"
                            @click="dialog = false;  deletarPessoa(pessoa.id)"
                        >
                            Deletar
                        </v-btn>

                        <v-btn
                            size="large" rounded="lg" color="#0D47A1" variant="tonal"
                            @click="dialog = false"
                        >
                            Cancelar
                        </v-btn>
                    </v-container>
                    </template>
                </v-card>
            </v-dialog>
        <v-app class="w-full mt-4">
            <div class="w-full flex justify-center">
                <v-card elevation="4" class="w-5/6 rounded-lg">
                    <form method="post" @submit.prevent="submit" class="p-8">
                        <v-container class="flex gap-8 justify-between">
                                    
                            <v-text-field
                            class="max-w-lg"
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
                            @input="form.validate('nome')"
                            @mouseout="form.validate('nome')"
                            @keydown="onlyLetras($event)"
                            :error-messages="form.errors.nome"
                            ></v-text-field>
                            


                            <v-text-field
                            class="max-w-md"
                            variant="outlined"
                            rounded="md"
                            v-model="form.CPF"
                            :counter="14"
                            label="CPF"
                            required
                            clearable
                            maxlength="14"
                            @keydown="formatarCpf($event)"
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            id="CPF"
                            @input="form.validate('CPF')"
                            @mouseout="form.validate('CPF')"
                            :error-messages="form.errors.CPF"
                            ></v-text-field>
                            

                        </v-container>
                        <v-container class="flex gap-8">
                            <div class="w-25">
                                <v-menu v-model="isMenuOpen" :close-on-content-click="false">
                                    <template v-slot:activator="{ props }">
                                    <v-text-field
                                        label="Data de nascimento"
                                        :model-value="formated"
                                        readonly
                                        v-bind="props"
                                        variant="outlined"
                                        rounded="md"
                                        base-color="#7CB342"
                                        color="#7CB342"
                                    >
                                    </v-text-field>
                                    </template>
                                    <v-date-picker rounded :max="new Date()" @click.self="isMenuOpen = false" v-model="selectedDate" color="#7CB342"></v-date-picker>
                                </v-menu>
                            </div>
            
                            <v-select
                            v-model="form.sexo"
                            variant="outlined"
                            rounded="md"
                            :items="items"
                            label="Sexo"
                            required
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-select>
                        </v-container>

                        <v-container class="flex gap-8">

                            <v-text-field
                            variant="outlined"
                            rounded="md"
                            v-model="form.cidade"
                            label="Cidade"
                            :counter="255"
                            clearable           
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>

                            <v-text-field
                            variant="outlined"
                            rounded="md"
                            v-model="form.bairro"
                            label="Bairro"
                            :counter="255"
                            clearable           
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>
                        </v-container>

                        <v-container class="flex gap-8 py-0">

                            <v-text-field
                            variant="outlined"
                            rounded="md"
                            v-model="form.rua"
                            label="Rua"
                            :counter="255"
                            clearable           
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>

                            <v-text-field
                            @keydown="onlyNumbers($event)"
                            variant="outlined"
                            rounded="md"
                            v-model="form.numero"
                            label="Número"
                            :counter="255"
                            clearable           
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>

                            <v-text-field
                            variant="outlined"
                            rounded="md"
                            v-model="form.complemento"
                            label="Complemento"
                            :counter="255"
                            clearable           
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>
                        </v-container>

                        <v-container class="flex gap-6 py-0 items-center">
                            <v-btn
                            rounded="md" color="#7CB342" prepend-icon="mdi-content-save-edit-outline" variant="flat" :disabled="!form.isDirty || form.processing || form.hasErrors"  
                            >
                                <button type="submit">
                                    Salvar 
                                    <v-icon v-if="form.processing" icon="mdi-loading" class="animate-spin h-5 w-5 mr-3"></v-icon>
                                </button>
                            </v-btn>
                            <v-btn
                              @click="dialog = true" rounded="md" color="#B71C1C" prepend-icon="mdi-delete-outline" variant="flat"
                            >
                                <button @click.prevent="">Excluir</button>
                            </v-btn>
                             <p v-if="form.isDirty" class="text-sm p-0 m-0 opacity-55">O formulário possui alterações não salvas!</p>
                        </v-container>
                    </form>
                </v-card>
            </div>
        </v-app>
    </main-layout>
</template>
