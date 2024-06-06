<template>
    <q-page padding class="container-sm">
        <div class="flex justify-between">
            <div>
                <div class="text-title">Profile</div>
            </div>
        </div>
        <br/>
        <q-form @submit="submit">
            <q-input v-model="form.full_name"
                     outlined
                     label="Full Name"
                     bg-color="white"
                     :error="!!form.errors?.full_name"
                     :error-message="form.errors?.full_name?.toString()"
                     :rules="[
                         val=>!!val || 'Full Name is required'
                     ]"
                     />

            <q-input v-model="form.designation"
                     outlined
                     label="Destination"
                     bg-color="white"
                     :error="!!form.errors?.designation"
                     :error-message="form.errors?.designation?.toString()"
                     />

            <q-input v-model="form.mobile"
                     outlined
                     mask="##########"
                     label="Mobile"
                     bg-color="white"
                     :error="!!form.errors?.mobile"
                     :error-message="form.errors?.mobile?.toString()"
                     :rules="[
                         val=>!!val || 'Mobile is required',
                         val=>val.length===10 || 'Invalid Mobile No',
                     ]"
                     />
            <div class="flex q-gutter-sm">
                <q-btn type="submit" color="primary" label="Update"/>
                <q-btn color="negative" outline label="Cancel" @click="$inertia.replace(route('dashboard'))"/>
            </div>
        </q-form>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {router, useForm} from "@inertiajs/vue3";
import {reactive} from "vue";
import {useQuasar} from "quasar";

defineOptions({
    layout: MainLayout
})
const props = defineProps(['data']);
const q = useQuasar();
const form=useForm({
    full_name:props.data.full_name,
    designation:props.data.designation,
    mobile:props.data.mobile,

})
const submit=e=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to update?',
        ok:'Yes',
        cancel:'No'
    }).onOk(()=>{
        form.put(route('profile.update'));
    })
}

</script>
