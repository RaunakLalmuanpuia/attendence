<template>
    <q-page padding class="container">
        <div class="flex justify-between">
            <div>
                <div class="text-title">Change Office</div>
            </div>
        </div>
        <br/>
        <div class="row q-col-gutter-sm">
            <div class="col-xs-12 col-sm-7">
                <q-form @submit="onSubmit">
                    <q-stepper
                        flat
                        v-model="state.step"
                        vertical
                        color="primary"
                        animated
                    >
                        <q-step
                            :name="1"
                            title="Your Current Office"
                            icon="settings"
                            :done="state.step > 1">

                           <p class="text-grey-7"> Name of the office <q-chip square :label="office?.name"/></p>
                            <q-stepper-navigation>
                                <q-btn @click="state.step = 2" color="primary" label="Next" />
                            </q-stepper-navigation>
                        </q-step>
                        <q-step
                            :name="2"
                            title="Your New Office"
                            icon="building"
                            :done="state.step > 2">
                            <q-select v-model="form.office"
                                      use-input
                                      clearable
                                      input-debounce="500"
                                      @filter="filterFn"
                                      :options="state.options"
                                      outlined
                                      :rules="[
                                      val=>!!val || 'Please select your new office'
                                  ]"
                                      label="Select your new office"
                            />
                            <q-checkbox class="text-grey-7" v-if="!!form.office" v-model="form.agree"  :label="'I agree that my current office: '+office?.name
                            + ' will be changed to : '+form?.office?.label + ' based on approval'"/>
                            <div class="flex q-gutter-sm">

                            </div>
                            <q-stepper-navigation class="flex q-gutter-sm q-mt-md">
                                <q-btn type="submit" :disable="!form.agree" label="Submit" color="primary"/>
                                <q-btn @click="state.step=1" label="Back" color="negative"/>
                            </q-stepper-navigation>

                        </q-step>
                    </q-stepper>
                </q-form>

            </div>
            <div class="col-xs-12 col-sm-5">
                <div class="q-pa-md bg-white">
                    <div class="text-lg text-dark">Previous Offices</div>
                    <q-list>
                        <q-item v-for="(posting,index) in postings" :key="index">
                            <q-item-section avatar>
                                <q-avatar size="md" color="secondary" text-color="white">{{posting?.office?.name[0]}}</q-avatar>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{posting?.office?.name}}</q-item-label>
                                <q-item-label caption> {{posting?.status}}</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item>
                            <q-item-section avatar>
                                <q-avatar size="md" color="primary" text-color="white">{{office?.name[0]}}</q-avatar>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{office?.name}}</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </div>
            </div>
        </div>
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
const props=defineProps(['offices','office','postings'])
const q = useQuasar();
const state=reactive({
    step:1,
    options: props.offices || []
})
const form=useForm({
    office:null,
    agree:false
})

const  filterFn= (val, update)=>{
    if (val) {
        const result=props.offices?.filter(item => item.label.includes(val));
        if (result) {
            update(()=>{
                state.options = result;
            })
        }else{
            update(()=>{
                state.options = props.offices;
            })
        }
    }else{
        update(()=>{
            state.options = props.offices;
        })
    }

}
const onSubmit=e=>{
    form.transform(data=>({office_id:data.office.value}))
        .post(route('posting.store'),{
            preserveState:false,
        onStart:params => q.loading.show(),
        onFinish:params => q.loading.hide(),
            onSuccess:params => state.step=1
    })
}
</script>
