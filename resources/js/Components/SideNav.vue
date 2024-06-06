<template>
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
        <q-item :key="'attendance.index'" :active="route().current()==='attendance.index'"
                active-class="active-menu"
                clickable class="default-list" @click="$inertia.get(route('attendance.index'))">
            <q-item-section avatar><q-icon name="o_event"/></q-item-section>
            <q-item-section><q-item-label>Attendance History</q-item-label></q-item-section>
        </q-item>
        <q-item :key="1123" :active="route().current()==='office.attendances'"
                active-class="active-menu"
                v-if="isAdmin || isManager" clickable class="default-list" @click="$inertia.get(route('office.attendances'))">
            <q-item-section avatar><q-icon name="o_fax"/></q-item-section>
            <q-item-section><q-item-label>Attendances</q-item-label></q-item-section>
        </q-item>
        <q-item :key="1343" :active="route().current()==='account.inactive' || route().current()==='account.active' "
                active-class="active-menu"
                v-if="isAdmin || isManager" clickable class="default-list" @click="$inertia.get(route('account.inactive'))">
            <q-item-section avatar><q-icon name="o_manage_accounts"/></q-item-section>
            <q-item-section><q-item-label>Accounts</q-item-label></q-item-section>
        </q-item>
        <q-item :key="1123" :active="route().current()==='notification.index'"
                active-class="active-menu"
                v-if="isAdmin || isManager" clickable class="default-list" @click="$inertia.get(route('notification.index'))">
            <q-item-section avatar><q-icon name="o_campaign"/></q-item-section>
            <q-item-section><q-item-label>Notification</q-item-label></q-item-section>
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

        <q-item :key="1888" :active="route().current()==='page.index' || route().current()==='page.create'|| route().current()==='page.edit' "
                active-class="active-menu"
                clickable class="default-list" @click="$inertia.get(route('page.index'))">
            <q-item-section avatar><q-icon name="o_description"/></q-item-section>
            <q-item-section><q-item-label>Pages</q-item-label></q-item-section>
        </q-item>


    </q-list>

</template>
<script setup>

import {computed,reactive} from "vue";
import {router, usePage} from "@inertiajs/vue3";
const isAdmin = computed(() => !!usePage().props.roles?.find(item => item === 'Admin'));
const isManager = computed(() => !!usePage().props.roles?.find(item => item === 'Manager'));
const menus=reactive({
     adminMenu:[
        {route_name:'dashboard',label:'Dashboard',icon:'o_dashboard',childrens:['dashboard.admin','dashboard.manager']},
        {route_name:'attendance.index',label:'Time and Attendance',icon:'o_event',childrens:['attendance.index','attendance.show']},
        {route_name:'office.attendances',label:'Office Attendances',icon: 'o_fax',childrens:['office.attendances']},
        {route_name:'account.inactive',label:'Accounts',icon: 'o_manage_accounts',childrens:['account.inactive','account.active','account.edit']},
        {route_name:'office.config.index',label:'Configuration',icon: 'o_settings',childrens:['office.config.index','office.config.edit']},
        {route_name:'office.index',label:'Master Data',icon: 'o_storage',childrens:['office.index','district.index']},
        {route_name:'user.inactive',label:'Users',icon: 'o_manage_accounts',childrens:['user.index','user.create','user.edit']},
    ],
     managerMenu:[
        {route_name:'dashboard',label:'Dashboard',icon:'o_dashboard'},
        {route_name:'attendance.index',label:'Time and Attendance',icon:'o_event'},
        {route_name:'office.attendances',label:'Office Attendances',icon: 'o_fax'},
        {route_name:'account.inactive',label:'Accounts',icon: 'o_manage_accounts'},
        {route_name:'office.config.index',label:'Configuration',icon: 'o_settings'},
    ],
     userMenu:[
        {route_name:'dashboard',label:'Dashboard',icon:'o_dashboard'},
        {route_name:'attendance.index',label:'Time and Attendance',icon:'o_event'},
    ]


})

const handleNavigation=routeName=>{
    router.get(route(routeName), {},{
        preserveState: false,

    })
}


</script>
<style>
.side-nav{
    box-shadow: 0px 2px 8px #E6E6E6;
    border-right: 1px solid #E3E3E3;
}
.default-list{
    max-height: 8px !important;
    min-height: 42px !important;
}
.q-item__section--avatar{
    min-width: 46px !important;
}
</style>
