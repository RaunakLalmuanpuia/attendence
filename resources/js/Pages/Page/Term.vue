<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div class="text-title">Privacy Policy</div>
        </div>
        <br/>
        <div v-html="props.data.content"/>
    </q-page>

</template>
<script setup>
import MainLayout from "@/Layouts/FrontLayout.vue";

defineOptions({
    layout:MainLayout
})
const props=defineProps(['data'])
</script>
