<template>
    <div @click="handleOpen" class="download-btn cursor-pointer  flex q-gutter-xs">
        <img :src="img" :ratio="1" width="32px"/>
        <div>
            <div class="download-caption text-black">{{caption}}</div>
            <div class="download-title text-black">{{title}}</div>
        </div>
    </div>
</template>
<script setup>

const props = defineProps(['img', 'title', 'caption', 'link']);

const handleOpen=e=>{
    window.open(props.link,'_blank')
}

</script>

<style scoped>
.download-btn{
    box-shadow: 1px 4px 4px 0px rgba(0,0,0,0.25);
    -webkit-box-shadow: 1px 4px 4px 0px rgba(0,0,0,0.25);
    -moz-box-shadow: 1px 4px 4px 0px rgba(0,0,0,0.25);
    padding: 4px 16px;
    border-radius: 8px;
    color: black;
}
.download-title{
    font-size: 18px;
    color: #000000;
    opacity: 1;
}
.download-caption{
    font-size: 14px;
    font-weight: 400;
    line-height: 0.7;
    opacity: 1;
    color: black;
}
</style>
