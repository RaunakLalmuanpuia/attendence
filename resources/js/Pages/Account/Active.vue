<template>
    <q-page class="container" padding>
        <div class="flex justify-between">
            <div>
                <div class="text-title">Accounts</div>
                <div class="text-caption text-grey-7">List of users are employess</div>
            </div>

            <div>
            </div>
        </div>
        <q-tabs
            class="q-mt-sm"
            stretch
            shrink
            v-model="state.tab"
            align="start"
            @update:model-value="handleNavigation"
        >
            <q-tab name="account.inactive"  label="Inactive" />
            <q-tab name="account.active"  label="Active" />
            <q-space/>
            <q-input v-model="state.search"
                     autofocus
                     outlined
                     dense
                     @keyup.enter="handleSearch"
                     bg-color="white"
                     placeholder="Enter fullname"
            >
                <template #append>
                    <q-icon name="o_search"/>
                </template>
            </q-input>
        </q-tabs>
        <br/>
        <q-list separator class="bg-white shadow-default ">
            <q-item clickable @click="$inertia.get(route('account.show',item.id))"
                    v-for="(item,index) in list?.data"
                    :key="index">
                <q-item-section>
                    <q-item-label>{{item?.full_name}}</q-item-label>
                    <div><q-chip class="text-caption q-ml-none q-mt-none" square v-for="office in item.offices" :label="office?.name"/></div>
                </q-item-section>
                <q-item-section side>
                    <div class="flex  q-gutter-sm">
                        <div class="text-caption">{{item?.mobile}}</div>
                        <q-icon size="sm" name="o_chevron_right"/>
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
    tab: route().current(),
    selected:null
})


const handleSearch=e=>{
    router.get(route('account.active'), {
        search: state.search
    });

}

const handleNavigation=(value)=>{
    router.get(route(value))
}
</script>
