import{r as $,T as j,o as m,c,w as g,a as i,g as l,Q as d,u as o,h as u}from"./app-B6ljmE4m.js";import{Q as O}from"./QSelect-Jg-3Vnic.js";import{Q as z}from"./QForm-BUpyPIkE.js";import{Q as M}from"./QLayout-CQJIOMGO.js";import{_ as T}from"./MainLayout-Dp50SWY5.js";import"./QChip-D021voJ6.js";import"./rtl-DFPa-2ov.js";import"./selection-CBxV_n-G.js";import"./FooterComponent-BOyJhOv3.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./use-quasar-DJymb8OE.js";const D=l("div",{class:"flex justify-between items-center"},[l("div",null,[l("div",{class:"text-title"},"New User")])],-1),I=l("br",null,null,-1),R={class:"col-xs-12"},A={class:"col-xs-12"},E={class:"col-xs-12"},G={class:"col-xs-12"},H={class:"col-xs-12"},J={class:"col-xs-12"},K={class:"col-xs-12"},L={class:"flex q-gutter-sm"},ne=Object.assign({layout:T},{__name:"Create",props:["roles","offices"],setup(f){const t=$({submitting:!1,toggle:"password"}),e=j({full_name:"",designation:"",mobile:"",password:"",role:null,office:null}),p=a=>t.toggle=t.toggle==="password"?"text":"password",P=a=>{e.transform(r=>{var n;return{office_id:(n=r==null?void 0:r.office)==null?void 0:n.value,...r}}).post(route("user.store"),{onStart:r=>t.submitting=!0,onFinish:r=>t.submitting=!1})};return(a,r)=>(m(),c(M,{class:"container",padding:""},{default:g(()=>[D,I,i(z,{class:"row q-col-gutter-sm",style:{"max-width":"720px"},onSubmit:P},{default:g(()=>{var n,b,_,w,V,v,x,y,S,Q,h,k,U,q,C,N,B,F;return[l("div",R,[i(d,{modelValue:o(e).full_name,"onUpdate:modelValue":r[0]||(r[0]=s=>o(e).full_name=s),error:!!((n=o(e).errors)!=null&&n.full_name),"error-message":(_=(b=o(e).errors)==null?void 0:b.full_name)==null?void 0:_.toString(),rules:[s=>!!s||"Full Name is required"],"bg-color":"white",label:"Name","no-error-icon":"",outlined:""},null,8,["modelValue","error","error-message","rules"])]),l("div",A,[i(d,{modelValue:o(e).designation,"onUpdate:modelValue":r[1]||(r[1]=s=>o(e).designation=s),error:!!((w=o(e).errors)!=null&&w.designation),"error-message":(v=(V=o(e).errors)==null?void 0:V.designation)==null?void 0:v.toString(),"bg-color":"white",label:"Designation","no-error-icon":"",outlined:""},null,8,["modelValue","error","error-message"])]),l("div",E,[i(d,{modelValue:o(e).mobile,"onUpdate:modelValue":r[2]||(r[2]=s=>o(e).mobile=s),error:!!((x=o(e).errors)!=null&&x.mobile),"error-message":(S=(y=o(e).errors)==null?void 0:y.mobile)==null?void 0:S.toString(),rules:[s=>!!s||"Mobile is required"],mask:"##########","bg-color":"white",label:"Mobile","no-error-icon":"",outlined:""},null,8,["modelValue","error","error-message","rules"])]),l("div",G,[i(O,{modelValue:o(e).role,"onUpdate:modelValue":r[3]||(r[3]=s=>o(e).role=s),error:!!((Q=o(e).errors)!=null&&Q.role),"error-message":(k=(h=o(e).errors)==null?void 0:h.role)==null?void 0:k.toString(),options:f.roles,"bg-color":"white",label:"Role","no-error-icon":"",outlined:""},null,8,["modelValue","error","error-message","options"])]),l("div",H,[i(O,{modelValue:o(e).office,"onUpdate:modelValue":r[4]||(r[4]=s=>o(e).office=s),error:!!((U=o(e).errors)!=null&&U.office_id),"error-message":(C=(q=o(e).errors)==null?void 0:q.office_id)==null?void 0:C.toString(),options:f.offices,rules:[s=>!!s||"Office is required"],"bg-color":"white",label:"Office","no-error-icon":"",outlined:""},null,8,["modelValue","error","error-message","options","rules"])]),l("div",J,[i(d,{modelValue:o(e).password,"onUpdate:modelValue":r[5]||(r[5]=s=>o(e).password=s),type:t.toggle,error:!!((N=o(e).errors)!=null&&N.password),"error-message":(F=(B=o(e).errors)==null?void 0:B.password)==null?void 0:F.toString(),rules:[s=>!!s||"Password is required"],"bg-color":"white",label:"Password","no-error-icon":"",outlined:""},{append:g(()=>[t.toggle==="password"?(m(),c(u,{key:0,onClick:p,flat:"",round:"",icon:"o_visibility_off"})):(m(),c(u,{key:1,onClick:p,flat:"",round:"",icon:"o_visibility"}))]),_:1},8,["modelValue","type","error","error-message","rules"])]),l("div",K,[l("div",L,[i(u,{loading:t.submitting,class:"sized-btn",color:"primary",label:"Save",type:"submit"},null,8,["loading"]),i(u,{class:"sized-btn",color:"negative",label:"Cancel",outline:"",onClick:r[6]||(r[6]=s=>a.$inertia.replace(a.route("user.index")))})])])]}),_:1})]),_:1}))}});export{ne as default};
