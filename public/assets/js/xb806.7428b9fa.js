(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["xb806"],{"823f":function(t,s,i){},"8bf8":function(t,s,i){"use strict";i.r(s);var e=function(){var t=this,s=t.$createElement,i=t._self._c||s;return i("div",{staticClass:"viewContainer"},[i("v-container",{staticClass:"pa-0"},[i("v-container",{staticClass:"inner-container"},[i("v-card",{staticClass:"mt-md-12 mt-0 content-tabs-wrapper",attrs:{tile:"",elevation:"0"}},[i("v-tabs",{staticClass:"px-5 content-tabs",attrs:{color:t.$store.state.app.dark?"white":"black","icons-and-text":"",height:"100","slider-size":"4","show-arrows":""}},[i("v-tabs-slider"),i("v-tab",{staticClass:"no-caps",attrs:{to:{name:"settings.profile"},ripple:!1}},[t._v(" "+t._s(t.$t("profile"))+" "),i("v-icon",{attrs:{size:"24"}},[t._v("mdi-account-circle")])],1),i("v-tab",{staticClass:"no-caps",attrs:{to:{name:"settings.business"},ripple:!1}},[t._v(" "+t._s(t.$t("business"))+" "),i("v-icon",{attrs:{size:"24"}},[t._v("mdi-briefcase")])],1),i("v-tab",{staticClass:"no-caps",attrs:{to:{name:"settings.business.subscription"},ripple:!1}},[t._v(" Billing "),i("v-icon",{attrs:{size:"24"}},[t._v("mdi-domain")])],1)],1),i("v-divider")],1),i("v-tabs-items",{staticStyle:{"background-color":"transparent"},model:{value:t.tab,callback:function(s){t.tab=s},expression:"tab"}},[i("v-tab-item",{attrs:{transition:!1,"reverse-transition":!1,value:"tab-0"}},[i("v-container",{staticClass:"pa-0"},[i("v-row",{attrs:{"no-gutters":""}},[i("v-col",{attrs:{cols:"12",sm:"12",md:"12"}},[i("v-card",{staticClass:"pa-5 mb-0",attrs:{flat:"",tile:""}},[i("v-card-text",[i("v-card",{attrs:{outlined:""}},[i("v-card-title",[t._v("Monthly Billing Report")]),i("v-select",{staticClass:"ma-5",attrs:{label:"Select a year",items:t.billings.transactionYears,"item-text":"0","item-value":"0"},model:{value:t.billings.transactionYear,callback:function(s){t.$set(t.billings,"transactionYear",s)},expression:"billings.transactionYear"}}),i("v-select",{staticClass:"ma-5",attrs:{label:"Select a month",items:t.getMonths,"item-text":"text","item-value":"value"},model:{value:t.billings.transactionMonth,callback:function(s){t.$set(t.billings,"transactionMonth",s)},expression:"billings.transactionMonth"}}),t.billings.loaded&&200==t.billings.status_code&&""!=t.transactionMonth?i("div",{staticClass:"ma-5"},[i("v-input",{attrs:{"prepend-icon":"mdi-trending-up",color:"black",messages:["Points Issuance Sessions: "+(this.billings.transaction_details.earnings.count||0)]}},[t._v("Total Points Issued: "+t._s(this.billings.transaction_details.earnings.amount))]),i("v-input",{attrs:{"prepend-icon":"mdi-trending-down",color:"black",messages:["Points Redemption Sessions: "+(this.billings.transaction_details.spendings.count||0)]}},[t._v("Total Points Redeemed: "+t._s(this.billings.transaction_details.spendings.amount))]),i("v-input",{attrs:{"prepend-icon":"mdi-clock",color:"black",messages:["Due Date (Net 7)"]}},[t._v(t._s(this.billings.due_details.date))]),i("v-input",{attrs:{"prepend-icon":"mdi-cash",color:"black",messages:["Amount Due | * XCD "+this.transaction_fee]}},[t._v(t._s(this.billings.due_details.amount))]),i("v-input",{attrs:{"prepend-icon":"mdi-file-document",color:"black",messages:["Invoice NO"]}},[t._v(t._s(this.billings.invoice_no))]),i("v-input",{attrs:{messages:["Payment Status"]}},[i("v-icon",{attrs:{slot:"prepend",color:1==t.billings.due_details.status?"green":"red"},slot:"prepend"},[t._v(t._s(1==this.billings.due_details.status?"mdi-check":"mdi-close"))]),t._v(" "+t._s(1==this.billings.due_details.status?"Paid":"Not Paid")+" ")],1),i("div"),i("v-list",[i("h4",{staticClass:"ma-3"},[t._v("Payment Options")]),i("v-list-item",{attrs:{"three-line":""}},[i("v-list-item-content",[i("v-list-item-title",[t._v("Pay by Check")]),i("v-list-item-subtitle",[t._v("Make check payable to: Piggy Bank Loyalty")]),i("v-list-item-subtitle",[t._v("Mail check to: Kingstown, St Vincent & the Grenadines")]),i("v-list-item-subtitle",[t._v("Alternatively, we can arrange pickup.")])],1)],1),i("v-list-item",{attrs:{"two-line":""}},[i("v-list-item-content",[i("v-list-item-title",[t._v("Squeeze Cash")]),i("v-list-item-subtitle",[t._v("Send XCD invoice amount to: SQ03017")]),i("v-list-item-subtitle",[t._v("Please include your invoice number(s)")])],1)],1),i("v-list-item",{attrs:{"three-line":""}},[i("v-list-item-content",[i("v-list-item-title",[t._v("Pay by Paypal:")]),i("v-list-item-subtitle",[t._v("Go to "),i("a",{attrs:{href:"https://www.paypal.me/caribbeanadventures",target:"_blank"}},[t._v("Paypal")])]),i("v-list-item-subtitle",[t._v("Please send USD amount on invoice")]),i("v-list-item-subtitle",[t._v("Please include your invoice number(s)")])],1)],1),i("v-list-item",{attrs:{"three-line":""}},[i("v-list-item-content",[i("v-list-item-title",[t._v("Payswif:")]),i("v-list-item-subtitle",[t._v("Send XCD invoice amount to: 17845303260")]),i("v-list-item-subtitle",[t._v("Please include your invoice number(s)")]),i("v-list-item-subtitle",[t._v("Alternatively, we can arrange pickup.")])],1)],1)],1)],1):t._e(),404==t.billings.status_code?i("h3",{staticClass:"ma-5"},[t._v("No Transaction Found")]):t._e()],1)],1)],1)],1),i("v-col",{attrs:{cols:"12",sm:"12",md:"12"}},[i("v-card",{staticClass:"pa-5 mb-0 mb-md-12",attrs:{flat:"",tile:""}},[i("v-card-text",[i("v-card",{attrs:{outlined:""}},[i("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.billings.headers,items:t.billings.transactions,"items-per-page":5}})],1)],1)],1)],1)],1)],1)],1)],1)],1)],1)],1)},n=[],a=i("a34a"),o=i.n(a),l=i("c1df"),r=i.n(l);function c(t,s,i,e,n,a,o){try{var l=t[a](o),r=l.value}catch(c){return void i(c)}l.done?s(r):Promise.resolve(r).then(e,n)}function u(t){return function(){var s=this,i=arguments;return new Promise((function(e,n){var a=t.apply(s,i);function o(t){c(a,e,n,o,l,"next",t)}function l(t){c(a,e,n,o,l,"throw",t)}o(void 0)}))}}var d={data:function(){return{billings:{loaded:!1,showdetails:"",headers:[{text:"Customer NO",align:"start",value:"customer_number"},{text:"Event",value:"event"},{text:"Points",value:"points"},{text:"Staff Name",value:"staff_name"},{text:"Date",value:"created_at"}],transactionMonth:"",transactionYear:(new Date).getFullYear(),transactionYears:[],transactions:[],transaction_details:{earnings:"",spendings:""},due_details:{date:"select a month",amount:"0",status:"0"},invoice_no:"",transaction_fee:""},locale:"en",tab:"tab-0",loading:!0,subscriptionSettings:null,paymentProcessed:!1,paymentProcessedGateway:null,subscriptionCancelled:!1,subscriptionForm:{loading:!1,months:null},stripeHandler:null,selectedMonths:null,selectedRemotePlanId:null}},created:function(){var t=this;this.axios("business/transaction-years").then((function(s){t.billings.transactionYears=s.data.years})),this.locale=this.$auth.check()?this.$auth.user().locale:Intl.DateTimeFormat().resolvedOptions().locale||this.$i18n.locale,this.moment.locale(this.locale.substr(0,2)),this.axios.get("/business/subscription/settings",{params:{locale:this.$i18n.locale}}).then((function(s){if(t.subscriptionSettings=s.data,t.loading=!1,"stripe"==t.subscriptionSettings.gateway){var i={load:function(t,s){var i,e=document.createElement("script");e.setAttribute("src",t),s&&(e.onreadystatechange=e.onload=function(){i||s(),i=!0}),document.getElementsByTagName("head")[0].appendChild(e)}},e=t;i.load("//checkout.stripe.com/checkout.js",(function(){e.stripeHandler=window.StripeCheckout.configure({key:e.subscriptionSettings.key,image:null,locale:"auto",token:function(t){e.subscriptionForm.loading=!0,e.axios.post("/business/stripe/token",{token:t.id,email:t.email,type:t.type,stripe_plan_id:e.selectedRemotePlanId,months:e.selectedMonths}).then((function(t){e.paymentProcessed=!0,e.paymentProcessedGateway="stripe",e.subscriptionForm.loading=!1})).catch((function(t){e.$root.$snackbar("An unknow error has occured. Please refresh this page and try again. Contact us if the error persists."),e.subscriptionForm.loading=!1,console.log(t)}))}})}))}}))},methods:{getTransactions:function(){var t=this;return u(o.a.mark((function s(){var i,e,n,a,l,c,u,d;return o.a.wrap((function(s){while(1)switch(s.prev=s.next){case 0:return s.next=2,t.axios.get("/business/transaction/".concat(t.billings.transactionMonth,"-").concat(t.billings.transactionYear));case 2:e=s.sent,n=e.data,t.billings.status_code=null!==(i=e.data.status_code)&&void 0!==i?i:200,a=n.transactions,l=n.transaction_details,c=n.due_details,u=n.invoice_no,d=n.transaction_fee,t.billings.transactions=a,t.transaction_fee=d,t.billings.transaction_details.earnings=l.earnings,t.billings.transaction_details.spendings=l.spendings,t.billings.due_details.date=r()(new Date(c.date)).format("DD MMM YYYY"),t.billings.due_details.amount=c.amount,t.billings.due_details.status=c.status,t.billings.invoice_no=u,t.billings.loaded=!0;case 15:case"end":return s.stop()}}),s)})))()},formatNumber:function(t){return new Intl.NumberFormat(this.locale.replace("_","-")).format(t)},formatCurrency:function(t,s){return new Intl.NumberFormat(this.locale.replace("_","-"),{style:"currency",currency:s}).format(t)},formatDate:function(t,s){return t=null===t?"-":"ago"==s?this.moment(t).fromNow():this.moment(t).format(s),t},goToCheckout:function(){var t=this;if(!0===this.$init.config.demo&&"manual"==this.subscriptionSettings.gateway)this.subscriptionForm.loading=!0,this.axios.post("/business/subscription/demo",{locale:this.locale,months:this.subscriptionForm.months}).then((function(s){"success"===s.data.status&&(t.$auth.user(s.data.user),t.paymentProcessed=!0)})).catch((function(s){if("error"===s.response.data.status){t.errorMsg=s.response.data.error;var i=s.response.data.errors||[];for(var e in i)t.$refs["subscriptionForm."+e].applyResult({errors:i[e],valid:!1,failedRules:{}})}})).finally((function(){window.scrollTo(0,0),t.subscriptionForm.loading=!1}));else if("manual"==this.subscriptionSettings.gateway)this.subscriptionForm.loading=!0;else if("stripe"==this.subscriptionSettings.gateway){var s,i,e;1==this.subscriptionForm.months&&(i=this.$t("monthly_subscription"),s=this.subscriptionSettings.plan_id_monthly,e=100*this.subscriptionSettings.premium_price_monthly),12==this.subscriptionForm.months&&(i=this.$t("annual_subscription"),s=this.subscriptionSettings.plan_id_yearly,e=100*this.subscriptionSettings.premium_price_yearly),this.selectedMonths=this.subscriptionForm.months,this.selectedRemotePlanId=s,this.stripeHandler.open({name:i,description:null,currency:this.subscriptionSettings.premium_price_currency,amount:e})}},cancelSubscription:function(){var t=this,s=this;"stripe"==this.subscriptionSettings.gateway&&this.$root.$confirm(this.$t("cancel_subscription"),this.$t("cancel_subscription_confirm")).then((function(i){i&&(s.loading=!0,t.axios.post("/business/stripe/cancel").then((function(t){s.subscriptionCancelled=!0,s.subscriptionSettings.remote_customer_id=null,s.loading=!1})).catch((function(t){console.log(t),s.loading=!1})))}))}},computed:{getMonths:function(){var t=[],s=r.a.months();for(var i in s)t.push({value:"1-".concat(parseInt(i)+1),text:s[i]});return t}},watch:{"billings.transactionMonth":function(t,s){this.billings.transactionMonth=t,""!==t&&this.getTransactions()},"billings.transactionYear":function(t,s){this.billings.transactionYear=t,this.billings.transactionMonth=""}}},m=d,p=(i("cb72"),i("2877")),b=i("6544"),v=i.n(b),h=i("b0af"),g=i("99d9"),_=i("62ad"),f=i("a523"),y=i("8fea"),w=i("ce7e"),C=i("132d"),k=i("b675"),S=i("8860"),P=i("da13"),x=i("5d23"),V=i("0fd9"),M=i("b974"),$=i("71a3"),I=i("c671"),T=i("fe57"),F=i("aac8"),D=i("9a96"),Y=Object(p["a"])(m,e,n,!1,null,null,null);s["default"]=Y.exports;v()(Y,{VCard:h["a"],VCardText:g["c"],VCardTitle:g["d"],VCol:_["a"],VContainer:f["a"],VDataTable:y["a"],VDivider:w["a"],VIcon:C["a"],VInput:k["a"],VList:S["a"],VListItem:P["a"],VListItemContent:x["a"],VListItemSubtitle:x["b"],VListItemTitle:x["c"],VRow:V["a"],VSelect:M["a"],VTab:$["a"],VTabItem:I["a"],VTabs:T["a"],VTabsItems:F["a"],VTabsSlider:D["a"]})},cb72:function(t,s,i){"use strict";var e=i("823f"),n=i.n(e);n.a}}]);