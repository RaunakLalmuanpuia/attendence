<template>
    <div class="q-pa-sm">
        <div class="text-lg text-weight-medium">District wise report (current week)</div>
        <Bar :style="style" v-if="!loading" :data="data" :options="options"/>

    </div>
</template>
<script setup>
import {onMounted, reactive, ref} from "vue";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'
import {Bar} from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)
const loading = ref(true);
const data = reactive({
    labels: [ ],
    datasets: [

    ]
})
const style=reactive({
    height: `500px`,
    position: 'relative'
})
const options = {
    responsive: true,
}
onMounted(() => {
    axios.get(route('statistic.district'))
        .then(res=>{
            const {districts} = res.data;
            const presentData={
                label: 'Present',
                borderWidth: 2,
                borderColor: '#369',
                backgroundColor: '#4dc4ad',
                borderRadius: 16,
                data:[]
            }
            const lateData={
                label: 'Late',
                borderWidth: 2,
                borderColor: '#369',
                backgroundColor: '#f68c43',
                borderRadius: 16,
                data:[]
            }

            let presents= [];
            let lates= [];
            districts.forEach((district,index,values)=>{
                data.labels.push(district.name)
                let presentCount=district.attendances.reduce((acc,val,index)=>{
                    let count = val.type === 'present' ? 1 : 0;
                    return acc+count;
                },0)
                let lateCount=district.attendances.reduce((acc,val,index)=>{
                    let count = val.type !== 'present' ? 1 : 0;
                    return acc+count;
                },0)
                presents.push(presentCount);
                lates.push(lateCount);
            })
            presentData.data = presents;
            lateData.data = lates;
            data.datasets.push(presentData)
            data.datasets.push(lateData)
            loading.value=false
        })
        .catch(err=>{
            console.log(err);
        })
})
</script>
