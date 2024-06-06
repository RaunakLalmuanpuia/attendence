<template>
    <div>
        <div class="text-lg text-weight-medium">Attendance Record</div>
        <div id="attendance_percent" style="height: 20vh"></div>
    </div>
</template>
<script setup>
import {onMounted} from "vue";
const props=defineProps(['present','late','absent'])

onMounted(()=>{
    new Chartist.Pie('#attendance_percent', {
        series: [props.present, props.late, props.absent]
    }, {
        donut: true,
        donutWidth: 60,
        donutSolid: true,
        startAngle: 270,
        showLabel: true
    });
})
</script>
