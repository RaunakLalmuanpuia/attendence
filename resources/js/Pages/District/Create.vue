<template>
    <q-card class="q-pa-md">
        <div class="flex justify-between items-center">
            <div class="text-lg text-weight-medium">New District</div>
            <q-icon size="md" name="o_close" flat round v-close-popup/>
        </div>
        <br/>
        <q-form @submit="handleSubmit" class="column q-gutter-sm">

            <q-input v-model="form.code"
                     autofocus
                     outlined
                     label="Code"
                     :rules="[
                         val=>!!val || 'Code is required'
                     ]"/>
            <q-input v-model="form.name"
                     outlined
                     label="Name"
                     :rules="[
                         val=>!!val || 'Name is required'
                     ]"/>
            <div class="flex justify-end items-center q-gutter-sm">
                <q-btn class="sized-btn" v-close-popup outline color="negative" label="Cancel"/>
                <q-btn class="sized-btn" :loading="state.submitting" type="submit" color="primary" label="Save"/>
            </div>
        </q-form>
    </q-card>
</template>
<script setup>

import {useForm} from "@inertiajs/vue3";
import {reactive} from "vue";

const state=reactive({
    submitting:false
})
const form=useForm({
    code:'',
    name:''
})

const handleSubmit=e=>{
    form.post(route('district.store'),{
        preserveState:false,
        onStart:params => state.submitting=true,
        onFinish: params => state.submitting = false
    })
}
</script>
