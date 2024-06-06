<template>
    <div>
        <div class="text-lg text-weight-medium">Attendance Record (Today)</div>
        <Doughnut v-if="!loading" :data="data" :options="options" />
    </div>
</template>
<script setup>
import {onMounted,ref, reactive} from "vue";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Doughnut } from 'vue-chartjs'

ChartJS.register(ArcElement, Tooltip, Legend)

const loading = ref(true);
const options = {
    responsive: true,
}
const data = reactive({
    labels: ['Present', 'Late', 'Absent'],
    datasets: [
        {
            backgroundColor: ['#41B883', '#f68c43', '#9d4f5b'],
            data: []
        }
    ]
})
onMounted(()=>{
    loading.value = true;
    axios.get(route('statistic.attendance'))
    .then(res=>{
        const {count_present,count_late,count_absent}=res.data
        const temp = [count_present, count_late, count_absent];
        data.datasets[0].data.push(...temp)
        loading.value = false;
    })
    .catch(err=>{
        console.log(err)
    })

})
</script>
