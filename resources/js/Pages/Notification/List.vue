<template>
    <q-page padding class="container">
        <div class="flex justify-between items-center">
            <div class="text-title">
                Notifications
            </div>
            <div class="flex q-gutter-sm">
                <q-input v-model="state.search"
                         outlined
                         bg-color="white"
                         @keyup.enter="handleSearch"
                         dense
                         placeholder="Type here"

                />
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
import {onMounted, reactive} from "vue";
import {router} from "@inertiajs/vue3";
import {useMasterData} from "@/Store/useMasterData";
defineOptions({
    layout:MainLayout
})

const masterData = useMasterData();
const props = defineProps(['list']);
const state=reactive({
    search:''
})
const handleSearch=e=>{
    router.get(route('notification.list'),{
        search:state.search
    })
}
const formatDateTime=(val)=>date.formatDate(val,'D/M/YYYY hh:mm a')

onMounted(()=>{
    masterData.setNotification(false)
})
</script>
