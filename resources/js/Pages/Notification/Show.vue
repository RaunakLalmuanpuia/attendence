<template>
    <q-page padding class="container">
        <div class="flex justify-between items-center">
            <div class="text-title">
                {{ data?.title }}
            </div>
            <div class="flex q-gutter-sm">
<!--                <q-btn @click="handleDelete" color="negative" label="Delete"/>-->
            </div>
        </div>
        <q-separator class="q-my-sm"/>

        <div class="bg-white q-pa-md">
            <div class="row q-col-gutter-sm">
                <div class="col-xs-12 col-sm-2">Title</div>
                <div class="col-xs-12 col-sm-10">{{data?.title}}</div>
                <div class="col-xs-12 col-sm-2">Message</div>
                <div class="col-xs-12 col-sm-10">{{data?.body}}</div>
                <div class="col-xs-12 col-sm-2">Notification time</div>
                <div class="col-xs-12 col-sm-10">{{formatDate(data?.schedule_at)}}</div>
            </div>
        </div>
        <br/>
        <q-list v-show="data?.attachments?.length>0" separator class="bg-white q-pa-md">
            <q-item v-for="item in data?.attachments" :key="item.id">
                <q-item-section avatar><q-icon name="article"/> </q-item-section>
                <q-item-section>
                    <q-item-label>{{item.name}}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <q-btn target="_blank" :href="item?.full_path" round icon="cloud_download"/>
                </q-item-section>
            </q-item>
        </q-list>

    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {date, useQuasar} from "quasar";
import {router} from "@inertiajs/vue3";


defineOptions({
    layout:MainLayout
})

const props = defineProps(['data']);
const q = useQuasar();
const formatDate = val => date.formatDate(val,'DD/MM/YYYY hh:mm a');

const handleDelete=e=>{
    q.dialog({
        message:'Do you want to delete?',
        ok:'Yes',
        cancel:'No'
    })
    .onOk(()=>{
        router.delete(route('notification.destroy',props.data.id))
    })
}
</script>
