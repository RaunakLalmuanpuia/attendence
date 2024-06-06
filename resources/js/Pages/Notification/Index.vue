<template>
    <q-page padding class="container">
        <div class="flex justify-between items-center">
            <div class="text-title">
                Notifications
            </div>
            <div class="flex q-gutter-sm">
                <q-btn @click="$inertia.get(route('notification.create'))" color="primary" label="New Notification"/>
            </div>
        </div>
        <q-separator class="q-my-sm"/>
        <q-list>
            <q-item clickable
                    class="q-mt-sm shadow-default bg-white q-pa-sm flex justify-between items-center"
                 v-for="item in list.data"
                    :key="item.id"
                    @click="$inertia.get(route('notification.show',item.id))">
                <q-item-section>
                    <q-item-label>{{item.title}}</q-item-label>
                    <q-item-label caption>{{formatDateTime(item.created_at)}}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <div class="flex q-gutter-sm items-center">
                        <q-chip square :label="formatDateTime(item?.schedule_at)"/>
                        <q-icon size="18px" name="chevron_right"/>

                    </div>
                </q-item-section>
            </q-item>

            <div class="flex q-gutter-sm">
                <q-btn @click="$inertia.get(list.prev_page_url)" :disable="!Boolean(list?.prev_page_url)" round flat icon="o_chevron_left"/>
                <q-btn @click="$inertia.get(list.next_page_url)" :disable="!Boolean(list?.next_page_url)" round flat icon="o_chevron_right"/>
            </div>
        </q-list>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {date} from "quasar";
defineOptions({
    layout:MainLayout
})

const props = defineProps(['list']);

const formatDateTime=(val)=>date.formatDate(val,'D/M/YYYY hh:mm a')
</script>
