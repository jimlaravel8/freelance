(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["x80e2"],{"0798":function(t,e,s){"use strict";s("0c18");var r=s("10d2"),i=s("afdd"),o=s("9d26"),a=s("f2e7"),n=s("7560"),c=s("f40d"),l=s("58df"),m=s("d9bd");function u(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,r)}return s}function d(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?u(Object(s),!0).forEach((function(e){h(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):u(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}function h(t,e,s){return e in t?Object.defineProperty(t,e,{value:s,enumerable:!0,configurable:!0,writable:!0}):t[e]=s,t}e["a"]=Object(l["a"])(r["a"],a["a"],c["a"]).extend({name:"v-alert",props:{border:{type:String,validator:function(t){return["top","right","bottom","left"].includes(t)}},closeLabel:{type:String,default:"$vuetify.close"},coloredBorder:Boolean,dense:Boolean,dismissible:Boolean,icon:{default:"",type:[Boolean,String],validator:function(t){return"string"===typeof t||!1===t}},outlined:Boolean,prominent:Boolean,text:Boolean,type:{type:String,validator:function(t){return["info","error","success","warning"].includes(t)}},value:{type:Boolean,default:!0}},computed:{__cachedBorder:function(){if(!this.border)return null;var t={staticClass:"v-alert__border",class:h({},"v-alert__border--".concat(this.border),!0)};return this.coloredBorder&&(t=this.setBackgroundColor(this.computedColor,t),t.class["v-alert__border--has-color"]=!0),this.$createElement("div",t)},__cachedDismissible:function(){var t=this;if(!this.dismissible)return null;var e=this.iconColor;return this.$createElement(i["a"],{staticClass:"v-alert__dismissible",props:{color:e,icon:!0,small:!0},attrs:{"aria-label":this.$vuetify.lang.t(this.closeLabel)},on:{click:function(){return t.isActive=!1}}},[this.$createElement(o["a"],{props:{color:e}},"$cancel")])},__cachedIcon:function(){return this.computedIcon?this.$createElement(o["a"],{staticClass:"v-alert__icon",props:{color:this.iconColor}},this.computedIcon):null},classes:function(){var t=d({},r["a"].options.computed.classes.call(this),{"v-alert--border":Boolean(this.border),"v-alert--dense":this.dense,"v-alert--outlined":this.outlined,"v-alert--prominent":this.prominent,"v-alert--text":this.text});return this.border&&(t["v-alert--border-".concat(this.border)]=!0),t},computedColor:function(){return this.color||this.type},computedIcon:function(){return!1!==this.icon&&("string"===typeof this.icon&&this.icon?this.icon:!!["error","info","success","warning"].includes(this.type)&&"$".concat(this.type))},hasColoredIcon:function(){return this.hasText||Boolean(this.border)&&this.coloredBorder},hasText:function(){return this.text||this.outlined},iconColor:function(){return this.hasColoredIcon?this.computedColor:void 0},isDark:function(){return!(!this.type||this.coloredBorder||this.outlined)||n["a"].options.computed.isDark.call(this)}},created:function(){this.$attrs.hasOwnProperty("outline")&&Object(m["a"])("outline","outlined",this)},methods:{genWrapper:function(){var t=[this.$slots.prepend||this.__cachedIcon,this.genContent(),this.__cachedBorder,this.$slots.append,this.$scopedSlots.close?this.$scopedSlots.close({toggle:this.toggle}):this.__cachedDismissible],e={staticClass:"v-alert__wrapper"};return this.$createElement("div",e,t)},genContent:function(){return this.$createElement("div",{staticClass:"v-alert__content"},this.$slots.default)},genAlert:function(){var t={staticClass:"v-alert",attrs:{role:"alert"},class:this.classes,style:this.styles,directives:[{name:"show",value:this.isActive}]};if(!this.coloredBorder){var e=this.hasText?this.setTextColor:this.setBackgroundColor;t=e(this.computedColor,t)}return this.$createElement("div",t,[this.genWrapper()])},toggle:function(){this.isActive=!this.isActive}},render:function(t){var e=this.genAlert();return this.transition?t("transition",{props:{name:this.transition,origin:this.origin,mode:this.mode}},[e]):e}})},"0c18":function(t,e,s){},5326:function(t,e,s){"use strict";s.r(e);var r=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{directives:[{name:"resize",rawName:"v-resize",value:t.onResize,expression:"onResize"}],staticClass:"viewContainer"},[s("v-container",{staticClass:"pa-0"},[s("v-img",{key:t.topBgImg,attrs:{src:t.topBgImg,"min-height":t.topBgImgHeight,"aspect-ratio":t.topBgImgAspectRation,transition:"fade-transition"}},[s("v-container",{staticClass:"inner-container"},[s("v-card",{staticClass:"mt-md-12 mt-0 content-tabs-wrapper",attrs:{tile:"",elevation:"0"}},[s("v-tabs",{staticClass:"px-5 content-tabs",attrs:{color:t.$store.state.app.dark?"white":"black","icons-and-text":"",height:"100","slider-size":"4","show-arrows":""}},[s("v-tabs-slider"),s("v-tab",{staticClass:"no-caps",attrs:{to:{name:"login"},ripple:!1}},[t._v(" "+t._s(t.$t("log_in"))+" "),s("v-icon",{attrs:{size:"24"}},[t._v("mdi-account")])],1),s("v-tab",{staticClass:"no-caps",attrs:{to:{name:"password.email"},ripple:!1}},[t._v(" "+t._s(t.$t("forgot_password"))+" "),s("v-icon",{attrs:{size:"24"}},[t._v("mdi-key-variant")])],1)],1),s("v-divider")],1),s("v-tabs-items",{staticStyle:{"background-color":"transparent"},model:{value:t.tab,callback:function(e){t.tab=e},expression:"tab"}},[s("v-tab-item",{attrs:{value:"tab-0"}},[s("v-container",{staticClass:"pa-0"},[s("v-row",{attrs:{"no-gutters":""}},[s("v-col",{attrs:{cols:"12",sm:"9",md:"6"}},[s("ValidationObserver",{ref:"form1",scopedSlots:t._u([{key:"default",fn:function(e){var r=e.invalid;return[s("v-form",{attrs:{model:t.form1,autocomplete:"off",method:"post"},on:{submit:function(e){return e.preventDefault(),t.submit("form1")}}},[s("v-card",{staticClass:"content-tabs-card pa-5 mb-0 mb-md-12",attrs:{flat:"",tile:""}},[s("v-card-text",[s("h1",{staticClass:"fg--text display-2 font-weight-medium mb-4",staticStyle:{"line-height":"4rem"}},[t._v(t._s(t.$t("sign_in")))]),s("v-alert",{staticClass:"mb-4",attrs:{value:t.form1.hasError,type:"error",tile:""}},[s("span",{domProps:{innerHTML:t._s(t.$t("login_not_recognized"))}})]),s("v-alert",{staticClass:"mb-4",attrs:{value:t.successRegistrationRedirect,type:"success",tile:""}},[t._v(t._s(t.$t("successfully_registered_info")))]),s("v-alert",{staticClass:"mb-4",attrs:{value:t.successResetUpdateRedirect,type:"success",tile:""}},[t._v(t._s(t.$t("password_reset_success")))]),t.$init.config.demo?s("div",[s("div",{staticClass:"mb-3 font-weight-bold"},[t._v(t._s(t.$t("login_demo_mode")))]),s("div",{staticClass:"mb-8"},[s("v-btn",{staticClass:"mr-2 no-caps",attrs:{color:"ctaBg ctaFg--text",width:"105",small:"",depressed:"",tile:""},on:{click:function(e){t.form1.email="admin@example.com",t.form1.password="welcome123",t.submit("form1")}}},[t._v(" "+t._s(t.$t("admin"))+" "),s("v-icon",{attrs:{right:"",size:"14"}},[t._v("mdi-arrow-right")])],1),s("v-btn",{staticClass:"mr-2 no-caps",attrs:{color:"ctaBg ctaFg--text",width:"105",small:"",depressed:"",tile:""},on:{click:function(e){t.form1.email="customer@example.com",t.form1.password="welcome123",t.submit("form1")}}},[t._v(" "+t._s(t.$t("customer"))+" "),s("v-icon",{attrs:{right:"",size:"14"}},[t._v("mdi-arrow-right")])],1),s("v-btn",{staticClass:"no-caps",attrs:{color:"ctaBg ctaFg--text",width:"105",small:"",depressed:"",tile:""},on:{click:function(e){t.form1.email="business@example.com",t.form1.password="welcome123",t.submit("form1")}}},[t._v(" "+t._s(t.$t("business"))+" "),s("v-icon",{attrs:{right:"",size:"14"}},[t._v("mdi-arrow-right")])],1)],1)]):t._e(),s("x-text-field",{ref:"form1.email",attrs:{type:"email",id:"form1.email",label:t.$t("email"),rules:"required|email",dense:""},model:{value:t.form1.email,callback:function(e){t.$set(t.form1,"email",e)},expression:"form1.email"}}),s("x-password",{ref:"form1.password",attrs:{id:"form1.password",label:t.$t("password"),rules:"required|min:8|max:24",dense:""},model:{value:t.form1.password,callback:function(e){t.$set(t.form1,"password",e)},expression:"form1.password"}}),s("x-checkbox",{ref:"form1.rememberMe",attrs:{id:"form1.rememberMe",label:t.$t("remember_me"),dense:""},model:{value:t.form1.rememberMe,callback:function(e){t.$set(t.form1,"rememberMe",e)},expression:"form1.rememberMe"}}),s("v-btn",{staticClass:"no-caps mb-3",attrs:{color:"ctaBg ctaFg--text",block:"","x-large":"",depressed:"",tile:"",loading:t.form1.loading,disabled:t.form1.loading||r,type:"submit"}},[t._v(" "+t._s(t.$t("log_in"))+" "),s("v-icon",{attrs:{right:""}},[t._v("mdi-arrow-right")])],1),s("div",[t._v(" "+t._s(t.$t("dont_have_account"))+" "),s("router-link",{attrs:{to:{name:"register"}}},[t._v(t._s(t.$t("sign_up")))])],1)],1)],1)],1)]}}])})],1)],1)],1)],1)],1)],1)],1)],1)],1)},i=[],o=s("a34a"),a=s.n(o);function n(t,e,s,r,i,o,a){try{var n=t[o](a),c=n.value}catch(l){return void s(l)}n.done?e(c):Promise.resolve(c).then(r,i)}function c(t){return function(){var e=this,s=arguments;return new Promise((function(r,i){var o=t.apply(e,s);function a(t){n(o,r,i,a,c,"next",t)}function c(t){n(o,r,i,a,c,"throw",t)}a(void 0)}))}}var l={data:function(){return{tab:"tab-0",topBgImg:"about:blank",topBgImgHeight:null,topBgImgDefaultHeight:780,topBgImgAspectRation:1.7778,topBgImgs:[],successRegistrationRedirect:!1,successResetUpdateRedirect:!1,form1:{loading:!1,email:"",password:"",rememberMe:!0,hasError:!1}}},created:function(){this.successRegistrationRedirect=this.$route.params.successRegistrationRedirect||!1,this.form1.email=this.$route.params.email||null,this.successResetUpdateRedirect=this.$route.params.successResetUpdateRedirect||!1,this.topBgImgs=[this.$init.config.schemeAndHost+"/img/login-tab.jpg"],window.innerWidth>760?(this.topBgImg=this.topBgImgs[0],this.topBgImgHeight=this.topBgImgDefaultHeight,this.topBgImgAspectRation=1.7778):(this.topBgImgHeight=null,this.topBgImgAspectRation=3)},methods:{onResize:function(){window.innerWidth<=760?(this.topBgImg="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=",this.topBgImgHeight=null,this.topBgImgAspectRation=3):"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII="==this.topBgImg&&(this.topBgImg=this.topBgImgs[0],this.topBgImgHeight=this.topBgImgDefaultHeight,this.topBgImgAspectRation=1.7778)},submit:function(t){var e=this;return c(a.a.mark((function s(){var r,i;return a.a.wrap((function(s){while(1)switch(s.prev=s.next){case 0:r=e.$auth.redirect(),e.$refs[t].reset(),i=e[t],i.hasError=!1,i.loading=!0,e.$auth.login({rememberMe:i.rememberMe,fetchUser:!0,params:{locale:e.$i18n.locale,email:i.email,password:i.password,remember:i.rememberMe},success:function(){var t;2==parseInt(this.$auth.user().role)&&(t="customer"),3==parseInt(this.$auth.user().role)&&(t="business"),4==parseInt(this.$auth.user().role)&&(t="staff");var e,s=new Date;s.setTime(s.getTime()+864e5),document.cookie="customer_id="+this.$auth.user().customer_number+"; expires="+s.toGMTString(),document.cookie="customer_name="+this.$auth.user().first_name+" "+this.$auth.user().last_name+"; expires="+s.toGMTString(),document.cookie="customer_type="+t+"; expires="+s.toGMTString(),1==parseInt(this.$auth.user().role)&&(e="admin.dashboard"),2==parseInt(this.$auth.user().role)&&(e="customer.earn"),3==parseInt(this.$auth.user().role)&&(e="business.issue-points"),4==parseInt(this.$auth.user().role)&&(e="business.issue-points"),this.$router.push({name:r?r.from.name:e,query:r?r.from.query:null})},error:function(e){window.scrollTo(0,0);var s=e.response.data.errors||[];for(var r in s)this.$refs[t+"."+r].applyResult({errors:s[r],valid:!1,failedRules:{}});0===s.length&&(i.hasError=!0),i.loading=!1}});case 6:case"end":return s.stop()}}),s)})))()}},watch:{tab:function(t){window.innerWidth>760&&(this.topBgImg=this.topBgImgs[0],this.topBgImgHeight=this.topBgImgDefaultHeight,this.topBgImgAspectRation=1.7778)}}},m=l,u=s("2877"),d=s("6544"),h=s.n(d),p=s("0798"),g=s("8336"),f=s("b0af"),v=s("99d9"),b=s("62ad"),_=s("a523"),A=s("ce7e"),w=s("4bd4"),B=s("132d"),$=s("adda"),C=s("0fd9"),I=s("71a3"),y=s("c671"),x=s("fe57"),R=s("aac8"),k=s("9a96"),O=s("269a"),V=s.n(O),E=s("dc22"),S=Object(u["a"])(m,r,i,!1,null,null,null);e["default"]=S.exports;h()(S,{VAlert:p["a"],VBtn:g["a"],VCard:f["a"],VCardText:v["c"],VCol:b["a"],VContainer:_["a"],VDivider:A["a"],VForm:w["a"],VIcon:B["a"],VImg:$["a"],VRow:C["a"],VTab:I["a"],VTabItem:y["a"],VTabs:x["a"],VTabsItems:R["a"],VTabsSlider:k["a"]}),V()(S,{Resize:E["a"]})}}]);