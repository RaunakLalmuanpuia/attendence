<template>
    <q-page class="container" padding>
        <div class="flex justify-between items-center">
                <div class="flex">
                    <q-btn icon="arrow_back_ios" round @click="$inertia.replace(route('account.active'))"/>
                    <div>
                        <div style="line-height: 0.9"  class="text-title">{{data?.full_name}}</div>
                        <div class="text-caption text-grey-7">Edit user account detail</div>
                    </div>
                </div>

            <div class="flex q-gutter-sm">
                <q-btn @click="handleActivation('activate')" v-if="!!data.deleted_at" color="primary" outline label="Activate" no-caps/>
                <q-btn @click="handleActivation('deactivate')" v-else outline label="Deactivate" no-caps/>
                <q-btn @click="onDelete" color="negative" label="Delete" no-caps/>
            </div>
        </div>
        <q-separator class="q-my-sm"/>
        <div class="row q-col-gutter-sm">
            <div class="col-xs-12 col-sm-7">
                <q-form @submit="submit" class="bg-white shadow-default q-pa-md">
                    <div class="text-lg text-weight-medium text-grey-7 q-mb-sm">Account Detail</div>
                    <div class="q-gutter-y-md">
                    <q-input v-model="form.employee_no"
                             label="EmploymentNo"

                             outlined
                             />

                    <q-input v-model="form.full_name"
                             label="Name"
                             :rules="[
                                 val=>!!val || 'Name is required'
                             ]"
                             outlined
                             />
                            </div>
                    <q-input v-model="form.mobile"
                             label="Mobile"
                             :error-message="form.errors?.mobile?.toString()"
                             :rules="[
                                 val=>!!val || 'Mobile is required'
                             ]"
                             outlined
                    />

                    <q-input v-model="form.designation"
                             label="Designation"
                             :rules="[
                                 val=>!!val || 'Designation is required'
                             ]"
                             outlined
                    />
                    <q-input v-model="form.password"
                             :type="type"
                             label="Password"

                             outlined>
                        <template #append>
                            <q-btn v-if="type==='password'" icon="visibility_off" round @click="type='text'"/>
                            <q-btn v-else icon="visibility" round @click="type='password'"/>
                        </template>
                    </q-input>


                    <div class="flex q-gutter-sm q-mt-sm">
                        <q-btn type="submit" label="Update" color="primary"/>
                        <q-btn @click="$inertia.replace(route('account.active'))" label="Cancel" color="negative"/> 
                    </div>
                </q-form>
            </div>
            <div class="col-xs-12 col-sm-5">
                <div class="bg-white shadow-default q-pa-sm">
                    <div class="text-lg text-weight-medium text-grey-7 q-mb-sm">Devices</div>

                    <q-list separator>
                        <q-item v-for="item in data.devices">
                            <q-item-section avatar>
                                <q-icon :color="item?.active?'positive':'warning'" name="fiber_manual_record"/>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{item?.name}}</q-item-label>
                                <q-item-label caption>{{item?.uid}}</q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                <div class="flex q-gutter-sm">
                                    <q-toggle
                                        @update:model-value="handleToggle(item)"
                                        v-model="item.active"
                                        color="green"
                                    />
                                </div>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </div>
            </div>
            <div class="col-xs-12">

            </div>
        </div>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {useQuasar} from "quasar";
import {reactive, ref} from "vue";
import {router, useForm} from "@inertiajs/vue3";

defineOptions({
    layout:MainLayout
})
const props=defineProps(['data'])
const q = useQuasar();
const type=ref('password')
const state=reactive({
    submitting:false
})
const form=useForm({
    employee_no:props.data.employee_no,
    full_name:props.data.full_name,
    designation:props.data.designation,
    mobile:props.data.mobile,
    password:props.data.password
})

const submit=e=>{
    form.put(route('account.update',props.data.id),{
        onStart:params => q.loading.show(),
        onFinish: params => q.loading.hide()
    })
}

const handleToggle=item=>{
    q.loading.show()
    axios.put(route('device.toggle',item.id))
    .then(res=>{
        const {message} = res.data;
        q.notify({
            type:'positive',
            message
        })
    })
    .catch(err=>{
        console.log(err)
    })
    .finally(()=>q.loading.hide())
}
const onDelete=e=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to delete?',
        ok:'Yes',
        cancel:'No'
    }).onOk(()=>{
        router.delete(route('account.destroy',props.data.id),{
            preserveState:false,
            onStart:params => state.submitting=true,
            onFinish:params => state.submitting=false
        })
    })
}

const handleActivation=(mode)=>{
    q.dialog({
        title:'Confirmation',
        message:mode==='activate'?'Do you want to Activate this account?':'Do you want to DeActivate this account?',
        ok:'Yes',
        cancel:'No'
    }).onOk(()=>{
        router.put(route(mode==='activate'?'account.activate':'account.deactivate',props.data.id),{
            preserveState:false,
            onStart:params => state.submitting=true,
            onFinish:params => state.submitting=false
        })
    })
}
</script>
