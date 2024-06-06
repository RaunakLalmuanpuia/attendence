<template>
    <q-page class="container" padding>
        <div class="flex justify-between">
            <div>
                <div class="text-title">Users</div>
            </div>
            <div>
                <q-btn @click="e=>$inertia.get(route('user.create'))" class="sized-btn" color="primary" label="New User"/>
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
            <q-tab name="office.index"  label="Inactive" />
            <q-tab name="district.index"  label="Active" />
            <q-space/>
            <q-input v-model="state.search"
                     autofocus
                     outlined
                     dense
                     @keyup.enter="handleSearch"
                     bg-color="white"
                     placeholder="Enter user name"
            >
                <template #append>
                    <q-icon name="o_search"/>
                </template>
            </q-input>
        </q-tabs>

        <br/>
        <q-list separator class="bg-white shadow-default ">
            <q-item v-for="(item,index) in list?.data" :key="index">
                <q-item-section>
                    <q-item-label>{{item.full_name}}</q-item-label>
                    <q-item-label caption>{{item?.mobile}}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <div class="flex">
                        <div>
                            <div class="text-sm text-bold">{{item?.offices[0]?.name}}</div>
                            <div class="text-sm text-weight-medium">{{item?.roles[0]?.name}}</div>
                        </div>
                        <q-btn @click="$inertia.get(route('user.edit',item?.id))" icon="o_chevron_right"/>
                    </div>
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

defineOptions({
    layout:MainLayout
})
const props = defineProps(['list','search']);

const q = useQuasar();
const state=reactive({
    search:props?.search,
    selected:null
})

const handleSearch=e=>{
    router.get(route('user.index'), {
        search: state.search
    });

}


</script>
