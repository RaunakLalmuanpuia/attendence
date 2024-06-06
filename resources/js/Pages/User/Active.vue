<template>
    <q-page class="container" padding>
        <div class="flex justify-between">
            <div>
                <div class="text-title">Users</div>
            </div>
            <div class="flex q-gutter-sm">
                <q-btn @click="e=>$inertia.get(route('user.create'))" class="sized-btn" color="primary" label="New User"/>
                <q-btn @click="state.openFilter=true" round icon="sort"/>
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
            <q-tab name="user.inactive"  label="Inactive" />
            <q-tab name="user.active"  label="Active" />
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
                    <q-item-label caption><q-chip square size="sm" v-for="office in item.offices" :label="office.name"/></q-item-label>
                </q-item-section>
                <q-item-section side>
                    <div class="flex">
                        <div>
                            <div class="text-sm text-bold">{{item?.mobile}}</div>
                            <div class="text-sm text-weight-medium">{{item?.roles[0]?.name || 'Staff'}}</div>
                        </div>
                        <q-btn @click="$inertia.get(route('user.edit',item?.id))" icon="o_chevron_right"/>
                    </div>
                </q-item-section>
            </q-item>
            <div class="flex q-gutter-sm">
                <q-btn :disable="!!!list.prev_page_url" @click="handlePaginate(list.prev_page_url)" flat round icon="o_chevron_left"/>
                <q-btn :disable="!!!list.next_page_url" @click="handlePaginate(list.next_page_url)" flat round icon="o_chevron_right"/>
            </div>
        </q-list>

        <q-dialog v-model="state.openFilter">
            <q-card class="q-pa-md" style="width: 380px">
                <div class="flex justify-between q-mt-sm">
                    <div class="text-md text-weight-medium">Filter</div>
                    <q-icon class="cursor-pointer" size="md" name="close" v-close-popup/>
                </div>
                <q-form @submit="handleFilter" class="column q-gutter-sm">
                    <q-select v-model="state.filter.office"
                              :options="offices"
                              clearable
                              outlined
                              label="Select Office"
                              />
                    <div class="flex justify-end q-gutter-sm">
                        <q-btn color="secondary" type="submit" flat label="Apply"/>
                        <q-btn color="negative"  v-close-popup flat label="Cancel"/>
                    </div>
                </q-form>
            </q-card>
        </q-dialog>

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
const props = defineProps(['list','search','offices','office']);

const q = useQuasar();
const state=reactive({
    search:props?.search,
    selected:null,
    tab: route().current(),
    openFilter:false,
    filter:{
        office:props.office
    }
})

const handleSearch=e=>{
    router.get(route('user.active'), {
        search: state.search,
        office_id:state.filter.office?.value
    });
}
const handleFilter=e=>{
    router.get(route('user.active'),{
        office_id:state.filter.office?.value,
        search: state.search,
    })
}
const handleNavigation=(value)=>router.get(route(value),{
    search: state.search,
})
const handlePaginate=url=>{
    router.get(url,{
        office_id:state.filter.office?.value,
        search: state.search,
    })
}

</script>
