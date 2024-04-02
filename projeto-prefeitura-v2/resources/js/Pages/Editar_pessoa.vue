<script setup>
    import { Head, Link, router, useForm } from '@inertiajs/vue3';
    import { computed, ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";

    const props = defineProps({
        pessoa: Object
    })

    // Date picker
    const isMenuOpen = ref(false)
    let selectedDate = ref()

    const formatarData = (date) => {
        let data = new Date(date);
        console.log(data.getDay());
        let ano = data.getFullYear();
        let mes = ('0' + (data.getMonth() + 1)).slice(-2);
        let dia = ('0' + data.getDate()).slice(-2);
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
        formated = formatarData(selectedDate.value)
    })

    // valores do select
    const items = [
        'masculino',
        'feminino',
        'outro'
    ]

    // número so aceita números
    const onlyNumbers = (event) => {
        if(/^[\d\-.]+$/.test(event.key) || event.key == 'Backspace') return
        else event.preventDefault();
    }
    
    // enviar form
    const form = useForm({
        id: props.pessoa.id,
        nome: props.pessoa.nome,
        sexo: props.pessoa.sexo,
        CPF: props.pessoa.CPF,
        data_nascimento: props.pessoa.data_nascimento,
        cidade: props.pessoa.cidade,
        bairro: props.pessoa.bairro,
        rua: props.pessoa.rua,
        numero: props.pessoa.numero,
        complemento: props.pessoa.complemento,
    })

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

    // fechar modal quando clica fora dele
    // botar a data no formato br na tabela de pessoas
    // são 3 formatos de data diferente: br(front), en(data base) e string(data picker)
</script>

<template>
    <main-layout paginaAtual="Editar Pessoa" class="w-full">
        <Head title="Editar pessoa"/>
        <v-app class="w-full mt-4">
            {{pessoa.data_nascimento}}
            <div class="w-full flex justify-center">
                <v-card elevation="4" class="w-5/6 rounded-lg">
                    <form method="post" @submit.prevent="form.submit('put', '/pessoa/update')" class="p-8">
                        <v-container class="flex gap-8">

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
                            ></v-text-field>

                            <v-text-field
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

                        <v-container class="flex gap-6 py-0">
                            <v-btn
                            rounded="md" color="#7CB342" prepend-icon="mdi-content-save-edit-outline" variant="flat"  
                            >
                                <input type="submit" value="Salvar">
                            </v-btn>
                            <v-btn
                             rounded="md" color="#B71C1C" prepend-icon="mdi-delete-outline" variant="flat"
                            >
                            Apagar
                            </v-btn>
                        </v-container>
                    </form>
                </v-card>
            </div>
        </v-app>
    </main-layout>
</template>
