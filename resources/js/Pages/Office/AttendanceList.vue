<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">Log Books</div>
            </div>
            <div class="flex q-gutter-sm items-center">


                <q-input v-model="state.search"
                         autofocus
                         outlined
                         bg-color="white"
                         rounded
                         dense
                         :debounce="800"
                         @update:model-value="handleSearch"
                         placeholder="Type name..."
                />
                <q-btn  color="primary" round @click="state.openFilter=true" icon="sort"/>
            </div>
        </div>


        <div class="flex justify-between items-center q-my-md">
            <p class="text-grey-7 text-weight-medium q-ma-none">
                Attendances for the dates of <q-chip square :label="dateStr"/> Filter :<q-chip square :label="state.filter" />
                <q-chip square v-for="office in state.offices" :key="office.id" :label="office.label"/>
            </p>
            <q-btn outline color="primary" @click="exportExcel"  label="Export Excel" no-caps/>
        </div>

        <q-separator spaced/>
        <q-list>
            <div class="q-mt-sm shadow-default bg-white q-pa-sm flex justify-between items-center"
                 v-for="item in list.data" :key="item.id">

                <div class="flex q-gutter-sm  items-center">
                    <div v-if="item.attendances?.length>0">
                        <q-icon size="21px" v-if="item?.attendances[0]?.type==='present'" name="fiber_manual_record" color="positive"/>
                        <q-icon size="21px" v-else-if="item?.attendances[0]?.type!=='present'" name="fiber_manual_record" color="warning"/>
                    </div>
                    <div v-else>
                        <q-icon size="21px"  name="fiber_manual_record" color="negative"/>
                    </div>

                    <div>
                        <q-item-label>{{item?.full_name}}</q-item-label>
                        <q-chip class="text-caption q-ml-none" v-if="item.attendances?.length>0" square :label="item?.attendances[0]?.office?.name"/>

                    </div>
                </div>


                <div>
                    <div class="flex items-center q-gutter-sm">
                        <div class="text-right" v-if="item.attendances?.length>0">
                            <div class="text-lg text-time">
                                {{item?.attendances.length>0 ?formatTime(item?.attendances[0]?.signin_at):'Absent'}}
                            </div>
                                <q-chip class="q-ma-none text-white text-caption" square color="positive" v-if="item?.attendances[0].type==='present'" label="Present"/>
                                <q-chip class="q-ma-none text-white text-caption" square color="warning" v-else label="Late"/>

                        </div>
                        <div v-else>
                            <q-chip class="text-white text-caption" square color="negative" label="Absent"/>
                        </div>

                        <q-btn-dropdown class="q-pa-xs" dropdown-icon="more_vert">
                            <q-list>
                                <q-item  v-close-popup clickable @click.stop="handleMenuItem(item,'log')">
                                    <q-item-section>
                                        <q-item-label>Log</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item v-if="item?.attendances.length>0"  v-close-popup clickable @click.stop="handleMenuItem(item?.attendances[0],'detail')">
                                    <q-item-section>
                                        <q-item-label>Detail</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item v-if="item?.attendances.length>0" v-close-popup clickable @click.stop="handleMenuItem(item?.attendances[0],'map')">
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
                <q-btn color="primary" @click="navigate(list.prev_page_url)" :disable="!Boolean(list?.prev_page_url)" round flat icon="o_chevron_left"/>
                <q-btn color="primary" @click="navigate(list.next_page_url)" :disable="!Boolean(list?.next_page_url)" round flat icon="o_chevron_right"/>
            </div>
        </q-list>

        <q-dialog v-model="state.openMap">
            <Geolocation :attendance="state.selected"/>
        </q-dialog>
        <q-dialog v-model="state.openFilter">
            <q-card style="width: 340px" class="q-pa-sm">
                <div class="flex justify-between items-center">
                    <div class="text-lg text-weight-medium">Filter</div>
                    <q-btn class="q-pa-xs" v-close-popup round icon="close"/>
                </div>
                <q-select v-model="state.offices"
                          :options="offices"
                          outlined
                          use-chips
                          multiple
                          label="Office"/>

                <q-input v-model="state.date"
                         class="q-mt-sm"
                         type="date"
                         outlined
                         label="Date"
                >
                </q-input>
                <div class="column q-mt-sm">
                    <q-radio v-model="state.filter" val="present" label="Present" />
                    <q-radio v-model="state.filter" val="late" label="Late" />
                    <q-radio v-model="state.filter" val="absent" label="Absent" />
                    <q-radio v-model="state.filter" val="all" label="All" />

                </div>


                <div class="flex items-center q-gutter-sm q-pa-sm">
                    <q-btn @click="doFilter" color="primary" label="Apply"/>
                    <q-btn color="negative" v-close-popup outline label="Close"/>
                </div>
            </q-card>

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
const q = useQuasar();
const props=defineProps(['list','filter','users','date','search','selectedOffices','offices'])
const {formatDateTime}=utils();
const state=reactive({
    search:props.search,
    date:props.date,
    openMap:false,
    openFilter:false,
    selected: null,
    filter:props.filter || 'all',
    offices:props.selectedOffices
})
const formatTime = val => date.formatDate(val, 'HH:mm a');
const dateStr=computed(()=>date.formatDate(state.date,'DD/MM/YYYY'))
const exportExcel=e=>{
    q.loading.show();
    axios.get(route('office.attendance-list.export'),{
        params:{
            office_ids: state.offices?.map(item => item.value),
            ...state
        }
    }).then(res=>{
        const {link} = res.data;
        window.open(link,'_blank')
    })
    .catch(err=>{
        console.log(err)
    })
    .finally(()=>q.loading.hide())
}
const handleMenuItem = (item, menu)=>{
    state.selected = item;
    if (menu==='log') {
        router.get(route('user.attendances',item.id))
    }
    if (menu==='detail') {
        router.get(route('attendance.show',item.id))
    }
    if (menu === 'map') {
        state.openMap = true;
    }
}
const handleSearch=val=>{
    router.get(route('office.attendance-list'), {
        office_ids: state.offices?.map(item => item.value),
        ...state
    })
}

const doFilter=e=>{
    state.openFilter = false;
    router.get(route('office.attendance-list'), {
        office_ids: state.offices?.map(item => item.value),
        ...state
    })
}
const navigate=url=> router.get(url, {
    ...state
})
const handleExport=val=>{
    // window.open(route('attendance.download') +"?present="+ props.present || false +"&date="+ date.formatDate(Date.now(),'YYYY-MM-DD')",'_blank')
    const from = state.filter.dateRange.from;
    const to = state.filter.dateRange.to;
    const present = state.filter.present;
    const late = state.filter.late;
    axios.get(route('office.attendances.download'),{
        params:{
            office_ids: state.offices?.map(item => item.value),
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
