<script setup>
    import { usePage } from "@inertiajs/vue3";
    import { useForm } from "laravel-precognition-vue-inertia";
    import { ref } from "vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage();

    const props = defineProps({
        numero_protocolo: Number
    });

    const form = useForm('post', `/anexos/${props.numero_protocolo}`,{
        files: null
    });

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
            form.files = null;
        }})
    }

</script>

<template>
        <v-container class="flex flex-column items-center justify-center py-0 items-center mt-2 relative h-full">
            <h2 class="pt-2">Anexar documentos:</h2>
            <form method="post" @submit.prevent="submit" class="w-full p-5 relative">
                <v-file-input
                    class="w-full"
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
                    @change="form.validate('files'); form.validate('files.0'); form.validate('files.1'); form.validate('files.2'); form.validate('files.3'); form.validate('files.4');form.validateFiles();"
                    @input="form.validate('files');  form.validate('files.0'); form.validate('files.1'); form.validate('files.2'); form.validate('files.3'); form.validate('files.4'); form.validateFiles();"
                    :error-messages="form.errors.files || form.errors['files.0'] || form.errors['files.1'] || form.errors['files.2'] || form.errors['files.3' || form.errors['files.4']]"
                    density="comfortable"
                ></v-file-input>
                <!-- <div class="absolute right-8 top-9 flex items-center mb-4 z-50">
                <v-btn  size="lg" :disabled="form.files == null" variant="text" color="grey" @click="form.files = null; form.validate('files'); form.validate('files.0'); form.validateFiles()"> <v-icon icon="mdi-close-circle"></v-icon> </v-btn>
                </div> -->
                <v-btn 
                class="mt-2"
                rounded="md" 
                block
                color="#7CB342"
                :disabled="!form.isDirty || form.processing || form.hasErrors" 
                type="submit"
                >Anexar</v-btn>
            </form>
        </v-container> 
</template>
