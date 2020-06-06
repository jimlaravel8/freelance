(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["x4d21"],{"0798":function(t,e,r){"use strict";r("0c18");var a=r("10d2"),s=r("afdd"),i=r("9d26"),o=r("f2e7"),n=r("7560"),l=r("2b0e"),c=l["a"].extend({name:"transitionable",props:{mode:String,origin:String,transition:String}}),u=r("58df"),d=r("d9bd");function m(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,a)}return r}function p(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?m(Object(r),!0).forEach((function(e){f(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):m(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function f(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}e["a"]=Object(u["a"])(a["a"],o["a"],c).extend({name:"v-alert",props:{border:{type:String,validator:function(t){return["top","right","bottom","left"].includes(t)}},closeLabel:{type:String,default:"$vuetify.close"},coloredBorder:Boolean,dense:Boolean,dismissible:Boolean,icon:{default:"",type:[Boolean,String],validator:function(t){return"string"===typeof t||!1===t}},outlined:Boolean,prominent:Boolean,text:Boolean,type:{type:String,validator:function(t){return["info","error","success","warning"].includes(t)}},value:{type:Boolean,default:!0}},computed:{__cachedBorder:function(){if(!this.border)return null;var t={staticClass:"v-alert__border",class:f({},"v-alert__border--".concat(this.border),!0)};return this.coloredBorder&&(t=this.setBackgroundColor(this.computedColor,t),t.class["v-alert__border--has-color"]=!0),this.$createElement("div",t)},__cachedDismissible:function(){var t=this;if(!this.dismissible)return null;var e=this.iconColor;return this.$createElement(s["a"],{staticClass:"v-alert__dismissible",props:{color:e,icon:!0,small:!0},attrs:{"aria-label":this.$vuetify.lang.t(this.closeLabel)},on:{click:function(){return t.isActive=!1}}},[this.$createElement(i["a"],{props:{color:e}},"$cancel")])},__cachedIcon:function(){return this.computedIcon?this.$createElement(i["a"],{staticClass:"v-alert__icon",props:{color:this.iconColor}},this.computedIcon):null},classes:function(){var t=p({},a["a"].options.computed.classes.call(this),{"v-alert--border":Boolean(this.border),"v-alert--dense":this.dense,"v-alert--outlined":this.outlined,"v-alert--prominent":this.prominent,"v-alert--text":this.text});return this.border&&(t["v-alert--border-".concat(this.border)]=!0),t},computedColor:function(){return this.color||this.type},computedIcon:function(){return!1!==this.icon&&("string"===typeof this.icon&&this.icon?this.icon:!!["error","info","success","warning"].includes(this.type)&&"$".concat(this.type))},hasColoredIcon:function(){return this.hasText||Boolean(this.border)&&this.coloredBorder},hasText:function(){return this.text||this.outlined},iconColor:function(){return this.hasColoredIcon?this.computedColor:void 0},isDark:function(){return!(!this.type||this.coloredBorder||this.outlined)||n["a"].options.computed.isDark.call(this)}},created:function(){this.$attrs.hasOwnProperty("outline")&&Object(d["a"])("outline","outlined",this)},methods:{genWrapper:function(){var t=[this.$slots.prepend||this.__cachedIcon,this.genContent(),this.__cachedBorder,this.$slots.append,this.$scopedSlots.close?this.$scopedSlots.close({toggle:this.toggle}):this.__cachedDismissible],e={staticClass:"v-alert__wrapper"};return this.$createElement("div",e,t)},genContent:function(){return this.$createElement("div",{staticClass:"v-alert__content"},this.$slots.default)},genAlert:function(){var t={staticClass:"v-alert",attrs:{role:"alert"},class:this.classes,style:this.styles,directives:[{name:"show",value:this.isActive}]};if(!this.coloredBorder){var e=this.hasText?this.setTextColor:this.setBackgroundColor;t=e(this.computedColor,t)}return this.$createElement("div",t,[this.genWrapper()])},toggle:function(){this.isActive=!this.isActive}},render:function(t){var e=this.genAlert();return this.transition?t("transition",{props:{name:this.transition,origin:this.origin,mode:this.mode}},[e]):e}})},"0c18":function(t,e,r){},3469:function(t,e,r){"use strict";r.r(e);var a=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{staticClass:"viewContainer"},[r("v-container",{staticClass:"inner-container"},[r("v-card",{staticClass:"mt-md-12 mt-0 content-tabs-wrapper",attrs:{tile:"",elevation:"0"}},[r("v-tabs",{staticClass:"px-5 content-tabs",attrs:{color:t.$store.state.app.dark?"white":"black","icons-and-text":"",height:"100","slider-size":"4","show-arrows":""}},[r("v-tab",{staticClass:"no-caps",attrs:{to:{name:"settings.profile"},ripple:!1}},[t._v(" "+t._s(t.$t("profile"))+" "),r("v-icon",{attrs:{size:"24"}},[t._v("mdi-account-circle")])],1),3===t.$auth.user().role?r("v-tab",{staticClass:"no-caps",attrs:{to:{name:"settings.business"},ripple:!1}},[t._v(" "+t._s(t.$t("business"))+" "),r("v-icon",{attrs:{size:"24"}},[t._v("mdi-briefcase")])],1):t._e(),3===t.$auth.user().role?r("v-tab",{staticClass:"no-caps",attrs:{to:{name:"settings.business.subscription"},ripple:!1}},[t._v(" Billing "),r("v-icon",{attrs:{size:"24"}},[t._v("mdi-domain")])],1):t._e()],1),r("v-divider"),r("v-tabs",{staticClass:"mt-5 px-5 content-tabs",attrs:{color:t.$store.state.app.dark?"white":"black","slider-size":"2","show-arrows":"","active-class":"font-weight-bold"},model:{value:t.tab,callback:function(e){t.tab=e},expression:"tab"}},[r("v-tab",{staticClass:"no-caps",attrs:{href:"#general",ripple:!1}},[t._v(t._s(t.$t("general")))]),r("v-tab",{staticClass:"no-caps",attrs:{href:"#localization",ripple:!1}},[t._v(t._s(t.$t("localization")))]),r("v-tab",{staticClass:"no-caps",attrs:{href:"#password",ripple:!1}},[t._v(t._s(t.$t("password")))])],1),r("v-divider"),r("v-card",{staticClass:"content-tabs-card pa-5 mb-0 mb-md-12",attrs:{flat:"",tile:""}},[r("ValidationObserver",{ref:"form1",scopedSlots:t._u([{key:"default",fn:function(e){var a=e.invalid;return[r("v-form",{attrs:{model:t.form1,id:"form1","lazy-validation":"",autocomplete:"off",method:"post","accept-charset":"UTF-8",enctype:"multipart/form-data"},on:{submit:function(e){return e.preventDefault(),t.submitForm("form1")}}},[r("v-card-text",[r("v-alert",{staticClass:"mb-8",attrs:{value:t.form1.has_error&&!t.form1.success,type:"error"}},["registration_validation_error"===t.form1.error?r("span",[t._v(t._s(t.$t("server_error")))]):"demo"===t.form1.error?r("span",[t._v("This is a demo user. You can't update or delete anything this account. If you want to test all user features, sign up with a new account.")]):r("span",[t._v(t._s(t.$t("correct_errors")))])]),r("v-alert",{staticClass:"mb-4",attrs:{value:t.form1.success,type:"success"}},[t._v(t._s(t.$t("update_success")))]),r("v-tabs-items",{attrs:{touchless:!1},model:{value:t.tab,callback:function(e){t.tab=e},expression:"tab"}},[r("v-tab-item",{attrs:{transition:!1,"reverse-transition":!1,value:"general"}},[r("v-container",{staticClass:"pa-0"},[r("v-row",{attrs:{"no-gutters":""}},[r("v-col",{attrs:{cols:"12",sm:"9",md:"6"}},[r("v-row",[r("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[r("x-text-field",{ref:"form1.first_name",attrs:{id:"form1.first_name",label:t.$t("first_name"),name:t.$t("first_name"),rules:"required|min:1|max:32",dense:""},model:{value:t.form1.first_name,callback:function(e){t.$set(t.form1,"first_name",e)},expression:"form1.first_name"}})],1),r("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[r("x-text-field",{ref:"form1.last_name",attrs:{id:"form1.last_name",label:t.$t("last_name"),name:t.$t("last_name"),rules:"required|min:1|max:32",dense:""},model:{value:t.form1.last_name,callback:function(e){t.$set(t.form1,"last_name",e)},expression:"form1.last_name"}})],1)],1),r("x-text-field",{ref:"form1.whatsapp",attrs:{id:"form1.whatsapp",label:2===t.$auth.user().role?"Whatsapp":"Contact Number",rules:"required|max:64",dense:""},model:{value:t.form1.whatsapp,callback:function(e){t.$set(t.form1,"whatsapp",e)},expression:"form1.whatsapp"}}),r("x-text-field",{ref:"form1.email",attrs:{type:"email",id:"form1.email",label:t.$t("email"),rules:"required|max:64|email",dense:""},model:{value:t.form1.email,callback:function(e){t.$set(t.form1,"email",e)},expression:"form1.email"}})],1)],1)],1)],1),r("v-tab-item",{attrs:{transition:!1,"reverse-transition":!1,value:"localization"}},[r("v-container",{staticClass:"pa-0"},[r("v-row",{attrs:{"no-gutters":""}},[r("v-col",{attrs:{cols:"12",sm:"9",md:"6"}},[r("x-autocomplete",{attrs:{items:t.locales,"item-value":"0","item-text":"1",label:"Language",name:t.$t("locale"),rules:"required",hint:t.$t("locale_hint"),"persistent-hint":!0,dense:""},model:{value:t.form1.locale,callback:function(e){t.$set(t.form1,"locale",e)},expression:"form1.locale"}}),r("x-autocomplete",{attrs:{items:t.locations,"item-value":"0","item-text":"1",label:"Location",name:"location",rules:"required","persistent-hint":!1,dense:""},model:{value:t.form1.location,callback:function(e){t.$set(t.form1,"location",e)},expression:"form1.location"}})],1)],1)],1)],1),r("v-tab-item",{attrs:{transition:!1,"reverse-transition":!1,value:"password"}},[r("v-container",{staticClass:"pa-0"},[r("v-row",{attrs:{"no-gutters":""}},[r("v-col",{attrs:{cols:"12",sm:"9",md:"6"}},[r("x-password",{ref:"form1.new_password",attrs:{id:"form1.new_password",label:t.$t("change_password"),name:t.$t("password"),rules:"min:8|max:24",hint:t.$t("leave_empty_for_no_change"),"persistent-hint":!0,dense:""},model:{value:t.form1.new_password,callback:function(e){t.$set(t.form1,"new_password",e)},expression:"form1.new_password"}})],1)],1)],1)],1)],1),r("v-container",{staticClass:"pa-0"},[r("v-row",{attrs:{"no-gutters":""}},[r("v-col",{attrs:{cols:"12",md:"8"}},[r("x-password",{ref:"form1.current_password",attrs:{id:"form1.current_password",label:t.$t("current_password"),name:t.$t("current_password"),rules:"required|min:8|max:24",hint:t.$t("current_password_info"),"persistent-hint":"",dense:""},model:{value:t.form1.current_password,callback:function(e){t.$set(t.form1,"current_password",e)},expression:"form1.current_password"}})],1)],1)],1)],1),r("v-card-actions",{staticClass:"mx-2"},[r("v-btn",{staticClass:"no-caps mb-2",staticStyle:{"min-width":"200px"},attrs:{color:"ctaBg ctaFg--text",tile:"",depressed:"","x-large":"",loading:t.form1.loading,disabled:t.form1.loading||a,type:"submit"}},[t._v(" "+t._s(t.$t("update"))+" "),r("v-icon",{attrs:{right:""}},[t._v("mdi-arrow-right")])],1)],1)],1)]}}])})],1)],1)],1)],1)},s=[],i=r("a34a"),o=r.n(i);function n(t,e,r,a,s,i,o){try{var n=t[i](o),l=n.value}catch(c){return void r(c)}n.done?e(l):Promise.resolve(l).then(a,s)}function l(t){return function(){var e=this,r=arguments;return new Promise((function(a,s){var i=t.apply(e,r);function o(t){n(i,a,s,o,l,"next",t)}function l(t){n(i,a,s,o,l,"throw",t)}o(void 0)}))}}var c={data:function(){return{tab:"general",activeFilePickerId:null,showDeleteAvatar:!1,showDeleteCover:!1,locales:[],locations:[],formDirty:!1,form1:{loading:!1,first_name:this.$auth.user().first_name,last_name:this.$auth.user().last_name,email:this.$auth.user().email,whatsapp:this.$auth.user().whatsapp,avatar_media_url:this.$auth.user().avatar,avatar_media_changed:!1,locale:this.$auth.user().locale,location:this.$auth.user().location,new_password:null,current_password:null,has_error:!1,error:null,success:!1}}},mounted:function(){var t=this;this.axios.get("/localization/locales",{params:{locale:this.$i18n.locale}}).then((function(e){t.locales=t.$_.toPairs({en:"English",en_US:"English (United States)",en_GB:"English (United Kingdom)"})})),this.locations=this.$_.toPairs({Anguilla:"Anguilla","Antigua and Barbuda":"Antigua and Barbuda",Dominica:"Dominica",Grenada:"Grenada",Montserrat:"Montserrat","St. Kitts and Nevis":"St. Kitts and Nevis","St. Lucia":"St. Lucia","St. Vincent":"St. Vincent"}),console.log(this.$auth.user())},created:function(){this.showDeleteAvatar=!this.$_.startsWith(this.form1.avatar_media_url,"data:image/png;base64")},watch:{form1:{handler:function(t,e){this.formDirty||(this.$refs["form1.current_password"].validate(),this.formDirty=!0)},deep:!0}},methods:{submitForm:function(t){var e=this;return l(o.a.mark((function r(){return o.a.wrap((function(r){while(1)switch(r.prev=r.next){case 0:e.$refs[t].reset(),e[t].success=!1,e[t].has_error=!1,e[t].loading=!0,e.updateProfile(t);case 5:case"end":return r.stop()}}),r)})))()},updateProfile:function(t){var e=this,r=this[t],a={headers:{"content-type":"multipart/form-data"}},s=Object.assign({},this.form1);s.avatar_media_url=null,s.language=this.$i18n.locale;var i=new FormData(document.getElementById("form1"));for(var o in s){var n=null===s[o]?"":s[o];i.append(o,n)}this.axios.post("/auth/profile",i,a).then((function(a){"success"===a.data.status&&(r.success=!0,r.new_password=null,r.current_password=null,e.$nextTick((function(){return e.$refs[t].reset()})),e.$auth.user(a.data.user))})).catch((function(a){if(r.has_error=!0,"error"===a.response.data.status){"undefined"!==typeof a.response.data.error&&(r.error=a.response.data.error),e.errorMsg=a.response.data.error;var s=a.response.data.errors||[];for(var i in s)e.$refs[t+"."+i].applyResult({errors:s[i],valid:!1,failedRules:{}})}})).finally((function(){window.scrollTo(0,0),r.loading=!1}))},pickFile:function(t){this.activeFilePickerId=t,document.getElementById(t).click()},onFilePicked:function(t){var e=this,r=t.target.files;if(void 0!==r[0]){if(r[0].name.lastIndexOf(".")<=0)return;var a=new FileReader;a.readAsDataURL(r[0]),a.addEventListener("load",(function(){switch(e.form1[e.activeFilePickerId+"_media_url"]=a.result,e.form1[e.activeFilePickerId+"_media_file"]=r[0],e.form1[e.activeFilePickerId+"_media_changed"]=!0,e.activeFilePickerId){case"avatar":e.showDeleteAvatar=!0;break}}))}else this.form1[this.activeFilePickerId+"_media_file"]="",this.form1[this.activeFilePickerId+"_media_url"]="",this.form1[this.activeFilePickerId+"_media_changed"]=!0}},computed:{app:function(){return this.$store.getters.app}}},u=c,d=r("2877"),m=r("6544"),p=r.n(m),f=r("0798"),h=r("8336"),v=r("b0af"),_=r("99d9"),b=r("62ad"),g=r("a523"),w=r("ce7e"),$=r("4bd4"),x=r("132d"),y=r("0fd9"),C=r("71a3"),k=r("c671"),B=r("fe57"),P=r("aac8"),O=Object(d["a"])(u,a,s,!1,null,null,null);e["default"]=O.exports;p()(O,{VAlert:f["a"],VBtn:h["a"],VCard:v["a"],VCardActions:_["a"],VCardText:_["c"],VCol:b["a"],VContainer:g["a"],VDivider:w["a"],VForm:$["a"],VIcon:x["a"],VRow:y["a"],VTab:C["a"],VTabItem:k["a"],VTabs:B["a"],VTabsItems:P["a"]})}}]);