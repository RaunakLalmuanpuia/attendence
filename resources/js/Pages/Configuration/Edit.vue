<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">Edit Configuration</div>
            </div>

        </div>
        <br/>
        <q-form class="row q-col-gutter-sm" style="max-width: 720px" @submit="submit">
            <div class="col-xs-12">
                <q-input v-model="form.name"
                         :error="!!form.errors?.name"
                         :error-message="form.errors?.name?.toString()"
                         :rules="[
                             val=>!!val || 'Name is required'
                         ]"
                         bg-color="white"
                         label="Name"
                         no-error-icon
                         outlined
                />
            </div>
            <div class="col-xs-12">
                <q-input v-model="form.code"
                         :error="!!form.errors?.code"
                         :error-message="form.errors?.code?.toString()"
                         :rules="[
                             val=>!!val || 'Data for qr code is required'
                         ]"
                         bg-color="white"
                         label="UID for QR Code"
                         no-error-icon
                         outlined
                />
            </div>
            <div class="col-xs-12">
                <q-select v-model="form.district"
                          :error="!!form.errors?.district_id"
                          :error-message="form.errors?.district_id?.toString()"
                          :options="districts"
                          :rules="[
                             val=>!!val || 'District is required'
                         ]"
                          bg-color="white"
                          label="District"
                          no-error-icon
                          outlined
                />
            </div>
            <div class="col-xs-12 col-sm-6">
                <q-input v-model="form.lat"
                         :error="!!form.errors?.lat"
                         :error-message="form.errors?.lat?.toString()"
                         :rules="[
                             val=>!!val || 'Latitude is required'
                         ]"
                         bg-color="white"
                         label="Latitude"
                         no-error-icon
                         outlined
                />
            </div>
            <div class="col-xs-12 col-sm-6">
                <q-input v-model="form.lng"
                         :error="!!form.errors?.lng"
                         :error-message="form.errors?.lng?.toString()"
                         :rules="[
                             val=>!!val || 'Longtitude is required'
                         ]"
                         bg-color="white"
                         label="Longtitude"
                         no-error-icon
                         outlined
                />
            </div>
            <div class="col-xs-12 col-sm-6">
                <q-input v-model="form.radius"
                         :error="!!form.errors?.radius"
                         :error-message="form.errors?.radius?.toString()"
                         :rules="[
                             val=>!!val || 'Radius is required'
                         ]"
                         bg-color="white"
                         label="Radius in Meter"
                         no-error-icon
                         outlined
                />
            </div>
            <div class="col-xs-12 col-sm-6">
                <q-input v-model="form.grace_period"
                         :error="!!form.errors?.grace_period"
                         :error-message="form.errors?.grace_period?.toString()"
                         :rules="[
                             val=>!!val || 'Grace Period is required'
                         ]"
                         bg-color="white"
                         label="Grace Period in Minute"
                         no-error-icon
                         outlined
                />
            </div>

            <div class="col-xs-12 col-sm-6">
                <q-input v-model="form.start_time"
                         :error="!!form.errors?.start_time"
                         :error-message="form.errors?.start_time?.toString()"
                         :rules="[
                             val=>!!val || 'Starting Time is required'
                         ]"
                         bg-color="white"
                         label="Starting Time"
                         no-error-icon
                         outlined
                         type="time"
                />
            </div>
            <div class="col-xs-12 col-sm-6">
                <q-input v-model="form.close_time"
                         :error="!!form.errors?.close_time"
                         :error-message="form.errors?.close_time?.toString()"
                         :rules="[
                             val=>!!val || 'Closing Time is required'
                         ]"
                         bg-color="white"
                         label="Closing Time"
                         no-error-icon
                         outlined
                         type="time"
                />
            </div>
            <div class="col-xs-12">
                <div class="flex q-gutter-sm">
                    <q-btn :loading="state.submitting" class="sized-btn" color="primary" label="Update" type="submit"/>
                    <q-btn class="sized-btn" color="negative" label="Cancel" outline
                           @click="$inertia.replace(route('office.config.index'))"/>
                </div>
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
const props = defineProps(['districts','data'])
const state = reactive({
    submitting: false
})
const q = useQuasar();
const form = useForm({
    name: props.data?.name,
    code: props.data?.qr_code?.code,
    lat: props?.data?.lat,
    lng: props?.data?.lng,
    radius: props?.data?.radius,
    grace_period: props?.data?.grace_period,
    district: ({value:props?.data?.district_id,label:props?.data?.district?.name}),
    start_time: props?.data?.start_time,
    close_time: props?.data?.close_time,

});
const submit = e => {
    form.transform(data => ({district_id: data?.district?.value, ...data}))
        .put(route('config.update',props.data.id), {
            preserveState: false,
            onStart: params => state.submitting = true,
            onFinish: params => state.submitting = false
        })
}

const handleDelete=e=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to delete ',
        ok:'Yes',
        cancel:'No'
    }).onOk(()=>{
        router.delete(route('office.destroy',props.data.id),{
            preserveState:false,
            onStart:params => state.submitting=true,
            onFinish: params => state.submitting = false
        })
    })
}

</script>
