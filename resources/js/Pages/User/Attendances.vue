<template>
    <q-page class="container" padding>
        <div class="flex justify-between">
            <div>
                <div class="text-title">Record of {{user?.full_name}}</div>
            </div>
            <div>
            </div>
        </div>
        <br/>
        <div class="row q-col-gutter-sm">
            <div class="col-xs-12 col-sm-4">
                <div class="shadow-default bg-white q-pa-sm">
                    <div class="flex justify-between items-center">
                        <div class="text-grey-7">Full Name</div>
                        <div class="text-grey-7 text-dark">{{user?.full_name}}</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-grey-7">Designation</div>
                        <div class="text-grey-7 text-dark">{{user?.designation}}</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-grey-7">Mobile</div>
                        <div class="text-grey-7 text-dark">{{user?.mobile}}</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-grey-7">Current User</div>
                        <div class="text-grey-7 text-dark">{{office?.name}}</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-8">
                <div class="shadow-default">
                    <q-date class="full-width text-lg" style="height: 450px"
                            flat
                            :title="title"
                            :subtitle="subtitle"
                            v-model="state.today"
                            today-btn
                            :events="state.events"
                            :event-color="eventColor"
                    />
                </div>
            </div>
        </div>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {computed, reactive} from "vue";
import {date} from "quasar";

defineOptions({
    layout:MainLayout
})
const props = defineProps(['user','office','attendances']);
const state=reactive({
    today:date.formatDate(Date.now(),'YYYY/MM/DD'),
    events:props.attendances.map(item=>date.formatDate(item?.signin_at,'YYYY/MM/DD'))
})

const eventFn=val=> props.attendances.find(item => date.formatDate(item?.signin_at, 'YYYY/MM/DD') === val);
const eventColor = val => {
    const found=props.attendances.find(item=>date.formatDate(item?.signin_at,'YYYY/MM/DD')===val)
    if (found) {
        return found.type==='present'?'positive':'warning'
    }
};

const subtitle=computed(()=>{
    const found=props.attendances.find(item=>date.formatDate(item?.signin_at,'YYYY/MM/DD')===state.today)
    if (found) {
        return `Office:${found?.office?.name} Lat: ${found.lat} Lng: ${found.lng}`;
    }
    return 'NA'
})
const title=computed(()=>{
    const found=props.attendances.find(item=>date.formatDate(item?.signin_at,'YYYY/MM/DD')===state.today)
    if (found) {
        return found?.type==='present' ? 'Present' :'Late';
    }
    return 'Absent'
})
</script>
