(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-home-deposit_in"],{2453:function(e,t,i){"use strict";(function(e){var n=i("ee27");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a=n(i("f3f3")),s=i("2f62"),o={data:function(){return{password:"",currency:"",coinInfo:{},coin_index:0,coin_id:0,array:[],coin_rate:0,array2:[],coin_RateId:0,current_msg:{},address:"",name:"",number:"",depositType:"fixed",reciveNumber:"0.0",code:"",disable:!1,load:!1,codeText:this.$t("login").r_send,accountNumber:"",timer:null,total:0}},computed:(0,a.default)({},(0,s.mapState)({theme:function(e){return e.theme}})),onLoad:function(e){this.$utils.setTheme(this.theme),this.depositType=e.type;this.depositType=e.type,this.total=e.total,"fixed"==e.type?uni.setNavigationBarTitle({title:"定期转入"}):"current"==e.type&&uni.setNavigationBarTitle({title:"活期转入"})},onShow:function(){this.getCoinInfo(),this.getCoinRate()},onPullDownRefresh:function(){},methods:{bindPickerChange:function(t){e("log",t," at pages\\home\\deposit_in.vue:96"),e("log","picker发送选择改变，携带值为",t.target.value," at pages\\home\\deposit_in.vue:97"),this.coin_index=t.target.value,this.coin_id=this.array[t.target.value].id},bindPickerChange2:function(t){e("log","picker发送选择改变，携带值为",t.target.value," at pages\\home\\deposit_in.vue:102"),this.coin_rate=t.target.value,this.coin_RateId=this.array[t.target.value].id},getCoinInfo:function(){var t=this;this.$utils.initDataToken({url:"fixed/get_currency_lists",type:"get",data:{}},(function(i){e("log",i," at pages\\home\\deposit_in.vue:112"),t.array=i,t.coin_id=i[0].id}))},getCoinRate:function(){var t=this;this.$utils.initDataToken({url:"fixed/get_deposit_percent",type:"get",data:{}},(function(i){for(var n=0;n<i.fixed.length;n++)e("log",i," at pages\\home\\deposit_in.vue:124"),i.fixed[n].desc=i.fixed[n].desc+"("+i.fixed[n].percent+")";t.current_msg=i.current[0],t.array2=i.fixed,t.coin_RateId=i.fixed[0].id}))},submit:function(){if("fixed"==this.depositType){if(e("log",this.number," at pages\\home\\deposit_in.vue:135"),e("log",this.coin_id," at pages\\home\\deposit_in.vue:136"),e("log",this.coin_RateId," at pages\\home\\deposit_in.vue:137"),""==this.number||0==this.number)return void plus.nativeUI.toast(this.$t("home.trade_num_msg"),{verticalAlign:"center"});this.$utils.initDataToken({url:"fixed/fixed_deposit_opreation",type:"post",data:{currency:this.coin_id,num:this.number,percent:this.coin_RateId,deposit_type:1}},(function(t){e("log",t," at pages\\home\\deposit_in.vue:152"),plus.nativeUI.toast(t,{verticalAlign:"center"}),setTimeout((function(){uni.switchTab({url:"/pages/home/index"})}),1e3)}))}else{if(e("log",this.number," at pages\\home\\deposit_in.vue:162"),e("log",this.coin_id," at pages\\home\\deposit_in.vue:163"),e("log",this.current_msg.id," at pages\\home\\deposit_in.vue:164"),""==this.number||0==this.number)return void plus.nativeUI.toast(this.$t("home.trade_num_msg"),{verticalAlign:"center"});this.$utils.initDataToken({url:"fixed/fixed_deposit_opreation",type:"post",data:{currency:this.coin_id,num:this.number,percent:this.current_msg.id,deposit_type:2}},(function(t){e("log",t," at pages\\home\\deposit_in.vue:179"),plus.nativeUI.toast(t,{verticalAlign:"center"}),setTimeout((function(){uni.switchTab({url:"/pages/home/index"})}),1e3)}))}}}};t.default=o}).call(this,i("0de9")["log"])},"35dc":function(e,t,i){"use strict";var n,a=function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("v-uni-view",{class:["vh100",{dark:"dark"==e.theme}]},[i("v-uni-view",{staticClass:"ptb20 bgPart plr20"},[i("v-uni-view",{staticClass:"mt10"},[e._v(e._s(e.$t("assets.zhehecny"))+":  "+e._s(e.total))])],1),i("v-uni-view",{staticClass:"plr20"},[i("v-uni-view",{staticClass:"mb20 mt20"},[i("v-uni-view",{},[e._v(e._s(e.$t("home.trade_num")))]),i("v-uni-input",{staticClass:"bdb1f h40 w100 input-uni",attrs:{type:"number",placeholder:e.$t("home.trade_num_msg")},model:{value:e.number,callback:function(t){e.number=t},expression:"number"}})],1),i("v-uni-view",{staticClass:"mb20 mt20"},[i("v-uni-view",{},[e._v(e._s(e.$t("home.coin_type")))]),i("v-uni-picker",{attrs:{value:e.coin_index,range:e.array,"range-key":"name"},on:{change:function(t){arguments[0]=t=e.$handleEvent(t),e.bindPickerChange.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"bdb1f h40 w100 input-uni",staticStyle:{"line-height":"80upx","padding-left":"20upx"}},[e._v(e._s(e.array[e.coin_index].name))])],1)],1),"fixed"==e.depositType?i("v-uni-view",{staticClass:"mb20 mt20"},[i("v-uni-view",{},[e._v(e._s(e.$t("home.coin_rate")))]),i("v-uni-picker",{attrs:{value:e.coin_rate,range:e.array2,"range-key":"desc"},on:{change:function(t){arguments[0]=t=e.$handleEvent(t),e.bindPickerChange2.apply(void 0,arguments)}}},[i("v-uni-view",{staticClass:"bdb1f h40 w100 input-uni",staticStyle:{"line-height":"80upx","padding-left":"20upx"}},[e._v(e._s(e.array2[e.coin_rate].desc))])],1)],1):e._e(),"current"==e.depositType?i("v-uni-view",{staticClass:"mb20"},[e._v(e._s(e.current_msg.desc)+e._s(e.current_msg.percent))]):e._e(),i("v-uni-view",{staticClass:"mt40 bgBlue radius4 ptb10 white ft14 tc mb10",on:{click:function(t){arguments[0]=t=e.$handleEvent(t),e.submit.apply(void 0,arguments)}}},[e._v(e._s(e.$t("home.trade_in")))])],1)],1)},s=[];i.d(t,"b",(function(){return a})),i.d(t,"c",(function(){return s})),i.d(t,"a",(function(){return n}))},"5da2":function(e,t,i){"use strict";i.r(t);var n=i("35dc"),a=i("5dc9");for(var s in a)"default"!==s&&function(e){i.d(t,e,(function(){return a[e]}))}(s);var o,r=i("f0c5"),u=Object(r["a"])(a["default"],n["b"],n["c"],!1,null,"f707a7b2",null,!1,n["a"],o);t["default"]=u.exports},"5dc9":function(e,t,i){"use strict";i.r(t);var n=i("2453"),a=i.n(n);for(var s in n)"default"!==s&&function(e){i.d(t,e,(function(){return n[e]}))}(s);t["default"]=a.a}}]);