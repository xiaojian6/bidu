(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-assets-tradeAccount"],{"0e89":function(t,e,s){"use strict";var i=s("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(s("cebc")),n=s("2f62"),c={data:function(){return{currency:"",tradeType:"",page:1,coinInfo:{},logList:[],titles:[this.$t("assets.tradeacc"),this.$t("assets.leveracc"),this.$t("assets.legalacc")],ExRate:6.5}},computed:(0,a.default)({},(0,n.mapState)({theme:function(t){return t.theme}})),onShow:function(){this.$utils.setTheme(this.theme)},onLoad:function(t){this.currency=t.currency,this.tradeType=t.type,this.getDetail(),this.getLog();var e=this;"change"==t.type?uni.setNavigationBarTitle({title:e.titles[0]}):"lever"==t.type?uni.setNavigationBarTitle({title:e.titles[1]}):uni.setNavigationBarTitle({title:e.titles[2]})},methods:{getDetail:function(){var t=this;this.$utils.initDataToken({url:"wallet/detail",type:"POST",data:{currency:this.currency,type:this.tradeType}},function(e){t.ExRate=e.ExRate-0,uni.stopPullDownRefresh(),t.coinInfo=e})},goTrade:function(){var t=uni.getStorageSync("tradeData")||{},e=this.coinInfo.currency_name;console.log(uni.getStorageSync("tradeData"),t),t.legal_name&&t.legal_name!=e&&(console.log(123),t.currency_name=e,t.currency_id=this.currency,uni.setStorageSync("tradeData",t)),uni.switchTab({url:"/pages/trade/trade"})},getLog:function(){var t=this;this.$utils.initDataToken({url:"wallet/legal_log",type:"POST",data:{currency:this.currency,type:this.tradeType,page:this.page}},function(e){uni.stopPullDownRefresh(),t.logList=t.logList.concat(e.list)})}},onPullDownRefresh:function(){this.page=1,this.logList=[],this.getDetail(),this.getLog()},onReachBottom:function(){this.page++,this.getLog()}};e.default=c},3638:function(t,e,s){"use strict";var i=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},[s("v-uni-view",{staticClass:"plr20 pt20 pb15 bgPart"},[s("v-uni-text",{staticClass:"bold ft18 blue2"},[t._v(t._s(t.coinInfo.currency_name))]),"change"==t.tradeType?s("v-uni-view",{staticClass:"mt10 flex alcenter mt15"},[s("v-uni-view",{staticClass:"flex1"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(this.$t("trade.use")))]),s("v-uni-view",{staticClass:"mt5 ft12 nWhite"},[t._v(t._s(t.coinInfo.change_balance))])],1),s("v-uni-view",{staticClass:"flex1 tc"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(t.$t("assets.lock")))]),s("v-uni-view",{staticClass:"mt5 ft12 nWhite"},[t._v(t._s(t.coinInfo.lock_change_balance))])],1),s("v-uni-view",{staticClass:"flex1 tr"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(t.$t("assets.zhehe"))+"(CNY)")]),s("v-uni-view",{staticClass:"mt5"},[t._v(t._s(Math.floor((t.coinInfo.change_balance-0)*(t.coinInfo.usdt_price-0)*t.ExRate*100)/100||"0.00"))])],1)],1):t._e(),"lever"==t.tradeType?s("v-uni-view",{staticClass:"mt10 flex alcenter mt15"},[s("v-uni-view",{staticClass:"flex1"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(this.$t("trade.use")))]),s("v-uni-view",{staticClass:"mt5 ft12 nWhite"},[t._v(t._s(t.coinInfo.lever_balance||"0.00"))])],1),s("v-uni-view",{staticClass:"flex1 tc"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(t.$t("assets.lock")))]),s("v-uni-view",{staticClass:"mt5 ft12 nWhite"},[t._v(t._s(t.coinInfo.lock_lever_balance||"0.00"))])],1),s("v-uni-view",{staticClass:"flex1 tr"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(t.$t("assets.zhehe"))+"(CNY)")]),s("v-uni-view",{staticClass:"mt5"},[t._v(t._s(Math.floor((t.coinInfo.lever_balance-0)*(t.coinInfo.usdt_price-0)*t.ExRate*100)/100||"0.00"))])],1)],1):t._e(),"legal"==t.tradeType?s("v-uni-view",{staticClass:"mt10 flex alcenter mt15"},[s("v-uni-view",{staticClass:"flex1"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(this.$t("trade.use")))]),s("v-uni-view",{staticClass:"mt5 ft12 nWhite"},[t._v(t._s(t.coinInfo.legal_balance||"0.00"))])],1),s("v-uni-view",{staticClass:"flex1 tc"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(t.$t("assets.lock")))]),s("v-uni-view",{staticClass:"mt5 ft12 nWhite"},[t._v(t._s(t.coinInfo.lock_legal_balance||"0.00"))])],1),s("v-uni-view",{staticClass:"flex1 tr"},[s("v-uni-view",{staticClass:"blue ft12"},[t._v(t._s(t.$t("assets.zhehe"))+"(CNY)")]),s("v-uni-view",{staticClass:"mt5"},[t._v(t._s(Math.floor((t.coinInfo.legal_balance-0)*(t.coinInfo.usdt_price-0)*t.ExRate*100)/100||"0.00"))])],1)],1):t._e()],1),s("v-uni-view",{staticClass:"plr10 ptb15 mt10 bgPart",staticStyle:{"min-height":"80vh"}},[s("v-uni-view",{staticClass:"ft16"},[t._v(t._s(t.$t("assets.records")))]),s("v-uni-view",{staticClass:"mt10 pb100"},[s("v-uni-view",{staticClass:"flex alcenter ptb10 bdb_blue3 nWhite"},[s("v-uni-view",{staticClass:"flex1"},[t._v(t._s(t.$t("trade.num")))]),s("v-uni-view",{staticClass:"flex1 "},[t._v(t._s(t.$t("assets.record")))]),s("v-uni-view",{staticClass:"flex1 tr"},[t._v(t._s(t.$t("trade.time")))])],1),t._l(t.logList,function(e,i){return t.logList.length>0?s("v-uni-view",{key:i,staticClass:"mt10 flex bdb_blue3 ptb5"},[s("v-uni-view",{staticClass:"flex1"},[t._v(t._s(e.value-0))]),s("v-uni-view",{staticClass:"flex1 wordbreak pr10"},[t._v(t._s(e.info))]),s("v-uni-view",{staticClass:"flex1 tr"},[t._v(t._s(e.created_time))])],1):t._e()}),0==t.logList.length?s("v-uni-view",{staticClass:"mt20 tc"},[s("v-uni-image",{staticClass:"wt60 h60",attrs:{src:"../../static/image/anonymous.png"}}),s("v-uni-view",[t._v(t._s(t.$t("home.norecord")))])],1):t._e()],2)],1),s("v-uni-view",{staticClass:"fixed pos_l0b0 w100 bgHeader bdt2f flex alcenter ptb10 zdx100"},["change"==t.tradeType?[s("v-uni-navigator",{staticClass:"flex1 tc",attrs:{url:"charge?currency="+t.currency+"&name="+t.coinInfo.currency_type}},[s("v-uni-image",{staticClass:"wt30 h30 ",attrs:{src:"../../static/image/cb01.png"}}),s("v-uni-view",{staticClass:"nWhite"},[t._v(t._s(t.$t("assets.charge")))])],1),s("v-uni-navigator",{staticClass:"flex1 tc",attrs:{url:"mention?currency="+t.currency+"&name="+t.coinInfo.currency_name}},[s("v-uni-image",{staticClass:"wt30 h30",attrs:{src:"../../static/image/tb01.png"}}),s("v-uni-view",{staticClass:"nWhite"},[t._v(t._s(t.$t("assets.mention")))])],1),t.coinInfo.is_legal?s("v-uni-navigator",{staticClass:"flex1 tc",attrs:{url:"transfer?name="+t.coinInfo.currency_name}},[s("v-uni-image",{staticClass:"wt30 h30 ",attrs:{src:"../../static/image/hz01.png"}}),s("v-uni-view",{staticClass:"nWhite"},[t._v(t._s(t.$t("assets.transfer")))])],1):t._e()]:t._e(),"lever"==t.tradeType?[t.coinInfo.is_legal?s("v-uni-navigator",{staticClass:"flex1 tc",attrs:{url:"transferTolever?name="+t.coinInfo.currency_name}},[s("v-uni-image",{staticClass:"wt30 h30 ",attrs:{src:"../../static/image/hz01.png"}}),s("v-uni-view",{staticClass:"nWhite"},[t._v(t._s(t.$t("assets.transfer")))])],1):t._e()]:t._e()],2)],1)},a=[];s.d(e,"a",function(){return i}),s.d(e,"b",function(){return a})},"3f59":function(t,e,s){"use strict";s.r(e);var i=s("3638"),a=s("b00f");for(var n in a)"default"!==n&&function(t){s.d(e,t,function(){return a[t]})}(n);var c=s("2877"),l=Object(c["a"])(a["default"],i["a"],i["b"],!1,null,"b9527b28",null);e["default"]=l.exports},b00f:function(t,e,s){"use strict";s.r(e);var i=s("0e89"),a=s.n(i);for(var n in i)"default"!==n&&function(t){s.d(e,t,function(){return i[t]})}(n);e["default"]=a.a}}]);