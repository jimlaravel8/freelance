(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["x434a"],{"0a6e":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"viewContainer"},[a("v-container",{staticClass:"content-container py-1"},[a("v-row",[a("v-col",{attrs:{cols:"12",sm:"12",md:"12",lg:"12",xl:"12"}},[a("v-card",{attrs:{loading:t.loading}},[a("v-menu",{ref:"menu",attrs:{"close-on-content-click":!1,"return-value":t.form.date,transition:"scale-transition","offset-y":"","max-width":"290px","min-width":"290px"},on:{"update:returnValue":function(e){return t.$set(t.form,"date",e)},"update:return-value":function(e){return t.$set(t.form,"date",e)}},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on,r=e.attrs;return[a("v-text-field",t._g(t._b({attrs:{label:"Picker in date","prepend-icon":"mdi-calendar"},model:{value:t.form.date,callback:function(e){t.$set(t.form,"date",e)},expression:"form.date"}},"v-text-field",r,!1),n))]}}]),model:{value:t.menu,callback:function(e){t.menu=e},expression:"menu"}},[a("v-date-picker",{attrs:{"no-title":"",scrollable:"",range:""},model:{value:t.form.date,callback:function(e){t.$set(t.form,"date",e)},expression:"form.date"}},[a("v-spacer"),a("v-btn",{attrs:{text:"",color:"primary"},on:{click:function(e){t.menu=!1}}},[t._v("Cancel")]),a("v-btn",{attrs:{text:"",color:"primary"},on:{click:t.filter_data}},[t._v("OK")])],1)],1)],1)],1),a("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[a("v-card",{attrs:{loading:t.loading}},[a("v-card-text",[a("h3",[t._v(t._s(this.points_earned))])]),a("v-card-subtitle",[a("p",[t._v("Total points earned last 24h")]),a("p",{staticStyle:{color:"red"}},[t._v("Total points issuance session: "+t._s(this.count_issuance))])])],1)],1),a("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[a("v-card",{attrs:{loading:t.loading}},[a("v-card-text",[a("h3",[t._v(t._s(this.points_spent))])]),a("v-card-subtitle",[a("p",[t._v("Total points spent last 24h")]),a("p",{staticStyle:{color:"red"}},[t._v("Total points redemption sessions: "+t._s(this.count_redemption))])])],1)],1),a("v-col",{attrs:{cols:"12",sm:"12",md:"12",lg:"4",xl:"3"}},[a("v-card",{attrs:{loading:t.loading}},[a("v-card-text",[a("h3",[t._v(t._s(this.total_businesses))])]),a("v-card-subtitle",[t._v("Total Businesses registered.")]),a("br"),a("br")],1)],1)],1),a("v-row",[a("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[a("v-hover",{scopedSlots:t._u([{key:"default",fn:function(e){var n=e.hover;return[a("v-card",[null===t.stats?a("v-skeleton-loader",{attrs:{type:"card"}}):t._e(),null!==t.stats?a("div",[a("v-responsive",{attrs:{"aspect-ratio":2.4}},[a("v-sparkline",{attrs:{labels:t.stats.userChartLabels,value:t.stats.userChartValues,type:"trend",smooth:"","stroke-linecap":"round",color:"grey","line-width":"3",padding:"16",height:"100%"}})],1),a("v-divider"),a("v-card-text",[a("h2",{staticClass:"title"},[t._v(" "+t._s(t.$t("users"))+" "),a("span",[t._v("("+t._s(t.stats.total.users)+")")])]),a("span",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0}},[t.stats.users.signupsChange<0?a("v-icon",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0},attrs:{size:"14"}},[t._v("mdi-arrow-down")]):t._e(),t.stats.users.signupsChange>0?a("v-icon",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0},attrs:{size:"14"}},[t._v("mdi-arrow-up")]):t._e(),t._v(" "+t._s(t.formatNumber(t.stats.users.signupsChange))+" ")],1),t._v(" "+t._s(t.$t("vs_past_7_days"))+" ")])],1):t._e(),a("v-fade-transition",[n?a("v-overlay",{attrs:{absolute:""}},[a("v-btn",{attrs:{"x-large":"",rounded:"",to:{name:"admin.users"},color:"primary"}},[t._v(" "+t._s(t.$t("more"))+" "),a("v-icon",{staticClass:"ml-1",attrs:{size:"15"}},[t._v("mdi-arrow-right")])],1)],1):t._e()],1)],1)]}}])})],1),a("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[a("v-hover",{scopedSlots:t._u([{key:"default",fn:function(e){var n=e.hover;return[a("v-card",[null===t.stats?a("v-skeleton-loader",{attrs:{type:"card"}}):t._e(),null!==t.stats?a("div",[a("v-responsive",{staticClass:"d-flex align-center",attrs:{"aspect-ratio":2.4,align:"center"}},[a("v-icon",{attrs:{size:"96",color:"grey"}},[t._v("mdi-account-circle")])],1),a("v-divider"),a("v-card-text",[a("h2",{staticClass:"title"},[t._v(t._s(t.$t("profile")))]),t._v(" "+t._s(t.$t("profile_info"))+" ")])],1):t._e(),a("v-fade-transition",[n?a("v-overlay",{attrs:{absolute:""}},[a("v-btn",{attrs:{"x-large":"",rounded:"",to:{name:"settings.profile"},color:"primary"}},[t._v(" "+t._s(t.$t("more"))+" "),a("v-icon",{staticClass:"ml-1",attrs:{size:"15"}},[t._v("mdi-arrow-right")])],1)],1):t._e()],1)],1)]}}])})],1),a("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[a("v-card",[null===t.stats?a("v-skeleton-loader",{attrs:{type:"card"}}):t._e(),null!==t.stats?a("div",[a("v-responsive",{staticClass:"d-flex align-center",attrs:{"aspect-ratio":2.4,align:"center"}},[a("v-icon",{attrs:{size:"96",color:"grey"}},[t._v("mdi-information-outline")])],1),a("v-divider"),a("v-card-text",[a("h2",{staticClass:"title"},[t._v(t._s(t.$t("version"))+" "+t._s(t.stats.version))]),t._v(" "+t._s(t.$t("version_info"))+" ")])],1):t._e()],1)],1)],1)],1)],1)},r=[],s=a("a34a"),i=a.n(s);function o(t,e,a,n,r,s,i){try{var o=t[s](i),l=o.value}catch(u){return void a(u)}o.done?e(l):Promise.resolve(l).then(n,r)}function l(t){return function(){var e=this,a=arguments;return new Promise((function(n,r){var s=t.apply(e,a);function i(t){o(s,n,r,i,l,"next",t)}function l(t){o(s,n,r,i,l,"throw",t)}i(void 0)}))}}var u={data:function(){return{locale:"en",stats:null,points_earned:0,points_spent:0,total_businesses:0,loading:!0,form:{date:["2020-08-10","2020-08-20"]},menu:!1}},created:function(){this.locale=this.$auth.check()?this.$auth.user().locale:Intl.DateTimeFormat().resolvedOptions().locale||this.$i18n.locale,this.moment.locale(this.locale.substr(0,2)),this.loadStats(),this.loadDailyStats()},computed:{dateRangeText:function(){return this.form.date_1.join(" ~ ")}},methods:{filter_data:function(){var t=this;this.$refs.menu.save(this.form.date),this.axios.post("/admin/statsfilter",this.form).then((function(e){var a=e.data;t.stats=a;var n=t.$_.map(a.users.signupsCurrentPeriod,(function(e,a){return t.moment(a).format("D")})),r=t.$_.map(a.users.signupsCurrentPeriod,(function(t,e){return t}));t.stats.userChartLabels=n,t.stats.userChartValues=r,t.overlay=!1,t.loading=!1}))},loadStats:function(){var t=this;this.axios.get("/admin/stats",{params:{locale:this.$i18n.locale}}).then((function(e){var a=e.data;t.stats=a;var n=t.$_.map(a.users.signupsCurrentPeriod,(function(e,a){return t.moment(a).format("D")})),r=t.$_.map(a.users.signupsCurrentPeriod,(function(t,e){return t}));t.stats.userChartLabels=n,t.stats.userChartValues=r,t.overlay=!1,t.loading=!1}))},formatNumber:function(t){return new Intl.NumberFormat(this.locale.replace("_","-")).format(t)},loadDailyStats:function(){var t=this;return l(i.a.mark((function e(){var a,n;return i.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,t.axios("/admin/dailypoints");case 2:a=e.sent,n=a.data,t.points_earned=parseInt(n[0].points_earned)||0,t.points_spent=parseInt(n[1].points_spent)||0,t.count_issuance=parseInt(n.count_issuance)||0,t.count_redemption=parseInt(n.count_redemption)||0,t.total_businesses=parseInt(n.total_businesses)||0,t.loading=!1;case 10:case"end":return e.stop()}}),e)})))()}}},c=u,d=a("2877"),h=a("6544"),p=a.n(h),f=a("8336"),v=a("b0af"),m=a("99d9"),g=a("62ad"),b=a("a523"),y=a("2e4b"),_=a("ce7e"),x=a("0789"),w=a("ce87"),S=a("132d"),C=a("e449"),k=a("a797"),V=a("6b53"),$=a("0fd9"),O=a("3129"),L=a("2fa4"),P=a("7f2e"),D=a("8654"),j=Object(d["a"])(c,n,r,!1,null,null,null);e["default"]=j.exports;p()(j,{VBtn:f["a"],VCard:v["a"],VCardSubtitle:m["b"],VCardText:m["c"],VCol:g["a"],VContainer:b["a"],VDatePicker:y["a"],VDivider:_["a"],VFadeTransition:x["c"],VHover:w["a"],VIcon:S["a"],VMenu:C["a"],VOverlay:k["a"],VResponsive:V["a"],VRow:$["a"],VSkeletonLoader:O["a"],VSpacer:L["a"],VSparkline:P["a"],VTextField:D["a"]})},1298:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"viewContainer"},[a("v-container",{staticClass:"content-container py-1"},[a("v-row",[a("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"6",xl:"6"}},[a("v-card",{attrs:{loading:t.loading}},[a("v-card-text",[a("h3",[t._v(t._s(this.points_earned))])]),a("v-card-subtitle",[a("p",[t._v("Total points earned last 24h")]),a("p",{staticStyle:{color:"red"}},[t._v("Total points issuance session: "+t._s(this.count_issuance))])])],1)],1),a("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"6",xl:"6"}},[a("v-card",{attrs:{loading:t.loading}},[a("v-card-text",[a("h3",[t._v(t._s(this.points_spent))])]),a("v-card-subtitle",[a("p",[t._v("Total points spent last 24h")]),a("p",{staticStyle:{color:"red"}},[t._v("Total points redemption sessions: "+t._s(this.count_redemption))])])],1)],1)],1),a("v-row",[a("v-col",{attrs:{cols:"12",sm:"6",md:"6",lg:"4",xl:"3"}},[a("v-hover",{scopedSlots:t._u([{key:"default",fn:function(e){var n=e.hover;return[a("v-card",[null===t.stats?a("v-skeleton-loader",{attrs:{type:"card"}}):t._e(),null!==t.stats?a("div",[a("v-responsive",{attrs:{"aspect-ratio":2.4}},[a("v-sparkline",{attrs:{labels:t.stats.userChartLabels,value:t.stats.userChartValues,type:"trend",smooth:"","stroke-linecap":"round",color:"grey","line-width":"3",padding:"16",height:"100%"}})],1),a("v-divider"),a("v-card-text",[a("h2",{staticClass:"title"},[t._v(" "+t._s(t.$t("customers"))+" "),a("span",[t._v("("+t._s(t.stats.total.users)+")")])]),a("span",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0}},[t.stats.users.signupsChange<0?a("v-icon",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0},attrs:{size:"14"}},[t._v("mdi-arrow-down")]):t._e(),t.stats.users.signupsChange>0?a("v-icon",{class:{"red--text":t.stats.users.signupsChange<0,"green--text":t.stats.users.signupsChange>0},attrs:{size:"14"}},[t._v("mdi-arrow-up")]):t._e(),t._v(" "+t._s(t.formatNumber(t.stats.users.signupsChange))+" ")],1),t._v(" "+t._s(t.$t("vs_past_7_days"))+" ")])],1):t._e(),a("v-fade-transition",[n?a("v-overlay",{attrs:{absolute:""}}):t._e()],1)],1)]}}])})],1)],1)],1)],1)},r=[],s=a("a34a"),i=a.n(s);function o(t,e,a,n,r,s,i){try{var o=t[s](i),l=o.value}catch(u){return void a(u)}o.done?e(l):Promise.resolve(l).then(n,r)}function l(t){return function(){var e=this,a=arguments;return new Promise((function(n,r){var s=t.apply(e,a);function i(t){o(s,n,r,i,l,"next",t)}function l(t){o(s,n,r,i,l,"throw",t)}i(void 0)}))}}var u={data:function(){return{locale:"en",stats:null,points_earned:0,points_spent:0,total_businesses:0,loading:!0}},created:function(){this.locale=this.$auth.check()?this.$auth.user().locale:Intl.DateTimeFormat().resolvedOptions().locale||this.$i18n.locale,this.moment.locale(this.locale.substr(0,2)),this.loadStats(),this.loadDailyStats()},methods:{loadStats:function(){var t=this;this.axios.get("/business/stats",{params:{locale:this.$i18n.locale}}).then((function(e){var a=e.data;t.stats=a;var n=t.$_.map(a.users.signupsCurrentPeriod,(function(e,a){return t.moment(a).format("D")})),r=t.$_.map(a.users.signupsCurrentPeriod,(function(t,e){return t}));t.stats.userChartLabels=n,t.stats.userChartValues=r,t.overlay=!1,t.loading=!1}))},formatNumber:function(t){return new Intl.NumberFormat(this.locale.replace("_","-")).format(t)},loadDailyStats:function(){var t=this;return l(i.a.mark((function e(){var a,n;return i.a.wrap((function(e){while(1)switch(e.prev=e.next){case 0:return e.next=2,t.axios("/business/dailypoints");case 2:a=e.sent,n=a.data,t.points_earned=parseInt(n[0].points_earned)||0,t.points_spent=parseInt(n[1].points_spent)||0,t.count_issuance=parseInt(n.count_issuance)||0,t.count_redemption=parseInt(n.count_redemption)||0,t.total_businesses=parseInt(n.total_businesses)||0,t.loading=!1;case 10:case"end":return e.stop()}}),e)})))()}}},c=u,d=a("2877"),h=a("6544"),p=a.n(h),f=a("b0af"),v=a("99d9"),m=a("62ad"),g=a("a523"),b=a("ce7e"),y=a("0789"),_=a("ce87"),x=a("132d"),w=a("a797"),S=a("6b53"),C=a("0fd9"),k=a("3129"),V=a("7f2e"),$=Object(d["a"])(c,n,r,!1,null,null,null);e["default"]=$.exports;p()($,{VCard:f["a"],VCardSubtitle:v["b"],VCardText:v["c"],VCol:m["a"],VContainer:g["a"],VDivider:b["a"],VFadeTransition:y["c"],VHover:_["a"],VIcon:x["a"],VOverlay:w["a"],VResponsive:S["a"],VRow:C["a"],VSkeletonLoader:k["a"],VSparkline:V["a"]})},"1f09":function(t,e,a){},"2a80":function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"viewContainer"},[a("v-container",{staticClass:"pt-2 pb-4"},[a("v-row",{attrs:{"no-gutters":""}},[a("v-col",{attrs:{cols:"12",sm:"12",md:"12",lg:"12",xl:"12"}},[a("v-card",[a("v-menu",{ref:"menu",attrs:{"close-on-content-click":!1,"return-value":t.form.date,transition:"scale-transition","offset-y":"","max-width":"290px","min-width":"290px"},on:{"update:returnValue":function(e){return t.$set(t.form,"date",e)},"update:return-value":function(e){return t.$set(t.form,"date",e)}},scopedSlots:t._u([{key:"activator",fn:function(e){var n=e.on,r=e.attrs;return[a("v-text-field",t._g(t._b({attrs:{label:"Picker in date","prepend-icon":"mdi-calendar"},model:{value:t.form.date,callback:function(e){t.$set(t.form,"date",e)},expression:"form.date"}},"v-text-field",r,!1),n))]}}]),model:{value:t.menu,callback:function(e){t.menu=e},expression:"menu"}},[a("v-date-picker",{attrs:{"no-title":"",scrollable:"",range:""},model:{value:t.form.date,callback:function(e){t.$set(t.form,"date",e)},expression:"form.date"}},[a("v-spacer"),a("v-btn",{attrs:{text:"",color:"primary"},on:{click:function(e){t.menu=!1}}},[t._v("Cancel")]),a("v-btn",{attrs:{text:"",color:"primary"},on:{click:t.filter_data}},[t._v("OK")])],1)],1)],1),a("v-col",{attrs:{cols:"12"}},[a("v-card",[a("v-card-title",[t._v(" Business Report "),a("v-spacer"),a("v-text-field",{attrs:{"append-icon":"mdi-magnify",label:"Search","single-line":"","hide-details":""},model:{value:t.search,callback:function(e){t.search=e},expression:"search"}})],1),a("v-data-table",{attrs:{headers:t.headers,items:t.business_report,search:t.search}})],1)],1)],1)],1)],1)],1)},r=[],s={data:function(){return{search:"",headers:[{text:"Name",value:"name"},{text:"phone",value:"phone"},{text:"Category",value:"category"},{text:"Billings summary",value:"billings"}],form:{date:["2020-08-10","2020-08-20"]},menu:!1,business_report:[]}},methods:{loadReport:function(){var t=this;this.axios.post("/admin/business_report",this.form).then((function(e){var a=e.data;t.business_report=a}))}},mounted:function(){this.loadReport()}},i=s,o=a("2877"),l=a("6544"),u=a.n(l),c=a("8336"),d=a("b0af"),h=a("99d9"),p=a("62ad"),f=a("a523"),v=a("8fea"),m=a("2e4b"),g=a("e449"),b=a("0fd9"),y=a("2fa4"),_=a("8654"),x=Object(o["a"])(i,n,r,!1,null,null,null);e["default"]=x.exports;u()(x,{VBtn:c["a"],VCard:d["a"],VCardTitle:h["d"],VCol:p["a"],VContainer:f["a"],VDataTable:v["a"],VDatePicker:m["a"],VMenu:g["a"],VRow:b["a"],VSpacer:y["a"],VTextField:_["a"]})},3129:function(t,e,a){"use strict";a("1f09");var n=a("c995"),r=a("24b2"),s=a("7560"),i=a("58df"),o=a("80d2");function l(t,e){return d(t)||c(t,e)||u()}function u(){throw new TypeError("Invalid attempt to destructure non-iterable instance")}function c(t,e){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t)){var a=[],n=!0,r=!1,s=void 0;try{for(var i,o=t[Symbol.iterator]();!(n=(i=o.next()).done);n=!0)if(a.push(i.value),e&&a.length===e)break}catch(l){r=!0,s=l}finally{try{n||null==o["return"]||o["return"]()}finally{if(r)throw s}}return a}}function d(t){if(Array.isArray(t))return t}function h(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,n)}return a}function p(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?h(Object(a),!0).forEach((function(e){f(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):h(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function f(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}e["a"]=Object(i["a"])(n["a"],r["a"],s["a"]).extend({name:"VSkeletonLoader",props:{boilerplate:Boolean,loading:Boolean,tile:Boolean,transition:String,type:String,types:{type:Object,default:function(){return{}}}},computed:{attrs:function(){return this.isLoading?this.boilerplate?{}:p({"aria-busy":!0,"aria-live":"polite",role:"alert"},this.$attrs):this.$attrs},classes:function(){return p({"v-skeleton-loader--boilerplate":this.boilerplate,"v-skeleton-loader--is-loading":this.isLoading,"v-skeleton-loader--tile":this.tile},this.themeClasses,{},this.elevationClasses)},isLoading:function(){return!("default"in this.$scopedSlots)||this.loading},rootTypes:function(){return p({actions:"button@2",article:"heading, paragraph",avatar:"avatar",button:"button",card:"image, card-heading","card-avatar":"image, list-item-avatar","card-heading":"heading",chip:"chip","date-picker":"list-item, card-heading, divider, date-picker-options, date-picker-days, actions","date-picker-options":"text, avatar@2","date-picker-days":"avatar@28",heading:"heading",image:"image","list-item":"text","list-item-avatar":"avatar, text","list-item-two-line":"sentences","list-item-avatar-two-line":"avatar, sentences","list-item-three-line":"paragraph","list-item-avatar-three-line":"avatar, paragraph",paragraph:"text@3",sentences:"text@2",table:"table-heading, table-thead, table-tbody, table-tfoot","table-heading":"heading, text","table-thead":"heading@6","table-tbody":"table-row-divider@6","table-row-divider":"table-row, divider","table-row":"table-cell@6","table-cell":"text","table-tfoot":"text@2, avatar@2",text:"text"},this.types)}},methods:{genBone:function(t,e){return this.$createElement("div",{staticClass:"v-skeleton-loader__".concat(t," v-skeleton-loader__bone")},e)},genBones:function(t){var e=this,a=t.split("@"),n=l(a,2),r=n[0],s=n[1],i=function(){return e.genStructure(r)};return Array.from({length:s}).map(i)},genStructure:function(t){var e=[];t=t||this.type||"";var a=this.rootTypes[t]||"";if(t===a);else{if(t.indexOf(",")>-1)return this.mapBones(t);if(t.indexOf("@")>-1)return this.genBones(t);a.indexOf(",")>-1?e=this.mapBones(a):a.indexOf("@")>-1?e=this.genBones(a):a&&e.push(this.genStructure(a))}return[this.genBone(t,e)]},genSkeleton:function(){var t=[];return this.isLoading?t.push(this.genStructure()):t.push(Object(o["q"])(this)),this.transition?this.$createElement("transition",{props:{name:this.transition},on:{afterEnter:this.resetStyles,beforeEnter:this.onBeforeEnter,beforeLeave:this.onBeforeLeave,leaveCancelled:this.resetStyles}},t):t},mapBones:function(t){return t.replace(/\s/g,"").split(",").map(this.genStructure)},onBeforeEnter:function(t){this.resetStyles(t),this.isLoading&&(t._initialStyle={display:t.style.display,transition:t.style.transition},t.style.setProperty("transition","none","important"))},onBeforeLeave:function(t){t.style.setProperty("display","none","important")},resetStyles:function(t){t._initialStyle&&(t.style.display=t._initialStyle.display||"",t.style.transition=t._initialStyle.transition,delete t._initialStyle)}},render:function(t){return t("div",{staticClass:"v-skeleton-loader",attrs:this.attrs,on:this.$listeners,class:this.classes,style:this.isLoading?this.measurableStyles:void 0},[this.genSkeleton()])}})},"7f2e":function(t,e,a){"use strict";var n=a("a9ad"),r=a("58df");function s(t){return l(t)||o(t)||i()}function i(){throw new TypeError("Invalid attempt to spread non-iterable instance")}function o(t){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t))return Array.from(t)}function l(t){if(Array.isArray(t)){for(var e=0,a=new Array(t.length);e<t.length;e++)a[e]=t[e];return a}}function u(t,e){var a=e.minX,n=e.maxX,r=e.minY,i=e.maxY,o=t.length,l=Math.max.apply(Math,s(t)),u=Math.min.apply(Math,s(t)),c=(n-a)/(o-1),d=(i-r)/(l-u||1);return t.map((function(t,e){return{x:a+e*c,y:i-(t-u)*d+1e-5*+(e===o-1)-1e-5*+(0===e),value:t}}))}function c(t,e){var a=e.minX,n=e.maxX,r=e.minY,i=e.maxY,o=t.length,l=Math.max.apply(Math,s(t)),u=Math.min.apply(Math,s(t));u>0&&(u=0),l<0&&(l=0);var c=n/o,d=(i-r)/(l-u),h=i-Math.abs(u*d);return t.map((function(t,e){var n=Math.abs(d*t);return{x:a+e*c,y:h-n+ +(t<0)*n,height:n,value:t}}))}function d(t){return parseInt(t,10)}function h(t,e,a){return d(t.x+a.x)===d(2*e.x)&&d(t.y+a.y)===d(2*e.y)}function p(t,e){return Math.sqrt(Math.pow(e.x-t.x,2)+Math.pow(e.y-t.y,2))}function f(t,e,a){var n={x:t.x-e.x,y:t.y-e.y},r=Math.sqrt(n.x*n.x+n.y*n.y),s={x:n.x/r,y:n.y/r};return{x:e.x+s.x*a,y:e.y+s.y*a}}function v(t,e){var a=arguments.length>2&&void 0!==arguments[2]&&arguments[2],n=arguments.length>3&&void 0!==arguments[3]?arguments[3]:75,r=t.shift(),s=t[t.length-1];return(a?"M".concat(r.x," ").concat(n-r.x+2," L").concat(r.x," ").concat(r.y):"M".concat(r.x," ").concat(r.y))+t.map((function(a,n){var s=t[n+1],i=t[n-1]||r,o=s&&h(s,a,i);if(!s||o)return"L".concat(a.x," ").concat(a.y);var l=Math.min(p(i,a),p(s,a)),u=l/2<e,c=u?l/2:e,d=f(i,a,c),v=f(s,a,c);return"L".concat(d.x," ").concat(d.y,"S").concat(a.x," ").concat(a.y," ").concat(v.x," ").concat(v.y)})).join("")+(a?"L".concat(s.x," ").concat(n-r.x+2," Z"):"")}function m(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,n)}return a}function g(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?m(Object(a),!0).forEach((function(e){b(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):m(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function b(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}function y(t){return y="function"===typeof Symbol&&"symbol"===typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"===typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},y(t)}e["a"]=Object(r["a"])(n["a"]).extend({name:"VSparkline",inheritAttrs:!1,props:{autoDraw:Boolean,autoDrawDuration:{type:Number,default:2e3},autoDrawEasing:{type:String,default:"ease"},autoLineWidth:{type:Boolean,default:!1},color:{type:String,default:"primary"},fill:{type:Boolean,default:!1},gradient:{type:Array,default:function(){return[]}},gradientDirection:{type:String,validator:function(t){return["top","bottom","left","right"].includes(t)},default:"top"},height:{type:[String,Number],default:75},labels:{type:Array,default:function(){return[]}},labelSize:{type:[Number,String],default:7},lineWidth:{type:[String,Number],default:4},padding:{type:[String,Number],default:8},showLabels:Boolean,smooth:{type:[Boolean,Number,String],default:!1},type:{type:String,default:"trend",validator:function(t){return["trend","bar"].includes(t)}},value:{type:Array,default:function(){return[]}},width:{type:[Number,String],default:300}},data:function(){return{lastLength:0}},computed:{parsedPadding:function(){return Number(this.padding)},parsedWidth:function(){return Number(this.width)},parsedHeight:function(){return parseInt(this.height,10)},parsedLabelSize:function(){return parseInt(this.labelSize,10)||7},totalHeight:function(){var t=this.parsedHeight;return this.hasLabels&&(t+=1.5*parseInt(this.labelSize,10)),t},totalWidth:function(){var t=this.parsedWidth;return"bar"===this.type&&(t=Math.max(this.value.length*this._lineWidth,t)),t},totalValues:function(){return this.value.length},_lineWidth:function(){if(this.autoLineWidth&&"trend"!==this.type){var t=this.parsedPadding*(this.totalValues+1);return(this.parsedWidth-t)/this.totalValues}return parseFloat(this.lineWidth)||4},boundary:function(){if("bar"===this.type)return{minX:0,maxX:this.totalWidth,minY:0,maxY:this.parsedHeight};var t=this.parsedPadding;return{minX:t,maxX:this.totalWidth-t,minY:t,maxY:this.parsedHeight-t}},hasLabels:function(){return Boolean(this.showLabels||this.labels.length>0||this.$scopedSlots.label)},parsedLabels:function(){for(var t=[],e=this._values,a=e.length,n=0;t.length<a;n++){var r=e[n],s=this.labels[n];s||(s="object"===y(r)?r.value:r),t.push({x:r.x,value:String(s)})}return t},normalizedValues:function(){return this.value.map((function(t){return"number"===typeof t?t:t.value}))},_values:function(){return"trend"===this.type?u(this.normalizedValues,this.boundary):c(this.normalizedValues,this.boundary)},textY:function(){var t=this.parsedHeight;return"trend"===this.type&&(t-=4),t},_radius:function(){return!0===this.smooth?8:Number(this.smooth)}},watch:{value:{immediate:!0,handler:function(){var t=this;this.$nextTick((function(){if(t.autoDraw&&"bar"!==t.type&&t.$refs.path){var e=t.$refs.path,a=e.getTotalLength();t.fill?(e.style.transformOrigin="bottom center",e.style.transition="none",e.style.transform="scaleY(0)",e.getBoundingClientRect(),e.style.transition="transform ".concat(t.autoDrawDuration,"ms ").concat(t.autoDrawEasing),e.style.transform="scaleY(1)"):(e.style.transition="none",e.style.strokeDasharray=a+" "+a,e.style.strokeDashoffset=Math.abs(a-(t.lastLength||0)).toString(),e.getBoundingClientRect(),e.style.transition="stroke-dashoffset ".concat(t.autoDrawDuration,"ms ").concat(t.autoDrawEasing),e.style.strokeDashoffset="0"),t.lastLength=a}}))}}},methods:{genGradient:function(){var t=this,e=this.gradientDirection,a=this.gradient.slice();a.length||a.push("");var n=Math.max(a.length-1,1),r=a.reverse().map((function(e,a){return t.$createElement("stop",{attrs:{offset:a/n,"stop-color":e||t.color||"currentColor"}})}));return this.$createElement("defs",[this.$createElement("linearGradient",{attrs:{id:this._uid,x1:+("left"===e),y1:+("top"===e),x2:+("right"===e),y2:+("bottom"===e)}},r)])},genG:function(t){return this.$createElement("g",{style:{fontSize:"8",textAnchor:"middle",dominantBaseline:"mathematical",fill:this.color||"currentColor"}},t)},genPath:function(){var t=u(this.normalizedValues,this.boundary);return this.$createElement("path",{attrs:{id:this._uid,d:v(t,this._radius,this.fill,this.parsedHeight),fill:this.fill?"url(#".concat(this._uid,")"):"none",stroke:this.fill?"none":"url(#".concat(this._uid,")")},ref:"path"})},genLabels:function(t){var e=this,a=this.parsedLabels.map((function(a,n){return e.$createElement("text",{attrs:{x:a.x+t+e._lineWidth/2,y:e.textY+.75*e.parsedLabelSize,"font-size":Number(e.labelSize)||7}},[e.genLabel(a,n)])}));return this.genG(a)},genLabel:function(t,e){return this.$scopedSlots.label?this.$scopedSlots.label({index:e,value:t.value}):t.value},genBars:function(){if(this.value&&!(this.totalValues<2)){var t=c(this.normalizedValues,this.boundary),e=(Math.abs(t[0].x-t[1].x)-this._lineWidth)/2;return this.$createElement("svg",{attrs:{display:"block",viewBox:"0 0 ".concat(this.totalWidth," ").concat(this.totalHeight)}},[this.genGradient(),this.genClipPath(t,e,this._lineWidth,"sparkline-bar-"+this._uid),this.hasLabels?this.genLabels(e):void 0,this.$createElement("g",{attrs:{"clip-path":"url(#sparkline-bar-".concat(this._uid,"-clip)"),fill:"url(#".concat(this._uid,")")}},[this.$createElement("rect",{attrs:{x:0,y:0,width:this.totalWidth,height:this.height}})])])}},genClipPath:function(t,e,a,n){var r=this,s="number"===typeof this.smooth?this.smooth:this.smooth?2:0;return this.$createElement("clipPath",{attrs:{id:"".concat(n,"-clip")}},t.map((function(t){return r.$createElement("rect",{attrs:{x:t.x+e,y:t.y,width:a,height:t.height,rx:s,ry:s}},[r.autoDraw?r.$createElement("animate",{attrs:{attributeName:"height",from:0,to:t.height,dur:"".concat(r.autoDrawDuration,"ms"),fill:"freeze"}}):void 0])})))},genTrend:function(){return this.$createElement("svg",this.setTextColor(this.color,{attrs:g({},this.$attrs,{display:"block","stroke-width":this._lineWidth||1,viewBox:"0 0 ".concat(this.width," ").concat(this.totalHeight)})}),[this.genGradient(),this.hasLabels&&this.genLabels(-this._lineWidth/2),this.genPath()])}},render:function(t){if(!(this.totalValues<2))return"trend"===this.type?this.genTrend():this.genBars()}})}}]);