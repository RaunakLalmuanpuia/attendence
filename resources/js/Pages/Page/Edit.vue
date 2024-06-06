<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div class="text-title">Edit Page</div>
            <q-btn @click="handleDelete" color="negative" outline label="Delete"/>
        </div>
        <q-separator class="q-my-sm"/>
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
                <q-btn :loading="submitting" type="submit" color="primary" label="Update"/>
                <q-btn @click="$inertia.replace(route('page.index'))" color="negative" outline label="Cancel"/>
            </div>
        </q-form>
    </q-page>

</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {router, useForm} from '@inertiajs/vue3';
import {ref} from "vue";
import {useQuasar} from "quasar";

defineOptions({
    layout:MainLayout
})
const props = defineProps(['options','data']);
const q = useQuasar();
const submitting=ref(false)
const form=useForm({
    title:props?.data.title,
    type:props?.data?.type,
    content:props?.data?.content
})
const submit=e=>{
    form.put(route('page.update',props.data.id),{
        onStart:params => submitting.value=true,
        onFinish: params => submitting.value = false
    });
}

const handleDelete=e=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to delete?',
        ok:'Yes',
        cancel:'No'
    }).onOk(()=>{
        router.delete(route('page.destroy', props.data.id));
    })
}

</script>
