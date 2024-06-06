<template>
    <q-page padding class="container">
        <div class="flex justify-between items-center">
            <div class="text-title">
                Notifications
            </div>
        </div>
        <br/>
        <div>
            <q-stepper
                v-model="state.step"
                vertical
                class="no-shadow"
                color="primary"
                animated
            >
                <q-step
                    :name="1"
                    title="Notification Detail"
                    icon="campaign"
                    :done="state.step > 1"
                >
                    <q-form @submit="submitDetail" class="column q-gutter-sm">
                        <q-input v-model="detailForm.title"
                                 outlined
                                 bg-color="grey-1"
                                 label="Title"
                                 :rules="[
                                     val=>!!val || 'Title is required'
                                 ]"
                        />
                        <q-input v-model="detailForm.body"
                                 outlined
                                 bg-color="grey-1"
                                 label="Message"
                                 :rules="[
                                     val=>!!val || 'Message is required'
                                 ]"
                        />
                        <q-input v-model="detailForm.url"
                                 type="url"
                                 bg-color="grey-1"
                                 outlined
                                 label="Icon URL"
                        />

                        <q-file class="q-mt-md" outlined bg-color="grey-1" v-model="state.file" label="Choose File" counter max-files="12">
                            <template v-slot:before>
                                <q-icon name="article"/>
                            </template>

                            <template v-slot:append>
                                <q-icon v-if="model !== null" name="close" @click.stop.prevent="model = null" class="cursor-pointer" />
                                <q-icon name="create_new_folder" @click.stop.prevent />
                            </template>

                            <template v-slot:hint>
                                Maximum size of file is 10mb
                            </template>

                            <template v-slot:after>
                                <q-btn @click="handleFileUpload" round dense flat icon="send" />
                            </template>
                        </q-file>
                        <q-list separator>
                            <q-item v-for="file in state.attachments">
                                <q-item-section avatar><q-icon name="article"/></q-item-section>
                                <q-item-section>
                                    <q-item-label>{{file?.name}}</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-btn @click="removeAttachment(file)" color="negative" round icon="highlight_off"/>
                                </q-item-section>
                            </q-item>
                        </q-list>
                        <div>
                            <q-btn type="submit" color="primary" label="Next"/>
                        </div>

                    </q-form>
                </q-step>

                <q-step
                    :name="2"
                    title="Target"
                    icon="group"
                    :done="state.step > 2"
                >
                    <q-form @submit="submitUser">
                        <q-chip v-for="item in users" :key="item.value"
                                clickable
                                :outline="!!!userForm.users.find(user=>user.value===item.value)"
                                square
                                color="dark"
                                text-color="white"
                                :selected="!!userForm.users.find(user=>user.value===item.value)"
                                :label="item.label"
                                @update:selected="val=>handleTarget(item,val)"
                        />
                    </q-form>
                    <div class="flex q-gutter-sm q-mt-sm">
                        <q-btn @click="state.step=1" color="primary" outline label="Back"/>
                        <q-btn :disable="userForm.users?.length<=0" @click="state.step=3" color="primary" label="Next"/>
                    </div>
                </q-step>

                <q-step
                    :name="3"
                    title="Schedule at"
                    icon="timer"
                    :done="state.step > 3"
                >
                    <q-form @submit="submitSchedule" class="column q-gutter-md">
                        <q-toggle v-model="scheduleForm.now" color="accent" label="Notify targets ?"/>

