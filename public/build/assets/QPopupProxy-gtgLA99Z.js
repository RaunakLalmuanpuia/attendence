import{y as P,B as y,j as p,n as h,D as x,a9 as b,z as Q,s as j}from"./app-B6ljmE4m.js";import{e as C,f as k,g as M}from"./MainLayout-Dp50SWY5.js";const D=P({name:"QPopupProxy",props:{...C,breakpoint:{type:[String,Number],default:450}},emits:["show","hide"],setup(a,{slots:g,emit:c,attrs:f}){const{proxy:s}=y(),{$q:l}=s,o=p(!1),t=p(null),i=h(()=>parseInt(a.breakpoint,10)),{canShow:v}=k({showing:o});function u(){return l.screen.width<i.value||l.screen.height<i.value?"dialog":"menu"}const n=p(u()),m=h(()=>n.value==="menu"?{maxHeight:"99vh"}:{});x(()=>u(),e=>{o.value!==!0&&(n.value=e)});function d(e){o.value=!0,c("show",e)}function w(e){o.value=!1,n.value=u(),c("hide",e)}return Object.assign(s,{show(e){v(e)===!0&&t.value.show(e)},hide(e){t.value.hide(e)},toggle(e){t.value.toggle(e)}}),b(s,"currentComponent",()=>({type:n.value,ref:t.value})),()=>{const e={ref:t,...m.value,...f,onShow:d,onHide:w};let r;return n.value==="dialog"?r=j:(r=M,Object.assign(e,{target:a.target,contextMenu:a.contextMenu,noParentEvent:!0,separateClosePopup:!0})),Q(r,e,g.default)}}});export{D as Q};
