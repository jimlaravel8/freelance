(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["x44c9"],{"0798":function(t,e,s){"use strict";s("0c18");var r=s("10d2"),a=s("afdd"),i=s("9d26"),o=s("f2e7"),n=s("7560"),c=s("f40d"),l=s("58df"),m=s("d9bd");function u(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,r)}return s}function d(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?u(Object(s),!0).forEach((function(e){h(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):u(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}function h(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}e["a"]=Object(l["a"])(r["a"],o["a"],c["a"]).extend({name:"v-alert",props:{border:{type:String,validator:function(t){return["top","right","bottom","left"].includes(t)}},closeLabel:{type:String,default:"$vuetify.close"},coloredBorder:Boolean,dense:Boolean,dismissible:Boolean,icon:{default:"",type:[Boolean,String],validator:function(t){return"string"===typeof t||!1===t}},outlined:Boolean,prominent:Boolean,text:Boolean,type:{type:String,validator:function(t){return["info","error","success","warning"].includes(t)}},value:{type:Boolean,default:!0}},computed:{__cachedBorder:function(){if(!this.border)return null;var t={staticClass:"v-alert__border",class:h({},"v-alert__border--".concat(this.border),!0)};return this.coloredBorder&&(t=this.setBackgroundColor(this.computedColor,t),t.class["v-alert__border--has-color"]=!0),this.$createElement("div",t)},__cachedDismissible:function(){var t=this;if(!this.dismissible)return null;var e=this.iconColor;return this.$createElement(a["a"],{staticClass:"v-alert__dismissible",props:{color:e,icon:!0,small:!0},attrs:{"aria-label":this.$vuetify.lang.t(this.closeLabel)},on:{click:function(){return t.isActive=!1}}},[this.$createElement(i["a"],{props:{color:e}},"$cancel")])},__cachedIcon:function(){return this.computedIcon?this.$createElement(i["a"],{staticClass:"v-alert__icon",props:{color:this.iconColor}},this.computedIcon):null},classes:function(){var t=d({},r["a"].options.computed.classes.call(this),{"v-alert--border":Boolean(this.border),"v-alert--dense":this.dense,"v-alert--outlined":this.outlined,"v-alert--prominent":this.prominent,"v-alert--text":this.text});return this.border&&(t["v-alert--border-".concat(this.border)]=!0),t},computedColor:function(){return this.color||this.type},computedIcon:function(){return!1!==this.icon&&("string"===typeof this.icon&&this.icon?this.icon:!!["error","info","success","warning"].includes(this.type)&&"$".concat(this.type))},hasColoredIcon:function(){return this.hasText||Boolean(this.border)&&this.coloredBorder},hasText:function(){return this.text||this.outlined},iconColor:function(){return this.hasColoredIcon?this.computedColor:void 0},isDark:function(){return!(!this.type||this.coloredBorder||this.outlined)||n["a"].options.computed.isDark.call(this)}},created:function(){this.$attrs.hasOwnProperty("outline")&&Object(m["a"])("outline","outlined",this)},methods:{genWrapper:function(){var t=[this.$slots.prepend||this.__cachedIcon,this.genContent(),this.__cachedBorder,this.$slots.append,this.$scopedSlots.close?this.$scopedSlots.close({toggle:this.toggle}):this.__cachedDismissible],e={staticClass:"v-alert__wrapper"};return this.$createElement("div",e,t)},genContent:function(){return this.$createElement("div",{staticClass:"v-alert__content"},this.$slots.default)},genAlert:function(){var t={staticClass:"v-alert",attrs:{role:"alert"},class:this.classes,style:this.styles,directives:[{name:"show",value:this.isActive}]};if(!this.coloredBorder){var e=this.hasText?this.setTextColor:this.setBackgroundColor;t=e(this.computedColor,t)}return this.$createElement("div",t,[this.genWrapper()])},toggle:function(){this.isActive=!this.isActive}},render:function(t){var e=this.genAlert();return this.transition?t("transition",{props:{name:this.transition,origin:this.origin,mode:this.mode}},[e]):e}})},"0c18":function(t,e,s){},bdf6:function(t,e,s){"use strict";s.r(e);var r=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{directives:[{name:"resize",rawName:"v-resize",value:t.onResize,expression:"onResize"}],staticClass:"viewContainer"},[s("v-container",{staticClass:"pa-0"},[s("v-img",{key:t.tabImg,attrs:{src:t.tabImg,"min-height":t.tabImgHeight,"aspect-ratio":t.tabImgAspectRation,transition:"fade-transition"}},[s("v-container",{staticClass:"inner-container"},[s("v-card",{staticClass:"mt-md-12 mt-0 content-tabs-wrapper",attrs:{tile:"",elevation:"0"}},[s("v-tabs",{staticClass:"px-5 content-tabs",attrs:{color:t.$store.state.app.dark?"white":"black","icons-and-text":"",height:"100","slider-size":"4","show-arrows":""}}),s("v-divider")],1),s("v-tabs-items",{staticStyle:{"background-color":"transparent"},model:{value:t.tab,callback:function(e){t.tab=e},expression:"tab"}},[s("v-tab-item",{attrs:{value:"tab-0"}},[s("v-container",{staticClass:"pa-0"},[s("v-row",{attrs:{"no-gutters":""}},[s("v-col",{attrs:{cols:"12",sm:"9",md:"6"}},[s("ValidationObserver",{ref:"form1",scopedSlots:t._u([{key:"default",fn:function(e){var r=e.invalid;return[s("v-form",{attrs:{model:t.form1,autocomplete:"off",method:"post"},on:{submit:function(e){return e.preventDefault(),t.submit("form1")}}},[s("v-card",{staticClass:"content-tabs-card pa-5 mb-0 mb-md-12",attrs:{flat:"",tile:""}},[s("v-card-text",[s("h1",{staticClass:"fg--text display-2 font-weight-medium mb-4",staticStyle:{"line-height":"4rem","max-width":"340px"}},[t._v(t._s(t.$t("registration_business_title")))]),s("p",{staticClass:"body-1"},[t._v(t._s(t.$t("registration_business_head")))]),s("v-alert",{staticClass:"mb-4",attrs:{value:t.form1.hasError&&!t.form1.success,type:"error",tile:""}},["registration_validation_error"==t.form1.error?s("span",[t._v(t._s(t.$t("server_error")))]):s("span",[t._v(t._s(t.$t("correct_errors")))])]),s("x-text-field",{ref:"form1.business_name",attrs:{id:"form1.business_name",label:t.$t("business_name"),name:t.$t("business_name"),rules:"required|min:2|max:32",dense:""},model:{value:t.form1.business_name,callback:function(e){t.$set(t.form1,"business_name",e)},expression:"form1.business_name"}}),s("x-autocomplete",{attrs:{items:t.locations,"item-value":"0","item-text":"1",label:"Location",name:"location",rules:"required","persistent-hint":!1,dense:""},on:{change:t.enter_code},model:{value:t.form1.location,callback:function(e){t.$set(t.form1,"location",e)},expression:"form1.location"}}),s("v-row",[s("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[s("x-text-field",{ref:"form1.first_name",attrs:{id:"form1.first_name",label:t.$t("first_name"),name:t.$t("name"),rules:"required|min:1|max:32",dense:""},model:{value:t.form1.first_name,callback:function(e){t.$set(t.form1,"first_name",e)},expression:"form1.first_name"}})],1),s("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[s("x-text-field",{ref:"form1.last_name",attrs:{id:"form1.last_name",label:t.$t("last_name"),name:t.$t("last_name"),rules:"required|min:1|max:32",dense:""},model:{value:t.form1.last_name,callback:function(e){t.$set(t.form1,"last_name",e)},expression:"form1.last_name"}})],1)],1),s("x-text-field",{ref:"form1.email",attrs:{type:"email",id:"form1.email",label:t.$t("email"),name:t.$t("email"),rules:"required|max:64|email",dense:""},model:{value:t.form1.email,callback:function(e){t.$set(t.form1,"email",e)},expression:"form1.email"}}),s("x-text-field",{ref:"form1.phone",attrs:{prefix:t.form1.country_code,id:"form1.phone",label:"Contact Number",name:"phone",rules:"required|min:7|max:7",dense:""},model:{value:t.form1.phone,callback:function(e){t.$set(t.form1,"phone",e)},expression:"form1.phone"}}),s("x-autocomplete",{ref:"form1.cid",attrs:{id:"form1.cid",loading:t.loading2,items:t.categories,"item-value":"cid","item-text":"name",label:"Business Category",name:"Business Category",rules:"required",dense:""},model:{value:t.form1.cid,callback:function(e){t.$set(t.form1,"cid",e)},expression:"form1.cid"}}),s("x-password",{ref:"form1.password",attrs:{id:"form1.password",label:t.$t("password"),name:t.$t("password"),rules:"required|min:8|max:24",dense:""},model:{value:t.form1.password,callback:function(e){t.$set(t.form1,"password",e)},expression:"form1.password"}}),s("v-btn",{staticClass:"no-caps mb-3",attrs:{color:"ctaBg ctaFg--text","x-large":"",tile:"",depressed:"",block:"",loading:t.form1.loading,disabled:t.form1.loading||r,type:"submit"}},[t._v(" "+t._s(t.$t("sign_up"))+" "),s("v-icon",{attrs:{right:""}},[t._v("mdi-arrow-right")])],1),s("i18n",{attrs:{path:"sign_up_consent"},scopedSlots:t._u([{key:"terms_and_conditions",fn:function(){return[s("router-link",{attrs:{to:{name:"terms"}}},[t._v(t._s(t.$t("terms_and_conditions")))])]},proxy:!0},{key:"privacy_policy",fn:function(){return[s("router-link",{attrs:{to:{name:"privacy"}}},[t._v(t._s(t.$t("privacy_policy")))])]},proxy:!0}],null,!0)})],1)],1)],1)]}}])})],1)],1)],1)],1)],1)],1)],1)],1)],1)},a=[],i=s("a34a"),o=s.n(i);function n(t,e,s,r,a,i,o){try{var n=t[i](o),c=n.value}catch(l){return void s(l)}n.done?e(c):Promise.resolve(c).then(r,a)}function c(t){return function(){var e=this,s=arguments;return new Promise((function(r,a){var i=t.apply(e,s);function o(t){n(i,r,a,o,c,"next",t)}function c(t){n(i,r,a,o,c,"throw",t)}o(void 0)}))}}var l={data:function(){return{tab:"tab-0",tabImg:"about:blank",tabImgHeight:null,tabImgDefaultHeight:780,tabImgAspectRation:1.7778,tabImgs:[],currencies:[],categories:[],locations:[],loading2:!0,form1:{loading:!1,cid:"",terms:"",business_name:"",currency:"XCD",first_name:"",last_name:"",location:"",email:"",phone:"",password:"",locale:"",timezone:"",hasError:!1,country_code:"",error:"",success:!1}}},created:function(){var t=this;this.locations=this.$_.toPairs({Anguilla:"Anguilla","Antigua and Barbuda":"Antigua and Barbuda",Barbados:"Barbados",Dominica:"Dominica",Grenada:"Grenada",Guyana:"Guyana",Jamaica:"Jamaica",Montserrat:"Montserrat","St. Kitts and Nevis":"St. Kitts and Nevis","St. Lucia":"St. Lucia","St. Vincent":"St. Vincent",Trinidad:"Trinidad","Virgin Islands":"Virgin Islands"}),this.form1.locale="en",this.form1.timezone=Intl.DateTimeFormat().resolvedOptions().timeZone||null,this.axios.get("/localization/currencies",{params:{locale:this.$i18n.locale}}).then((function(e){t.currencies=t.$_.toPairs(e.data)})),this.axios.get("/category/all").then((function(e){t.categories=e.data,t.loading2=!1})),this.tabImgs=[this.$init.config.schemeAndHost+"/img/sign-up-business-tab.jpg"],window.innerWidth>760?(this.tabImg=this.tabImgs[0],this.tabImgHeight=this.tabImgDefaultHeight,this.tabImgAspectRation=1.7778):(this.tabImgHeight=null,this.tabImgAspectRation=3);var e=new Array;this.tabImgs.forEach((function(t,s){e[s]=new Image,e[s].src=t}))},methods:{enter_code:function(){"Anguilla"==this.form1.location?this.form1.country_code="1264":"Antigua and Barbuda"==this.form1.location?this.form1.country_code="1268":"Dominica"==this.form1.location?this.form1.country_code="1767":"Grenada"==this.form1.location?this.form1.country_code="1473":"Montserrat"==this.form1.location?this.form1.country_code="1664":"St. Kitts and Nevis"==this.form1.location?this.form1.country_code="1869":"St. Lucia"==this.form1.location?this.form1.country_code="1758":"St. Vincent"==this.form1.location?this.form1.country_code="1784":"Barbados"==this.form1.location?this.form1.country_code="1246":"Jamaica"==this.form1.location?this.form1.country_code="1876":"Trinidad"==this.form1.location?this.form1.country_code="1868":"Guyana"==this.form1.location?this.form1.country_code="1592":"Virgin Islands"==this.form1.location&&(this.form1.country_code="1284")},onResize:function(){if(window.innerWidth<=760)this.tabImg="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=",this.tabImgHeight=null,this.tabImgAspectRation=3;else if("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="==this.tabImg){var t=this.tab.split("-");this.tabImg=this.tabImgs[t[1]],this.tabImgHeight=this.tabImgDefaultHeight,this.tabImgAspectRation=1.7778}},submit:function(t){var e=this;return c(o.a.mark((function s(){var r;return o.a.wrap((function(s){while(1)switch(s.prev=s.next){case 0:e.$refs[t].reset(),r=e[t],r.hasError=!1,r.loading=!0,e.$auth.register({data:{language:e.$i18n.locale,type:"business",business_name:r.business_name,currency:r.currency,cid:r.cid,first_name:r.first_name,last_name:r.last_name,email:r.email,phone:r.phone,password:r.password,locale:r.locale,timezone:r.timezone,location:r.location,country_code:r.country_code,terms:r.terms},success:function(){r.success=!0,this.$auth.login({rememberMe:!0,fetchUser:!0,params:{locale:this.$i18n.locale,location:r.location,email:r.email,whatsapp:r.whatsapp,password:r.password,country_code:r.country_code,remember:!0},success:function(){var t;2==parseInt(this.$auth.user().role)&&(t="customer"),3==parseInt(this.$auth.user().role)&&(t="business"),4==parseInt(this.$auth.user().role)&&(t="staff");var e,s=new Date;s.setTime(s.getTime()+864e5),document.cookie="customer_id="+this.$auth.user().customer_number+"; expires="+s.toGMTString(),document.cookie="customer_name="+this.$auth.user().first_name+" "+this.$auth.user().last_name+"; expires="+s.toGMTString(),document.cookie="customer_type="+t+"; expires="+s.toGMTString(),1===this.$auth.user().role&&(e="admin.dashboard"),2===this.$auth.user().role&&(e="customer.earn"),3===this.$auth.user().role&&(e="business.issue-points"),4===this.$auth.user().role&&(e="staff.dashboard"),this.$router.push({name:e})}})},error:function(e){var s=e.response.data.errors||[];for(var a in s)this.$refs[t+"."+a].applyResult({errors:s[a],valid:!1,failedRules:{}});0===s.length&&(r.hasError=!0),r.loading=!1}});case 5:case"end":return s.stop()}}),s)})))()}},watch:{tab:function(t){if(window.innerWidth>760){var e=t.split("-");this.tabImg=this.tabImgs[e[1]],this.tabImgHeight=this.tabImgDefaultHeight,this.tabImgAspectRation=1.7778}}}},m=l,u=s("2877"),d=s("6544"),h=s.n(d),f=s("0798"),p=s("8336"),b=s("b0af"),g=s("99d9"),v=s("62ad"),_=s("a523"),A=s("ce7e"),y=s("4bd4"),$=s("132d"),w=s("adda"),x=s("0fd9"),C=s("c671"),I=s("fe57"),B=s("aac8"),k=s("269a"),O=s.n(k),S=s("dc22"),V=Object(u["a"])(m,r,a,!1,null,null,null);e["default"]=V.exports;h()(V,{VAlert:f["a"],VBtn:p["a"],VCard:b["a"],VCardText:g["c"],VCol:v["a"],VContainer:_["a"],VDivider:A["a"],VForm:y["a"],VIcon:$["a"],VImg:w["a"],VRow:x["a"],VTabItem:C["a"],VTabs:I["a"],VTabsItems:B["a"]}),O()(V,{Resize:S["a"]})}}]);