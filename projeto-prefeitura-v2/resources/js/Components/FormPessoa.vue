<script setup>
    import { useForm } from 'laravel-precognition-vue-inertia';
    import { router, usePage} from '@inertiajs/vue3';
    import { computed, onMounted, ref, watch } from 'vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage()

    const props = defineProps({
        values: Object,
        method: String,
        route: String,
        text_button_submit: String,
    })

    // Mascara cpf
    const mascaraCpf = (cpf) => {
        if(cpf == null) return '';
        cpf = cpf.replace(/\D/g, '');
        cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
        return cpf
    }

    //  Definindo conteúdo 
    let data = {
        nome: null,
        sexo: null,
        CPF: null,
        data_nascimento: null,
        cidade: null,
        bairro: null,
        rua: null,
        numero: null,
        complemento: null,
    };

    if(props.values != null){
        data = {
            id: props.values.id,
            nome: props.values.nome,
            sexo: props.values.sexo,
            CPF: props.values.CPF,
            data_nascimento: props.values.data_nascimento,
            cidade: props.values.cidade,
            bairro: props.values.bairro,
            rua: props.values.rua,
            numero: props.values.numero,
            complemento: props.values.complemento
        }
    }

    // Date picker
    const isMenuOpen = ref(false)
    let selectedDate = ref()

    const formatarData = (date) => {
        if(date == null) return null
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

    let formated = ref(formatarData(data.data_nascimento));// variavel que é exposta no front

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

    const form = useForm(props.method, props.route, data);

    // cpf
    let showerCpf = ref(mascaraCpf(form.CPF));

    // formatação do cpf
    const formatarCpf = (event) => {
        if(event.key == 'Backspace' || event.key == 'v' && event.ctrlKey == true){
            // se for backspace faz nada
        }else if(showerCpf.value != null && /^[\d]+$/.test(event.key) ){
            // testa de a tecla clicada é um numero/-/.
            if(showerCpf.value.length == 3 || showerCpf.value.length == 7){    
            // add o ponto/traço dinamicamente
                showerCpf.value += '.'
            }else if(showerCpf.value.length == 11) {
                showerCpf.value += '-';
            }
        }else if(showerCpf.value == null && (/^[\d]+$/.test(event.key))){
            return;
        }else{
            // se for alguma letra não escreve
            event.preventDefault()
        }
    }

    watch(showerCpf, () => {
        if(showerCpf.value == null) return '';
        form.CPF = showerCpf.value;
        form.CPF = form.CPF.replace(/\D/g, '');
        console.log(form.CPF);
    })

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
            if(props.method == 'post'){
                form.reset();
                formated = null
                form.submit()
            }
        }})
    }

    watch(selectedDate, (newValue, oldValue) => {
        form.data_nascimento = formatarDataBanco(selectedDate.value)
    })


    // montar o form já com as validações aparecendo
    onMounted(() => {
        if(props.method == 'post'){
                form.submit()
        } 
    })

    // são 3 formatos de data diferente: br(front), en(data base) e string(data picker)
</script>

<template>
    <form @submit.prevent="submit" class="p-8">
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
            @change="form.validate('nome')"
            @mouseout="form.validate('nome')"
            @keydown="onlyLetras($event)"
            :error-messages="form.errors.nome"
            ></v-text-field>

            <v-text-field
            class="max-w-md"
            variant="outlined"
            rounded="md"
            v-model="showerCpf"
            :counter="14"
            label="CPF"
            required
            :clearable="method == 'post'"
            maxlength="14"
            @keydown="formatarCpf($event)"
            type="input"
            base-color="#7CB342"
            color="#7CB342"
            id="CPF"
            @change="form.validate('CPF')"
            @mouseout="form.validate('CPF')"
            :error-messages="form.errors.CPF"
            :readonly="method == 'put'"
            ></v-text-field>
            

        </v-container>
        <v-container class="flex gap-8">
            <div class="w-25">
                <v-menu v-model="isMenuOpen" :close-on-content-click="false">
                    <template v-slot:activator="{ props }">
                    <v-text-field
                        id="data_nascimento"
                        label="Data de nascimento"
                        :model-value="formated"
                        type="input"
                        readonly
                        v-bind="props"
                        variant="outlined"
                        rounded="md"
                        base-color="#7CB342"
                        color="#7CB342"
                        required
                        @change="form.validate('data_nascimento')"
                        @mouseout="form.validate('data_nascimento')"   
                        :error-messages="form.errors.data_nascimento"
                    >
                    </v-text-field>
                    </template>
                    <v-date-picker rounded :max="new Date()" @click.self="isMenuOpen = false" @click="form.validate('data_nascimento')" v-model="selectedDate" color="#7CB342"></v-date-picker>
                </v-menu>
            </div>

            <v-select
            id="sexo"
            v-model="form.sexo"
            variant="outlined"
            rounded="md"
            :items="items"
            label="Sexo"
            required
            @input="form.validate('sexo')"
            @mouseout="form.validate('sexo')"
            :error-messages="form.errors.sexo"
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
            rounded="md" color="#7CB342" prepend-icon="mdi-content-save-edit-outline" variant="flat" :disabled="!form.isDirty || form.processing || form.hasErrors" type="submit"
            >
                    {{text_button_submit}}
                    <v-icon v-if="form.processing" icon="mdi-loading" class="animate-spin h-5 w-5 mr-3"></v-icon>
            </v-btn>
            <slot></slot>
                <p v-if="form.isDirty && method == 'put'" class="text-sm p-0 m-0 opacity-55">O formulário possui alterações não salvas!</p>
        </v-container>
    </form>
</template>
