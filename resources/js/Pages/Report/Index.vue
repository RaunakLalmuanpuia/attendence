<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
            <div>
                <div class="text-title">Report</div>
            </div>
            <div>
                    <q-checkbox v-model="right" label="Sync with E-leave Portal" />
            </div>
        </div>
        <br/>
        <div class="row q-col-gutter-sm bg-white ">
            <div class="col-xs-12 col-sm-7">
                <div class=" bg-white q-pa-md">
                    <q-form @submit="submit" class="column q-gutter-sm">
                        <div class="text-lg text-grey-7 text-md">Filter</div>
                        <q-select v-model="form.office"
                                  :options="options.offices"
                                  outlined
                                  bg-color="white"
                                  label="Office"
                                  :rules="[
                                      val=>!!val || 'Please select office'
                                  ]"
                        />


                        <q-input v-model="form.fromDate"
                                 type="date"
                                 outlined
                                 label="Report starting from"
                                 bg-color="white"
                                 :rules="[
                                     val=>!!val || ''
                                 ]"
                        />
                        <q-input v-model="form.toDate"
                                 type="date"
                                 outlined
                                 label="Report Ends to"
                                 bg-color="white"
                        />
                        <div class="flex justify-end q-gutter-sm">
                            <q-btn type="submit" color="primary" label="Generate"/>
                            <q-btn @click="reset" color="negative" outline label="Reset"/>
                        </div>

                    </q-form>
                </div>
            </div>
            <div class="col-xs-12 col-sm-5">
                <div class=" column q-pa-md">
                        <div class="text-lg text-grey-7 text-md">Reports</div>
                        <q-list separator>
                            <q-item v-for="item in reports.data" :key="item.id">
                                <q-item-section avatar>
                                    <q-icon v-if="item.status==='Processed'" size="sm" color="positive" name="fiber_manual_record"/>
                                    <q-icon v-else size="sm" color="warning" name="fiber_manual_record"/>
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>{{item?.title}}</q-item-label>
                                    <q-item-label caption>Generated At : {{formatDate(item?.created_at)}}</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-btn :disable="item.status!=='Processed'" @click="handleDownload(item.path)" icon="download" outline size="sm" round/>
                                </q-item-section>
                            </q-item>
                            <div class="flex q-gutter-sm">
                                <q-btn :disable="!!!reports.prev_page_url" @click="$inertia.get(reports.prev_page_url)" flat round icon="o_chevron_left"/>
                                <q-btn :disable="!!!reports.next_page_url" @click="$inertia.get(reports.next_page_url)" flat round icon="o_chevron_right"/>
                            </div>
                        </q-list>

                </div>
            </div>
        </div>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {onMounted, reactive} from "vue";
import {date} from "quasar";

defineOptions({
    layout: MainLayout
})
const props = defineProps(['toDate','fromDate','offices','reports'])

const options=reactive({
    offices:[],
    users:[]
})
const form =useForm({
    office:null,
    user:null,
    fromDate:props.fromDate,
    toDate: props.toDate
})

const formatDate=val=>date.formatDate(val,'DD-MM-YYYY hh:mm a')
const reset=e=>{
    form.office=null;
    form.user=null;
    form.fromDate=props.fromDate
    form.toDate=null
}
const handleDownload=url=>{
    window.open(url,'_blank')
}
const fetchOfficeUser=val=>{
    if (val) {
        axios.get(route('report.users',val.value))
        .then(res=>{
            const {list} = res.data;
            options.users = list;
        })
        .catch(err=>options.users=[])
    }
}
const submit=e=>{
    form.transform(data=>({office_id:data.office.value,user_id:data.user?.value,...data}))
    .post(route('report.generate'),{
        preserveState: false
    });
}
onMounted(()=>{
    options.offices = props.offices;
})

</script>
