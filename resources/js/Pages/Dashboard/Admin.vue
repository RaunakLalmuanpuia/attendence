
<template>
    <q-page class="container" padding>
        <div class="row q-col-gutter-sm">
            <div v-show="$q.screen.gt.sm" class="col-xs-12 col-sm-2">

                <div class="column full-height justify-between text-center   q-pa-sm bg-white shadow-default">
                    <div class="column items-center justify-center">
                        <q-avatar color="warning">A</q-avatar>
                        <div class="text-weight-medium text-dark text-lg">{{ user?.full_name }}</div>
                        <div class="text-weight-medium text-grey-7 q-ma-none">{{user?.designation}}</div>
                        <div class="text-weight-medium text-grey-7 q-ma-none">{{user?.mobile}}</div>
                        <div class="text-weight-medium text-grey-7 q-ma-none">{{state?.lat}}</div>
                        <div class="text-weight-medium text-grey-7 q-ma-none">{{state?.lng}}</div>
                    </div>


                    <q-btn class="q-mt-xl" color="primary" label="Logout" flat   icon="logout"/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-10">
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-4">
                        <div class="bg-primary text-white q-pa-sm shadow-default">
                            <div class="flex justify-between items-center">
                                <div class="text-xl text-bold">{{ count_office }}</div>
                                <q-icon size="32px" name="o_apartment"/>
                            </div>
                            <div class="text-md ">Total Registered Office</div>
                        </div>
                    </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="bg-secondary text-white q-pa-sm shadow-default">
                        <div class="flex justify-between items-center">
                            <div class="text-xl text-bold ">{{ count_user }}</div>
                            <q-icon size="32px" name="o_group"/>
                        </div>
                        <div class="text-md ">Total Registered Users</div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="bg-warning text-white q-pa-sm shadow-default">
                        <div class="flex justify-between items-center">
                            <div class="text-xl text-bold ">{{ count_attendance }}</div>
                            <q-icon size="32px" name="perm_contact_calendar"/>
                        </div>
                        <div class="text-md ">Recorded Attendance for this week</div>
                    </div>
                </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="bg-white q-pa-sm shadow-default">
                            <div class="flex justify-between items-center">
                                <OfficePercent/>

                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 ">
                        <div style="height: 100%" class="bg-white q-pa-sm  shadow-default">
                            <LateList/>
<!--                            <AttendancePercent :present="count_present" :late="count_late" :absent="absent"/>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="row q-col-gutter-sm">
            <div class="col-xs-12 col-sm-6">
                <div class="q-pa-sm bg-white full-height shadow-default">
                    <OfficeChart/>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6">
                <div class="q-pa-sm bg-white shadow-default">
                    <DistrictChart />
                </div>
            </div>
        </div>
    </q-page>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import DistrictChart from "@/Components/DistrictChart.vue";
import OfficeChart from "@/Components/OfficeChart.vue";
import AttendancePercent from "@/Components/AttendancePercent.vue";
import OfficePercent from "@/Components/OfficePercent.vue";
import {computed, onMounted, reactive} from "vue";
import {usePage} from "@inertiajs/vue3";
import LateList from "@/Components/LateList.vue";

defineOptions({
    layout:MainLayout
})
const props=defineProps(['count_user','count_office','count_attendance'])
const state=reactive({
    lat:'',
    lng:''
})
const absent=computed(()=>props.count_user-(props.count_present+props.count_late))

const user=computed(()=>usePage().props?.auth?.user)

onMounted(()=>{
    navigator.geolocation.getCurrentPosition(function(location) {
        state.lat=location.coords.latitude;
        state.lng = location.coords.longitude;
    })
})
</script>
