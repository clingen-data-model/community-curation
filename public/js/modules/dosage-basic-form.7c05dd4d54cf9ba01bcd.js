(window.webpackJsonp=window.webpackJsonp||[]).push([[11],{"KHd+":function(e,t,n){"use strict";function a(e,t,n,a,o,s,i,r){var l,d="function"==typeof e?e.options:e;if(t&&(d.render=t,d.staticRenderFns=n,d._compiled=!0),a&&(d.functional=!0),s&&(d._scopeId="data-v-"+s),i?(l=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),o&&o.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(i)},d._ssrRegister=l):o&&(l=r?function(){o.call(this,(d.functional?this.parent:this).$root.$options.shadowRoot)}:o),l)if(d.functional){d._injectStyles=l;var u=d.render;d.render=function(e,t){return l.call(t),u(e,t)}}else{var c=d.beforeCreate;d.beforeCreate=c?[].concat(c,l):[l]}return{exports:e,options:d}}n.d(t,"a",(function(){return a}))},lEiH:function(e,t,n){"use strict";n.r(t);var a=n("wd/R"),o=n.n(a),s={props:{attestation:Object},data:function(){return{attendedTraining:null,readDosageEvalProcess:null,signature:null,signedAt:o()().format("YYYY-MM-DD")}},computed:{allYes:function(){return 1===this.attendedTraining&&1===this.readDosageEvalProcess}}},i=n("KHd+"),r=Object(i.a)(s,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("attestation-form",{attrs:{title:"Dosage Volunteer Attestation",signable:e.allYes}},[n("question-block",[n("template",{slot:"question-text"},[e._v("\n            I have attended the live Zoom session training for Dosage Sensitivity Curation. \n        ")]),e._v(" "),n("radio-group",{attrs:{slot:"answer-block",name:"attended_zoom_training",options:[{label:"Yes",value:1},{label:"No",value:0}]},slot:"answer-block",model:{value:e.attendedTraining,callback:function(t){e.attendedTraining=t},expression:"attendedTraining"}})],2),e._v(" "),n("question-block",[n("template",{slot:"question-text"},[e._v("\n            I have read the original publication outlining the dosage evaluation process (Riggs et al., 2012).\n        ")]),e._v(" "),n("radio-group",{attrs:{slot:"answer-block",name:"read_dosage_eval_process",options:[{label:"Yes",value:1},{label:"No",value:0}]},slot:"answer-block",model:{value:e.readDosageEvalProcess,callback:function(t){e.readDosageEvalProcess=t},expression:"readDosageEvalProcess"}})],2),e._v(" "),n("div",{attrs:{slot:"signature-text"},slot:"signature-text"},[e._v("\n        I, "+e._s(e.attestation.user.name)+", attest that as of "+e._s(e.signedAt)+", I have completed all the elements of the Dosage Sensitivity Curation training\n    ")])],1)}),[],!1,null,null,null);t.default=r.exports}}]);
//# sourceMappingURL=dosage-basic-form.7c05dd4d54cf9ba01bcd.js.map