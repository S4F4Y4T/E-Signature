import{V as B,_ as F,a as $,b as j,c,d as I,D as K,e as O,f as W,F as q,s as z,g as E,h as G,i as H}from"./VSnackbar-BlbqK5gD.js";import{r as l,w as J,f as i,e as o,j as L,o as p,b as a,y as M,t as Q,v as X,n as V,a2 as Y,d as Z,x as ee}from"./main-DVhTzj6_.js";import{V as te}from"./VRow-SJpi5HhE.js";import{V as ae}from"./VCardText-BPq8PiTh.js";import{V as le}from"./VDivider-BXqhd4w-.js";import{V as R}from"./VCard-D2IwzXG8.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";/* empty css              */import"./VTextField-CnhC3aJE.js";import"./VList-5189Enff.js";import"./VImg--2CvGgS5.js";import"./VOverlay-qv7wRq73.js";import"./VChip-SlX7MuY7.js";import"./VTooltip-DyUgKrhK.js";import"./index-DYf1Ga7p.js";import"./VMenu-Dn7JMhfw.js";const ge={__name:"[id]",setup(oe){const _=L(),w=l(_.params.id),b=l(_.query.fullview),u=l(!0);parseInt(b.value)===0&&(u.value=!1);const T=[{title:"Verify",icon:z},{title:"Review",icon:E},{title:"Sign",icon:G},{title:"Download",icon:H}],s=l(0),U=l(!0);let n=l(null),d=l(null),S=l("error");const m=t=>{var x,k,h,C;const e=t.data,r=t.type,P=t==null?void 0:t.custom;if(n.value=!0,S.value=r,r==="success"||P===!0)d.value=e;else if(typeof((k=(x=e==null?void 0:e.response)==null?void 0:x._data)==null?void 0:k.message)=="object"&&((C=(h=e==null?void 0:e.response)==null?void 0:h._data)==null?void 0:C.message)!==null){let D="";for(const N in e.response._data.message)e.response._data.message.hasOwnProperty(N)&&(D+=`${e.response._data.message[N]}
`);d.value=D.trim()}else d.value=e.response._data.message},v=t=>{s.value=s.value+t},f=t=>{s.value=s.value-t},y=l(0),A=l(0),g=l(0);return J(s,t=>{(t===1||t===2)&&y.value++,g.value=t}),(t,e)=>(p(),i(B,{class:"v-container"},{default:o(()=>[a(R,null,{default:o(()=>[u.value?(p(),i(te,{key:0},{default:o(()=>[a(ae,null,{default:o(()=>[a(F,{"current-step":s.value,"onUpdate:currentStep":e[0]||(e[0]=r=>s.value=r),items:T,"is-active-step-valid":U.value,align:"center"},null,8,["current-step","is-active-step-valid"])]),_:1})]),_:1})):M("",!0),a(le)]),_:1}),a($,{modelValue:V(n),"onUpdate:modelValue":e[1]||(e[1]=r=>Y(n)?n.value=r:n=r),location:"top end",variant:"flat",color:V(S)},{default:o(()=>[Q(X(V(d)),1)]),_:1},8,["modelValue","color"]),Z("div",{class:ee({"step-rule":s.value===0})},[a(R,null,{default:o(()=>[a(j,{modelValue:s.value,"onUpdate:modelValue":e[2]||(e[2]=r=>s.value=r),disabled:""},{default:o(()=>[a(c,null,{default:o(()=>[a(I,{onNextStep:v,onAlert:m,"short-url":w.value},null,8,["short-url"])]),_:1}),a(c,null,{default:o(()=>[(p(),i(K,{key:y.value,viewMode:u.value,currentStep:g.value,onPrevStep:f,onNextStep:v},null,8,["viewMode","currentStep"]))]),_:1}),a(c,null,{default:o(()=>[(p(),i(O,{key:A.value,onPrevStep:f,onNextStep:v,"short-url":w.value,onAlert:m},null,8,["short-url"]))]),_:1}),a(c,null,{default:o(()=>[a(W,{viewMode:u.value,onPrevStep:f,onAlert:m},null,8,["viewMode"])]),_:1})]),_:1},8,["modelValue"])]),_:1}),u.value?(p(),i(q,{key:0})):M("",!0)],2)]),_:1}))}};export{ge as default};
