(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{"KHd+":function(t,e,i){"use strict";function n(t,e,i,n,o,s,r,a){var c,d="function"==typeof t?t.options:t;if(e&&(d.render=e,d.staticRenderFns=i,d._compiled=!0),n&&(d.functional=!0),s&&(d._scopeId="data-v-"+s),r?(c=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),o&&o.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(r)},d._ssrRegister=c):o&&(c=a?function(){o.call(this,(d.functional?this.parent:this).$root.$options.shadowRoot)}:o),c)if(d.functional){d._injectStyles=c;var l=d.render;d.render=function(t,e){return c.call(e),l(t,e)}}else{var u=d.beforeCreate;d.beforeCreate=u?[].concat(u,c):[c]}return{exports:t,options:d}}i.d(e,"a",(function(){return n}))},OvQD:function(t,e,i){"use strict";i.r(e);var n={props:{initialActivities:{required:!1,type:Array,default:function(){return[]}}},data:function(){return{curationActivities:this.initialActivities,fields:[{key:"id",label:"ID",sortable:!0},{key:"name",label:"Name",sortable:!0}]}},methods:{navigateToActivity:function(t){window.location="/curation-activities/"+t.id}}},o=i("KHd+"),s=Object(o.a)(n,(function(){return(0,this._self._c)("b-table",{attrs:{fields:this.fields,items:this.curationActivities},on:{"row-clicked":this.navigateToActivity}})}),[],!1,null,null,null);e.default=s.exports}}]);
//# sourceMappingURL=curation-activity-list.b937a8820c0d277dd93d.js.map