<template>
    <q-header   height-hint="70" v-if="isAuthenticated"  :class="classObject">
        <q-toolbar style="min-height: 60px" class="q-px-lg">
            <q-btn  class="text-primary" round dense color="primary" icon="o_menu" @click="handleMenuClicked" />
            <q-toolbar-title>
                <q-breadcrumbs separator=">" class="text-primary text-md">
                    <q-breadcrumbs-el v-for="r in breadcrumbs"
                                      :key="r.name"
                                      @click="$inertia.replace(route(r.name))"
                                      :label="r.label"/>
                </q-breadcrumbs>
            </q-toolbar-title>
            <div class="flex items-center q-gutter-sm">
                <div v-show="$q.screen.gt.xs" style="line-height: 1">
                    <div class="text-lg text-weight-medium ">{{$page?.props?.auth?.user?.full_name}}</div>
                    <div class="text-sm text-weight-medium ">{{$page?.props?.auth?.user?.designation}}</div>
                </div>
                <q-btn-dropdown  dropdown-icon="o_account_circle">
                        <q-list dense>
                            <q-item clickable @click="$inertia.get(route('profile.edit'))">
                                <q-item-section>
                                    <q-item-label>Profile</q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-item clickable @click="$inertia.get(route('posting.index'))">
                                <q-item-section>
                                    <q-item-label>Change Office</q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-item clickable @click="$inertia.get(route('profile.change-password'))">
                                <q-item-section>
                                    <q-item-label>Change Password</q-item-label>

                                </q-item-section>
                            </q-item>
                            <q-item clickable @click="logout">
                                <q-item-section>
                                    <q-item-label>Log out</q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>

                </q-btn-dropdown>

                <q-icon size="21px" @click="$inertia.get(route('notification.list'))" class="cursor-pointer" name="notifications">
                    <q-badge v-if="hasNotification" floating color="negative" rounded/>
                </q-icon>
            </div>
        </q-toolbar>
    </q-header>
    <q-header v-else class="bg-transparent text-primary">
        <q-toolbar>
            <q-toolbar-title>
                Kai
            </q-toolbar-title>
        </q-toolbar>
    </q-header>
</template>
<script setup>

import {computed,toRef} from "vue";
import {usePage,router} from "@inertiajs/vue3";
import {useMasterData} from "@/Store/useMasterData";
import {storeToRefs} from "pinia";
import {LocalStorage} from "quasar";

const isAuthenticated=computed(()=>true)

const props=defineProps(['isTop'])
const emit=defineEmits(['onToggle'])
const masterData = useMasterData();
const {breadcrumbs,hasNotification} = storeToRefs(masterData);
const logout=()=>router.delete(route('login.destroy'))

const onTop = toRef(props.isTop);

const handleMenuClicked=e=>{
    emit('onToggle')
}

const classObject=computed(()=>({
    'bg-transparent':props.isTop,
    'text-primary':props.isTop,
    'bg-white shadow-bottom-5 text-primary':!props.isTop,
}))


</script>
