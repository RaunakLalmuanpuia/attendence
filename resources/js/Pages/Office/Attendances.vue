<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">Log Book</div>
            </div>
            <div class="flex q-gutter-sm items-center">
                <q-chip  :outline="!state.filter.present" v-model:selected="state.filter.present" color="primary" text-color="white">
                    Present
                </q-chip>
                <q-chip  :outline="!state.filter.late" v-model:selected="state.filter.late" color="warning" text-color="white">
                    Late
                </q-chip>
                <q-select placeholder="Select Employee"
                          v-model="state.filter.user"
                          :options="users"
                          style="min-width: 220px"
                          outlined
                          dense
                          rounded
                          bg-color="white"
                />
                <q-input hide-bottom-space placeholder="Date of attendance"
                         outlined bg-color="white" rounded dense
                         v-model="state.filter.rangeText">

                    <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <q-date @range-end="handleRange" range v-model="state.filter.dateRange">
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat />
                                    </div>
                                </q-date>
                            </q-popup-proxy>
                        </q-icon>
                    </template>
                </q-input>
                <q-btn @click="handleFilter" rounded label="Filter" color="primary"/>
                <q-btn @click="handleExport" color="primary" outline rounded icon="download" no-caps/>
            </div>
        </div>
        <br/>
        <q-list>
            <div class="q-mt-sm shadow-default bg-white q-pa-sm flex justify-between items-center"
                 v-for="item in list.data" :key="item.id">

                <div class="flex q-gutter-sm  items-center">
                    <q-icon size="24px" v-if="item.type==='present'" name="fiber_manual_record" color="positive"/>
                    <q-icon size="24px" v-else name="fiber_manual_record" color="warning"/>
                    <div>
                        <q-item-label>{{item?.user?.full_name}}</q-item-label>
                        <q-item-label caption>{{formatDateTime(item?.signin_at)}}</q-item-label>
                    </div>
                </div>
                <div class="text-center">
                    <q-chip text-color="white" square color="secondary":label="item?.device?.name"/>
                </div>
                <div>
                    <div class="flex items-center q-gutter-sm">
                        <q-chip class="text-white" square color="positive" v-if="item.type==='present'" label="Present"/>
                        <q-chip class="text-white" square color="warning" v-else label="Late"/>
                        <q-btn-dropdown class="q-pa-xs" dropdown-icon="more_vert">
                            <q-list>
                                <q-item v-close-popup clickable @click.stop="handleMenuItem(item,'detail')">
                                    <q-item-section>
                                        <q-item-label>Detail</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item v-close-popup clickable @click.stop="handleMenuItem(item,'map')">
                                    <q-item-section>
                                        <q-item-label>View Geolocation</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-btn-dropdown>

                    </div>
                </div>
            </div>
            <div class="flex q-gutter-sm">
                <q-btn @click="$inertia.get(list.prev_page_url)" :disable="!Boolean(list?.prev_page_url)" round flat icon="o_chevron_left"/>
                <q-btn @click="$inertia.get(list.next_page_url)" :disable="!Boolean(list?.next_page_url)" round flat icon="o_chevron_right"/>
            </div>
        </q-list>

        <q-dialog v-model="state.openMap">
            <Geolocation :attendance="state.selected"/>
        </q-dialog>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {computed, reactive} from "vue";
import {router} from '@inertiajs/vue3';
import {useQuasar} from "quasar";
import { date } from 'quasar'
import utils from "@/Util/utils";
import Geolocation from "@/Components/Geolocation.vue";

defineOptions({
    layout:MainLayout
})
const props=defineProps(['list','users','user','present','late','from','to'])
const {formatDateTime}=utils();
const state=reactive({
    filter:{
        present:!!props.present,
        late:!!props.late,
        user: ({value:props.user?.id,label:props?.user?.full_name}) || null,
        rangeText: "From: "+props.from+ ' to:'+props.to,
        dateRange: {
            from:props.from || date.formatDate(Date.now(),'Y/MM/DD'),
            to:props.to ||date.formatDate(Date.now(),'Y/MM/DD'),
        }
    },
    openMap:false,
    selected: null
})

const range=computed(()=>{
    const  fr=state.filter.dateRange?.from
    const  to=state.filter.dateRange?.to
    if (fr && to) {
        return "From: "+fr+ ' to:'+to
    }
    return 'NA'
})
const handleMenuItem = (item, menu)=>{
    state.selected = item;
    if (menu==='detail') {
        router.get(route('attendance.show',item.id))
    }
    if (menu === 'map') {
        state.openMap = true;
    }
}
const handleRange=val=>{
    state.filter.rangeText = ""+val.from?.year+'/'+val.from.month+'/'+val.from.day  +'-'+ val.to?.year+'/'+val.to.month+'/'+val.to.day;
}
const handleFilter=val=>{
    const from = state.filter.dateRange.from;
    const to = state.filter.dateRange.to;
    const present = state.filter.present;
    const late = state.filter.late;
    router.get(route('office.attendances'),{
        user_id:state.filter.user?.value,
        from,
        to,
        present,
        late
    })
}
const handleExport=val=>{
    // window.open(route('attendance.download') +"?present="+ props.present || false +"&date="+ date.formatDate(Date.now(),'YYYY-MM-DD')",'_blank')
    const from = state.filter.dateRange.from;
    const to = state.filter.dateRange.to;
    const present = state.filter.present;
    const late = state.filter.late;
    axios.get(route('office.attendances.download'),{
        params:{
            user_id:state.filter.user?.value,
            from,
            to,
            present,
            late
        }
    }).then(res=>{
            const {link} = res.data;
            window.open(link,'_blank')
        })
    .catch(err=>console.log(err))
}
</script>
