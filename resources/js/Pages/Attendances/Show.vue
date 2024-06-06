<template>
    <q-page padding class="container">
        <div class="flex justify-between">
            <div>
                <div class="text-title">Attendance on {{formatDateTime(data.signin_at)}}</div>
            </div>
            <div>
                <q-btn v-if="canSignout" outline color="negative" label="Signout" @click="handleSignout"/>
            </div>
        </div>
        <br/>
        <div class="row q-col-gutter-sm">
            <div class="col-xs-12 col-sm-6">
                <fieldset class="bg-white q-pa-md shadow-default text-weight-medium text-lg">
                    <legend class="q-ma-md q-px-sm bg-dark rounded-borders text-white">Attendance recorded time</legend>

                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Sign in at</div>
                        <div class="text-dark text-weight-medium">{{ formatDateTime(data?.signin_at) }}</div>
                    </div>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Sign out at</div>
                        <div class="text-dark text-weight-medium">{{ !!data?.signout_at? formatDateTime(data?.signout_at) :'NA' }}</div>
                    </div>

                    <q-separator class="q-my-sm"/>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Office</div>
                        <div class="text-dark text-weight-medium">{{ data?.office?.name }}</div>
                    </div>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">District</div>
                        <div class="text-dark text-weight-medium">{{ data?.office?.district?.name }}</div>
                    </div>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Lat</div>
                        <div class="text-dark text-weight-medium">{{ data?.lat }}</div>
                    </div>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Lng</div>
                        <div class="text-dark text-weight-medium">{{ data?.lng }}</div>
                    </div>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Radius in Meter</div>
                        <div class="text-dark text-weight-medium">{{ data?.office?.radius }}</div>
                    </div>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Grace Period in Minute</div>
                        <div class="text-dark text-weight-medium">{{ data?.office?.grace_period }}</div>
                    </div>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Starting Time</div>
                        <div class="text-dark text-weight-medium">{{ data?.office?.start_time }}</div>
                    </div>
                    <div class="flex justify-between items-center text-md">
                        <div class="text-grey-7">Closing Time</div>
                        <div class="text-dark text-weight-medium">{{ data?.office?.close_time }}</div>
                    </div>
                </fieldset>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div>
                    <fieldset class="bg-white q-pa-md shadow-default text-weight-medium text-lg">
                        <legend class="q-ma-md q-px-sm bg-dark rounded-borders text-white">Location</legend>

                        <div class="flex justify-between items-center text-md">
                            <div class="text-grey-7">Sign in (Coordinate)</div>
                            <div class="text-dark text-weight-medium">{{ data?.lat +' '+data?.lng }}</div>
                        </div>
                        <div class="flex justify-between items-center text-md">
                            <div class="text-grey-7">Sign out (Coordinate)</div>
                            <div class="text-dark text-weight-medium">{{ data?.signout_lat || '--' +' '+(data?.signout_lng || 'NA') }}</div>
                        </div>
                        <div class="flex justify-end items-center q-mt-sm">
                            <q-btn @click="handleOpenMap" color="primary" outline label="View in Map" no-caps/>
                        </div>

                    </fieldset>
                    <fieldset class="bg-white q-pa-md shadow-default text-weight-medium text-lg">
                        <legend class="q-ma-md q-px-sm bg-dark rounded-borders text-white">Device</legend>
                        <div class="flex justify-between items-center text-md">
                            <div class="text-grey-7">Device Name</div>
                            <div class="text-dark text-weight-medium">{{ data.device?.name }}</div>
                        </div>
                        <div class="flex justify-between items-center text-md">
                            <div class="text-grey-7">UID</div>
                            <div class="text-dark text-weight-medium">{{ data.device?.uid }}</div>
                        </div>
                    </fieldset>
                </div>

            </div>
        </div>
        <q-dialog v-model="state.openAttendance">
            <geolocation :attendance="data"/>

        </q-dialog>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import utils from "@/Util/utils";
import {computed, reactive} from "vue";
import Geolocation from "@/Components/Geolocation.vue";
import {useQuasar} from "quasar";
import {router, usePage} from "@inertiajs/vue3";

defineOptions({
    layout:MainLayout
})
const props=defineProps(['data']);
const {formatDateTime} = utils();
const state=reactive({
    openAttendance:false,
})


const q = useQuasar();

const handleSignout=e=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to sign out',
        ok:'Yes',
        cancel:'No'
    })
    .onOk(()=>{
        router.delete(route('attendance.signout',props.data.id),{
            preserveState:false
        })
    })

}
const canSignout = computed(() => props.data.signout_at === null && props.data.user_id === usePage().props.auth?.user?.id);
const handleOpenMap=item=>{
    state.openAttendance = true;
}
</script>
