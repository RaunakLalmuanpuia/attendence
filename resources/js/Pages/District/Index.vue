<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">Master Data</div>
                <div class="text-caption text-grey-7">Edit of master data are to be careful which may leads to a disruption of application</div>
            </div>

            <div>
                <q-btn @click="e=>state.openCreate=true" class="sized-btn" color="primary" label="New District"/>
            </div>
        </div>
        <br/>
            <q-tabs
                stretch
                shrink
                v-model="state.tab"
                align="start"
                @update:model-value="handleNavigation"
            >
                <q-tab name="office.index"  label="Offices" />
                <q-tab name="district.index"  label="Districts" />
            </q-tabs>
        <br/>
        <q-list separator class="bg-white shadow-default ">
            <q-item v-for="(item,index) in list" :key="index">
                <q-item-section avatar>
                    <q-chip square :label="item.code"/>
                </q-item-section>
                <q-item-section>
                    <q-item-label>{{item.name}}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <q-btn-dropdown dropdown-icon="more_vert">
                        <q-list  dense>
                            <q-item  dense clickable @click="handleEdit(item)">
                                <q-item-section>
                                    <q-item-label>Edit</q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-item dense clickable @click="e=>deleteConfirm(item)">
                                <q-item-section>
                                    <q-item-label>Delete</q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-btn-dropdown>
                </q-item-section>
            </q-item>
        </q-list>

        <q-dialog v-model="state.openCreate">
            <Create/>
        </q-dialog>

        <q-dialog v-model="state.openEdit">
            <Edit :district="state.selected"/>
        </q-dialog>

    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {reactive} from "vue";
import {router} from '@inertiajs/vue3';
import {useQuasar} from "quasar";
import Create from "@/Pages/District/Create.vue";
import Edit from "@/Pages/District/Edit.vue";

defineOptions({
    layout:MainLayout
})
const props = defineProps(['list']);

const q = useQuasar();
const state=reactive({
    tab: route().current(),
    openCreate:false,
    openEdit:false,
    selected:null
})

const handleEdit=item=>{
    state.selected=item;
    state.openEdit = true;
}
const deleteConfirm=item=>{
    q.dialog({
        title:'Confirmation',
        message:'Are you sure to delete',
        cancel:'No',
        ok:'Yes'
    }).onOk(()=>{
        router.delete(route('district.destroy',item.id),{
            onStart:params => q.loading.show(),
            onFinish:params => q.loading.hide()
        })
    })
}
const handleNavigation=(value)=>{
    router.get(route(value))
}
</script>
