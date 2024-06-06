<template>
    <q-date :event-color="eventColorFn" :events="eventFn" minimal  @navigation="navigate" class="q-mt-sm" flat/>
</template>
<script setup>

import {onMounted, reactive} from "vue";
import {date} from "quasar";

const state=reactive({
    events: [],
    holidays:[],
    attendances : []
})

const eventColorFn=val=>{
    const attendance=state.attendances.find(item=>{
        return date.formatDate(item?.signin_at, 'YYYY/MM/DD') === val;
    })
    console.log('att',attendance)
    if (attendance) {
        return attendance.type==='present'?'positive':'warning'
    }else{
        return 'negative';
    }
    return 'negative';

}
const navigate=view=>{
    const {year, month} = view;
    fetchUserAttendances(year,month)
}
const eventFn=val=>{
    const currDate=date.extractDate(val,'YYYY/MM/DD')
    if (currDate.getDay() === 6 || currDate.getDay()===0) {
        state.holidays.push(currDate)
        return false;
    }
    if (currDate.getTime() > Date.now()) {
        return false;
    }
    return true;

}
onMounted(()=>{
   fetchUserAttendances()
})
const fetchUserAttendances=(year,month)=>{
    axios.get(route('misc.attendances'),{
        params:{
            year,month
        }
    }).then(res=>{
            const {list} = res.data;
            state.attendances = list;
            state.events = list.map(item=>date.formatDate(item?.signin_at,'YYYY/MM/DD'));
        })
        .catch(err=>console.log(err))
}
</script>
