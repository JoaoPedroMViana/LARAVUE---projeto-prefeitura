<script setup>

    import { usePage } from "@inertiajs/vue3";
    import { useForm } from "laravel-precognition-vue-inertia";
    import { ref } from "vue";
    import { toast } from 'vue3-toastify';
    import 'vue3-toastify/dist/index.css';

    const page = usePage();

    const props = defineProps({
        user_id: Number,
        user_ativo: String,
    });

    let data = {
        id: props.user_id,
        password: '',
    }

    const form = useForm('put', '/user/mudar_senha', data);

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
        }})
    }

    let show1 = ref(false);
</script>

<template>
    <div class="px-10 py-8">
        <v-text-field
            v-model="form.password"
            variant="outlined"
            rounded="md"
            :append-inner-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :type="show1 ? 'text' : 'password'"
            label="Senha"
            id="password"
            counter
            :readonly="user_ativo =='N'"
            base-color="#7CB342"
            color="#7CB342"
            required
            @click:append-inner="show1 = !show1"
            @change="form.validate('password')"
            @mouseout="form.validate('password')"
            :error-messages="form.errors.password"
        ></v-text-field>
        <v-btn
            rounded="md" 
            block
            color="#7CB342"
            :disabled="!form.isDirty || form.processing || form.hasErrors" 
            :readonly="user_ativo == 'N'"
            @click.prevent="submit"
            ><v-icon icon="mdi-swap-horizontal"></v-icon>Mudar senha
        </v-btn>
    </div>
</template>
