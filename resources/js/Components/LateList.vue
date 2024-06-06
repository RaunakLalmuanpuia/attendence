<template>
    <q-list   class="full-height">
        <q-item dense>
            <q-item-section>
                <q-item-label class="text-weight-medium">Recently joined users.</q-item-label>
            </q-item-section>
        </q-item>
        <q-item v-for="(user,index) in state.list" :key="index">
            <q-item-section avatar>
                <q-avatar text-color="secondary" size="md" color="warning">{{user?.full_name[0].toUpperCase()}}</q-avatar>
            </q-item-section>
            <q-item-section>
                <q-item-label>{{user?.full_name}}</q-item-label>
                <q-item-label caption>{{user?.designation}}</q-item-label>
            </q-item-section>
        </q-item>
    </q-list>
</template>
<script setup>

import {onMounted, reactive} from "vue";
import {useQuasar} from "quasar";

const q = useQuasar();
const state=reactive({
    list:[]
})


onMounted(()=>{
    axios.get(route('statistic.late'))
    .then(res=>{
        const {list} = res.data;
        state.list = list;
    })
    .catch(err=>{
        console.log(err)
    })

})
</script>
