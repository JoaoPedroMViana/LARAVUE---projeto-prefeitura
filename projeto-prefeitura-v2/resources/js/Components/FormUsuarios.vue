<script setup>
    import { Head, Link, usePage} from '@inertiajs/vue3';
    import { useForm } from 'laravel-precognition-vue-inertia';
    import { onMounted, ref, watch } from 'vue';
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage();

    const props = defineProps({
        method: String,
        route: String,
        text_button_submit: String,
    });

    let data = {
        name: null,
        email: null,
        perfil: null,
        cpf: null,
        ativo: 'S',
        password: null,
        password_confirmation: null,
    };

    const form = useForm(props.method, props.route, data);

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
        }});
    };

    // nome só aceita letras
    const onlyLetras = (event) => {
        if(!/^[A-Za-zÀ-ú\s]+$/.test(event.key)){
            event.preventDefault()
        }
    }

     // formatação do cpf
    const formatarCpf = (event) => {
        if(event.key == 'Backspace'){
            // se for backspace faz nada
        }else if(form.cpf != null && /^[\d]+$/.test(event.key) ){
            // testa de a tecla clicada é um numero/-/.
            if(form.cpf.length == 3 || form.cpf.length == 7){    
            // add o ponto/traço dinamicamente
                form.cpf += '.'
            }else if(form.cpf.length == 11) {
                form.cpf += '-';
            }
        }else if(form.cpf == null && (/^[\d]+$/.test(event.key))){
            return;
        }else{
            // se for alguma letra não escreve
            event.preventDefault()
        }
    }

    // Input senha
    let show1 = ref(true);

    // montar o form já com as validações aparecendo
    onMounted(() => {
        if(props.method == 'post'){
                form.submit()
        }
    })

    let upperCase = () => {
        form.perfil = form.perfil.toUpperCase()
    }
</script>

<template>
        <form action="post" @submit.prevent="submit" class="p-8">
        <v-container class=" flex flex-column gap-8 justify-between">          
            <div class="flex gap-8">
                <v-text-field
                variant="outlined"
                rounded="md"
                v-model="form.name"
                :counter="255"
                label="Nome"
                required
                maxlength="255"
                clearable
                type="input"
                base-color="#7CB342"
                color="#7CB342"
                id="name"
                @change="form.validate('name')"
                @mouseout="form.validate('name')"
                @keydown="onlyLetras($event)"
                :error-messages="form.errors.name"
                ></v-text-field>

                <v-text-field
                variant="outlined"
                rounded="md"
                v-model="form.email"
                label="E-mail"
                required
                maxlength="255"
                :counter="255"
                type="input"
                base-color="#7CB342"
                color="#7CB342"
                id="email"
                @change="form.validate('email')"
                @mouseout="form.validate('email')"
                :error-messages="form.errors.email"
                ></v-text-field>
            </div>
            <div class="flex gap-8">
                <v-text-field
                variant="outlined"
                rounded="md"
                v-model="form.cpf"
                :counter="14"
                label="CPF"
                required
                :clearable="method == 'post'"
                maxlength="14"
                @keydown="formatarCpf($event)"
                type="input"
                base-color="#7CB342"
                color="#7CB342"
                id="cpf"
                @change="form.validate('cpf')"
                @mouseout="form.validate('cpf')"
                :error-messages="form.errors.cpf"
                :readonly="method == 'put'"
                ></v-text-field>
                
                <v-text-field
                variant="outlined"
                rounded="md"
                v-model="form.perfil"
                label="Perfil"
                required
                maxlength="1"
                :counter="1"
                type="input"
                base-color="#7CB342"
                color="#7CB342"
                id="perfil"
                @change="form.validate('perfil')"
                @mouseout="form.validate('perfil')"
                @input="upperCase"
                :error-messages="form.errors.perfil"
                ></v-text-field>
            </div>

            <v-text-field
                v-model="form.password"
                variant="outlined"
                rounded="md"
                :append-inner-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
                :type="show1 ? 'text' : 'password'"
                label="Senha"
                id="password"
                counter
                base-color="#7CB342"
                color="#7CB342"
                required
                @click:append-inner="show1 = !show1"
                @change="form.validate('password')"
                @mouseout="form.validate('password')"
                :error-messages="form.errors.password"
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
