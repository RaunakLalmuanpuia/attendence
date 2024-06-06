<template>
    <q-layout @scroll="handleScroll" view="LHh lpR lff">
        <TopBar :isTop="state.isTop" @onToggle="toggleLeftDrawer"/>

        <q-drawer width="250" show-if-above v-model="leftDrawerOpen" side="left" >
            <q-list class="text-grey-9 text-weight-medium">
                <div class="text-center">
                    <q-img class="q-pa-md" width="120px" src="/assets/images/logo.png"/>
                </div>
                <q-item :key="'dashboard'" :active="route().current()==='dashboard' || route().current()==='dashboard.admin' || route().current()==='dashboard.manager'"
                        active-class="active-menu"
                        clickable class="default-list" @click="$inertia.get(route('dashboard'))">
                    <q-item-section avatar><q-icon name="o_dashboard"/></q-item-section>
                    <q-item-section><q-item-label>Dashboard</q-item-label></q-item-section>
                </q-item>
                <q-item class="q-ml-xs q-mt-sm" dense>
                    <q-item-section>
                        <q-item-label class="text-grey-7 text-weight-regular">ATTENDANCE</q-item-label>
                    </q-item-section>
                </q-item>
                <q-item :key="'attendance.index'" :active="route().current()==='attendance.index'"
                        active-class="active-menu"
                        clickable class="default-list" @click="$inertia.get(route('attendance.index'))">
                    <q-item-section avatar><q-icon name="o_event"/></q-item-section>
                    <q-item-section><q-item-label>Log</q-item-label></q-item-section>
                </q-item>
                <q-item :key="1123" :active="route().current()==='office.attendance-list'"
                        active-class="active-menu"
                        v-if="isAdmin || isManager" clickable class="default-list" @click="$inertia.get(route('office.attendance-list'))">
                    <q-item-section avatar><q-icon name="o_beenhere"/></q-item-section>
                    <q-item-section><q-item-label>Log Book</q-item-label></q-item-section>
                </q-item>
                <q-item :key="1343" :active="route().current()==='account.inactive' || route().current()==='account.active' "
                        active-class="active-menu"
                        v-if="isAdmin || isManager" clickable class="default-list" @click="$inertia.get(route('account.inactive'))">
                    <q-item-section avatar><q-icon name="o_manage_accounts"/></q-item-section>
                    <q-item-section><q-item-label>Accounts</q-item-label></q-item-section>
                </q-item>


                <q-item class="q-ml-xs q-mt-sm" v-if="isAdmin || isManager" dense>
                    <q-item-section>
                        <q-item-label class="text-grey-7 text-weight-regular">SYSTEM</q-item-label>
                    </q-item-section>
                </q-item>
                <q-item :key="5451" :active="route().current()==='report.index'"
                        active-class="active-menu"
                        v-if="isAdmin || isManager" clickable class="default-list" @click="$inertia.get(route('report.index'))">
                    <q-item-section avatar><q-icon name="o_analytics"/></q-item-section>
                    <q-item-section><q-item-label>Report</q-item-label></q-item-section>
                </q-item>
                <q-item :key="1123" :active="route().current()==='notification.index' || route().current()==='notification.create'|| route().current()==='notification.show' "
                        active-class="active-menu"
                        v-if="isAdmin || isManager" clickable class="default-list" @click="$inertia.get(route('notification.index'))">
                    <q-item-section avatar><q-icon name="o_campaign"/></q-item-section>
                    <q-item-section><q-item-label>Notification</q-item-label></q-item-section>
                </q-item>
                <q-item :key="5451" :active="route().current()==='posting.submitted' || route().current()==='posting.rejected'|| route().current()==='posting.approved' "
                        active-class="active-menu"
                        v-if="isAdmin" clickable class="default-list" @click="$inertia.get(route('posting.submitted'))">
                    <q-item-section avatar><q-icon name="o_home_work"/></q-item-section>
                    <q-item-section><q-item-label>Change Office</q-item-label></q-item-section>
                </q-item>
                <q-item :key="5451" :active="route().current()==='office.config.index' || route().current()==='office.config.edit' "
                        active-class="active-menu"
                        v-if="isAdmin || isManager" clickable class="default-list" @click="$inertia.get(route('office.config.index'))">
                    <q-item-section avatar><q-icon name="o_settings"/></q-item-section>
                    <q-item-section><q-item-label>Configuration</q-item-label></q-item-section>
                </q-item>
                <q-item :key="167" :active="route().current()==='district.index' || route().current()==='office.index' "
                        active-class="active-menu"
                        v-if="isAdmin" clickable class="default-list" @click="$inertia.get(route('office.index'))">
                    <q-item-section avatar><q-icon name="o_storage"/></q-item-section>
                    <q-item-section><q-item-label>Master Data</q-item-label></q-item-section>
                </q-item>
                <q-item :key="188" :active="route().current()==='user.index' || route().current()==='user.create'|| route().current()==='user.edit' "
                        active-class="active-menu"
                        v-if="isAdmin" clickable class="default-list" @click="$inertia.get(route('user.inactive'))">
                    <q-item-section avatar><q-icon name="o_manage_accounts"/></q-item-section>
                    <q-item-section><q-item-label>User Accounts</q-item-label></q-item-section>
                </q-item>

                <q-item v-if="isAdmin"  :key="1888" :active="route().current()==='page.index' || route().current()==='page.create'|| route().current()==='page.edit' "
                        active-class="active-menu"
                        clickable class="default-list" @click="$inertia.get(route('page.index'))">
                    <q-item-section avatar><q-icon name="o_description"/></q-item-section>
                    <q-item-section><q-item-label>Pages</q-item-label></q-item-section>
                </q-item>


            </q-list>
        </q-drawer>

        <q-page-container class="default-background">
            <slot/>
        </q-page-container>

        <q-footer  class="bg-primary text-footer q-py-md">
