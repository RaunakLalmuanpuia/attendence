
<template>
    <q-page class="container" padding>
        <div class="row q-col-gutter-sm">
            <div class="col-xs-12 col-sm-4">
                    <div class="column  q-pa-xs q-ma-sm-lg  bg-white shadow-default">
                        <div class="text-center">
                            <q-avatar class="text-capitalize" size="32px" color="orange">{{user?.full_name[0]}}</q-avatar>
                            <div class="text-weight-medium text-dark">{{ user?.full_name }}</div>
                            <div class="text-weight-medium text-grey-7 q-ma-none">{{user?.designation}}</div>
                            <q-separator class="full-width"/>
                            <div class="text-weight-medium text-grey-7 q-ma-none">Latitude: <span class="text-bold text-dark">{{state?.lat}}</span></div>
                            <div class="text-weight-medium text-grey-7 q-ma-none">Longitude:<span class="text-bold text-dark">{{state?.lng}}</span> </div>
                            <q-btn @click="handleMapOpen" color="secondary" no-caps flat label="View in Map"/>
                        </div>

                        <q-list class="q-pa-none" separator v-if="current_sessions?.length>0">
                            <q-item>
                                <q-item-section>
                                    <q-item-label class="text-dark text-lg">Active Sessions</q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-item v-for="item in current_sessions" :key="item.id" class="q-px-xs">
                                <q-item-section  avatar><q-icon name="error_outline" color="warning"/> </q-item-section>
                                <q-item-section>
                                    <q-item-label>{{formatHour(item?.signin_at)}}</q-item-label>
                                    <q-item-label caption>{{formatDateOnly(item?.signin_at)}}</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-btn @click="logout(item)" round icon="logout" flat/>
                                </q-item-section>
                            </q-item>

                        </q-list>
                    </div>
                    <KaiCalendar class="full-width"/>

            </div>
            <div class="col-xs-12 col-sm-8  q-col-gutter-sm">

<!--                <div v-if="today_attendance===null" class="col-xs-12">-->
<!--                    <div class="bg-warning text-white q-pa-sm shadow-default">-->
<!--                        <div class="flex justify-between items-center">-->
<!--                            <div class="text-xl text-bold ">{{today}}</div>-->
<!--                            <q-btn @click="submitAttendance" color="secondary" label="Submit Now" no-caps/>-->
<!--                        </div>-->
<!--                        <div class="text-md ">Submit today attendance for office <span class="text-weight-medium">{{props.office?.name}}</span></div>-->
<!--                    </div>-->
<!--                </div>-->
                <div class="row q-col-gutter-sm">
                    <div class="col-xs-12 col-sm-6">
                        <div class="bg-secondary text-white q-pa-sm shadow-default">
                            <div class="flex justify-between items-center">
                                <div class="text-xl text-bold ">{{ countPresent  }}</div>
                                <q-icon size="26px" name="person_add_alt"/>
                            </div>
                            <div class="text-md ">This week: PRESENT</div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="bg-warning text-white q-pa-sm shadow-default">
                            <div class="flex justify-between items-center">
                                <div class="text-xl text-bold ">{{ countLate  }}</div>
                                <q-icon size="26px" name="o_person_remove"/>
                            </div>
                            <div class="text-md ">This week: LATE</div>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12">
                    <div class="q-pa-sm">
                        <div class="text-lg text-bold text-dark">Weekly Attendances</div>
                    </div>

                    <q-list >
                        <div class="flex justify-between items-center shadow-default  q-mt-sm bg-white q-pa-sm" v-for="item in weekly_attendances">
                            <div>
                                <div class="text-lg text-bold text-weight-medium">{{formatHour(item.signin_at)}}</div>
                                <div class="text-sm text-grey-7 text-weight-medium">{{formatDate(item?.signin_at)}}</div>
                            </div>
                            <div>
                                <q-chip square text-color="white" v-if="item.type==='late'" color="warning" label="Late"/>
                                <q-chip square text-color="white" v-else color="positive"  label="Present"/>
                            </div>
                            <div>
                                <q-btn @click="$inertia.get(route('attendance.show',item.id))" round flat icon="o_chevron_right"/>
                            </div>
                        </div>
                    </q-list>
                </div>


            </div>
        </div>


        <q-dialog v-model="state.openMap">
            <MapView :welcomeText="'Hello i am here'" :lat="state.lat" :lng="state.lng" :office="office"/>
        </q-dialog>

    </q-page>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {date, useQuasar} from "quasar";
import {router} from "@inertiajs/vue3";
import utils from "@/Util/utils";
import {computed, onMounted, reactive} from "vue";
import MapView from "@/Components/MapView.vue";
import KaiCalendar from "@/Components/KaiCalendar.vue";

defineOptions({
    layout:MainLayout
})

const props=defineProps(['user','weekly_attendances','current_sessions','office'])
const q = useQuasar();
const {formatSignoutTime,APIRESPONSE} = utils();
const state=reactive({
    lat:null,
    lng: null,
    openMap:false
})

const today=computed(()=>date.formatDate(Date.now()))

const formatDate=val=>date.formatDate(val,'DD/MM/YYYY hh:mm a')
const formatDateOnly=val=>date.formatDate(val,'DD/MM/YYYY')
const formatHour=val=>date.formatDate(val,'hh:mm a')


const countPresent=computed(()=>{
    return props.weekly_attendances.reduce((prev,curr,index)=>{
        const val=curr.type==='present'?1:0
        return prev+val;
    },0)
})
const countLate=computed(()=>{
    return props.weekly_attendances.reduce((prev,curr,index)=>{
        const val=curr.type==='late'?1:0
        return prev+val;
    },0)
})
const logout=item=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to sign out this session?',
        ok:'Yes',
        cancel:'No'
    }).onOk(()=>{
            router.delete(route('attendance.signout',item.id),{
                onStart:params => q.loading.show(),
                onFinish:params => q.loading.hide()
            })
        })
}
const submitAttendance=e=>{
    navigator.geolocation.getCurrentPosition(function(location) {
        q.dialog({
            title:'Confirmation',
            message:'Do you want to submit your attendance for '+today.value,
            ok:'Yes',
            cancel:'No'
        }).onOk(()=>{
            axios.post(route('attendance.submit'),{
                lat:location.coords.latitude,
                lng:location.coords.longitude,
                office_id:props.office?.id
            }).then(res=>{
                const {status,message}=res.data
                if (status === APIRESPONSE.INVALID_GEO) {
                    q.notify({
                        type:'negative',
                        message
                    });
                }
                if (status === APIRESPONSE.ALREADY_SIGNIN) {
                    q.notify({
                        type:'negative',
                        message
                    });
                }
                if (status === APIRESPONSE.SUCCESS) {
                    router.get(route('dashboard'),{},{
                        preserveState: false
                    })
                }
            }).catch(err=>console.log(err))

        })
    })

}

const handleMapOpen=e=>{
    state.openMap = true;
}
onMounted(()=>{
    navigator.geolocation.getCurrentPosition(function(location) {
        state.lat=location.coords.latitude;
        state.lng = location.coords.longitude;
    })

})
</script>