<!--                        <q-input v-if="!scheduleForm.now" v-model="scheduleForm.schedule_date"-->
<!--                                 type="date"-->
<!--                                 outlined-->
<!--                                 label="Choose date time to schedule"-->
<!--                                 no-error-icon-->
<!--                                 :rules="[-->
<!--                                 val=>scheduleForm.now ? null :!!val || 'Date time is required'-->
<!--                             ]"-->
<!--                        >-->
<!--                            <template #append>-->
<!--                                <q-icon name="calendar"/>-->
<!--                            </template>-->
<!--                        </q-input>-->
<!--                        <q-input v-if="!scheduleForm.now" v-model="scheduleForm.schedule_time"-->
<!--                                 type="time"-->
<!--                                 outlined-->
<!--                                 label="Choose date time to schedule"-->
<!--                                 no-error-icon-->
<!--                                 :rules="[-->
<!--                                 val=>scheduleForm.now ? null :!!val || 'Date time is required'-->
<!--                             ]"-->
<!--                        >-->
<!--                            <template #append>-->
<!--                                <q-icon name="schedule"/>-->
<!--                            </template>-->
<!--                        </q-input>-->
<!--                        <q-separator class="q-my-sm"/>-->

                        <div class="flex ">
                            <q-btn @click="state.step=2" color="primary" outline label="Back"/>
                            <q-btn type="submit" class="q-ml-sm"  color="primary" label="Submit"/>
                        </div>
                    </q-form>

                </q-step>
                <q-step
                    :name="4"
                    title="Done"
                    icon="check"
                    :done="state.step > 3">
                    <div class="text-center full-width">
                        <div class="text-accent text-lg">You are done</div>
                        <div>
                            <q-icon size="42px" color="positive" name="check_circle"/>
                        </div>
                            <q-btn class="q-mt-md" @click="$inertia.get(route('notification.create'),{},{preserveState:false})"
                                   color="primary" outline label="New Notification"/>
                    </div>

                </q-step>
            </q-stepper>

        </div>
    </q-page>
</template>
<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import {reactive, toRaw} from "vue";
import {useQuasar} from "quasar";
import {useForm} from "@inertiajs/vue3";


defineOptions({
    layout:MainLayout
})

const props = defineProps(['users', 'offices']);
const detailForm=useForm({
    title:'',
    url:'',
    body:'',
})
const scheduleForm=useForm({
    schedule_date:'',
    schedule_time:'',
    now:false,
})
const userForm=useForm({
    users:[]
})
const state=reactive({
    step:1,
    file:null,
    attachments:[]
})
const q = useQuasar();
const handleTarget=(item,val)=>{
    if (val) {
        userForm.users.push(item);
    }else {
        const temp=userForm.users.filter(user => user.value !== item.value);
        userForm.users = temp;
    }
}
const submitSchedule=e=>{
    const data = detailForm.data();
    data['user_ids'] = userForm.users.map(val => val.value);
    data['attachments'] = state.attachments.map(item=>item.id)
    const merged = {...data, ...scheduleForm.data()};
    q.loading.show({message:'Submit...'})
    axios.post(route('notification.store'),merged)
        .then(res=>{
            const {message, data} = res.data;
            q.notify({message,type:'positive'})
            state.step = 4;
        })
        .catch(err=>{
            console.log(err);
        })
    .finally(()=>q.loading.hide())
}
const submitUser=e=>{
    state.step = 3;
}
const submitDetail=e=>{
    state.step = 2;
}

const handleFileUpload=e=>{
    if (!state.file) {
        q.notify({message:'Please select file before upload',type:'negative'})
        return;
    }
    let formData = new FormData();
    formData.append('attachment', state.file);
    q.loading.show({message:'Uploading file'})
    axios.post(route('attachment.store'),formData)
    .then(res=>{
        const {data,message} = res.data;
        q.notify({type:'positive',message})
        state.attachments.push(data);
        state.file = null;
    })
    .catch(err=>q.notify({message:'Something went wrong'}))
    .finally(()=>q.loading.hide())

}

const removeAttachment=attachment=>{
    q.dialog({
        title:'Confirmation',
        message:'Do you want to remove?',
        ok:'Yes',
        cancel:'No'
    })
    .onOk(()=>{
        q.loading.show()
        axios.delete(route('attachment.destroy',attachment.id))
        .then(res=>{
            const {message} = res.data;
            q.notify({type:'positive',message})
            const temp=state.attachments.filter(val => val.id !== attachment.id);
            state.attachments = temp;

        })
        .catch(err=>console.log(err))
        .finally(()=>q.loading.show())
    })
}

</script>
