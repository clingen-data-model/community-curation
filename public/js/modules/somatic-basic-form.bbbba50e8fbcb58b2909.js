(window.webpackJsonp=window.webpackJsonp||[]).push([[23],{"KHd+":function(e,t,n){"use strict";function o(e,t,n,o,a,s,i,r){var c,l="function"==typeof e?e.options:e;if(t&&(l.render=t,l.staticRenderFns=n,l._compiled=!0),o&&(l.functional=!0),s&&(l._scopeId="data-v-"+s),i?(c=function(e){(e=e||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(e=__VUE_SSR_CONTEXT__),a&&a.call(this,e),e&&e._registeredComponents&&e._registeredComponents.add(i)},l._ssrRegister=c):a&&(c=r?function(){a.call(this,(l.functional?this.parent:this).$root.$options.shadowRoot)}:a),c)if(l.functional){l._injectStyles=c;var d=l.render;l.render=function(e,t){return c.call(t),d(e,t)}}else{var u=l.beforeCreate;l.beforeCreate=u?[].concat(u,c):[c]}return{exports:e,options:l}}n.d(t,"a",(function(){return o}))},VmLU:function(e,t,n){"use strict";n.r(t);var o=n("wd/R"),a=n.n(o),s={props:{attestation:{type:Object,required:!0}},data:function(){return{readSOP:null,watchedGettingStarted:null,watchedAddingEvidence:null,watchedEditingEntities:null,createdCIVicAccount:null,signedUpForPractice:null,chosenTaskForce:null,signedAt:a()().format("YYYY-MM-DD")}},computed:{allYes:function(){return 1===this.readSOP&&1===this.watchedGettingStarted&&1===this.watchedAddingEvidence&&1===this.watchedEditingEntities&&1===this.createdCIVicAccount&&1===this.signedUpForPractice&&null!==this.chosenTaskForce&&""!==this.chosenTaskForce}}},i=n("KHd+"),r=Object(i.a)(s,(function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("attestation-form",{attrs:{title:"Somatic Cancer Volunteer Curator Attestation",signable:e.allYes}},[n("p",[n("strong",[e._v('\n            Please review the statements below and check "Yes" or "No". Then sign your name to signify that you have reviewed these training materials.\n        ')])]),e._v(" "),n("question-block",[n("div",{attrs:{slot:"question-text"},slot:"question-text"},[e._v("\n            I have reviewed the CIViC Knowledge Model and Standard Operating Procedures for Curation and Clinical Interpretation of Variants in Cancer: \n            "),n("a",{attrs:{href:"https://www.ncbi.nlm.nih.gov/pubmed/27814769",target:"civic-sop"}},[e._v("https://www.ncbi.nlm.nih.gov/pubmed/27814769")])]),e._v(" "),n("radio-group",{attrs:{slot:"answer-block",name:"readSOP",options:[{label:"Yes",value:1},{label:"No",value:0}]},slot:"answer-block",model:{value:e.readSOP,callback:function(t){e.readSOP=t},expression:"readSOP"}})],1),e._v(" "),n("question-block",[n("div",{attrs:{slot:"question-text"},slot:"question-text"},[e._v('\n            I have watched the "CIViC - Getting Started" video.\n        ')]),e._v(" "),n("radio-group",{attrs:{slot:"answer-block",name:"watchedGettingStarted",options:[{label:"Yes",value:1},{label:"No",value:0}]},slot:"answer-block",model:{value:e.watchedGettingStarted,callback:function(t){e.watchedGettingStarted=t},expression:"watchedGettingStarted"}})],1),e._v(" "),n("question-block",[n("div",{attrs:{slot:"question-text"},slot:"question-text"},[e._v('\n            I have watched the "CIViC - Adding Evidence" video. \n        ')]),e._v(" "),n("radio-group",{attrs:{slot:"answer-block",name:"watchedAddingEvidence",options:[{label:"Yes",value:1},{label:"No",value:0}]},slot:"answer-block",model:{value:e.watchedAddingEvidence,callback:function(t){e.watchedAddingEvidence=t},expression:"watchedAddingEvidence"}})],1),e._v(" "),n("question-block",[n("div",{attrs:{slot:"question-text"},slot:"question-text"},[e._v('\n            I have watched the "CIViC - Editing Entities" video.\n        ')]),e._v(" "),n("radio-group",{attrs:{slot:"answer-block",name:"watchedEditingEntities",options:[{label:"Yes",value:1},{label:"No",value:0}]},slot:"answer-block",model:{value:e.watchedEditingEntities,callback:function(t){e.watchedEditingEntities=t},expression:"watchedEditingEntities"}})],1),e._v(" "),n("question-block",[n("div",{attrs:{slot:"question-text"},slot:"question-text"},[e._v("\n            I have created an account in CIViC\n        ")]),e._v(" "),n("radio-group",{attrs:{slot:"answer-block",name:"createdCIVicAccount",options:[{label:"Yes",value:1},{label:"No",value:0}]},slot:"answer-block",model:{value:e.createdCIVicAccount,callback:function(t){e.createdCIVicAccount=t},expression:"createdCIVicAccount"}})],1),e._v(" "),n("question-block",[n("div",{attrs:{slot:"question-text"},slot:"question-text"},[e._v("\n            I have signed up for a practice curation assignment in CIViC: \n            "),n("a",{attrs:{href:"https://docs.google.com/spreadsheets/d/1vBDR3xVaKgkOSW_7VTO8Mxe2wo1WCJV1mbT3nO1lCM4/edit#gid=0",target:"practice-session"}},[e._v("\n                https://docs.google.com/spreadsheets/d/1vBDR3xVaKgkOSW_7VTO8Mxe2wo1WCJV1mbT3nO1lCM4/edit#gid=0\n            ")])]),e._v(" "),n("radio-group",{attrs:{slot:"answer-block",name:"signedUpForPractice",options:[{label:"Yes",value:1},{label:"No",value:0}]},slot:"answer-block",model:{value:e.signedUpForPractice,callback:function(t){e.signedUpForPractice=t},expression:"signedUpForPractice"}})],1),e._v(" "),n("question-block",[n("div",{attrs:{slot:"question-text"},slot:"question-text"},[e._v("\n            I have chosen a curation activity/disease task force. (Please list your choice below)\n        ")]),e._v(" "),n("div",{staticClass:"form-inline",attrs:{slot:"answer-block"},slot:"answer-block"},[n("input",{directives:[{name:"model",rawName:"v-model",value:e.chosenTaskForce,expression:"chosenTaskForce"}],staticClass:"form-control",attrs:{type:"text",name:"chosenTaskForce"},domProps:{value:e.chosenTaskForce},on:{input:function(t){t.target.composing||(e.chosenTaskForce=t.target.value)}}})])]),e._v(" "),n("br"),e._v(" "),n("div",{attrs:{slot:"signature-text"},slot:"signature-text"},[e._v("I, "+e._s(e.attestation.user.name)+", attest that as of "+e._s(e.signedAt)+" I have completed all the elements of the Somatic Cancer Training.")])],1)}),[],!1,null,null,null);t.default=r.exports}}]);
//# sourceMappingURL=somatic-basic-form.bbbba50e8fbcb58b2909.js.map