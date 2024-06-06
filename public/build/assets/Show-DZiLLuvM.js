import{j as S,r as $,T as U,o as m,c as p,w as d,g as l,a,h as u,t as w,k as N,Q as g,u as n,e as F,f as A,F as B,O as x,d as I,i as h,l as O}from"./app-B6ljmE4m.js";import{Q as E}from"./QForm-BUpyPIkE.js";import{_ as T,Q as j,a as L,b as V,c as Q}from"./MainLayout-Dp50SWY5.js";import{Q as M}from"./QLayout-CQJIOMGO.js";import{u as P}from"./use-quasar-DJymb8OE.js";import"./selection-CBxV_n-G.js";import"./FooterComponent-BOyJhOv3.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const Y={class:"flex justify-between items-center"},z={class:"flex"},G={style:{"line-height":"0.9"},class:"text-title"},H=l("div",{class:"text-caption text-grey-7"},"Edit user account detail",-1),J={class:"flex q-gutter-sm"},K={class:"row q-col-gutter-sm"},R={class:"col-xs-12 col-sm-7"},W=l("div",{class:"text-lg text-weight-medium text-grey-7 q-mb-sm"},"Account Detail",-1),X={class:"flex q-gutter-sm q-mt-sm"},Z={class:"col-xs-12 col-sm-5"},ee={class:"bg-white shadow-default q-pa-sm"},te=l("div",{class:"text-lg text-weight-medium text-grey-7 q-mb-sm"},"Devices",-1),ae={class:"flex q-gutter-sm"},oe=l("div",{class:"col-xs-12"},null,-1),me=Object.assign({layout:T},{__name:"Show",props:["data"],setup(f){const r=f,c=P(),b=S("password"),_=$({submitting:!1}),s=U({employee_no:r.data.employee_no,full_name:r.data.full_name,designation:r.data.designation,mobile:r.data.mobile,password:r.data.password}),q=i=>{s.put(route("account.update",r.data.id),{onStart:e=>c.loading.show(),onFinish:e=>c.loading.hide()})},C=i=>{c.loading.show(),axios.put(route("device.toggle",i.id)).then(e=>{const{message:y}=e.data;c.notify({type:"positive",message:y})}).catch(e=>{console.log(e)}).finally(()=>c.loading.hide())},D=i=>{c.dialog({title:"Confirmation",message:"Do you want to delete?",ok:"Yes",cancel:"No"}).onOk(()=>{x.delete(route("account.destroy",r.data.id),{preserveState:!1,onStart:e=>_.submitting=!0,onFinish:e=>_.submitting=!1})})},k=i=>{c.dialog({title:"Confirmation",message:i==="activate"?"Do you want to Activate this account?":"Do you want to DeActivate this account?",ok:"Yes",cancel:"No"}).onOk(()=>{x.put(route(i==="activate"?"account.activate":"account.deactivate",r.data.id),{preserveState:!1,onStart:e=>_.submitting=!0,onFinish:e=>_.submitting=!1})})};return(i,e)=>(m(),p(M,{class:"container",padding:""},{default:d(()=>{var y;return[l("div",Y,[l("div",z,[a(u,{icon:"arrow_back_ios",round:"",onClick:e[0]||(e[0]=o=>i.$inertia.replace(i.route("account.active")))}),l("div",null,[l("div",G,w((y=f.data)==null?void 0:y.full_name),1),H])]),l("div",J,[f.data.deleted_at?(m(),p(u,{key:0,onClick:e[1]||(e[1]=o=>k("activate")),color:"primary",outline:"",label:"Activate","no-caps":""})):(m(),p(u,{key:1,onClick:e[2]||(e[2]=o=>k("deactivate")),outline:"",label:"Deactivate","no-caps":""})),a(u,{onClick:D,color:"negative",label:"Delete","no-caps":""})])]),a(N,{class:"q-my-sm"}),l("div",K,[l("div",R,[a(E,{onSubmit:q,class:"bg-white shadow-default q-pa-md"},{default:d(()=>{var o,v;return[W,a(g,{modelValue:n(s).employee_no,"onUpdate:modelValue":e[3]||(e[3]=t=>n(s).employee_no=t),label:"EmploymentCode",rules:[t=>!!t||"EmploymentCode is required"],outlined:""},null,8,["modelValue","rules"]),a(g,{modelValue:n(s).full_name,"onUpdate:modelValue":e[4]||(e[4]=t=>n(s).full_name=t),label:"Name",rules:[t=>!!t||"Name is required"],outlined:""},null,8,["modelValue","rules"]),a(g,{modelValue:n(s).mobile,"onUpdate:modelValue":e[5]||(e[5]=t=>n(s).mobile=t),label:"Mobile","error-message":(v=(o=n(s).errors)==null?void 0:o.mobile)==null?void 0:v.toString(),rules:[t=>!!t||"Mobile is required"],outlined:""},null,8,["modelValue","error-message","rules"]),a(g,{modelValue:n(s).designation,"onUpdate:modelValue":e[6]||(e[6]=t=>n(s).designation=t),label:"Designation",rules:[t=>!!t||"Designation is required"],outlined:""},null,8,["modelValue","rules"]),a(g,{modelValue:n(s).password,"onUpdate:modelValue":e[9]||(e[9]=t=>n(s).password=t),type:b.value,label:"Password",outlined:""},{append:d(()=>[b.value==="password"?(m(),p(u,{key:0,icon:"visibility_off",round:"",onClick:e[7]||(e[7]=t=>b.value="text")})):(m(),p(u,{key:1,icon:"visibility",round:"",onClick:e[8]||(e[8]=t=>b.value="password")}))]),_:1},8,["modelValue","type"]),l("div",X,[a(u,{type:"submit",label:"Update",color:"primary"}),a(u,{onClick:e[10]||(e[10]=t=>i.$inertia.replace(i.route("account.active"))),label:"Cancel",color:"negative"})])]}),_:1})]),l("div",Z,[l("div",ee,[te,a(j,{separator:""},{default:d(()=>[(m(!0),F(B,null,A(f.data.devices,o=>(m(),p(L,null,{default:d(()=>[a(V,{avatar:""},{default:d(()=>[a(I,{color:o!=null&&o.active?"positive":"warning",name:"fiber_manual_record"},null,8,["color"])]),_:2},1024),a(V,null,{default:d(()=>[a(Q,null,{default:d(()=>[h(w(o==null?void 0:o.name),1)]),_:2},1024),a(Q,{caption:""},{default:d(()=>[h(w(o==null?void 0:o.uid),1)]),_:2},1024)]),_:2},1024),a(V,{side:""},{default:d(()=>[l("div",ae,[a(O,{"onUpdate:modelValue":[v=>C(o),v=>o.active=v],modelValue:o.active,color:"green"},null,8,["onUpdate:modelValue","modelValue"])])]),_:2},1024)]),_:2},1024))),256))]),_:1})])]),oe])]}),_:1}))}});export{me as default};
