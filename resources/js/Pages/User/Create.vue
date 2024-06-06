<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">New User</div>
            </div>
        </div>
        <br/>
        <q-form class="row q-col-gutter-sm" style="max-width: 720px" @submit="submit">
            <div class="col-xs-12">
                <q-input v-model="form.full_name"
                         :error="!!form.errors?.full_name"
                         :error-message="form.errors?.full_name?.toString()"
                         :rules="[
                             val=>!!val || 'Full Name is required'
                         ]"
                         bg-color="white"
                         label="Name"
                         no-error-icon
                         outlined
                />
            </div>
            <div class="col-xs-12">
                <q-input v-model="form.designation"
                         :error="!!form.errors?.designation"
                         :error-message="form.errors?.designation?.toString()"
                         bg-color="white"
                         label="Designation"
                         no-error-icon
                         outlined
                />
            </div>

            <div class="col-xs-12">
                <q-input v-model="form.mobile"
                         :error="!!form.errors?.mobile"
                         :error-message="form.errors?.mobile?.toString()"
                         :rules="[
                             val=>!!val || 'Mobile is required'
                         ]"
                         mask="##########"
                         bg-color="white"
                         label="Mobile"
                         no-error-icon
                         outlined
                />
            </div>
            <div class="col-xs-12">
                <q-select v-model="form.role"
                          :error="!!form.errors?.role"
                          :error-message="form.errors?.role?.toString()"
                          :options="roles"

                          bg-color="white"
                          label="Role"
                          no-error-icon
                          outlined
                />
            </div>
            <div class="col-xs-12">
                <q-select v-model="form.office"
                          :error="!!form.errors?.office_id"
                          :error-message="form.errors?.office_id?.toString()"
                          :options="offices"
                          :rules="[
                             val=>!!val || 'Office is required'
                         ]"
                          bg-color="white"
                          label="Office"
                          no-error-icon
                          outlined
                />
            </div>

            <div class="col-xs-12">
                <q-input v-model="form.password"
                         :type="state.toggle"
                         :error="!!form.errors?.password"
                         :error-message="form.errors?.password?.toString()"
                         :rules="[
                             val=>!!val || 'Password is required'
                         ]"
                         bg-color="white"
                         label="Password"
                         no-error-icon
                         outlined
                >
                    <template #append>
                        <q-btn @click="handleToggle" v-if="state.toggle==='password'" flat round icon="o_visibility_off"/>
                        <q-btn @click="handleToggle" v-else flat round icon="o_visibility"/>
                    </template>
                </q-input>
            </div>


            <div class="col-xs-12">
                <div class="flex q-gutter-sm">
                    <q-btn :loading="state.submitting" class="sized-btn" color="primary" label="Save" type="submit"/>
                    <q-btn class="sized-btn" color="negative" label="Cancel" outline
                           @click="$inertia.replace(route('user.index'))"/>
                </div>
            </div>

        </q-form>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {reactive} from "vue";

defineOptions({
    layout: MainLayout
})
const props = defineProps(['roles','offices'])
const state = reactive({
    submitting: false,
    toggle:'password'
})
const form = useForm({
    full_name: '',
    designation: '',
    mobile:'',
    password:'',
    role:null,
    office:null
});

const handleToggle=val=>state.toggle=state.toggle==='password'?'text':'password'
const submit = e => {
    form.transform(data => ({office_id: data?.office?.value, ...data}))
        .post(route('user.store'), {
            onStart: params => state.submitting = true,
            onFinish: params => state.submitting = false
        })
}

</script>
