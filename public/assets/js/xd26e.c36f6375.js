(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["xd26e"],{"0798":function(t,e,r){"use strict";r("0c18");var s=r("10d2"),a=r("afdd"),i=r("9d26"),o=r("f2e7"),n=r("7560"),c=r("2b0e"),l=c["a"].extend({name:"transitionable",props:{mode:String,origin:String,transition:String}}),m=r("58df"),u=r("d9bd");function d(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,s)}return r}function h(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?d(Object(r),!0).forEach((function(e){p(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):d(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function p(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}e["a"]=Object(m["a"])(s["a"],o["a"],l).extend({name:"v-alert",props:{border:{type:String,validator:function(t){return["top","right","bottom","left"].includes(t)}},closeLabel:{type:String,default:"$vuetify.close"},coloredBorder:Boolean,dense:Boolean,dismissible:Boolean,icon:{default:"",type:[Boolean,String],validator:function(t){return"string"===typeof t||!1===t}},outlined:Boolean,prominent:Boolean,text:Boolean,type:{type:String,validator:function(t){return["info","error","success","warning"].includes(t)}},value:{type:Boolean,default:!0}},computed:{__cachedBorder:function(){if(!this.border)return null;var t={staticClass:"v-alert__border",class:p({},"v-alert__border--".concat(this.border),!0)};return this.coloredBorder&&(t=this.setBackgroundColor(this.computedColor,t),t.class["v-alert__border--has-color"]=!0),this.$createElement("div",t)},__cachedDismissible:function(){var t=this;if(!this.dismissible)return null;var e=this.iconColor;return this.$createElement(a["a"],{staticClass:"v-alert__dismissible",props:{color:e,icon:!0,small:!0},attrs:{"aria-label":this.$vuetify.lang.t(this.closeLabel)},on:{click:function(){return t.isActive=!1}}},[this.$createElement(i["a"],{props:{color:e}},"$cancel")])},__cachedIcon:function(){return this.computedIcon?this.$createElement(i["a"],{staticClass:"v-alert__icon",props:{color:this.iconColor}},this.computedIcon):null},classes:function(){var t=h({},s["a"].options.computed.classes.call(this),{"v-alert--border":Boolean(this.border),"v-alert--dense":this.dense,"v-alert--outlined":this.outlined,"v-alert--prominent":this.prominent,"v-alert--text":this.text});return this.border&&(t["v-alert--border-".concat(this.border)]=!0),t},computedColor:function(){return this.color||this.type},computedIcon:function(){return!1!==this.icon&&("string"===typeof this.icon&&this.icon?this.icon:!!["error","info","success","warning"].includes(this.type)&&"$".concat(this.type))},hasColoredIcon:function(){return this.hasText||Boolean(this.border)&&this.coloredBorder},hasText:function(){return this.text||this.outlined},iconColor:function(){return this.hasColoredIcon?this.computedColor:void 0},isDark:function(){return!(!this.type||this.coloredBorder||this.outlined)||n["a"].options.computed.isDark.call(this)}},created:function(){this.$attrs.hasOwnProperty("outline")&&Object(u["a"])("outline","outlined",this)},methods:{genWrapper:function(){var t=[this.$slots.prepend||this.__cachedIcon,this.genContent(),this.__cachedBorder,this.$slots.append,this.$scopedSlots.close?this.$scopedSlots.close({toggle:this.toggle}):this.__cachedDismissible],e={staticClass:"v-alert__wrapper"};return this.$createElement("div",e,t)},genContent:function(){return this.$createElement("div",{staticClass:"v-alert__content"},this.$slots.default)},genAlert:function(){var t={staticClass:"v-alert",attrs:{role:"alert"},class:this.classes,style:this.styles,directives:[{name:"show",value:this.isActive}]};if(!this.coloredBorder){var e=this.hasText?this.setTextColor:this.setBackgroundColor;t=e(this.computedColor,t)}return this.$createElement("div",t,[this.genWrapper()])},toggle:function(){this.isActive=!this.isActive}},render:function(t){var e=this.genAlert();return this.transition?t("transition",{props:{name:this.transition,origin:this.origin,mode:this.mode}},[e]):e}})},"0c18":function(t,e,r){},"935e":function(t,e,r){"use strict";r.r(e);var s=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("div",{directives:[{name:"resize",rawName:"v-resize",value:t.onResize,expression:"onResize"}],staticClass:"viewContainer"},[r("v-container",{staticClass:"pa-0"},[r("v-img",{key:t.tabImg,attrs:{src:t.tabImg,"min-height":t.tabImgHeight,"aspect-ratio":t.tabImgAspectRation,transition:"fade-transition"}},[r("v-container",{staticClass:"inner-container"},[r("v-card",{staticClass:"mt-md-12 mt-0 content-tabs-wrapper",attrs:{tile:"",elevation:"0"}},[r("v-tabs",{staticClass:"px-5 content-tabs",attrs:{color:t.$store.state.app.dark?"white":"black","icons-and-text":"",height:"100","slider-size":"4","show-arrows":""}},[r("v-tabs-slider"),r("v-tab",{staticClass:"no-caps",attrs:{to:{name:"register"},ripple:!1}},[t._v(" "+t._s(t.$t("customer"))+" "),r("v-icon",{attrs:{size:"24"}},[t._v("mdi-account-circle")])],1)],1),r("v-divider")],1),r("v-tabs-items",{staticStyle:{"background-color":"transparent"},model:{value:t.tab,callback:function(e){t.tab=e},expression:"tab"}},[r("v-tab-item",{attrs:{value:"tab-0"}},[r("v-container",{staticClass:"pa-0"},[r("v-row",{attrs:{"no-gutters":""}},[r("v-col",{attrs:{cols:"12",sm:"9",md:"6"}},[r("ValidationObserver",{ref:"form1",scopedSlots:t._u([{key:"default",fn:function(e){var s=e.invalid;return[r("v-form",{attrs:{model:t.form1,autocomplete:"off",method:"post"},on:{submit:function(e){return e.preventDefault(),t.submit("form1")}}},[r("v-card",{staticClass:"content-tabs-card pa-5 mb-0 mb-md-12",attrs:{flat:"",tile:""}},[r("v-card-text",[r("h1",{staticClass:"fg--text display-2 font-weight-medium mb-4",staticStyle:{"line-height":"4rem","max-width":"300px"}},[t._v(t._s(t.$t("registration_user_title")))]),r("p",{staticClass:"body-1"},[t._v(t._s(t.$t("registration_user_head")))]),r("v-alert",{staticClass:"mb-4",attrs:{value:t.form1.hasError&&!t.form1.success,type:"error",tile:""}},["registration_validation_error"==t.form1.error?r("span",[t._v(t._s(t.$t("server_error")))]):r("span",[t._v(t._s(t.$t("correct_errors")))])]),r("v-row",[r("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[r("x-text-field",{ref:"form1.first_name",attrs:{id:"form1.first_name",label:t.$t("first_name"),name:t.$t("name"),rules:"required|min:1|max:32",dense:""},model:{value:t.form1.first_name,callback:function(e){t.$set(t.form1,"first_name",e)},expression:"form1.first_name"}})],1),r("v-col",{staticClass:"py-0",attrs:{cols:"12",sm:"6"}},[r("x-text-field",{ref:"form1.last_name",attrs:{id:"form1.last_name",label:t.$t("last_name"),name:t.$t("last_name"),rules:"required|min:1|max:32",dense:""},model:{value:t.form1.last_name,callback:function(e){t.$set(t.form1,"last_name",e)},expression:"form1.last_name"}})],1)],1),r("x-autocomplete",{attrs:{items:t.locations,"item-value":"0","item-text":"1",label:"Location",name:"location",rules:"required","persistent-hint":!1,dense:""},on:{change:t.enter_code},model:{value:t.form1.location,callback:function(e){t.$set(t.form1,"location",e)},expression:"form1.location"}}),r("x-text-field",{ref:"form1.whatsapp",attrs:{prefix:t.form1.country_code,id:"form1.whatsapp",label:"Phone number",name:"phone number",rules:"required|min:7|max:7",dense:"",type:"number"},model:{value:t.form1.whatsapp,callback:function(e){t.$set(t.form1,"whatsapp",e)},expression:"form1.whatsapp"}}),r("x-text-field",{ref:"form1.email",attrs:{type:"email",id:"form1.email",label:t.$t("email"),name:t.$t("email"),rules:"required|max:64|email",dense:""},model:{value:t.form1.email,callback:function(e){t.$set(t.form1,"email",e)},expression:"form1.email"}}),r("x-password",{ref:"form1.password",attrs:{id:"form1.password",label:t.$t("password"),name:t.$t("password"),rules:"required|min:8|max:24",dense:""},model:{value:t.form1.password,callback:function(e){t.$set(t.form1,"password",e)},expression:"form1.password"}}),r("v-btn",{staticClass:"no-caps mb-3",attrs:{color:"ctaBg ctaFg--text","x-large":"",tile:"",depressed:"",block:"",loading:t.form1.loading,disabled:t.form1.loading||s,type:"submit"}},[t._v(" "+t._s(t.$t("sign_up"))+" "),r("v-icon",{attrs:{right:""}},[t._v("mdi-arrow-right")])],1),r("div",{staticClass:"caption"},[r("i18n",{attrs:{path:"sign_up_consent"},scopedSlots:t._u([{key:"terms_and_conditions",fn:function(){return[r("router-link",{attrs:{to:{name:"terms"}}},[t._v(t._s(t.$t("terms_and_conditions")))])]},proxy:!0},{key:"privacy_policy",fn:function(){return[r("router-link",{attrs:{to:{name:"privacy"}}},[t._v(t._s(t.$t("privacy_policy")))])]},proxy:!0}],null,!0)})],1)],1)],1)],1)]}}])})],1)],1)],1)],1)],1)],1)],1)],1)],1)},a=[],i=r("a34a"),o=r.n(i);function n(t,e,r,s,a,i,o){try{var n=t[i](o),c=n.value}catch(l){return void r(l)}n.done?e(c):Promise.resolve(c).then(s,a)}function c(t){return function(){var e=this,r=arguments;return new Promise((function(s,a){var i=t.apply(e,r);function o(t){n(i,s,a,o,c,"next",t)}function c(t){n(i,s,a,o,c,"throw",t)}o(void 0)}))}}var l={data:function(){return{tab:"tab-0",tabImg:"about:blank",tabImgHeight:null,tabImgDefaultHeight:780,tabImgAspectRation:1.7778,tabImgs:[],form1:{loading:!1,terms:"",first_name:"",last_name:"",email:"",whatsapp:"",password:"",locale:"",timezone:"",hasError:!1,error:"",country_code:"",success:!1}}},created:function(){this.locations=this.$_.toPairs({Anguilla:"Anguilla","Antigua and Barbuda":"Antigua and Barbuda",Dominica:"Dominica",Grenada:"Grenada",Montserrat:"Montserrat","St. Kitts and Nevis":"St. Kitts and Nevis","St. Lucia":"St. Lucia","St. Vincent":"St. Vincent"}),this.form1.locale="en",this.form1.timezone=Intl.DateTimeFormat().resolvedOptions().timeZone||null,this.tabImgs=[this.$init.config.schemeAndHost+"/img/sign-up-customer-tab.jpg"],window.innerWidth>760?(this.tabImg=this.tabImgs[0],this.tabImgHeight=this.tabImgDefaultHeight,this.tabImgAspectRation=1.7778):(this.tabImgHeight=null,this.tabImgAspectRation=3);var t=new Array;this.tabImgs.forEach((function(e,r){t[r]=new Image,t[r].src=e}))},methods:{enter_code:function(){"Anguilla"==this.form1.location?this.form1.country_code="1264":"Antigua and Barbuda"==this.form1.location?this.form1.country_code="1268":"Dominica"==this.form1.location?this.form1.country_code="1767":"Grenada"==this.form1.location?this.form1.country_code="1473":"Montserrat"==this.form1.location?this.form1.country_code="1664":"St. Kitts and Nevis"==this.form1.location?this.form1.country_code="1869":"St. Lucia"==this.form1.location?this.form1.country_code="1758":"St. Vincent"==this.form1.location&&(this.form1.country_code="1784")},onResize:function(){if(window.innerWidth<=760)this.tabImg="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=",this.tabImgHeight=null,this.tabImgAspectRation=3;else if("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="==this.tabImg){var t=this.tab.split("-");this.tabImg=this.tabImgs[t[1]],this.tabImgHeight=this.tabImgDefaultHeight,this.tabImgAspectRation=1.7778}},submit:function(t){var e=this;return c(o.a.mark((function r(){var s;return o.a.wrap((function(r){while(1)switch(r.prev=r.next){case 0:e.$refs[t].reset(),s=e[t],s.hasError=!1,s.loading=!0,e.$auth.register({data:{language:e.$i18n.locale,first_name:s.first_name,last_name:s.last_name,email:s.email,whatsapp:s.whatsapp,password:s.password,locale:s.locale,timezone:s.timezone,location:s.location,terms:s.terms,country_code:s.country_code},success:function(){s.success=!0,this.$auth.login({rememberMe:!0,fetchUser:!0,params:{locale:this.$i18n.locale,location:s.location,email:s.email,password:s.password,whatsapp:s.whatsapp,country_code:s.country_code,remember:!0},success:function(){var t;2==parseInt(this.$auth.user().role)&&(t="customer.earn"),3==parseInt(this.$auth.user().role)&&(t="business.issue-points"),4==parseInt(this.$auth.user().role)&&(t="business.issue-points");var e,r=new Date;r.setTime(r.getTime()+864e5),document.cookie="customer_id="+this.$auth.user().customer_number+"; expires="+r.toGMTString(),document.cookie="customer_name="+this.$auth.user().first_name+" "+this.$auth.user().last_name+"; expires="+r.toGMTString(),document.cookie="customer_type="+t+"; expires="+r.toGMTString(),1==parseInt(this.$auth.user().role)&&(e="admin.dashboard"),2==parseInt(this.$auth.user().role)&&(e="customer.earn"),3==parseInt(this.$auth.user().role)&&(e="business.issue-points"),4==parseInt(this.$auth.user().role)&&(e="business.issue-points"),this.$router.push({name:e})}})},error:function(e){var r=e.response.data.errors||[];for(var a in r)this.$refs[t+"."+a].applyResult({errors:r[a],valid:!1,failedRules:{}});0===r.length&&(s.hasError=!0),s.loading=!1}});case 5:case"end":return r.stop()}}),r)})))()}},watch:{tab:function(t){if(window.innerWidth>760){var e=t.split("-");this.tabImg=this.tabImgs[e[1]],this.tabImgHeight=this.tabImgDefaultHeight,this.tabImgAspectRation=1.7778}}}},m=l,u=r("2877"),d=r("6544"),h=r.n(d),p=r("0798"),f=r("8336"),b=r("b0af"),g=r("99d9"),v=r("62ad"),_=r("a523"),A=r("ce7e"),w=r("4bd4"),y=r("132d"),$=r("adda"),I=r("0fd9"),C=r("71a3"),x=r("c671"),k=r("fe57"),S=r("aac8"),B=r("9a96"),O=r("269a"),V=r.n(O),E=r("dc22"),R=Object(u["a"])(m,s,a,!1,null,null,null);e["default"]=R.exports;h()(R,{VAlert:p["a"],VBtn:f["a"],VCard:b["a"],VCardText:g["c"],VCol:v["a"],VContainer:_["a"],VDivider:A["a"],VForm:w["a"],VIcon:y["a"],VImg:$["a"],VRow:I["a"],VTab:C["a"],VTabItem:x["a"],VTabs:k["a"],VTabsItems:S["a"],VTabsSlider:B["a"]}),V()(R,{Resize:E["a"]})}}]);