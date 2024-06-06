<template>
    <q-page padding class="container">
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">Configuration</div>
            </div>
            <div>

            </div>
        </div>
        <br/>
        <div class="row q-col-gutter-sm">
            <div v-for="item in offices" class="col-xs-12 col-sm-6">
                <div class="shadow-default bg-white q-pa-md">
                    <div class="flex justify-between">
                        <div class="flex items-center q-gutter-sm">
                            <div>
                                <QRCodeVue3
                                    @click="e=>handleQrClicked(item)"
                                    :width="60"
                                    :height="60"
                                    :value="item?.qr_code?.code"/>
                            </div>

                            <div>
                                <div class="text-lg">{{item?.name}}</div>
                                <div class="text-md text-grey-7">{{item?.district?.name}}</div>
                            </div>

                        </div>

                        <q-btn @click="$inertia.get(route('config.edit',item.id))" class="q-pa-none q-ma-none text-grey-7" icon="o_settings"/>
                    </div>
                    <br/>
                    <div class="flex justify-between items-center">
                        <div class="text-md text-grey-7">Latitude</div>
                        <div class="text-md text-bold">{{item?.lat}}</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-md text-grey-7">Longtitude</div>
                        <div class="text-md text-bold">{{item?.lng}}</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-md text-grey-7">Radius (Geofence)</div>
                        <div class="text-md text-bold">{{item?.radius}} Meter</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-md text-grey-7">Starting Time</div>
                        <div class="text-md text-bold">{{item?.start_time}}</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="text-md text-grey-7">Closing Time</div>
                        <div class="text-md text-bold">{{item?.close_time}}</div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="text-md text-grey-7">Grace Period</div>
                        <div class="text-md text-bold">{{item?.grace_period}}</div>
                    </div>
                    <q-separator class="q-my-sm"/>
                    <div class="flex q-gutter-sm">
                        <q-btn color="dark" @click="handleQrClicked(item)" icon="o_print" label="Print QR"/>
                    </div>

                </div>
            </div>
        </div>

        <q-dialog v-model="state.openPrint">
            <QrPrint :office="state.selected"/>
        </q-dialog>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import QRCodeVue3 from "qrcode-vue3";
import {reactive} from "vue";
import QrPrint from "@/Components/QrPrint.vue";

defineOptions({
    layout:MainLayout
})
const props = defineProps(['offices']);
const state=reactive({
    selected:null,
    openPrint:false
})
const handleQrClicked=item=>{
    console.log(item);
    state.selected = item;
    state.openPrint=true
}


</script>
