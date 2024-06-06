import {defineStore} from "pinia";
import {LocalStorage} from "quasar";

export const useMasterData = defineStore('masterData', {
    state: () => ({
        breadcrumbs: [],
        messages:[],
        notification:false,
    }),
    getters: {
        hasNotification: (state) => state.notification,
    },
    actions: {
        setBreadcrumbs:function (val) {
            this.breadcrumbs=val;
        },
        setMessages:function (param){
            this.messages=param
        },
        setNotification:function (param){
            if (param) {
                LocalStorage.set('notification',param)
            }else{
                LocalStorage.remove('notification')
            }
            this.notification=param
        }
    },
})
