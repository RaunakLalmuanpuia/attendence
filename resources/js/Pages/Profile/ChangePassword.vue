<template>
    <q-page padding class="container-sm">
        <div class="flex justify-between">
            <div>
                <div class="text-title">Change Password</div>
            </div>
        </div>
        <br/>
        <q-form @submit="onSubmit">
            <q-input v-model="form.password"
                     type="password"
                     outlined
                     :error="!!form?.errors?.password"
                     :error-message="form?.errors?.password"
                     bg-color="white"
                     label="Old Password"
                     :rules="[
                         val=>!!val || 'New password is required'
                     ]"
            />
            <q-input v-model="form.new_password"
                     :type="type"
                     outlined
                     bg-color="white"
                     label="New Password"
                     :rules="[
                         val=>!!val || 'New Password is required',
                         val=>val.length>=6 || 'Minimum character of password is 6'
                     ]">
                <template #append>
                    <q-btn v-if="type==='password'" @click="type='text'" round icon="visibility_off"/>
                    <q-btn v-else @click="type='password'" round icon="visibility"/>
                </template>
            </q-input>
            <q-input v-model="form.confirm_password"
                     :type="type"
                     outlined
                     bg-color="white"
                     label="Confirm New Password"
                     :rules="[
                         val=>form.new_password===val || 'Password must be same'
                     ]"/>
            <div class="flex q-gutter-sm">
                <q-btn class="sized-btn" type="submit" color="primary" label="Save"/>
                <q-btn class="sized-btn" @click="$inertia.get(route('dashboard'))" color="negative" outline label="Cancel"/>
            </div>
        </q-form>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {router, useForm} from "@inertiajs/vue3";
import {reactive, ref} from "vue";
import {useQuasar} from "quasar";

defineOptions({
    layout: MainLayout
})
const q = useQuasar();
const type=ref('password')
const form=useForm({
    password:'',
    new_password:'',
    confirm_password:''
})
const onSubmit=e=>{
    form.put(route('profile.update-password'),{
        onStart:params => q.loading.show(),
        onFinish:params => q.loading.hide(),
        onSuccess: params => q.notify({message:'Password changed successfully. Please login again'})
    })
}
</script>
