<script setup>
    import { Head, Link, router } from '@inertiajs/vue3';
    import { computed, ref, watch } from 'vue';
    import MainLayout from "../Layouts/MainLayout.vue";

    const props = defineProps({
        pessoa: Object
    })

    let nome = ref(props.pessoa.nome);
    let sexo = ref(props.pessoa.sexo);
    let cpf = ref(props.pessoa.CPF);
    let data_nascimento = ref(props.pessoa.data_nascimento);
    let cidade = ref(props.pessoa.cidade);
    let bairro = ref(props.pessoa.bairro);
    let rua = ref(props.pessoa.rua);
    let numero = ref(props.pessoa.numero);
    let complemento = ref(props.pessoa.complemento);


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
    }// formata a data do date picker

    let formated = ref(formatarData(data_nascimento.value));// variavel que é exposta no front
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

    // formatação do cpf
    const formatarCpf = (event) => {
        if(event.key == 'Backspace'){
            // se for backspace faz nada
        }else if(cpf.value != null && /^[\d\-.]+$/.test(event.key) ){
            // testa de a tecla clicada é um numero/-/.
            if(cpf.value.length == 3 || cpf.value.length == 7){    
            // add o ponto/traço dinamicamente
                cpf.value += '.'
            }else if(cpf.value.length == 11) {
                cpf.value += '-';
            }
        }else if(cpf.value == null && (/^[\d\-.]+$/.test(event.key))){
            return;
        }else{
            // se for alguma letra não escreve
            event.preventDefault()
        }
    }

    // número so aceita números
    const onlyNumbers = (event) => {
        if(/^[\d\-.]+$/.test(event.key) || event.key == 'Backspace') return
        else event.preventDefault();
    }
    // fechar modal quando clica fora dele
</script>

<template>
    <main-layout paginaAtual="Editar Pessoa" class="w-full">
        <Head title="Editar pessoa"/>
        <v-app class="w-full mt-4">
            <div class="w-full flex justify-center">
                <v-card elevation="4" class="w-5/6 rounded-lg">
                    <form @submit.prevent="" class="p-8">
                        <v-container class="flex gap-8">

                            <v-text-field
                            variant="outlined"
                            rounded="md"
                            v-model="nome"
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
                            v-model="cpf"
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
                            v-model="sexo"
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
                            v-model="cidade"
                            label="Cidade"
                            :counter="255"
                            required
                            clearable           
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>

                            <v-text-field
                            variant="outlined"
                            rounded="md"
                            v-model="bairro"
                            label="Bairro"
                            :counter="255"
                            required
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
                            v-model="rua"
                            label="Rua"
                            :counter="255"
                            required
                            clearable           
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>

                            <v-text-field
                            @keydown="onlyNumbers($event)"
                            variant="outlined"
                            rounded="md"
                            v-model="numero"
                            label="Número"
                            :counter="255"
                            required
                            clearable           
                            type="input"
                            base-color="#7CB342"
                            color="#7CB342"
                            ></v-text-field>

                            <v-text-field
                            variant="outlined"
                            rounded="md"
                            v-model="complemento"
                            label="Complemento"
                            :counter="255"
                            required
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
                            Salvar
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
