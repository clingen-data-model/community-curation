(window.webpackJsonp=window.webpackJsonp||[]).push([[33],{0:function(t,e){},1:function(t,e){},10:function(t,e){},11:function(t,e){},12:function(t,e){},13:function(t,e){},14:function(t,e){},"1CCS":function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function i(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var s=function(){function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[];r(this,t),this.items=e}var e,n,s;return e=t,(n=[{key:Symbol.iterator,value:function(){return this.items.values()}},{key:"push",value:function(t){this.items.push(t)}},{key:"shift",value:function(t){this.items.shift(t)}},{key:"unshift",value:function(t){this.items.unshift(t)}},{key:"filter",value:function(e){return new t(this.items.filter(e))}},{key:"map",value:function(e){return new t(this.items.map(e))}},{key:"flat",value:function(){return new t(this.items.flat)}},{key:"includes",value:function(t){return this.items.includes(t)}},{key:"all",value:function(){return this.items}},{key:"get",value:function(t){return this.items[t]}},{key:"put",value:function(t,e){return this.items[t]=e}},{key:"primary",value:function(){return new t(this.items.filter((function(t){return t.aptitude.is_primary})))}},{key:"secondary",value:function(){return new t(this.items.filter((function(t){return!t.aptitude.is_primary})))}},{key:"granted",value:function(){return new t(this.items.filter((function(t){return null!=t.granted_at})))}},{key:"trained",value:function(){return new t(this.items.filter((function(t){return null!=t.trained_at})))}},{key:"untrained",value:function(){return new t(this.items.filter((function(t){return null===t.trained_at})))}},{key:"pending",value:function(){return new t(this.items.filter((function(t){return null===t.granted_at})))}},{key:"needsAttestation",value:function(){return this.pending().trained()}},{key:"length",get:function(){return this.items.length}}])&&i(e.prototype,n),s&&i(e,s),t}();e.a=s},2:function(t,e){},3:function(t,e){},4:function(t,e){},5:function(t,e){},6:function(t,e){},7:function(t,e){},8:function(t,e){},9:function(t,e){},DOxs:function(t,e,n){"use strict";var r=n("wEql");n.n(r).a},ERWH:function(t,e,n){"use strict";var r=n("o0o1"),i=n.n(r),s=n("kJa9");function a(t,e,n,r,i,s,a){try{var o=t[s](a),u=o.value}catch(t){return void n(t)}o.done?e(u):Promise.resolve(u).then(r,i)}var o=function(){var t,e=(t=i.a.mark((function t(e){return i.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.next=2,window.axios.get("/api/volunteers/"+e).then((function(t){var e=t.data.data;return e=new s.a(e)}));case 2:return t.abrupt("return",t.sent);case 3:case"end":return t.stop()}}),t)})),function(){var e=this,n=arguments;return new Promise((function(r,i){var s=t.apply(e,n);function o(t){a(s,r,i,o,u,"next",t)}function u(t){a(s,r,i,o,u,"throw",t)}o(void 0)}))});return function(t){return e.apply(this,arguments)}}();e.a=o},NBHl:function(t,e,n){"use strict";var r=n("o0o1"),i=n.n(r);function s(t,e,n,r,i,s,a){try{var o=t[s](a),u=o.value}catch(t){return void n(t)}o.done?e(u):Promise.resolve(u).then(r,i)}var a=function(){var t,e=(t=i.a.mark((function t(){var e;return i.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(e=JSON.parse(localStorage.getItem("volunteer-types"))){t.next=5;break}return t.next=4,window.axios.get("/api/volunteer-types").then((function(t){return localStorage.setItem("volunteer-types",JSON.stringify(t.data.data)),t.data.data}));case 4:e=t.sent;case 5:return t.abrupt("return",e);case 6:case"end":return t.stop()}}),t)})),function(){var e=this,n=arguments;return new Promise((function(r,i){var a=t.apply(e,n);function o(t){s(a,r,i,o,u,"next",t)}function u(t){s(a,r,i,o,u,"throw",t)}o(void 0)}))});return function(){return e.apply(this,arguments)}}();e.a=a},Qsky:function(t,e,n){"use strict";var r=n("o0o1"),i=n.n(r),s=n("g6NE");function a(t,e,n,r,i,s,a){try{var o=t[s](a),u=o.value}catch(t){return void n(t)}o.done?e(u):Promise.resolve(u).then(r,i)}var o=function(){var t,e=(t=i.a.mark((function t(){var e;return i.a.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return e=Object.keys(s.a.state.configs.volunteers.statuses).map((function(t){return{name:t,id:s.a.state.configs.volunteers.statuses[t]}})),console.log(e),t.abrupt("return",e);case 3:case"end":return t.stop()}}),t)})),function(){var e=this,n=arguments;return new Promise((function(r,i){var s=t.apply(e,n);function o(t){a(s,r,i,o,u,"next",t)}function u(t){a(s,r,i,o,u,"throw",t)}o(void 0)}))});return function(){return e.apply(this,arguments)}}();e.a=o},TP6i:function(t,e,n){"use strict";n.r(e);var r=n("o0o1"),i=n.n(r),s=n("1Iqk");var a=n("ERWH"),o=function(t){var e="/api/volunteers?"+Object(s.a)(t,!0);return window.axios.get(e)},u=n("gwWm"),l=n("IAky"),c=n("Qsky"),f=n("NBHl"),p={props:{assignments:{type:Array,default:[]}},methods:{primaryAptitudeGranted:function(t){return t.user_aptitudes.find((function(t){return 1==t.aptitude.is_primary})).granted_at}}},v=n("KHd+"),d=Object(v.a)(p,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("ul",{staticClass:"list-unstyled"},t._l(t.assignments,(function(e,r){return n("li",{key:r},[t._v("\n        "+t._s(e.assignable.name)+"\n        "),e.sub_assignments.length>0?n("small",[t._v("\n            -\n            "),n("span",[t._v("\n                "+t._s(e.sub_assignments.map((function(t){return t.assignable.name})).join(", "))+"\n            ")])]):t._e()])})),0)}),[],!1,null,null,null).exports;function y(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function h(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?y(Object(n),!0).forEach((function(e){m(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):y(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function m(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var _={props:{filter:{type:Object,default:function(){return{}}},sortBy:{type:String,default:"last_name"},sortDesc:{type:Boolean,value:!1}},data:function(){return{}},filters:{filterToLabel:function(t){if("searchTerm"==t)return"Search";var e=t.replace("_id","").replace("_"," ");return e.charAt(0).toUpperCase()+e.slice(1)}},computed:{assignmentReportUrl:function(){var t="/assignments-report?"+Object(s.a)(h(h({},this.filter),{sortBy:this.sortBy,sortDesc:this.sortDesc}));return console.log(t),t},hasFilters:function(){var t=this;return Object.keys(this.filter).filter((function(e){return null!==t.filter[e]&&""!=t.filter[e]})).length>0},activeFilters:function(){var t=this;return Object.keys(this.filter).filter((function(e){return null!==t.filter[e]&&""!==t.filter[e]})).reduce((function(e,n){return e[n]=t.filter[n],e}),{})}},methods:{}},g=Object(v.a)(_,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("span",[n("a",{staticClass:"btn btn-sm btn-primary",attrs:{href:t.assignmentReportUrl,id:"assignments-button"}},[t._v("\n        Assignments Report\n        "),t.hasFilters?n("span",[t._v("*")]):t._e()]),t._v(" "),t.hasFilters?n("b-popover",{attrs:{target:"assignments-button",triggers:"hover"},scopedSlots:t._u([{key:"title",fn:function(){return[n("small",[t._v("\n                The report will be filtered by:\n                "),n("ul",t._l(t.activeFilters,(function(e,r){return n("li",{key:r},[t._v("\n                        "+t._s(t._f("filterToLabel")(r))+"\n                    ")])})),0)])]},proxy:!0}],null,!1,2705363192)}):t._e()],1)}),[],!1,null,null,null).exports;n("HEbw");function b(t,e,n,r,i,s,a){try{var o=t[s](a),u=o.value}catch(t){return void n(t)}o.done?e(u):Promise.resolve(u).then(r,i)}function w(t){return function(){var e=this,n=arguments;return new Promise((function(r,i){var s=t.apply(e,n);function a(t){b(s,r,i,a,o,"next",t)}function o(t){b(s,r,i,a,o,"throw",t)}a(void 0)}))}}function k(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}var O={components:{AssignmentBriefList:d,AssignmentsReportButton:g},data:function(){return{listType:"unassigned",volunteers:[],loadingVolunteers:!1,showAssignmentModal:!1,currentVolunteer:null,tableFields:[{key:"id",label:"ID",sortable:!0},k({key:"first_name",label:"First",sortable:!0},"key","first_name"),k({key:"last_name",label:"Last",sortable:!0},"key","last_name"),k({key:"email",label:"Email",sortable:!0},"key","email"),k({key:"type",label:"Type",sortable:!1},"key","volunteer_type.name"),{label:"Status",sortable:!1,key:"volunteer_status.name"},{key:"assignments",sortable:!1}],volunteerTypes:[],volunteerStatuses:[],activities:[],panels:[],filters:{searchTerm:null,volunteer_type_id:null,volunteer_status_id:null,curation_activity_id:null,curation_group_id:null},totalRows:0,pageLength:25,currentPage:1,sortKey:null,sortDesc:!1}},computed:{fields:function(){var t=JSON.parse(JSON.stringify(this.tableFields));return-1==this.filters.curation_activity_id&&(t.splice(t.findIndex((function(t){return"volunteer_type.name"==t.key})),1),t.push({label:"Priorities",sortable:!1,key:"latest_priorities"}),t.push(k({label:"Sign-up date",sortable:!1,key:"created_at"},"sortable",!0))),t},activeFilters:function(){var t=this;return Object.keys(this.filters).filter((function(e){return null!==t.filters[e]})).reduce((function(e,n){return e[n]=t.filters[n],e}),{})},filteredCurationGroups:function(){var t=this;return this.panels?this.filters.curation_activity_id?this.panels.filter((function(e){return e.curation_activity_id==t.filters.curation_activity_id})):this.panels:[]}},watch:{filters:{handler:function(t,e){localStorage.setItem("volunteers-table-filters",JSON.stringify(this.filters))},deep:!0},listType:function(){localStorage.setItem("volunteers-table-list-type",JSON.stringify(this.listType)),this.setFiltersForListType()}},methods:{setFiltersForListType:function(){"unassigned"==this.listType?(this.clearFilters(),this.filters.curation_activity_id=-1):this.clearFilters()},clearFilters:function(){this.filters={searchTerm:null,volunteer_type_id:null,volunteer_status_id:null,curation_activity_id:null,curation_group_id:null}},reconcileFilters:function(){1==this.filters.volunteer_type_id&&(this.filters.curation_activity_id=null,this.filters.curation_group_id=null)},volunteerProvider:function(t,e){var n=this;-1==this.filters.curation_activity_id&&(t.with=["priorities","priorities.curationActivity","priorities.curationGroup"]),o(t).then((function(t){n.totalRows=t.data.meta.total,e(t.data.data)}))},handleSortChanged:function(){this.resetCurrentPage()},handleFiltered:function(){this.resetCurrentPage()},updateCurrentVolunteer:function(){var t=this;return w(i.a.mark((function e(){return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t.$refs.volunteersTable.refresh(),e.next=3,Object(a.a)(t.currentVolunteer.id);case 3:t.currentVolunteer=e.sent;case 4:case"end":return e.stop()}}),e)})))()},addAssignmentsToVolunteer:function(t){var e=this;return w(i.a.mark((function n(){return i.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,Object(a.a)(t.id);case 2:e.currentVolunteer=n.sent,e.showAssignmentModal=!0;case 4:case"end":return n.stop()}}),n)})))()},resetCurrentPage:function(){this.currentPage=1},getCurationActivities:function(){var t=this;return w(i.a.mark((function e(){return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(l.a)();case 2:t.activities=e.sent;case 3:case"end":return e.stop()}}),e)})))()},getCurationGroups:function(){var t=this;return w(i.a.mark((function e(){return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(u.a)();case 2:t.panels=e.sent;case 3:case"end":return e.stop()}}),e)})))()},getStatuses:function(){var t=this;return w(i.a.mark((function e(){return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(c.a)();case 2:t.volunteerStatuses=e.sent;case 3:case"end":return e.stop()}}),e)})))()},getTypes:function(){var t=this;return w(i.a.mark((function e(){return i.a.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,Object(f.a)();case 2:t.volunteerTypes=e.sent;case 3:case"end":return e.stop()}}),e)})))()},navigateToVolunteer:function(t){console.log("NavigateToVolunteer"),window.location="/volunteers/"+t.id},syncFiltersFromLocalStorage:function(){var t=JSON.parse(localStorage.getItem("volunteers-table-filters"));t&&(this.filters=t)},syncListTypeFromLocalStorage:function(){var t=JSON.parse(localStorage.getItem("volunteers-table-list-type"));console.info("storedListType",t),t&&(this.listType=t)}},mounted:function(){this.getCurationActivities(),this.getCurationGroups(),this.getStatuses(),this.getTypes(),this.syncFiltersFromLocalStorage(),this.syncListTypeFromLocalStorage(),this.setFiltersForListType()}},P=(n("DOxs"),Object(v.a)(O,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",[n("div",{staticClass:"d-flex justify-content-between alert alert-info"},[t._v("\n        We've added a video showing you how to use this page: \n        "),n("screen-info",{attrs:{"video-url":"https://www.youtube.com/embed/q25Ok1gScS0",title:"Learn about the Volunteers List"}})],1),t._v(" "),n("div",{staticClass:"card volunteer-index"},[n("div",{staticClass:"card-header"},[n("div",{staticClass:"float-right d-flex"},[n("assignments-report-button",{staticClass:"mr-1 ",attrs:{filter:t.filters,"sort-by":t.sortKey,"sort-desc":t.sortDesc}})],1),t._v(" "),n("h1",[n("select",{directives:[{name:"model",rawName:"v-model",value:t.listType,expression:"listType"}],staticClass:"header-select",on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.listType=e.target.multiple?n:n[0]}}},[n("option",{attrs:{value:"unassigned"}},[t._v("Unassigned Volunteers")]),t._v(" "),n("option",{attrs:{value:"all"}},[t._v("All Volunteers")])])])]),t._v(" "),n("div",{staticClass:"card-body"},[n("div",{staticClass:"d-flex mb-2 p-0 filter-row"},[n("div",{staticClass:"form-inline pr-3 border-right mr-3 align-items-start"},[n("label",{attrs:{for:"filter-input"}},[t._v("Search:")]),t._v("\n                     \n                    "),n("b-form-input",{staticClass:"form-control form-control-sm align-top",attrs:{debounce:"500",type:"text",placeholder:"filter rows",id:"filter-input"},model:{value:t.filters.searchTerm,callback:function(e){t.$set(t.filters,"searchTerm",e)},expression:"filters.searchTerm"}})],1),t._v(" "),"all"==t.listType?n("div",{attrs:{id:"type-filter-container"}},[n("select",{directives:[{name:"model",rawName:"v-model",value:t.filters.volunteer_type_id,expression:"filters.volunteer_type_id"}],staticClass:"form-control form-control-sm",class:{active:t.filters.volunteer_type_id},attrs:{id:"type-select"},on:{change:[function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.filters,"volunteer_type_id",e.target.multiple?n:n[0])},t.reconcileFilters]}},[n("option",{domProps:{value:null}},[t._v("Any Type")]),t._v(" "),t._l(t.volunteerTypes,(function(e,r){return n("option",{key:r,domProps:{value:e.id}},[t._v("\n                            "+t._s(e.name)+"\n                        ")])}))],2)]):t._e(),t._v(" "),n("div",{attrs:{id:"status-filter-container"}},[n("select",{directives:[{name:"model",rawName:"v-model",value:t.filters.volunteer_status_id,expression:"filters.volunteer_status_id"}],staticClass:"form-control form-control-sm",class:{active:t.filters.volunteer_status_id},attrs:{id:"status-select"},on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.filters,"volunteer_status_id",e.target.multiple?n:n[0])}}},[n("option",{domProps:{value:null}},[t._v("Any Status")]),t._v(" "),t._l(t.volunteerStatuses,(function(e,r){return n("option",{key:r,domProps:{value:e.id}},[t._v("\n                            "+t._s(e.name)+"\n                        ")])}))],2)]),t._v(" "),"all"==t.listType?n("div",{attrs:{id:"curation-activity-filter-container"}},[n("select",{directives:[{name:"model",rawName:"v-model",value:t.filters.curation_activity_id,expression:"filters.curation_activity_id"}],staticClass:"form-control form-control-sm",class:{active:t.filters.curation_activity_id},attrs:{id:"activity-select",disabled:1==t.filters.volunteer_type_id},on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.filters,"curation_activity_id",e.target.multiple?n:n[0])}}},[n("option",{domProps:{value:null}},[t._v("Any Activity")]),t._v(" "),t._l(t.activities,(function(e,r){return n("option",{key:r,domProps:{value:e.id}},[t._v("\n                            "+t._s(e.name)+"\n                        ")])})),t._v(" "),n("option",{domProps:{value:-1}},[t._v("Not assigned to activity")])],2)]):t._e(),t._v(" "),"all"==t.listType?n("div",{attrs:{id:"curation-group-filter-container"}},[n("select",{directives:[{name:"model",rawName:"v-model",value:t.filters.curation_group_id,expression:"filters.curation_group_id"}],staticClass:"form-control form-control-sm",class:{active:t.filters.curation_group_id},staticStyle:{"max-width":"200px"},attrs:{id:"panel-select",disabled:1==t.filters.volunteer_type_id},on:{change:function(e){var n=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.$set(t.filters,"curation_group_id",e.target.multiple?n:n[0])}}},[n("option",{domProps:{value:null}},[t._v("Any Curation Group")]),t._v(" "),n("option",{domProps:{value:-1}},[t._v("Not assigned to curation group")]),t._v(" "),t._l(t.filteredCurationGroups,(function(e,r){return n("option",{key:r,domProps:{value:e.id}},[t._v("\n                            "+t._s(e.name)+"\n                        ")])}))],2)]):t._e(),t._v(" "),n("div",{staticClass:"ml-auto px-2"},[n("b-pagination",{staticClass:"border-left pl-3",attrs:{size:"sm","hide-goto-end-buttons":"","total-rows":t.totalRows,"per-page":t.pageLength},model:{value:t.currentPage,callback:function(e){t.currentPage=e},expression:"currentPage"}})],1)]),t._v(" "),n("b-table",{ref:"volunteersTable",attrs:{items:t.volunteerProvider,fields:t.fields,"sort-by":t.sortKey,"sort-desc":t.sortDesc,"no-local-sorting":!0,"show-empty":!0,filter:t.filters,"current-page":t.currentPage,busy:t.loadingVolunteers},on:{"update:sortBy":function(e){t.sortKey=e},"update:sort-by":function(e){t.sortKey=e},"update:sortDesc":function(e){t.sortDesc=e},"update:sort-desc":function(e){t.sortDesc=e},"sort-changed":t.handleSortChanged,"update:busy":function(e){t.loadingVolunteers=e},"row-clicked":t.navigateToVolunteer},scopedSlots:t._u([{key:"cell(id)",fn:function(e){var r=e.item;return[n("a",{attrs:{href:"/volunteers/"+r.id}},[t._v(t._s(r.id))])]}},{key:"cell(name)",fn:function(e){var r=e.item;return[n("a",{attrs:{href:"/volunteers/"+r.id}},[t._v(t._s(r.name))])]}},{key:"cell(email)",fn:function(e){var r=e.item;return[n("a",{attrs:{href:"/volunteers/"+r.id}},[t._v(t._s(r.email))])]}},{key:"cell(assignments)",fn:function(e){var r=e.item;return[r&&r.assignments.length>0?n("assignment-brief-list",{attrs:{assignments:r.assignments}}):t._e(),t._v(" "),0==r.assignments.length&&-1==t.filters.curation_activity_id?n("button",{staticClass:"btn btn-light border btn-xs",on:{click:function(e){return t.addAssignmentsToVolunteer(r)}}},[t._v("Assign")]):t._e()]}},{key:"cell(latest_priorities)",fn:function(e){var r=e.item;return[n("small",[n("ol",{staticClass:"pl-3"},t._l(r.priorities,(function(e){return n("li",{key:e.id},[t._v("\n                                "+t._s(e.curation_activity.name)+"\n                                "),e.curation_group?n("span",[t._v("\n                                    - "+t._s(e.curation_group.name)+"\n                                ")]):t._e()])})),0)])]}},{key:"cell(created_at)",fn:function(e){var n=e.item;return[t._v("\n                    "+t._s(t._f("formatDate")(n.created_at,"YYYY-MM-DD"))+"\n                ")]}}])})],1),t._v(" "),n("b-modal",{attrs:{"hide-header":"","hide-footer":""},model:{value:t.showAssignmentModal,callback:function(e){t.showAssignmentModal=e},expression:"showAssignmentModal"}},[n("assignment-form",{attrs:{volunteer:t.currentVolunteer,showVolunteer:""},on:{saved:t.updateCurrentVolunteer}})],1)],1)])}),[],!1,null,null,null));e.default=P.exports},UZ01:function(t,e,n){(t.exports=n("I1BE")(!1)).push([t.i,".header-select{border:none;background:transparent}",""])},kJa9:function(t,e,n){"use strict";function r(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function i(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var s=function(){function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[];r(this,t),this.items=e}var e,n,s;return e=t,(n=[{key:Symbol.iterator,value:function(){return this.items.values()}},{key:"push",value:function(t){this.items.push(t)}},{key:"shift",value:function(t){this.items.shift(t)}},{key:"unshift",value:function(t){this.items.unshift(t)}},{key:"filter",value:function(e){return new t(this.items.filter(e))}},{key:"map",value:function(e){return new t(this.items.map(e))}},{key:"includes",value:function(t){return this.items.includes(t)}},{key:"all",value:function(){return this.items}},{key:"get",value:function(t){return this.items[t]}},{key:"put",value:function(t,e){return this.items[t]=e}},{key:"primary",value:function(){return this.filter((function(t){return t.is_primary}))}},{key:"secondary",value:function(){return this.filter((function(t){return!t.is_primary}))}},{key:"length",get:function(){return this.items.length}}])&&i(e.prototype,n),s&&i(e,s),t}(),a=n("1CCS");function o(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function u(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?o(Object(n),!0).forEach((function(e){l(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):o(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function l(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function c(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function f(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var p=function(){function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};c(this,t);var n={id:null,user_id:null,assignable_type:null,assignable_id:null,assignment_status_id:null,parent_id:null,status:{},assignable:{aptitudes:[]},sub_assignments:[],user_aptitudes:[]};for(var r in this.attributes=u(u({},n),e),this.attributes)this.attributes.hasOwnProperty(r)&&(this[r]=this.hydrateAttribute(r,e[r]))}var e,n,r;return e=t,(n=[{key:"getUnassignedAptitudes",value:function(){var t=this,e=this.assignable.aptitudes.filter((function(e){return!t.user_aptitudes.map((function(t){return t.aptitude_id})).includes(e.id)}));return new s(e)}},{key:"hasSubAssignments",value:function(){return this.attributes.sub_assignments.length>0}},{key:"hydrateAttribute",value:function(e,n){switch(e){case"sub_assignments":return null==n?n:n.map((function(e){return new t(e)}));case"user_aptitudes":var r=new a.a;return n?(n.forEach((function(t){r.push(t)})),r):r;case"aptitudes":return new s(n)}return n}}])&&f(e.prototype,n),r&&f(e,r),t}();function v(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function d(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?v(Object(n),!0).forEach((function(e){y(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):v(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function y(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}function h(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function m(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}var _=function(){function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};h(this,t);var n={name:null,id:null,volunteer_type:{id:null,name:""},volunteer_status:{id:null,name:""},priorities:[],latest_priorities:[{}],assignments:[]};for(var r in this.attributes=d(d({},n),e),this.attributes)this.attributes.hasOwnProperty(r)&&(this[r]=this.hydrateAttribute(r,e[r]))}var e,n,r;return e=t,(n=[{key:"isLoaded",value:function(){return null!==this.attributes&&void 0!==this.attributes}},{key:"isBaseline",value:function(){if(this.isLoaded())return 1==this.attributes.volunteer_type_id}},{key:"isComprehensive",value:function(){if(this.isLoaded())return 2==this.attributes.volunteer_type_id}},{key:"getAssignedActivities",value:function(){return this.isLoaded?this.attributes.assignments.map((function(t){return t.assignable})):[]}},{key:"assignedToBaseline",value:function(){return this.getAssignedActivities().map((function(t){return t.name})).includes("Baseline")}},{key:"hydrateAttribute",value:function(t,e){return"assignments"==t&&e&&(e=e.map((function(t){return new p(t)}))),e}},{key:"hasApplication",value:function(){return void 0!==this.application&&null!==this.application}},{key:"hasDemographicInfo",value:function(){return null!=this.application.self_desc.rawValue&&null!=this.application.highest_ed.rawValue&&null!=this.application.race_ethnicity.rawValue&&this.application.race_enthnicity!=[]}}])&&m(e.prototype,n),r&&m(e,r),t}();e.a=_},wEql:function(t,e,n){var r=n("UZ01");"string"==typeof r&&(r=[[t.i,r,""]]);var i={hmr:!0,transform:void 0,insertInto:void 0};n("aET+")(r,i);r.locals&&(t.exports=r.locals)}}]);
//# sourceMappingURL=volunteer-index.abb02e4bd520e5db269a.js.map