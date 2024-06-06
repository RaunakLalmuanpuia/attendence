<template>
    <q-layout>
        <q-page-container>
            <q-page>
                <div class="row fullscreen">
                    <div class="col-sm-6 column flex justify-center items-center border-right">
                        <q-carousel
                            style="min-width: 520px"
                            v-model="slide"
                            swipeable
                            animated
                            navigation
                            padding
                            autoplay
                            height="450px"
                            control-color="primary"
                            class="bg-transparent"
                        >
                            <q-carousel-slide v-for="image in slideItems" :name="image.name" >
                                <q-img width="450"  :src="image.src"/>
                            </q-carousel-slide>

                        </q-carousel>
                        <div class="flex justify-center items-center">
                            <q-btn @click="$inertia.get(route('home'))" color="primary" label="Back to Home" no-caps outline/>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 flex justify-center items-center">
                        <div class="forgot-card">
                            <div class="column ">

                                <div>
                                    <div class="login-title q-mb-md">Login via OTP</div>
                                </div>

                                <q-input v-if="!sent" v-model="form.mobile"
                                         class="q-mt-md"
                                         outlined
                                         bg-color="white"
                                         label="Mobile No"
                                         no-error-icon
                                         mask="##########"
                                         :rules="[
                                             val=>!!val || 'Mobile No is required'
                                         ]"
                                />

                                <q-input v-else v-model="form.otp"

                                         mask="####"
                                         placeholder="####"
                                         outlined
                                         bg-color="white"
                                         no-error-icon
                                         :rules="[
                                             val=>!!val || 'OTP is required'
                                         ]"
                                         label="OTP">

                                </q-input>

                                <div>
                                    <q-btn @click="sendOtp" v-if="!sent" :loading="submitting" :disable="form.mobile.length!=10"  style="min-height: 48px" color="primary" label="Send OTP" class="full-width"/>
                                    <q-btn @click="verifyOtp" v-else :loading="submitting" :disable="form.otp.length!=4"  style="min-height: 48px" color="positive" label="Verify OTP" class="full-width"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </q-page>
        </q-page-container>
    </q-layout>
</template>
<script setup>


import {reactive, ref} from "vue";
import {router} from "@inertiajs/vue3";
import {useQuasar} from "quasar";

const slideItems=ref([
    {src:'/assets/images/c1.jpeg',name:1},
    {src:'/assets/images/c2.png',name:2},
])
const q = useQuasar();
const submitting = ref(false);
const sent = ref(false);
const slide=ref(1)
const form=reactive({
    mobile:'',
    otp:''
})
const toggle=ref('password')
const togglePassword=e=>{
    toggle.value=toggle.value==='password'?'text':'password'
}

const sendOtp=e=>{
    q.loading.show();
    axios.post(route('otp.send', form.mobile))
        .then(res => {
            const {message} = res.data;
            q.notify({message,type:'positive'})
            sent.value = true;
        })
        .catch(e=>{
            q.notify({message:'Mobile no does not exist',type:'negative'})
        })
        .finally(() => q.loading.hide());
}

const verifyOtp=e=>{
    router.post(route('otp.verify'),{
        mobile:form.mobile,
        otp: form.otp
    },{
        onStart: params => submitting.value = true,
        onFinish: params => submitting.value = false
    })
}
</script>
<style scoped>
.login-title{
    font-weight: 600;
    color: #444;
    font-size: 21px;
}

.forgot-card{
    padding: 8px;
}
.border-right{
    border-right-width: 1px;
    border-right-style: solid;
    border-color: rgba(0, 0, 0, 0.12);
    transition: background 0.3s, opacity 0.3s;
}
</style>
