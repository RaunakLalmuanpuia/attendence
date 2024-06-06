<template>
    <q-page class="container" padding>
        <div class="flex justify-between">
            <div>
                <div class="text-title">Office Change Request</div>
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
            <q-tab name="posting.submitted"  label="Submitted" />
            <q-tab name="posting.rejected"  label="Rejected" />
            <q-tab name="posting.approved"  label="Approved" />
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
                    <q-item-label>{{item.user?.full_name}}</q-item-label>
                    <q-item-label caption>{{item.user?.mobile}}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <div class="flex justify-end items-end">
                        <div>
                            <div class="text-sm text-bold">{{item?.office?.name}}</div>
                            <q-chip class="float-right" size="sm" :label="item?.status" square/>
                        </div>
                        <q-btn-dropdown dropdown-icon="more_vert" dense>
                            <q-list v-close-popup>
                                <q-item clickable @click="handleAction(item,'detail')" dense>
                                    <q-item-section>
                                        <q-item-label>Detail</q-item-label>
                                    </q-item-section>
                                </q-item>

                            </q-list>
                        </q-btn-dropdown>
                    </div>
                </q-item-section>
            </q-item>
            <div class="flex q-gutter-sm">
                <q-btn :disable="!!!list.prev_page_url" @click="$inertia.get(list.prev_page_url)" flat round icon="o_chevron_left"/>
                <q-btn :disable="!!!list.next_page_url" @click="$inertia.get(list.next_page_url)" flat round icon="o_chevron_right"/>
            </div>
        </q-list>


        <q-dialog v-model="state.openDetail">
            <q-card class="q-pa-md">
                <div class="flex justify-between items-center">
                    <div class="text-lg">Detail</div>
                </div>
                <q-separator class="q-my-sm"/>
                <div class="flex justify-between items-center">
                    <div class="text-grey-7">Name</div>
                    <div class="text-dark">{{state.selected?.user?.full_name}}</div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-grey-7">Designation</div>
                    <div class="text-dark">{{state.selected?.user?.designation}}</div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-grey-7">Mobile</div>
                    <div class="text-dark">{{state.selected?.user?.mobile}}</div>
                </div>
                <div class="flex justify-between items-center">
                    <div class="text-grey-7">Requested Office</div>
                    <div class="text-dark">{{state.selected?.office?.name}}</div>
                </div>
                <div class="flex justify-end q-mt-sm">
                    <q-btn v-close-popup flat label="Close"/>
                </div>
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
const props = defineProps(['list','search']);

const q = useQuasar();
const state=reactive({
    selected:null,
    openDetail:false,
    tab: route().current(),
})

const handleSearch=e=>{
    router.get(route('user.active'), {
        search: state.search
    });
}
const handleNavigation=(value)=>router.get(route(value))

const handleAction=(item,action)=>{
    switch (action) {
        case 'detail':
            state.selected=item;
            state.openDetail = true;
            break;
        case 'reject':
            q.dialog({
                title:'Confirmation',
                message:'Do you want to Reject this request',
                ok:'Yes',
                cancel:'No'
            }).onOk(()=>{
                router.put(route('posting.reject',item.id),{},{
                    preserveState:false
                })
            })
            break;
        case 'approve':
            q.dialog({
                title:'Confirmation',
                message:'Do you want to Approve this request',
                ok:'Yes',
                cancel:'No'
            }).onOk(()=>{
                router.put(route('posting.reject',item.id),{},{
                    preserveState:false
                })
            })
            break;
    }
}

</script>
