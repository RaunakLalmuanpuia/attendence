<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">Attendance History</div>
            </div>
            <div class="flex q-gutter-sm items-center">
                <q-chip  :outline="!state.filter.present" v-model:selected="state.filter.present" color="primary" text-color="white">
                    Present
                </q-chip>
                <q-chip :outline="!state.filter.late" v-model:selected="state.filter.late" color="warning" text-color="white">
                    Late
                </q-chip>
                <q-input hide-bottom-space outlined bg-color="white"  rounded dense :model-value="state.filter.rangeText">
                    <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                <q-date v-model="state.filter.dateRange" range @range-end="handleRange">
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat />
                                    </div>
                                </q-date>
                            </q-popup-proxy>
                        </q-icon>
                    </template>
                </q-input>
                <q-btn @click="handleFilter" color="primary" rounded label="Filter" no-caps/>
                <q-btn @click="handleExport" color="primary" outline rounded icon="download" no-caps/>


            </div>
        </div>
        <br/>
        <q-list>
            <q-item @click="$inertia.get(route('attendance.show',item.id))" clickable class="bg-white shadow-default q-mb-sm" v-for="item in list.data" :key="item.id">
                <q-item-section avatar>
                    <q-icon v-if="item.type==='present'" name="fiber_manual_record" color="positive"/>
                    <q-icon v-else name="fiber_manual_record" color="warning"/>
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{item?.formatted_signin}}</q-item-label>
                    <q-item-label caption>
                        <span>Sign in: {{formatTime(item?.signin_at)}}</span>
                        <span class="q-ml-xl">Sign Out:  {{ item?.signout_at ? formatTime(item?.signout_at) :'NA'}} </span>
                    </q-item-label>
                </q-item-section>
                <q-item-section side>
                    <div class="flex items-center q-gutter-sm">
                        <q-chip class="text-white" square color="positive" v-if="item.type==='present'" label="Present"/>
                        <q-chip class="text-white" square color="warning" v-else label="Late"/>
                        <q-btn round flat icon="o_chevron_right"/>

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
import {reactive} from "vue";
import {router} from '@inertiajs/vue3';
import {useQuasar} from "quasar";
import { date } from 'quasar'
import utils from "@/Util/utils";

defineOptions({
    layout:MainLayout
})
const props=defineProps(['list','present','late','from','to'])
const {formatDateTime}=utils();
const state=reactive({
    filter:{
        present:props.present || false,
        late:props.late || false,
        rangeText: "From: "+props.from+ ' to:'+props.to,
        dateRange: {
            from:props.from || date.formatDate(Date.now(),'Y/MM/DD'),
            to:props.to ||date.formatDate(Date.now(),'Y/MM/DD'),
        }
    }
})


const formatTime=val=>date.formatDate(val,'hh:mm a',)

const handleExport=val=>{
    // window.open(route('attendance.download') +"?present="+ props.present || false +"&date="+ date.formatDate(Date.now(),'YYYY-MM-DD')",'_blank')

    axios.get(route('attendance.download'),{
        params:{from:state.filter.dateRange?.from,to:state.filter.dateRange?.to,...state.filter}
    })
    .then(res=>{
        const {link} = res.data;
        window.open(link,'_blank')
    })
}
const handleRange=val=>{
    state.filter.rangeText = ""+val.from?.year+'/'+val.from.month+'/'+val.from.day  +'-'+ val.to?.year+'/'+val.to.month+'/'+val.to.day;
}
const handleFilter=val=>{

    router.get(route('attendance.index'),{
        from:state.filter.dateRange?.from,to:state.filter.dateRange?.to,...state.filter
    })
}
</script>
