<script setup>
    import { usePage } from "@inertiajs/vue3";
    import { useForm } from "laravel-precognition-vue-inertia";
    import { onMounted, ref } from "vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage();

    const props = defineProps({
        protocolo_id: Number,
    });

    let formatarDataBanco = (date) => {
        let data = new Date(date);
        let ano = data.getFullYear();
        let mes = ('0' + (data.getMonth() + 1)).slice(-2);
        let dia = ('0' + data.getDate()).slice(-2);
        let formatada = `${ano}-${mes}-${dia}`
        return formatada;
    };

    let data = {
        protocolo_id: props.protocolo_id,
        descricao: null,
        situacao: null,
        data_registro: formatarDataBanco(new Date())
    }

    const form = useForm('post','/acompanhamento/store', data);

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
            form.reset();
            form.submit()
            }});
    }

    onMounted(() => {
        form.submit()
    })
</script>

<template>
    <form @submit.prevent="submit" class="p-8">
        <v-container class="flex flex-column gap-8 justify-between">
            <v-textarea
                variant="outlined"
                rounded="md"
                v-model="form.descricao"
                :counter="2000"
                label="Descrição"
                required
                maxlength="2000"
                clearable
                no-resize
                auto-grow
                base-color="#7CB342"
                color="#7CB342"
                id="descricao"
                @change="form.validate('descricao')"
                @mouseout="form.validate('descricao')"
                :error-messages="form.errors.descricao"
            ></v-textarea>
            <v-radio-group
                v-model="form.situacao"
                inline
                required
                @change="form.validate('situacao')"
                :error-messages="form.errors.situacao"
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
            <v-btn
            rounded="md" color="#7CB342" prepend-icon="mdi-content-save-outline" variant="flat" :disabled="!form.isDirty || form.processing || form.hasErrors" type="submit"
            >
                    Adicionar
                    <v-icon v-if="form.processing" icon="mdi-loading" class="animate-spin h-5 w-5 mr-3"></v-icon>
            </v-btn>
        </v-container>
    </form>
</template>
