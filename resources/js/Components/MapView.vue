<template>
        <GoogleMap :api-key="MAP_KEY" style="width: 100%; height: 550px" :center="infoPosition" :zoom="15">

            <CustomMarker :options="{ position: yourLocation, anchorPoint: 'BOTTOM_CENTER' }">
                <div style="text-align: center">
                    <div class="text-weight-medium text-primary">{{welcomeText}}</div>
                    <img src="/assets/images/emp.jpeg" width="20" height="50" style="margin-top: 8px" />
                </div>
            </CustomMarker>


            <InfoWindow  :options="{ position: circleOption.center }">
                <div class="text-caption">{{office?.name}}</div>
            </InfoWindow>

            <Circle :options="circleOption" />
        </GoogleMap>

</template>
<script setup>
import {computed, defineComponent, onMounted, toRaw, ref} from "vue";
import { GoogleMap, CustomMarker,InfoWindow,Circle,Marker } from "vue3-google-map";
import utils from "@/Util/utils";

const {MAP_KEY} = utils();
const props=defineProps(['lat','lng','office','welcomeText'])


const yourLocation = computed(() => ({lat: Number(props?.lat), lng: Number(props?.lng)}));
const circleOption=computed(()=>({
    center:{
        lat:Number(props?.office.lat),
        lng:Number(props?.office.lng),
    },
    radius:Number(props?.office.radius)

}))


onMounted(()=>{


})

</script>