<!--            <MobileFooter v-if="$q.screen.lt.sm"/>-->
            <FooterComponent/>
        </q-footer>

    </q-layout>
</template>

<script setup>
import {ref, watch, computed, onMounted, reactive} from 'vue'
import SideNav from "@/Components/SideNav.vue";
import TopBar from "@/Components/TopBar.vue";
import {useQuasar} from "quasar";
import {router, usePage} from "@inertiajs/vue3";
import {useMasterData} from "@/Store/useMasterData";
import {storeToRefs} from "pinia";
import MobileFooter from "@/Components/MobileFooter.vue";
import { initializeApp } from "firebase/app";
import { getMessaging,onMessage,getToken } from "firebase/messaging";
import FooterComponent from "@/Components/FooterComponent.vue";

const leftDrawerOpen = ref(false);
const toggleLeftDrawer = () =>{
    leftDrawerOpen.value = !leftDrawerOpen.value
}

const q = useQuasar();
const masterData = useMasterData();
const state=reactive({
    token:'',
    isTop:true
})
const notification=computed(()=>usePage().props.flash_notification)
const breadcrumbs=computed(()=>usePage().props.breadcrumbs)


const isAdmin = computed(() => !!usePage().props.roles?.find(item => item === 'Admin'));
const isManager = computed(() => !!usePage().props.roles?.find(item => item === 'Manager'));


watch(notification,(newVal,oldVal)=>{
    if (newVal) {
        const {type, message} = newVal;
        q.notify({type,message,closeBtn:true,classes:'accent'})
    }

},{immediate:true})

watch(usePage().props.breadcrumbs,(newVal,oldVal)=>{

    if (oldVal) {
        masterData.setBreadcrumbs(oldVal);
    }


},{immediate:true})

onMounted(()=>{
    const firebaseConfig = {
        apiKey: "AIzaSyCfe9cb6yhTcZ01nkz_vbdTCSNWBZ_pUgk",
        authDomain: "edu-kai-6a52d.firebaseapp.com",
        projectId: "edu-kai-6a52d",
        storageBucket: "edu-kai-6a52d.appspot.com",
        messagingSenderId: "132041864750",
        appId: "1:132041864750:web:ecc97d8c28fe0e811751bf",
        measurementId: "G-VZNJEWF7P1"
    };


    const app = initializeApp(firebaseConfig);

    const messaging = getMessaging(app);
    onMessage(messaging, (payload) => {
        masterData.setNotification(true)
        const {notification} = payload;
        q.notify({
            message: notification?.title,
            caption:notification.body,
            icon: notification.image || 'https://w7.pngwing.com/pngs/537/580/png-transparent-bell-notification-communication-information-icon.png',
            actions:[
                { label: 'Open', color: 'white', handler: () => {
                        // router.get(route)
                        const id=payload.data['key'];
                        router.get(route('notification.show',id),{},{
                            preserveState:false
                        })
                }
                }
            ]
        })
        console.log(payload);
        // ...
    });

    const submitToken=()=>{
        getToken(messaging, { vapidKey: 'BMDdBUyz03NWhQc6rj1JMxCXSemfn5intcvr-40W9zSo_YlsJT-kcQWG3K04HKY3aELzzmLEepdZAuxf_LOAqlI' })
            .then((token) => {
                state.token=token
                if (token) {
                    axios.post(route('token.upload'), {
                        token
                    }).then(res => console.log(res.data))
                        .catch(err => console.log(err));
                    // ...
                } else {
                    // Show permission request UI
                    console.log('No registration token available. Request permission to generate one.');
                    // ...
                }
            }).catch((err) => {
            console.log('An error occurred while retrieving token. ', err);
            // ...
        });
    }
    function initFirebaseMessagingRegistration() {
        if (q.platform.is.firefox) {
            if (Notification.permission !== "granted") {
                q.notify({
                    message: 'Do you want to receive an important notification',
                    color: 'primary',
                    avatar: 'https://w7.pngwing.com/pngs/537/580/png-transparent-bell-notification-communication-information-icon.png',
                    actions: [
                        { label: 'Grant', color: 'yellow', handler: () => {
                                Notification.requestPermission()
                                    .then(perm=>{
                                        console.log(perm);
                                        if (perm === 'granted') {
                                            submitToken();
                                        }
                                    })
                            } },
                        { label: 'Dismiss', color: 'white', handler: () => { /* ... */ } }
                    ]
                });
            }


            return;
        }


        Notification.requestPermission().then(permission=>{
            console.log("persmission is retrived ",permission);
            if (permission === 'granted') {
                submitToken();
            }
        })
        .catch(err=>{
            console.log('An error occurred while retrieving token. ', err);
        });

    }
    navigator.permissions.query({ name: 'geolocation' })
        .then(status=>console.log('status',status))
    initFirebaseMessagingRegistration();


});
const handleScroll=detail=>{
    const {position} = detail;
    if (position < 10) {
        state.isTop=true
    }else {
        state.isTop=false
    }
}
</script>
<style>
.footer-link{
    text-decoration: none;
}
</style>
