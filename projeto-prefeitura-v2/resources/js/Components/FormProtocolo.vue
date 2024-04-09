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
        clear: Boolean,
        pessoas_select: Object
    })

    // formatar datas
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

    //  Definindo conteúdo
    let data = {
        descricao: null,
        data_registro: formatarDataBanco(new Date()),
        prazo: null,
        pessoa_id: null
    };

    if(props.values != null){
        data = {
            numero: props.values[0].numero,
            descricao: props.values[0].descricao,
            data_registro: props.values[0].data_registro,
            prazo: props.values[0].prazo,
            pessoa_id: props.values[0].pessoa_id
        }
    }


    // Date picker
    const isMenuOpen = ref(false)
    let selectedDate = ref()

    let formated = ref(formatarData(data.data_registro));// variavel que é exposta no front

    watch(selectedDate, (newValue, oldValue) => {
        isMenuOpen.value = false
        formated = formatarData(formatarDataBanco(selectedDate.value))
        console.log(selectedDate.value);
    })

    // valores do select
    const items = [
        // botar o nome dos usuarios, precisa ver um jeito de botar o id como value
    ]
    for(let pessoa of props.pessoas_select){
        items.push({title: `${pessoa.nome}`, value: `${pessoa.id}`})
    }


    // número só aceita números
    const onlyNumbers = (event) => {
        if(/^[\d\-.]+$/.test(event.key) || event.key == 'Backspace') return
        else event.preventDefault();
    }

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
                formated = formatarData(formatarDataBanco(new Date()))
                form.submit()
            }
        }})
    }

    watch(selectedDate, (newValue, oldValue) => {
        form.data_registro = formatarDataBanco(selectedDate.value)
    })


    // montar o form já com as validações aparecendo
    onMounted(() => {
        if(props.method == 'post'){
                form.submit()
        }
    })

    // Reset form
    let limparForm = computed(() => {
        return props.clear
    })

    watch(limparForm, () => {
        if(form.isDirty || form.processing){
            form.reset()
            formated = formatarData(formatarDataBanco(new Date()))
            form.submit()
        }
    })
   
    // gerenciamento do select
    let select = ref()
    if(props.values != null){
        select.value = props.values[0].pessoa.nome;
    }

    watch(select, () => {
        form.pessoa_id = select.value;
    })
    // são 3 formatos de data diferente: br(front), en(data base) e string(data picker)

    // v-model aponta pra uma variavel
    // data continua do jeito que ta
    // quando essa variavel tiver alguma alteração o form.pessoa_id recebe esse valor
</script>

<template>
    <form action="post" @submit.prevent="submit" class="p-8">
        <v-container class="py-0 flex flex-col gap-4">
            <v-select
            id="pessoa_id"
            v-model="select"
            variant="outlined"
            rounded="md"
            :items="items"
            label="Contruibuinte"
            required
            @change="form.validate('pessoa_id')"
            @input="form.validate('pessoa_id')"
            @mouseout="form.validate('pessoa_id')"
            :error-messages="form.errors.pessoa_id"
            base-color="#7CB342"
            color="#7CB342"

            ></v-select>

            <v-textarea
            class="w-full"
            variant="outlined"
            rounded="md"
            v-model="form.descricao"
            :counter="2000"
            label="Descrição"
            required
            maxlength="2000"
            clearable
            base-color="#7CB342"
            color="#7CB342"
            id="descricao"
            no-resize
            auto-grow
            @change="form.validate('descricao')"
            @mouseout="form.validate('descricao')"
            :error-messages="form.errors.descricao"
            ></v-textarea>      

            <div class="flex gap-8">
                <div class="w-25">
                    <v-menu v-model="isMenuOpen" :close-on-content-click="false">
                        <template v-slot:activator="{ props }">
                        <v-text-field
                            id="data_registro"
                            label="Data de registro"
                            :model-value="formated"
                            type="input"
                            readonly
                            v-bind="props"
                            variant="outlined"
                            rounded="md"
                            base-color="#7CB342"
                            color="#7CB342"
                            required
                            @change="form.validate('data_registro')"
                            @mouseout="form.validate('data_registro')"   
                            :error-messages="form.errors.data_registro"
                        >
                        </v-text-field>
                        </template>
                        <v-date-picker rounded :max="new Date()" @click.self="isMenuOpen = false" @click="form.validate('data_registro')" v-model="selectedDate" color="#7CB342"></v-date-picker>
                    </v-menu>
                </div>
                
                <v-text-field
                @keydown="onlyNumbers($event)"
                variant="outlined"
                rounded="md"
                v-model="form.prazo"
                label="Prazo (em dias)"
                :counter="2"
                maxlength="2"
                clearable           
                type="input"
                base-color="#7CB342"
                color="#7CB342"
                required
                @change="form.validate('prazo')"
                @mouseout="form.validate('prazo')"   
                :error-messages="form.errors.prazo"
                ></v-text-field>
            </div>

        </v-container>

        <v-container class="flex gap-6 py-0 items-center mt-6">
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