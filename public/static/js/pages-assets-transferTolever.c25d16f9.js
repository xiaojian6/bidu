(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-assets-transferTolever"],{"06b0":function(t,e,a){"use strict";var n=a("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,a("7f7f");var s=n(a("cebc")),i=a("2f62"),l={data:function(){return{number:"",name:"",coinInfo:{},changeName:[this.$t("assets.legalacc"),this.$t("assets.leveracc")],changeType:["legal","lever"],type:0,hasChange:0,animateTab1:"",animateTab2:"",currencyLegal:{},currencyLever:{},balance:"",wallet_id:""}},computed:(0,s.default)({},(0,i.mapState)({theme:function(t){return t.theme}})),onLoad:function(t){this.name=t.name,uni.setNavigationBarTitle({title:this.$t("assets").transfer}),this.$utils.setTheme(this.theme),this.getList()},onPullDownRefresh:function(){this.getList()},methods:{getList:function(){var t=this;this.$utils.initDataToken({url:"wallet/list",type:"POST"},function(e){uni.stopPullDownRefresh();var a=e.legal_wallet.balance,n=a.filter(function(e){return e.currency_name==t.name})||[];n.length>0&&(t.currencyLegal=n[0],0==t.type&&(t.balance=n[0].legal_balance),t.wallet_id=n[0].id);var s=e.lever_wallet.balance,i=s.filter(function(e){return e.currency_name==t.name})||[];i.length>=0&&(t.currencyLever=i[0],1==t.type&&(t.balance=i[0].lever_balance))})},tapChange:function(){console.log(this.hasChange),this.type=0==this.type?1:0,this.changeType=this.changeType.reverse(),console.log(this.changeType),0==this.type?this.balance=this.currencyLegal.legal_balance:this.balance=this.currencyLever.lever_balance,this.hasChange++},all:function(){console.log(123),this.number=this.balance},transfer:function(){var t=this;if(!this.number)return this.$utils.showLayer(this.$t("assets.p_transfernum"));this.$utils.initDataToken({url:"wallet/transfer",type:"POST",data:{wallet_id:this.wallet_id,number:this.number,from:this.changeType[0],to:this.changeType[1]}},function(e){t.number="",t.$utils.showLayer(e),setTimeout(function(){t.getList()},1500)})}}};e.default=l},2282:function(t,e,a){"use strict";a.r(e);var n=a("06b0"),s=a.n(n);for(var i in n)"default"!==i&&function(t){a.d(e,t,function(){return n[t]})}(i);e["default"]=s.a},"334c":function(t,e,a){"use strict";var n=a("b73f"),s=a.n(n);s.a},4924:function(t,e,a){e=t.exports=a("2350")(!1),e.push([t.i,".animateUp[data-v-3caee17b]{-webkit-transform:translateY(%?-80?%);-ms-transform:translateY(%?-80?%);transform:translateY(%?-80?%);-webkit-transition:all .5s;-o-transition:all .5s;transition:all .5s}.animateDown[data-v-3caee17b]{-webkit-transform:translateY(%?80?%);-ms-transform:translateY(%?80?%);transform:translateY(%?80?%);-webkit-transition:all .5s;-o-transition:all .5s;transition:all .5s}.animateOff[data-v-3caee17b]{-webkit-transform:translateY(0);-ms-transform:translateY(0);transform:translateY(0);-webkit-transition:all .5s;-o-transition:all .5s;transition:all .5s}",""])},9648:function(t,e,a){"use strict";var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},[a("v-uni-view",{staticClass:"plr15 ptb30 bgPart"},[a("v-uni-view",{staticClass:" flex alcenter between bd2f"},[a("v-uni-view",{staticClass:"flex alcenter pl15 flex1"},[a("v-uni-view",{staticClass:"flex column alcenter "},[a("v-uni-text",{staticClass:"wt5 h5 radius50p bgBlue"}),a("v-uni-view",{staticClass:"h20 mt5 mb5 bdl2f"}),a("v-uni-text",{staticClass:"wt5 h5 radius50p bgred"})],1),a("v-uni-view",{staticClass:"ml10 flex1"},[a("v-uni-view",{staticClass:"h40 lh40 bdb2f flex alcenter"},[a("v-uni-text",{staticClass:"blue pr10"},[t._v(t._s(t.$t("assets.from")))]),a("v-uni-view",{class:[{animateDown:1==t.type,animateOff:0==t.type}]},[t._v(t._s(t.changeName[0]))])],1),a("v-uni-view",{staticClass:"h40 lh40 flex alcenter"},[a("v-uni-text",{staticClass:"blue pr10"},[t._v(t._s(t.$t("assets.to")))]),a("v-uni-view",{class:[{animateUp:1==t.type,animateOff:0==t.type}]},[t._v(t._s(t.changeName[1]))])],1)],1)],1),a("v-uni-view",{staticClass:"plr15 bg2 h80 flex alcenter jscenter"},[a("v-uni-view",{staticClass:"wt30 h30 radius50p bggray flex alcenter jscenter",on:{click:function(e){e=t.$handleEvent(e),t.tapChange(e)}}},[a("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"../../static/image/transfer1.png"}})],1)],1)],1)],1),a("v-uni-view",{staticClass:"mt10 plr15 bgPart pt20",staticStyle:{"min-height":"75.0vh"}},[a("v-uni-view",{},[t._v(t._s(t.$t("assets.transfernum")))]),a("v-uni-view",{staticClass:"flex alcenter between bdb1f mt10"},[a("v-uni-input",{staticClass:"h40 flex1",attrs:{type:"number",placeholder:t.$t("assets.p_transfernum")},model:{value:t.number,callback:function(e){t.number=e},expression:"number"}}),a("v-uni-view",{staticClass:"flex alcenter"},[a("v-uni-text",{staticClass:"blue ft14 pr10 bdr_white50"},[t._v(t._s(t.name||"--"))]),a("v-uni-view",{staticClass:"pl10",on:{click:function(e){e=t.$handleEvent(e),t.all(e)}}},[t._v(t._s(t.$t("trade.all")))])],1)],1),a("v-uni-view",{staticClass:"mt10 blue ft12"},[t._v(t._s(t.$t("trade.use"))),a("v-uni-text",[t._v(t._s(t.balance))]),t._v(t._s(t.name))],1),a("v-uni-view",{staticClass:"mt50 bgBlue radius4 ptb10 white ft14 tc mb10",on:{click:function(e){e=t.$handleEvent(e),t.transfer(e)}}},[t._v(t._s(t.$t("assets.transfer")))])],1)],1)},s=[];a.d(e,"a",function(){return n}),a.d(e,"b",function(){return s})},b73f:function(t,e,a){var n=a("4924");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var s=a("4f06").default;s("7de527c7",n,!0,{sourceMap:!1,shadowMode:!1})},c49f:function(t,e,a){"use strict";a.r(e);var n=a("9648"),s=a("2282");for(var i in s)"default"!==i&&function(t){a.d(e,t,function(){return s[t]})}(i);a("334c");var l=a("2877"),r=Object(l["a"])(s["default"],n["a"],n["b"],!1,null,"3caee17b",null);e["default"]=r.exports}}]);