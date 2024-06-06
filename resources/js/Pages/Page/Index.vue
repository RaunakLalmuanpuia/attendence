<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div class="text-title">Pages</div>
            <q-btn @click="$inertia.get(route('page.create'))" color="primary" label="New Page" no-caps/>
        </div>
        <br/>
        <q-list>
            <q-item @click="$inertia.get(route('page.edit',item.id))" class="q-mt-sm shadow-default bg-white" clickable v-for="item in list" :key="item.id">
                <q-item-section avatar>
                    <q-icon name="o_description"/>
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{item?.title}}</q-item-label>
                    <q-item-label>{{formatDate(item?.created_at)}}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <div class="flex q-gutter-sm">
                        <q-chip square :label="item?.type"/>
                        <q-icon size="28px" name="chevron_right"/>
                    </div>
                </q-item-section>
            </q-item>
        </q-list>
    </q-page>

</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {date} from 'quasar';

defineOptions({
    layout:MainLayout
})
const formatDate=val=>date.formatDate(val,'DD-MM-YYYY h:m a')
const props=defineProps(['list'])
</script>
