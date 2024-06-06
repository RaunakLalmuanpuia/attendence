<template>
    <q-toolbar style="min-height: 70px" class="flex justify-between bg-white shadow-default">
        <q-item @click="$inertia.replace(route('dashboard'),{},{preserveState:false})" clickable>
            <q-item-section avatar>
                <q-icon :color="(route().current()==='dashboard' || route().current()==='admin.dashboard' || route().current()==='manager.dashboard' )
                ?'secondary':'grey-9'" color="grey-9" name="o_dashboard"/>
            </q-item-section>
        </q-item>
        <q-item @click="$inertia.replace(route('attendance.index'),{},{preserveState:false})" clickable>
            <q-item-section avatar>
                <q-icon :color="route().current()==='attendance.index'?'secondary':'grey-9'" name="o_event"/>
            </q-item-section>
        </q-item>
        <q-item @click="$inertia.replace(route('profile.edit'),{},{preserveState:false})" clickable>
            <q-item-section avatar>
                <q-icon :color="route().current()==='profile.edit'?'secondary':'grey-9'" name="o_account_circle"/>
            </q-item-section>
        </q-item>
    </q-toolbar>
</template>
<script setup>


import {computed} from "vue";

const isDashboard=computed(()=>route().current()==='dashboard' || route().current()==='admin.dashboard' || route().current()==='manager.dashboard')
const isAttendance=computed(()=>route().current()==='attendance.index')
const isProfile=computed(()=>route().current()==='profile.edit')

</script>
