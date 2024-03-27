<script setup>
    import { Link } from "@inertiajs/vue3";
     
     const props = defineProps({
        selectedClass: String, 
        pag: String,
        nome: String,
        route: String
     });
</script>

<template>
    <div class="w-full">
            <Link as="button" :disabled="pag == nome" :href="route" :class="selectedClass">
                <slot></slot>
            </Link>
    </div>
</template>