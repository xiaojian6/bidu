(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-mine-lang"],{7427:function(t,e,n){"use strict";var a=n("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i=a(n("cebc")),s=n("2f62"),r={data:function(){return{lang:"",languages:[{name:"简体中文",type:"zh"},{name:"English",type:"en"},{name:"繁體中文",type:"hk"}]}},computed:(0,i.default)({},(0,s.mapState)({theme:function(t){return t.theme}})),onLoad:function(){uni.setNavigationBarTitle({title:this.$t("home").lang}),this.lang=uni.getStorageSync("lang")||"zh",this.$utils.setTheme(this.theme)},methods:{changeLang:function(t){var e=this;this.$utils.initData({url:"lang/set",data:{lang:t},type:"POST"},function(n){console.log(t),e.lang=t,uni.setStorageSync("lang",t),e.$i18n.locale=t,uni.setNavigationBarTitle({title:e.$t("home").lang}),e.changeFooter()})},changeFooter:function(){uni.setTabBarItem({index:0,text:this.$t("market.home")}),uni.setTabBarItem({index:1,text:this.$t("market.market")}),uni.setTabBarItem({index:2,text:this.$t("trade.bibi")}),uni.setTabBarItem({index:3,text:this.$t("assets.lever")}),uni.setTabBarItem({index:4,text:this.$t("assets.assets")})}}};e.default=r},"9c28":function(t,e,n){"use strict";n.r(e);var a=n("f41c"),i=n("e3b2");for(var s in i)"default"!==s&&function(t){n.d(e,t,function(){return i[t]})}(s);var r=n("2877"),u=Object(r["a"])(i["default"],a["a"],a["b"],!1,null,"08cb1c88",null);e["default"]=u.exports},e3b2:function(t,e,n){"use strict";n.r(e);var a=n("7427"),i=n.n(a);for(var s in a)"default"!==s&&function(t){n.d(e,t,function(){return a[t]})}(s);e["default"]=i.a},f41c:function(t,e,n){"use strict";var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},t._l(t.languages,function(e,a){return n("v-uni-view",{key:a,staticClass:"flex alcenter h50 plr15 between bdb1f langs",on:{click:function(n){n=t.$handleEvent(n),t.changeLang(e.type)}}},[n("v-uni-text",[t._v(t._s(e.name))]),n("v-uni-image",{directives:[{name:"show",rawName:"v-show",value:e.type==t.lang,expression:"item.type==lang"}],staticClass:"wt15 h15",attrs:{src:"../../static/image/nike.png"}})],1)}),1)},i=[];n.d(e,"a",function(){return a}),n.d(e,"b",function(){return i})}}]);