<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">Master Data</div>
                <div class="text-caption text-grey-7">Edit of master data are to be careful which may leads to a disruption of application</div>
            </div>

            <div>
                <q-btn @click="e=>$inertia.get(route('office.create'))" class="sized-btn" color="primary" label="New Office"/>
            </div>
        </div>
        <br/>
        <q-tabs
            stretch
            shrink
            v-model="state.tab"
            align="start"
            @update:model-value="handleNavigation"
        >
            <q-tab name="office.index"  label="Offices" />
            <q-tab name="district.index"  label="Districts" />
            <q-space/>
            <q-input v-model="state.search"
                     autofocus
                     outlined
                     dense
                     @keyup.enter="handleSearch"
                     bg-color="white"
                     placeholder="Enter office name"
            >
                <template #append>
                    <q-icon name="o_search"/>
                </template>
            </q-input>
        </q-tabs>
        <br/>
        <q-list separator class="bg-white shadow-default ">
            <q-item v-for="(item,index) in list?.data" :key="index">
                <q-item-section avatar>
                    <QRCodeVue3
                        :width="50"
                        :height="50"
                        :value="item?.qr_code?.code"/>
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{item.name}}</q-item-label>
                    <q-item-label caption>{{item.district?.name}}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <q-btn @click="$inertia.get(route('office.edit',item?.id))" icon="o_chevron_right"/>
                </q-item-section>
            </q-item>
            <div class="flex q-gutter-sm">
                <q-btn :disable="!!!list.prev_page_url" @click="$inertia.get(list.prev_page_url)" flat round icon="o_chevron_left"/>
                <q-btn :disable="!!!list.next_page_url" @click="$inertia.get(list.next_page_url)" flat round icon="o_chevron_right"/>
            </div>
        </q-list>



    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {reactive} from "vue";
import {router} from '@inertiajs/vue3';
import {useQuasar} from "quasar";
import QRCodeVue3 from "qrcode-vue3";

defineOptions({
    layout:MainLayout
})
const props = defineProps(['list','search']);

const q = useQuasar();
const state=reactive({
    search:props?.search,
    tab: route().current(),
    openCreate:false,
    openEdit:false,
    selected:null
})

const handleEdit=item=>{
    state.selected=item;
    state.openEdit = true;
}
const handleSearch=e=>{
    router.get(route('office.index'), {
        search: state.search
    });

}

const handleNavigation=(value)=>{
    router.get(route(value))
}
</script>
