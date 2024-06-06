<template>
    <q-card style="width: 450px" class="q-pa-sm">
        <div class="print-hide flex justify-between items-center">
            <div class="text-lg text-weight-medium">Print QR</div>
            <q-btn size="sm" round outline icon="close" v-close-popup/>
        </div>
        <q-separator class="q-my-sm"/>
        <div class="column justify-between items-center">
            <QRCodeVue3
                :width="250"
                :height="250"
                :value="props?.office?.qr_code?.code"/>
            <br/>
            <div class="text-lg text-weight-medium print-only">{{props?.office?.name}}</div>
            <div class="text-md print-only">{{props?.office?.district?.name}}</div>
        </div>
        <q-separator class="q-my-sm"/>
        <div class="flex justify-end q-gutter-sm">
            <q-btn class="print-hide" @click="handlePrint" color="primary" label="Print"/>
            <q-btn class="print-hide" color="negative" outline label="Close" v-close-popup/>
        </div>
    </q-card>
</template>
<script setup>
import QRCodeVue3 from "qrcode-vue3";

const props=defineProps(['office']);


const handlePrint=e=>{
    window.print();
}
</script>
