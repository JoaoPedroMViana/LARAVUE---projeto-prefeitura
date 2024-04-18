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
        pessoas_select: Object,
        departamentos_select: Object
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
        pessoa_id: null,
        files: null,
        departamento_id: null,
        situacao: 'A'
    };

    if(props.values != null){
        data = {
            numero: props.values[0].numero,
            descricao: props.values[0].descricao,
            data_registro: props.values[0].data_registro,
            prazo: props.values[0].prazo,
            pessoa_id: props.values[0].pessoa_id,
            files: null,
            departamento_id: props.values[0].departamento_id,
            situacao: props.values[0].situacao
        }
    }


    // Date picker
    const isMenuOpen = ref(false)
    let selectedDate = ref()

    let formated = ref(formatarData(data.data_registro));// variavel que é exposta no front

    watch(selectedDate, (newValue, oldValue) => {
        isMenuOpen.value = false
        formated = formatarData(formatarDataBanco(selectedDate.value))

    })

    // valores do select
    const items = [
        // botar o nome dos usuarios, precisa ver um jeito de botar o id como value
    ]
    const items_departamento = [

    ]
    for(let pessoa of props.pessoas_select){
        items.push({title: `${pessoa.nome}`, value: `${pessoa.id}`})
    }
    for(let departamento of props.departamentos_select){
        items_departamento.push({title: `${departamento.nome}`, value: `${departamento.id}`})
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
            select.value = null;
            form.submit()
        }
    })
   
    // gerenciamento do select
    let select = ref(null)
    let select_departamento = ref(null);
    if(props.values != null){
        select.value = props.values[0].pessoa.nome;
        select_departamento.value = props.values[0].departamento.nome;
    }

    watch(select, () => {
        form.pessoa_id = select.value;
    })
    watch(select_departamento, () => {
        form.departamento_id = select_departamento.value;
    })
    // são 3 formatos de data diferente: br(front), en(data base) e string(data picker)

</script>

<template>
    <form action="post" enctype="multipart/form-data" @submit.prevent="submit" class="p-8 pb-4">
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
            density="comfortable"
            ></v-select>
            
             <v-select
            id="departamento_id"
            v-model="select_departamento"
            variant="outlined"
            rounded="md"
            :items="items_departamento"
            label="Departamento"
            required
            @change="form.validate('departamento_id')"
            @input="form.validate('departamento_id')"
            @mouseout="form.validate('departamento_id')"
            :error-messages="form.errors.departamento_id"
            base-color="#7CB342"
            color="#7CB342"
            density="comfortable"
            ></v-select>

            <v-radio-group
                v-model="form.situacao"
                inline
                density="compact"
                v-if="method == 'put'"
            >
            <v-radio
                label="Aberto"
                value="A"
                color="red"
                class="pr-2"
            ></v-radio>
            <v-radio
                label="Em atendimento"
                value="E"
                color="yellow"
                class="pr-2"
            ></v-radio>
            <v-radio
                label="Solucionado"
                value="S"
                color="green"
            ></v-radio>
            </v-radio-group>

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
            density="comfortable"
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
                             density="comfortable"
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
                density="comfortable"
                ></v-text-field>
            </div>

        </v-container>
        <v-container v-if="props.method == 'post'" class="flex gap-6 py-0 items-center mt-2 relative">
            <v-file-input
                id="files"
                v-model="form.files"
                label="Documentos"
                chips
                base-color="#7CB342"
                color="#7CB342"
                rounded="md"
                variant="outlined"
                counter
                multiple
                show-size
               :clearable="false"
                @change="form.validate('files'); form.validate('files.0'); form.validateFiles();"
                @input="form.validate('files');  form.validate('files.0'); form.validateFiles();"
                :error-messages="form.errors.files || form.errors['files.0']"
                density="comfortable"
            ></v-file-input>
            <div class="absolute right-8 h-full flex items-center mb-4 z-50">
                <v-btn  size="lg" :disabled="form.files == null" variant="text" color="grey" @click="form.files = null; form.validate('files'); form.validate('files.0'); form.validateFiles()"> <v-icon icon="mdi-close-circle"></v-icon> </v-btn>
            </div>
        </v-container>
         
        <v-container class="flex gap-6 py-0 items-center mt-2">
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
