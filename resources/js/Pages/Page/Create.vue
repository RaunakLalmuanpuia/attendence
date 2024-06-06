<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div class="text-title">New Page</div>
        </div>
        <br/>
        <q-form @submit="submit">
            <q-input v-model="form.title"
                     outlined
                     bg-color="white"
                     label="Title"
                     :rules="[
                         val=>!!val || 'Title is required'
                     ]"
            />
            <q-select v-model="form.type"
                     :options="options"
                      outlined
                      bg-color="white"
                      label="Type"
                      :rules="[
                          val=>!!val || 'Type is required'
                      ]"/>
            <q-input v-model="form.content"
                     outlined
                     bg-color="white"
                     label="Content"
                     :rules="[
                          val=>!!val || 'Content is required'
                      ]"/>

            <div class="flex q-gutter-sm q-mt-sm">
                <q-btn :loading="submitting" type="submit" color="primary" label="Save"/>
                <q-btn @click="$inertia.replace(route('page.index'))" color="negative" outline label="Cancel"/>
            </div>
        </q-form>
    </q-page>

</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {useForm} from '@inertiajs/vue3';
import {ref} from "vue";

defineOptions({
    layout:MainLayout
})
const props = defineProps(['options']);

const submitting=ref(false)
const form=useForm({
    title:'',
    type:'',
    content:''
})
const submit=e=>{
    form.post(route('page.store'),{
        onStart:params => submitting.value=true,
        onFinish: params => submitting.value = false
    });
}

</script>
