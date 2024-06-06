
<template>
    <q-page class="container" padding>
        <div class="row q-col-gutter-sm">
            <div class="col-xs-12 col-sm-4">
                <div class="column   q-pa-sm bg-white ">
                    <div class="text-center">
                        <q-icon size="32px" name="o_account_circle"/>
                        <div class="text-weight-medium text-dark">{{ user?.full_name }}</div>
                        <div class="text-weight-medium text-grey-7 q-ma-none">{{user?.designation}}</div>
                    </div>

                    <q-list class="q-pa-none" separator>
                        <q-item>
                            <q-item-section>
                                <q-item-label class="text-dark text-lg">Active Sessions</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item v-for="item in current_sessions" :key="item.id" class="q-pl-none">
                            <q-item-section avatar><q-icon name="error_outline" color="warning"/> </q-item-section>
                            <q-item-section>
                                <q-item-label>{{formatDateTime(item?.signin_at)}}</q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                <q-btn @click="logout(item)" round icon="logout" flat/>
                            </q-item-section>
                        </q-item>

                    </q-list>
                </div>
                <KaiCalendar class="full-width"/>
            </div>
            <div class="col-xs-12 col-sm-8 row q-col-gutter-sm">
                <!--                <div class="row q-col-gutter-md">-->
                <div class="col-xs-12 col-sm-4">
                    <div class="bg-primary q-pa-sm shadow-default text-white">
                        <div class="flex justify-between items-center">
                            <div class="text-xl text-bold ">{{ total_users }}</div>
                            <q-icon size="24px" name="people_outline"/>
                        </div>
                        <div class="text-md text-grey-2">No of officials</div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="bg-positive q-pa-sm shadow-default text-white">
                        <div class="flex justify-between items-center">
                            <div class="text-xl text-bold ">{{ attendances.length }}</div>
                            <q-icon size="24px" name="o_group_add"/>
                        </div>
                        <div class="text-md text-grey-2">Present on Today's</div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="bg-warning q-pa-sm shadow-default text-white">
                        <div class="flex justify-between items-center">
                            <div class="text-xl text-bold ">{{ total_users-(attendances.length) }}</div>
                            <q-icon size="24px" name="o_group_remove"/>
                        </div>
                        <div class="text-md text-grey-2">Absent on Today's  </div>
                    </div>
                </div>

                <div class="col-xs-12" v-if="devices?.length>0">
                    <div class="q-pa-sm  ">
                        <div class="text-lg text-bold text-dark">New Device Request</div>
                        <q-list >
                            <q-item class="shadow-default  q-mt-sm bg-warning text-white" v-for="item in devices">
                                <q-item-section>
                                    <q-item-label>{{item?.name}}</q-item-label>
                                    <q-item-label caption>{{item?.user?.full_name}}</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <div class="flex q-gutter-sm">
                                        <q-btn color="primary" @click="itemApprove(item)" round outline size="sm" icon="o_check"/>
                                        <q-btn color="negative" @click="itemReject(item)" round outline size="sm" icon="o_close"/>
                                    </div>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="q-pa-sm">
                        <div class="text-lg text-bold text-dark">Today's Attendances</div>
                    </div>

                    <q-list >
                        <div class="flex justify-between items-center shadow-default  q-mt-sm bg-white q-pa-sm" v-for="item in attendances">
                           <div>
                               <div class="flex items-center q-gutter-sm">
                                   <q-icon class="q-pa-sm" v-if="item.type==='present'" name="fiber_manual_record" color="positive"/>
                                   <q-icon class="q-pa-sm" v-else name="fiber_manual_record" color="warning"/>
                                   <div class="q-ml-sm">
                                       <div class="text-bold text-weight-medium">{{item?.user?.full_name}}</div>
                                       <div class="text-grey-7 text-weight-medium">Signin at: {{formatSignoutTime(item?.signin_at)}}</div>
                                   </div>
                               </div>

                           </div>
                           <div>
                               <q-chip v-if="item.type==='late'" text-color="white" color="warning" label="Late"/>
                               <q-chip v-else color="positive" text-color="white"  label="Present"/>
                           </div>
                           <div class="flex q-gutter-sm items-center">
                               <q-chip v-if="!!item?.signout_at" square :label="formatSignoutTime(item?.signout_at)"/>
                               <q-btn @click="$inertia.get(route('attendance.show',item.id))" round flat icon="o_chevron_right"/>
                           </div>
                        </div>
                    </q-list>
                </div>


            </div>
        </div>
    </q-page>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {useQuasar} from "quasar";
import {router} from "@inertiajs/vue3";
import utils from "@/Util/utils";
import KaiCalendar from "@/Components/KaiCalendar.vue";

defineOptions({
    layout:MainLayout
})

const props=defineProps(['user','devices','attendances','current_sessions','total_users'])
const q = useQuasar();
const {formatSignoutTime,formatDateTime} = utils();

const itemApprove=item=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to approved?',
        ok:'Yes',
        cancel:'No'
    })
    .onOk(()=>{
        router.post(route('device.approve',item.id),{},{
            onStart:params => q.loading.show(),
            onFinish:params => q.loading.hide()
        })
    })
}
const itemReject=item=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to rejected?',
        ok:'Yes',
        cancel:'No'
    })
        .onOk(()=>{
            router.post(route('device.reject',item.id),{},{
                onStart:params => q.loading.show(),
                onFinish:params => q.loading.hide()
            })
        })
}
const logout=item=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to sign out this session?',
        ok:'Yes',
        cancel:'No'
    })
        .onOk(()=>{
            router.delete(route('attendance.signout',item.id),{
                onStart:params => q.loading.show(),
                onFinish:params => q.loading.hide()
            })
        })
}
</script>
