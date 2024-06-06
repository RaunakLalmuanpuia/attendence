<template>
    <q-layout>
        <q-page-container>
            <q-page>
                <div class="row fullscreen">
                    <div v-if="$q.screen.gt.sm" class="column col-sm-6 flex justify-center items-center border-right">
                            <q-carousel
                                style="width: 620px"
                                v-model="slide"
                                swipeable
                                animated
                                navigation
                                padding
                                :autoplay="true"
                                height="550px"
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
                        <div class="login-card">
                            <q-form class="column " @submit="login">

                                <div class="flex items-center q-gutter-sm">
                                    <q-img width="60px" src="/assets/images/logo.png"/>
                                   <div>
                                       <div class="login-title">KAI</div>
                                       <div class="login-caption"> Login to get start</div>
                                   </div>
                                </div>
                                <div class="error-msg">{{form.errors?.email}}</div>

                                <label class="text-md text-grey-7 q-mt-md q-mb-xs" for="mobile">Mobile</label>
                                <q-input v-model="form.mobile"
                                         id="mobile"
                                         outlined
                                         bg-color="white"

                                         no-error-icon
                                         mask="##########"
                                         :rules="[
                                             val=>!!val || 'Mobile No is required'
                                         ]"
                                />
                                <label class="text-md text-grey-7 q-mb-xs" for="mobile">Password</label>
                                <q-input v-model="form.password"
                                         :type="toggle"
                                         outlined
                                         bg-color="white"
                                         no-error-icon
                                         :rules="[
                                             val=>!!val || 'Password is required'
                                         ]"
                                         >
                                    <template #append>
                                        <q-icon v-if="toggle==='password'" @click="togglePassword" name="o_visibility_off"/>
                                        <q-icon v-else @click="togglePassword" name="o_visibility"/>
                                    </template>
                                </q-input>
                                <div class="text-right">
                                    <a @click="$inertia.get(route('forgot.password'))" class="text-accent" href="#">Forgot Password?</a>
                                </div>
                                <div>
                                    <q-separator/>
                                </div>

                                <p class="text-grey-7">By entering Mobile & Password you agree with our <a :href="route('page.term')" class="text-accent">Terms and Conditions</a></p>
                                <div>
                                    <q-btn :loading="submitting" type="submit" style="min-height: 48px" color="primary" label="Login" class="full-width"/>
                                </div>

                            </q-form>
                        </div>
                    </div>
                </div>
            </q-page>
        </q-page-container>
    </q-layout>
</template>
<script setup>


import {reactive, ref} from "vue";
import {useForm} from "@inertiajs/vue3";

const slideItems=ref([
    {src:'/assets/images/Citizen.png',name:1},
    {src:'/assets/images/Teacher.png',name:2},
])
const submitting = ref(false);
const slide=ref(1)
const form=useForm({
    mobile:'',
    password:''
})
const toggle=ref('password')
const togglePassword=e=>{
    toggle.value=toggle.value==='password'?'text':'password'
}

const login=e=>{
    form.post(route('login.store'),{
        onStart:params => submitting.value=true,
        onFinish:params => submitting.value=false
    })
}
</script>
<style>
.login-title{
    line-height: 0.9;
    font-weight: 600;
    color: #444;
    font-size: 21px;
}
.error-msg{
    color: #bf6262;
    font-size: 14px;
    font-style: italic;
}
.login-caption{
    font-weight: 400;
    color: #434242;
    font-size: 14px;
}
.login-card{
    padding: 16px;
}
.border-right{
    border-right-width: 1px;
    border-right-style: solid;
    border-color: rgba(0, 0, 0, 0.12);
    transition: background 0.3s, opacity 0.3s;
}
</style>
