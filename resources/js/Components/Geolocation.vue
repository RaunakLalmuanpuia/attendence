<template>
        <GoogleMap :api-key="MAP_KEY" style="width: 100%; height: 550px" :center="infoPosition" :zoom="20">

            <CustomMarker :options="{ position: attendanceLocation, anchorPoint: 'BOTTOM_CENTER' }">
                <div style="text-align: center">
                    <div class="text-weight-medium text-primary">Hi my name is {{user?.full_name}}</div>
                    <div class="text-caption">Sign-in from here</div>
                    <img src="/assets/images/emp.jpeg" width="20" height="50" style="margin-top: 8px" />
                </div>
            </CustomMarker>

            <CustomMarker v-if="!!attendance?.signout_lat" :options="{ position: signoutLocation, anchorPoint: 'BOTTOM_CENTER' }">
                <div style="text-align: center">
                    <div class="text-caption">I am sign-out from here</div>
                    <img src="/assets/images/emp.jpeg" width="20" height="50" style="margin-top: 8px" />
                </div>
            </CustomMarker>
            <InfoWindow  :options="{ position: infoPosition }">
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
const props=defineProps(['attendance'])


const attendanceLocation = computed(() => ({lat: Number(props.attendance?.lat), lng: Number(props.attendance?.lng)}));
const signoutLocation = computed(() => ({lat: Number(props.attendance?.signout_lat), lng: Number(props.attendance?.signout_lng)}));
const infoPosition = computed(() => ({lat: Number(props.attendance?.office.lat), lng: Number(props.attendance?.office.lng)}));
const circleOption=computed(()=>({
    center:{
        lat:Number(props.attendance?.office.lat),
        lng:Number(props.attendance?.office.lng),
    },
    radius:Number(props.attendance?.office.radius)

}))
const user = computed(() => props.attendance?.user);
const office = computed(() => props.attendance?.office);

onMounted(()=>{


})

</script>
