(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-legal-storeDetail"],{"043d":function(n,t,e){"use strict";var a=function(){var n=this,t=n.$createElement,e=n._self._c||t;return n.showPopup?e("v-uni-view",{staticClass:"uni-popup"},[e("v-uni-view",{staticClass:"uni-popup__mask",class:[n.ani,n.animation?"ani":"",n.custom?"":"uni-custom"],on:{click:function(t){t=n.$handleEvent(t),n.close(!0)}}}),e("v-uni-view",{staticClass:"uni-popup__wrapper",class:[n.type,n.ani,n.animation?"ani":"",n.custom?"":"uni-custom"],on:{click:function(t){t=n.$handleEvent(t),n.close(!0)}}},[e("v-uni-view",{staticClass:"uni-popup__wrapper-box",on:{click:function(t){t.stopPropagation(),t=n.$handleEvent(t),n.clear(t)}}},[n._t("default")],2)],1)],1):n._e()},i=[];e.d(t,"a",function(){return a}),e.d(t,"b",function(){return i})},"07ac":function(n,t,e){var a=e("ac4a");"string"===typeof a&&(a=[[n.i,a,""]]),a.locals&&(n.exports=a.locals);var i=e("4f06").default;i("68e09a18",a,!0,{sourceMap:!1,shadowMode:!1})},"177a":function(n,t,e){"use strict";e.r(t);var a=e("385c"),i=e.n(a);for(var s in a)"default"!==s&&function(n){e.d(t,n,function(){return a[n]})}(s);t["default"]=i.a},2011:function(n,t,e){"use strict";var a=e("bb5a"),i=e.n(a);i.a},"385c":function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0;var a={name:"UniPopup",props:{animation:{type:Boolean,default:!0},type:{type:String,default:"center"},custom:{type:Boolean,default:!1},maskClick:{type:Boolean,default:!0},show:{type:Boolean,default:!0}},data:function(){return{ani:"",showPopup:!1}},watch:{show:function(n){n?this.open():this.close()}},created:function(){},methods:{clear:function(){},open:function(){var n=this;this.$emit("change",{show:!0}),this.showPopup=!0,this.$nextTick(function(){setTimeout(function(){n.ani="uni-"+n.type},30)})},close:function(n){var t=this;!this.maskClick&&n||(this.$emit("change",{show:!1}),this.ani="",this.$nextTick(function(){setTimeout(function(){t.showPopup=!1},300)}))}}};t.default=a},"6d2d":function(n,t,e){"use strict";e.r(t);var a=e("c8b0"),i=e.n(a);for(var s in a)"default"!==s&&function(n){e.d(t,n,function(){return a[n]})}(s);t["default"]=i.a},"74e2":function(n,t,e){t=n.exports=e("2350")(!1),t.push([n.i,".uni-popup[data-v-6d67815d]{position:fixed;top:0;top:0;bottom:0;left:0;right:0;z-index:1000;overflow:hidden}.uni-popup__mask[data-v-6d67815d]{position:absolute;top:0;bottom:0;left:0;right:0;z-index:998;background:rgba(0,0,0,.4);opacity:0}.uni-popup__mask.ani[data-v-6d67815d]{-webkit-transition:all .3s;-o-transition:all .3s;transition:all .3s}.uni-popup__mask.uni-bottom[data-v-6d67815d],.uni-popup__mask.uni-center[data-v-6d67815d],.uni-popup__mask.uni-top[data-v-6d67815d]{opacity:1}.uni-popup__wrapper[data-v-6d67815d]{position:absolute;z-index:999;-webkit-box-sizing:border-box;box-sizing:border-box}.uni-popup__wrapper.ani[data-v-6d67815d]{-webkit-transition:all .3s;-o-transition:all .3s;transition:all .3s}.uni-popup__wrapper.top[data-v-6d67815d]{top:0;left:0;width:100%;-webkit-transform:translateY(-100%);-ms-transform:translateY(-100%);transform:translateY(-100%)}.uni-popup__wrapper.bottom[data-v-6d67815d]{bottom:0;left:0;width:100%;-webkit-transform:translateY(100%);-ms-transform:translateY(100%);transform:translateY(100%)}.uni-popup__wrapper.center[data-v-6d67815d]{width:100%;height:100%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-transform:scale(1.2);-ms-transform:scale(1.2);transform:scale(1.2);opacity:0}.uni-popup__wrapper-box[data-v-6d67815d]{position:relative;-webkit-box-sizing:border-box;box-sizing:border-box}.uni-popup__wrapper.uni-custom .uni-popup__wrapper-box[data-v-6d67815d]{\n\t/* padding: 30upx; */background:#fff}.uni-popup__wrapper.uni-custom.center .uni-popup__wrapper-box[data-v-6d67815d]{position:relative;max-width:80%;max-height:80%;overflow-y:scroll}.uni-popup__wrapper.uni-custom.bottom .uni-popup__wrapper-box[data-v-6d67815d],.uni-popup__wrapper.uni-custom.top .uni-popup__wrapper-box[data-v-6d67815d]{width:100%;max-height:500px;overflow-y:scroll}.uni-popup__wrapper.uni-bottom[data-v-6d67815d],.uni-popup__wrapper.uni-top[data-v-6d67815d]{-webkit-transform:translateY(0);-ms-transform:translateY(0);transform:translateY(0)}.uni-popup__wrapper.uni-center[data-v-6d67815d]{-webkit-transform:scale(1);-ms-transform:scale(1);transform:scale(1);opacity:1}",""])},a480:function(n,t,e){"use strict";var a=function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("v-uni-view",{class:["vh100",{dark:"dark"==n.theme}]},[e("v-uni-view",{staticClass:"status_bar"},[e("v-uni-view",{staticClass:"top_view"})],1),e("v-uni-view",{staticClass:"header fixed flex alcenter between plr15"},[e("v-uni-view",[e("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"../../static/image/forward.png",mode:"aspectFit"},on:{click:function(t){t=n.$handleEvent(t),n.back()}}})],1),e("v-uni-view",{staticClass:"gray_e ft16 bold"},[n._v(n._s(n.Data.name))]),e("v-uni-view")],1),e("v-uni-view",{staticClass:"pt50  bgWhite fixed w100 lf0 zdx100"},[e("v-uni-view",{staticClass:"flex between alcenter plr15 ptb10"},[e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-view",{staticClass:"white flex alcenter"},[n.Data.name?e("v-uni-view",{staticClass:"logo bgBlue2 ft12 flex alcenter jscenter "},[n._v(n._s(n._f("chart0")(n.Data.name)))]):n._e(),e("v-uni-text",{staticClass:"ml10 ft14 ellipsis",staticStyle:{"max-width":"110upx"}},[n._v(n._s(n.Data.name))])],1),e("v-uni-view",{staticClass:"gray75 ml10 flex column"},[e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{staticClass:"mr5"},[n._v(n._s(n.$t("store.rzshop")))]),e("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"../../static/image/VIP.png",mode:"aspectFit"}})],1),e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{staticClass:"mr5"},[n._v(n._s(n.$t("store.regtime")))]),n.Data.create_time?e("v-uni-text",[n._v(n._s(n.Data.create_time.substring(0,10)))]):n._e()],1)],1)],1),e("v-uni-view",{staticClass:"flex alcenter jscenter h25 plr20 bgBlue2 radius4"},[e("v-uni-text",{staticClass:"white ft14",on:{click:function(t){t=n.$handleEvent(t),n.fabu0()}}},[n._v(n._s(n.$t("store.fabu")))])],1)],1),e("v-uni-view",{staticClass:"flex between bdb27 plr15 gray75 ptb10"},[e("v-uni-view",{staticClass:"flex column alcenter"},[e("v-uni-text",[n._v(n._s(n.Data.total))]),e("v-uni-text",{staticClass:"mt5"},[n._v(n._s(n.$t("store.allorder")))])],1),e("v-uni-view",{staticClass:"flex column alcenter"},[e("v-uni-text",[n._v(n._s(n.Data.thirtyDays))]),e("v-uni-text",{staticClass:"mt5"},[n._v(n._s(n.$t("store.thirtyorder")))])],1),e("v-uni-view",{staticClass:"flex column alcenter"},[e("v-uni-text",[n._v(n._s(n.Data.done))]),e("v-uni-text",{staticClass:"mt5"},[n._v(n._s(n.$t("store.doneorder")))])],1),e("v-uni-view",{staticClass:"flex column alcenter"},[e("v-uni-text",[n._v(n._s(n._f("toFixed2")(n.Data.done/n.Data.total*100||0))+"%")]),e("v-uni-text",{staticClass:"mt5"},[n._v(n._s(n.$t("store.donelv")))])],1)],1),e("v-uni-view",{staticClass:"flex between plr15 gray75 ptb10"},[e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{staticClass:"mr5"},[n._v(n._s(n.$t("store.renzhengmobile")))]),1==n.Data.prove_mobile?e("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"../../static/image/VIP.png",mode:"aspectFit"}}):e("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"../../static/image/regi.png",mode:"aspectFit"}})],1),e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{staticClass:"mr5"},[n._v(n._s(n.$t("store.renzhengauth")))]),1==n.Data.prove_real?e("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"../../static/image/VIP.png",mode:"aspectFit"}}):e("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"../../static/image/regi.png",mode:"aspectFit"}})],1),e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{staticClass:"mr5"},[n._v(n._s(n.$t("store.renzhenghigh")))]),1==n.Data.prove_level?e("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"../../static/image/VIP.png",mode:"aspectFit"}}):e("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"../../static/image/regi.png",mode:"aspectFit"}})],1)],1),e("v-uni-view",{staticClass:"ptb15 ptb10 flex alcenter jscenter",staticStyle:{background:"#141e34"}},[e("v-uni-view",{class:"sell"==n.type?"flex alcenter jscenter ptb10 plr10 bgBlue2":"flex alcenter jscenter ptb10 plr10 bgGray",on:{click:function(t){t=n.$handleEvent(t),n.changetype("sell")}}},[e("v-uni-text",[n._v(n._s(n.$t("store.mysell")))])],1),e("v-uni-view",{class:"buy"==n.type?"flex alcenter jscenter ptb10 plr10 bgBlue2":"flex alcenter jscenter ptb10 plr10 bgGray",on:{click:function(t){t=n.$handleEvent(t),n.changetype("buy")}}},[e("v-uni-text",[n._v(n._s(n.$t("store.mybuy")))])],1)],1),e("v-uni-view",{staticClass:"bdb27 h40 flex alcenter around"},[e("v-uni-view",{class:["h40 flex jscenter alcenter",{cur:0==n.done}],on:{click:function(t){t=n.$handleEvent(t),n.changedone()}}},[e("v-uni-text",[n._v(n._s(n.$t("store.notdone")))])],1),e("v-uni-view",{class:["h40 flex jscenter alcenter",{cur:1==n.done}],on:{click:function(t){t=n.$handleEvent(t),n.changedone()}}},[e("v-uni-text",[n._v(n._s(n.$t("store.done")))])],1)],1)],1),e("v-uni-view",{staticClass:"list"},[n._l(n.orderlist,function(t,a){return e("v-uni-view",{key:a,staticClass:"gray75 flex column w100 plr20 ptb10 bdb27"},[e("v-uni-view",{staticClass:"flex mt5"},[e("v-uni-view",{staticClass:" flex alcenter"},[e("v-uni-view",{staticClass:"logo bgBlue2 ft12 flex alcenter jscenter white"},[n._v(n._s(n._f("chart0")(t.seller_name)))]),e("v-uni-text",{staticClass:"ml10 ft14"},[n._v(n._s(t.seller_name))])],1)],1),e("v-uni-view",{staticClass:"flex between mt10"},[e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{},[n._v(n._s(n.$t("legal.num")))]),e("v-uni-text",{staticClass:"mlr5"},[n._v(n._s(n._f("toFixed3")(t.surplus_number-0)))]),e("v-uni-text",{},[n._v(n._s(t.currency_name))])],1),e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{},[n._v(n._s(n.$t("legal.price")))])],1)],1),e("v-uni-view",{staticClass:"flex between mt5"},[e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{},[n._v(n._s(n.$t("legal.limit")))]),e("v-uni-text",{},[n._v("￥"+n._s(n._f("toFixed3")(t.limitation.min-0))+"-￥"+n._s(n._f("toFixed3")(t.limitation.max-0)))])],1),e("v-uni-view",{staticClass:"flex alcenter"},[e("v-uni-text",{staticClass:"blue21 ft16"},[n._v("￥"+n._s(n._f("toFixed4")(t.price-0)))])],1)],1),e("v-uni-view",{staticClass:"flex between mt5"},[e("v-uni-view",{staticClass:"flex alcenter"},["ali_pay"==t.way?e("v-uni-image",{staticClass:"wt15 h15 mr5",attrs:{src:"../../static/image/zhi.png",mode:"aspectFit"}}):n._e(),"we_chat"==t.way?e("v-uni-image",{staticClass:"wt15 h15 mr5",attrs:{src:"../../static/image/wechat.png",mode:"aspectFit"}}):n._e(),"bank"==t.way?e("v-uni-image",{staticClass:"wt15 h15 mr5",attrs:{src:"../../static/image/card.png",mode:"aspectFit"}}):n._e()],1),e("v-uni-view",{staticClass:"flex"},[e("v-uni-view",{staticClass:"flex alcenter jscenter h25 plr10 bgBlue2 radius4 mr10",on:{click:function(e){e=n.$handleEvent(e),n.togglePopup("center","tip",t.id)}}},[e("v-uni-text",{staticClass:"white ft13"},[n._v(n._s(n.$t("store.yichang")))])],1),e("v-uni-view",{staticClass:"flex alcenter jscenter h25 plr10 bgBlue2 radius4 mr10",on:{click:function(e){e=n.$handleEvent(e),n.withdraw(a,t.id)}}},[e("v-uni-text",{staticClass:"white ft13"},[n._v(n._s(n.$t("store.back")))])],1),e("v-uni-view",{staticClass:"flex alcenter jscenter h25 plr10 bgBlue2 radius4",on:{click:function(e){e=n.$handleEvent(e),n.goorderlist(t.id)}}},[e("v-uni-text",{staticClass:"white ft13"},[n._v(n._s(n.$t("store.lookorder")))])],1)],1)],1)],1)}),e("v-uni-view",{class:["tc pt30 pt100 pb100 hidden",{block:0==n.orderlist.length&&n.over}]},[e("v-uni-image",{staticClass:"h50 wt50",attrs:{src:"/static/image/nodata.png"}}),e("v-uni-view",{staticClass:"gray7"},[n._v(n._s(n.$t("home.norecord")))])],1),e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!n.hasMore&&n.orderlist.length>10,expression:"!hasMore && orderlist.length>10"}],staticClass:"tc gray7 ptb20"},[n._v(n._s(n.$t("home.nomore")))])],2),e("uni-popup",{ref:"popup",attrs:{type:n.popType}},[e("v-uni-view",{staticClass:"bottom-content bgPart flex column plr10 gray75"},[e("v-uni-view",{staticClass:"bdb27 h40 flex alcenter around"},[e("v-uni-view",{class:["h40 flex jscenter alcenter plr10",{cur:"sell"==n.fabuType}],on:{click:function(t){t=n.$handleEvent(t),n.changeFtype("sell")}}},[e("v-uni-text",[n._v(n._s(n.$t("legal.sell")))])],1),e("v-uni-view",{class:["h40 flex jscenter alcenter plr10",{cur:"buy"==n.fabuType}],on:{click:function(t){t=n.$handleEvent(t),n.changeFtype("buy")}}},[e("v-uni-text",[n._v(n._s(n.$t("legal.buy")))])],1)],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("legal.coin"))+":")]),e("v-uni-text",{staticClass:"flex2"},[n._v(n._s(n.Data.currency_name))])],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("store.p_payment"))+":")]),e("v-uni-view",{staticClass:"flex2 flex alcenter between"},n._l(n.payList,function(t,a){return e("v-uni-view",{key:a,staticClass:"flex alcenter",on:{click:function(t){t=n.$handleEvent(t),n.choosePay(a)}}},[e("v-uni-image",{staticClass:"h20 wt20",attrs:{src:n.payway===a?"/static/image/select1.png":"/static/image/select0.png"}}),e("v-uni-text",{staticClass:"pl5"},[n._v(n._s(t.name))])],1)}),1)],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("store.p_coin"))+":")]),e("v-uni-view",{staticClass:"flex2 flex alcenter between"},n._l(n.coinList,function(t,a){return e("v-uni-view",{key:a,staticClass:"flex alcenter",on:{click:function(t){t=n.$handleEvent(t),n.chooseCoin(a)}}},[e("v-uni-image",{staticClass:"h20 wt20",attrs:{src:n.paycoin==a?"/static/image/select1.png":"/static/image/select0.png"}}),e("v-uni-text",{staticClass:"pl5"},[n._v(n._s(t.value))])],1)}),1)],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("legal.price"))+":")]),e("v-uni-input",{staticClass:"flex2",attrs:{type:"number",value:"",placeholder:n.$t("store.p_price")},model:{value:n.price,callback:function(t){n.price=t},expression:"price"}})],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("trade.num"))+":")]),e("v-uni-input",{staticClass:"flex2",attrs:{type:"number",value:"",placeholder:n.$t("trade.p_num")},model:{value:n.total_number,callback:function(t){n.total_number=t},expression:"total_number"}})],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("store.minnum"))+":")]),e("v-uni-input",{staticClass:"flex2",attrs:{type:"number",value:"",placeholder:n.$t("store.p_min")},model:{value:n.min_number,callback:function(t){n.min_number=t},expression:"min_number"}})],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("store.maxnum"))+":")]),e("v-uni-input",{staticClass:"flex2",attrs:{type:"number",value:"",placeholder:n.$t("store.p_max")},model:{value:n.max_number,callback:function(t){n.max_number=t},expression:"max_number"}})],1),e("v-uni-view",{staticClass:"flex between alcenter mtb10  plr10"},[e("v-uni-view",{staticClass:"bgBlue2 white ptb10 flex1 tc radius4",on:{click:function(t){t=n.$handleEvent(t),n.fabu()}}},[n._v(n._s(n.$t("store.fabu")))])],1)],1)],1),e("uni-popup",{ref:"popup2",attrs:{type:n.popType2}},[e("v-uni-view",{staticClass:"bottom-content bgPart flex column plr10 gray75"},[e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10 plr10"},[e("v-uni-text",{staticClass:"ft18 white bold tc"},[n._v(n._s(n.$t("login.e_pdeal")))])],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10 plr10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("login.s_dealpwd")))]),e("v-uni-input",{staticClass:"flex2 bd_input h30 pl10 radius4",attrs:{type:"password",value:"",placeholder:n.$t("login.e_pdeal")},model:{value:n.password,callback:function(t){n.password=t},expression:"password"}})],1),e("v-uni-view",{staticClass:"flex between alcenter mtb10  plr10"},[e("v-uni-view",{staticClass:"bgBlue2 white ptb10 flex1 tc radius4",on:{click:function(t){t=n.$handleEvent(t),n.fabu2()}}},[n._v(n._s(n.$t("store.fabu")))])],1)],1)],1),e("uni-popup",{ref:"popup3",attrs:{type:n.popType3}},[e("v-uni-view",{staticClass:"bottom-content bgPart flex column plr10 gray75"},[e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10 plr10"},[e("v-uni-text",{staticClass:"ft18 white bold tc"},[n._v(n._s(n.$t("login.e_pdeal")))])],1),e("v-uni-view",{staticClass:"flex alcenter bdb27 ptb10 plr10"},[e("v-uni-text",{staticClass:"flex1"},[n._v(n._s(n.$t("login.s_dealpwd")))]),e("v-uni-input",{staticClass:"flex2 bd_input h30 pl10 radius4",attrs:{type:"password",value:"",placeholder:n.$t("login.e_pdeal")},model:{value:n.ceil_password,callback:function(t){n.ceil_password=t},expression:"ceil_password"}})],1),e("v-uni-view",{staticClass:"flex between alcenter mtb10  plr10"},[e("v-uni-view",{staticClass:"bgBlue2 white ptb10 flex1 tc radius4",on:{click:function(t){t=n.$handleEvent(t),n.fabu3()}}},[n._v(n._s(n.$t("login.e_confrim")))])],1)],1)],1),e("uni-popup",{attrs:{show:n.show,type:n.popType,custom:!0,"mask-click":!1}},[e("v-uni-view",{staticClass:"uni-tip"},[e("v-uni-view",{staticClass:"uni-tip-title"},[n._v(n._s(n.$t("store.makeyichang"))+"？")]),e("v-uni-view",{staticClass:"uni-tip-group-button"},[e("v-uni-view",{staticClass:"uni-tip-button white",on:{click:function(t){t=n.$handleEvent(t),n.cancel("tip1")}}},[n._v(n._s(n.$t("store.ithink")))]),e("v-uni-view",{staticClass:"uni-tip-button gray75",on:{click:function(t){t=n.$handleEvent(t),n.cancel("tip2")}}},[n._v(n._s(n.$t("login.e_confrim")))])],1)],1)],1)],1)},i=[];e.d(t,"a",function(){return a}),e.d(t,"b",function(){return i})},aa4a:function(n,t,e){"use strict";var a=e("07ac"),i=e.n(a);i.a},ac4a:function(n,t,e){t=n.exports=e("2350")(!1),t.push([n.i,"\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\t/* uni-checkbox-group {\n\t    flex: 2 !important;\n\t} */uni-checkbox .uni-checkbox-input[data-v-48da599d]{background:#007aff!important}.uni-popup__wrapper .uni-popup__wrapper-box[data-v-48da599d]{padding:10px;background:#141e34}.logo[data-v-48da599d]{height:%?45?%;width:%?45?%;border-radius:50%}.cur[data-v-48da599d]{color:#217dc1;border-bottom:%?4?% solid #217dc1}.list[data-v-48da599d]{padding-top:%?720?%}\n\t/* 撤回弹窗 */.uni-tip[data-v-48da599d]{padding:%?30?%;width:%?600?%;background:#131e34;-webkit-box-sizing:border-box;box-sizing:border-box;border-radius:%?20?%}.uni-tip-title[data-v-48da599d]{text-align:center;font-weight:700;font-size:%?32?%;color:#fff}.uni-tip-group-button[data-v-48da599d]{margin-top:%?60?%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex}.uni-tip-button[data-v-48da599d]{width:100%;text-align:center;font-size:%?28?%}",""])},bb5a:function(n,t,e){var a=e("74e2");"string"===typeof a&&(a=[[n.i,a,""]]),a.locals&&(n.exports=a.locals);var i=e("4f06").default;i("c1601db2",a,!0,{sourceMap:!1,shadowMode:!1})},c331:function(n,t,e){"use strict";e.r(t);var a=e("a480"),i=e("6d2d");for(var s in i)"default"!==s&&function(n){e.d(t,n,function(){return i[n]})}(s);e("aa4a");var l=e("2877"),o=Object(l["a"])(i["default"],a["a"],a["b"],!1,null,"48da599d",null);t["default"]=o.exports},c8b0:function(n,t,e){"use strict";var a=e("288e");Object.defineProperty(t,"__esModule",{value:!0}),t.default=void 0,e("c5f6");var i=a(e("cebc")),s=e("2f62"),l=a(e("cdb1")),o={components:{uniPopup:l.default},data:function(){return{storeid:"",Data:{},type:"sell",done:!1,page:1,orderlist:[],hasMore:!0,over:!1,popType:"",popType2:"",popType3:"",fabuType:"sell",way:0,payway:0,paycoin:0,price:"",total_number:"",min_number:"",max_number:"",password:"",ceil_password:"",ceil_id:"",ceil_index:"",currency_id:"",show:!1,id:"",payList:[{value:"ali_pay",name:this.$t("store.alipay")},{value:"we_chat",name:this.$t("store.wechat")},{value:"bank",name:this.$t("store.bank")}],coinList:[{value:"CNY"},{value:"USD"},{value:"JPY"}]}},computed:(0,i.default)({},(0,s.mapState)({theme:function(n){return n.theme}})),onLoad:function(n){this.storeid=n.id,this.$utils.setTheme(this.theme)},filters:{chart0:function(n){return n.charAt(0)},toFixed2:function(n,t){return n=Number(n),n.toFixed(2)},toFixed3:function(n,t){return n=Number(n),n.toFixed(3)},toFixed4:function(n,t){return n=Number(n),n.toFixed(4)}},onShow:function(){var n=this;n.getinfo()},onHide:function(){},methods:{choosePay:function(n){this.payway=n},chooseCoin:function(n){this.paycoin=n},changeFtype:function(n){this.fabuType=n},togglePopup:function(n,t,e){this.id=e,this.popType=n,this.popType2=n,this.popType3=n,"tip"===t?this.show=!0:this.$refs[t].open()},fabu0:function(){this.clear(),this.togglePopup("bottom","popup")},cancel:function(n){if("tip1"!==n)return"tip2"===n?(this.show=!1,void this.abnormal()):void this.$refs[n].close();this.show=!1},clear:function(){var n=this;n.fabuType="sell",n.price="",n.total_number="",n.min_number="",n.max_number="",n.password=""},back:function(){uni.navigateBack()},goorderlist:function(n){uni.navigateTo({url:"/pages/legal/storeOrder?id="+n,animationType:"pop-in",animationDuration:300})},changetype:function(n){var t=this;t.page=1,t.hasMore=!0,t.over=!1,t.type=n,t.getlist()},changedone:function(){var n=this;n.page=1,n.hasMore=!0,n.over=!1,n.done=!n.done,n.getlist()},getinfo:function(){var n=this;n.$utils.initDataToken({url:"seller_info",data:{id:n.storeid}},function(t){console.log(t),n.Data=t,n.currency_id=n.Data.currency_id}),n.getlist()},getlist:function(){var n=this;n.$utils.initDataToken({url:"seller_trade",data:{id:n.storeid,page:n.page,type:n.type,was_done:n.done}},function(t){var e=t.data;uni.stopPullDownRefresh(),n.orderlist=1==n.page?e:n.orderlist.concat(e),n.over=!0,n.hasMore=!(t.page>=t.pages),console.log(n.hasMore)})},fabu:function(){var n=this,t=n.payList[n.payway].value,e=n.coinList[n.paycoin].value;return console.log(t,e),t?e?!n.price||n.price-0<=0?n.$utils.showLayer(n.$t("store.p_price")):!n.total_number||n.total_number-0<=0?n.$utils.showLayer(n.$t("trade.p_num")):!n.min_number||n.min_number-0<=0?n.$utils.showLayer(n.$t("store.p_min")):!n.max_number||n.max_number-0<=0?n.$utils.showLayer(n.$t("store.p_max")):n.max_number-0<n.min_number-0?n.$utils.showLayer(n.$t("store.t_minmax")):void n.togglePopup("bottom","popup2"):n.$utils.showLayer(n.$t("store.p_coin")):n.$utils.showLayer(n.$t("store.p_payment"))},fabu2:function(){var n=this;if(!n.password)return n.$utils.showLayer(n.$t("login.e_pdeal"));var t=n.payList[n.payway].value,e=n.coinList[n.paycoin].value;n.$utils.initDataToken({url:"legal_send",data:{type:n.fabuType,way:t,coin_code:e,price:n.price,total_number:n.total_number,min_number:n.min_number,max_number:n.max_number,currency_id:n.currency_id,password:n.password},type:"post"},function(t){n.cancel("popup3"),n.cancel("popup2"),n.cancel("popup"),n.password="",n.ceil_password="",n.$utils.showLayer(t),setTimeout(function(){n.getlist()},1e3)})},fabu3:function(){var n=this;if(!n.ceil_password)return n.$utils.showLayer(n.$t("login.e_pdeal"));n.$utils.initDataToken({url:"back_send",data:{id:n.ceil_id,password:n.ceil_password},type:"post"},function(t){n.cancel("popup3"),n.cancel("popup2"),n.cancel("popup"),n.ceil_id="",n.ceil_index="",n.ceil_password="",n.$utils.showLayer(t),setTimeout(function(){n.orderlist.splice(n.ceil_index,1)},1e3)})},withdraw:function(n,t){this.ceil_id=t,this.ceil_index=n,this.togglePopup("bottom","popup3")},abnormal:function(){var n=this;n.$utils.initDataToken({url:"error_send",data:{id:this.id,password:123456},type:"post"},function(t){n.$utils.showLayer(t),setTimeout(function(){n.getlist()},1e3)})},onPullDownRefresh:function(){this.page=1,this.getlist()},onReachBottom:function(){this.hasMore&&(this.page++,this.getlist())}}};t.default=o},cdb1:function(n,t,e){"use strict";e.r(t);var a=e("043d"),i=e("177a");for(var s in i)"default"!==s&&function(n){e.d(t,n,function(){return i[n]})}(s);e("2011");var l=e("2877"),o=Object(l["a"])(i["default"],a["a"],a["b"],!1,null,"6d67815d",null);t["default"]=o.exports}}]);