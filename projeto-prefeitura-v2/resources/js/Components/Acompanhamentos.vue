<script setup>
    const props = defineProps({
        acompanhamentos: Object
    });

    const formatarData = (date) => {
        if(date == null) return null
        const dataString = date;
        const [ano, mes, dia] = dataString.split('-');
        let formatada = `${dia}/${mes}/${ano}`
        return formatada;
    }
</script>

<template>
    <section>
        <div v-if="props.acompanhamentos.length != 0" class="flex flex-column gap-4 w-full items-center">
            <v-card elevation="4" class="w-5/6 rounded-lg mt-2 mb-2 flex flex-column gap-2" v-for="acompanhamento in props.acompanhamentos" :key="acompanhamento.id">
                <h2 class="p-8 pb-4 text-lime-600">Acompanhamento</h2>
                <div class="p-8 pb-4 pt-4">
                    <h2 class="pb-2 text-lime-800">Descrição:</h2>
                    <p>{{acompanhamento.descricao}}</p>
                </div>
                <p class="p-8 pb-4 pt-4 opacity-55">{{formatarData(acompanhamento.data)}}</p>
            </v-card>
        </div>
        <div v-else class="flex flex-column gap-4 w-full items-center">
            <v-card elevation="4" class="w-5/6 rounded-lg mt-2 mb-2 flex flex-column gap-2">
                <h2 class="p-8 flex items-center gap-2 text-grey"><v-icon icon="mdi-information"></v-icon> O protocolo não possui acompanhamentos</h2>
            </v-card>
        </div>
    </section>
</template>
