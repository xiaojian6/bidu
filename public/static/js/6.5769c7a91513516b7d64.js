webpackJsonp([6],{Zxu2:function(t,e,s){"use strict";Object.defineProperty(e,"__esModule",{value:!0});var a=s("woOf"),i=s.n(a),n={data:function(){return{current:0,token:"",sellerId:"",info:{lists:{data:[]}},showWhich:"none",isShow:!1,showDetail:!1,detail:{money:"",num:""},timer:"",rate:"--",filterPms:{id:"",page:1,wasDone:!1,type:"sell"},list:[],submitPms:{type:"sell"},typeList:[{name:this.$t("td.sell"),type:"sell"},{name:this.$t("fat.wantBuy"),type:"buy"}],type:"sell",way:-1,price:"",total_number:"",min_number:"",max_number:"",password:"",coin:-1}},filters:{toFixeds:function(t){return(t=Number(t)).toFixed(3)}},created:function(){this.token=window.localStorage.getItem("token")||"",this.token&&(this.sellerId=this.$route.query.id||"",this.getSellerInfo(),this.getList())},methods:{changeType:function(t,e){this.current=t,this.type=e},getSellerInfo:function(t){var e=this;this.showWhich="none",t||(this.page=1);var s=layer.load();this.$http({url:"/api/seller_info",params:{id:this.sellerId,page:1},headers:{Authorization:this.token}}).then(function(t){if(layer.close(s),"ok"==t.data.type){e.info=i()({},t.data.message);var a=(t.data.message.done/t.data.message.total*100).toFixed(2);e.rate="NaN"==a?"0.00":a+"%",e.currency_id=t.data.message.currency_id}})},getList:function(){var t=this,e=arguments.length>0&&void 0!==arguments[0]&&arguments[0],s=this,a={};e||(this.filterPms.page=1),a.page=this.filterPms.page,"none"!=this.filterPms.type&&(a.type=this.filterPms.type),"none"!=this.filterPms.wasDone&&(a.was_done=this.filterPms.wasDone),a.id=this.sellerId;var i=layer.load();this.$http({url:"/api/seller_trade",params:a,headers:{Authorization:this.token}}).then(function(a){if(layer.close(i),"ok"==a.data.type){var n=a.data.message;e?n.data.length?(t.list=n.data.concat(t.list),t.filterPms.page+=1):layer.msg(s.$t("td.nomore")):(t.list=n.data,n.data.length&&(t.filterPms.page+=1))}})},changeOrder:function(t,e){var s=this;t=t,e=e;layer.prompt({title:s.$t("td.pwd"),formType:1,btn:[s.$t("td.confirm"),s.$t("td.canceil")]},function(a,i){s.password=a,s.$http({url:"/api/"+t,method:"post",data:{id:e,password:s.password},headers:{Authorization:s.token}}).then(function(t){"ok"==t.data.type?(layer.msg(t.data.message),s.getList()):layer.msg(t.data.message)}),layer.close(i)})},setDetail:function(t){this.detail=i()({which:"money",money:"",num:""},t),this.showDetail=!0;var e=60,s=this;s.timer=setInterval(function(){e--,s.$refs.remainTime.innerHTML=e,0==e&&(s.showDetail=!1,clearInterval(s.timer))},1e3)},buySell:function(){var t=this,e="";if("money"==this.detail.which){if(""==(e=this.detail.money))return;if(e-0-this.detail.limitation.min<0)return void layer.msg(this.$t("fat.notlow"));if(e-0-this.detail.limitation.max>0)return void layer.msg(this.$t("fat.nothigh"))}else{if(""==(e=this.detail.num))return;if(e>this.detail.surplus_number)return void layer.msg(this.$t("fat.notnum"))}this.$http({url:"/api/do_legal_deal",method:"post",data:{means:this.detail.which,value:e,id:this.detail.id},headers:{Authorization:this.token}}).then(function(e){if(t.showDetail=!1,"ok"==e.data.type){var s=e.data.message;layer.msg(s.msg),"sell"==t.detail.type?t.$router.push({path:"/legalPay",query:{id:msg.data.id}}):t.$router.push({path:"/components/payCannel",query:{id:msg.data.id}})}})},cancel:function(){clearInterval(this.timer),this.showDetail=!1},fb:function(){this.isShow=!0},close:function(){this.isShow=!1},fabu:function(){var t=this,e=this,s=(this.type,this.way),a=this.coin,i=this.price,n=this.total_number,r=this.min_number-0,l=this.max_number-0,o=(this.currency_id,this.password);return s<0?layer.msg(e.$t("ctc.payway")):a<0?layer.msg(e.$t("td.pcoin")):!i||i<=0?layer.msg(e.$t("ctc.pprice")):!n||n<=0?layer.msg(e.$t("fat.pnums")):!r||r<=0?layer.msg(e.$t("fat.pmin")):!l||l<=0?layer.msg(e.$t("fat.pmax")):l<r?layer.msg(e.$t("fat.pnot")):o?void this.$http({url:"/api/legal_send",method:"post",data:{type:this.type,way:this.way,price:this.price,total_number:this.total_number,min_number:r,max_number:l,currency_id:this.currency_id,password:this.password,coin_code:a},headers:{Authorization:this.token}}).then(function(s){"ok"==s.data.type?(layer.msg(s.data.message),e.type="sell",e.way="",e.coin="",e.price="",e.total_number="",e.min_number="",e.max_number="",e.password="",t.isShow=!1,t.getSellerInfo(),t.getList()):layer.msg(s.data.message)}):layer.msg(e.$t("td.pwd"))}}},r={render:function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"white",attrs:{id:"legal-shop-detail"}},[a("div",{staticClass:"top flex bgf8"},[a("div",{staticClass:"top-t flex bod_rc"},[t.info.name?a("div",{staticClass:"logo ft18"},[t._v(t._s(t.info.name.charAt(0)))]):t._e(),t._v(" "),a("div",[a("div",[t._v(t._s(t.info.name))]),t._v(" "),a("div",[t._v(t._s(t.$t("fat.register_time"))+"："+t._s(t.info.create_time))])])]),t._v(" "),a("div",{staticClass:"top-b flex"},[a("div",[a("div",[t._v(t._s(t.info.total))]),t._v(" "),a("div",[t._v(t._s(t.$t("fat.odtotal")))])]),t._v(" "),a("div",[a("div",[t._v(t._s(t.info.thirtyDays))]),t._v(" "),a("div",[t._v(t._s(t.$t("fat.odmonth")))])]),t._v(" "),a("div",[a("div",[t._v(t._s(t.info.done))]),t._v(" "),a("div",[t._v(t._s(t.$t("fat.odfinish")))])]),t._v(" "),a("div",[a("div",[t._v(t._s(t.rate))]),t._v(" "),a("div",[t._v(t._s(t.$t("fat.odrate")))])])]),t._v(" "),a("div",{staticClass:"submit flex"},[a("div",{on:{click:t.fb}},[t._v(t._s(t.$t("fat.submit")))])])]),t._v(" "),a("div",{staticClass:"md flex bgf8"},[a("div",[a("span",[t._v(t._s(t.$t("fat.phone")))]),t._v(" "),1==t.info.prove_mobile?a("img",{attrs:{src:s("4AGf"),alt:""}}):a("img",{attrs:{src:s("fICz"),alt:""}})]),t._v(" "),a("div",[a("span",[t._v(t._s(t.$t("fat.realAuth")))]),t._v(" "),1==t.info.prove_real?a("img",{attrs:{src:s("4AGf"),alt:""}}):a("img",{attrs:{src:s("fICz"),alt:""}})]),t._v(" "),a("div",[a("span",[t._v(t._s(t.$t("fat.advAuth")))]),t._v(" "),1==t.info.prove_level?a("img",{attrs:{src:s("4AGf"),alt:""}}):a("img",{attrs:{src:s("fICz"),alt:""}})])]),t._v(" "),a("div",{staticClass:"list bgf8"},[a("div",{staticClass:"tab"},[a("div",{staticClass:"flex"},[a("div",[t._v(t._s(t.$t("fat.type"))+"：")]),t._v(" "),a("div",[a("span",{class:{now:"sell"==t.filterPms.type},on:{click:function(e){t.filterPms.type="sell",t.getList()}}},[t._v(t._s(t.$t("fat.mysell")))]),t._v(" "),a("span",{class:{now:"buy"==t.filterPms.type},on:{click:function(e){t.filterPms.type="buy",t.getList()}}},[t._v(t._s(t.$t("fat.mybuy")))])])]),t._v(" "),a("div",{staticClass:"flex"},[a("div",[t._v(t._s(t.$t("fat.status"))+"：")]),t._v(" "),a("div",[a("span",{class:{now:0==t.filterPms.wasDone},on:{click:function(e){t.filterPms.wasDone=!1,t.getList()}}},[t._v(t._s(t.$t("td.nofinish")))]),t._v(" "),a("span",{class:{now:1==t.filterPms.wasDone},on:{click:function(e){t.filterPms.wasDone=!0,t.getList()}}},[t._v(t._s(t.$t("td.finished")))])])])]),t._v(" "),a("div",{staticClass:"ul-head tc bdb "},[a("div",{staticClass:"w10 tl"},[t._v(t._s(t.$t("td.currency")))]),t._v(" "),a("div",{staticClass:"w15"},[t._v(t._s(t.$t("td.num")))]),t._v(" "),a("div",{staticClass:"w25"},[t._v(t._s(t.$t("td.limit")))]),t._v(" "),a("div",{staticClass:"w15"},[t._v(t._s(t.$t("td.price")))]),t._v(" "),a("div",{staticClass:"w10"},[t._v(t._s(t.$t("td.method")))]),t._v(" "),a("div",{staticClass:"tr"},[t._v(t._s(t.$t("td.do")))])]),t._v(" "),a("ul",{class:[t.showWhich+"-box"]},t._l(t.list,function(e,i){return a("li",{key:i,staticClass:"bod_bc tc bdb",class:["buy"==e.type?"buy-item":"sell-item"]},[a("div",{staticClass:"w10 tl"},[t._v(t._s(e.currency_name))]),t._v(" "),a("div",{staticClass:"w15"},[t._v(t._s(t._f("toFixeds")(e.surplus_number||"0.000")))]),t._v(" "),a("div",{staticClass:"w25"},[t._v(t._s(t._f("toFixeds")(e.limitation.min||"0.000"))+"-"+t._s(t._f("toFixeds")(e.limitation.max||"0.000")))]),t._v(" "),a("div",{staticClass:"w15"},[t._v(t._s(t._f("toFixeds")(e.price||"0.000"))+" "+t._s(e.coin_code))]),t._v(" "),a("div",{staticClass:"flex alcenter center w10"},["ali_pay"==e.way?a("img",{attrs:{src:s("F43K")}}):t._e(),t._v(" "),"we_chat"==e.way?a("img",{attrs:{src:s("PVjh")}}):t._e(),t._v(" "),"bank"==e.way?a("img",{attrs:{src:s("ufz/")}}):t._e()]),t._v(" "),a("div",{staticClass:"tr"},[a("router-link",{attrs:{tag:"span",to:{path:"/shopLegalRecord",query:{id:e.id}}}},[t._v(t._s(t.$t("fat.seeOrder")))]),t._v(" "),1!=e.is_done?a("span",{on:{click:function(s){t.changeOrder("back_send",e.id)}}},[t._v(t._s(t.$t("fat.withdraw")))]):t._e(),t._v(" "),1!=e.is_done?a("span",{on:{click:function(s){t.changeOrder("error_send",e.id)}}},[t._v(t._s(t.$t("fat.abnormal")))]):t._e()],1)])})),t._v(" "),t.list.length?a("div",{staticClass:"more",on:{click:function(e){t.getList(!0)}}},[t._v(t._s(t.$t("td.more")))]):a("div",{staticClass:"more"},[t._v(t._s(t.$t("td.nomore")))])]),t._v(" "),t.isShow?a("div",{staticClass:"submit-box"},[a("div",{staticClass:"content"},[a("p",{staticClass:"close",on:{click:t.close}},[t._v("X")]),t._v(" "),a("div",{staticClass:"tab bdb"},[a("div",[t._v(t._s(t.$t("fat.psType"))+"：")]),t._v(" "),t._l(t.typeList,function(e,s){return a("div",{class:{now:s==t.current},on:{click:function(a){t.changeType(s,e.type)}}},[t._v(t._s(e.name))])})],2),t._v(" "),a("div",{staticClass:"flex"},[a("span",[t._v(t._s(t.$t("td.currency"))+"：")]),t._v(" "),a("span",[t._v(t._s(t.info.currency_name))])]),t._v(" "),a("div",{staticClass:"flex"},[a("span",[t._v(t._s(t.$t("td.method"))+"：")]),t._v(" "),a("select",{directives:[{name:"model",rawName:"v-model",value:t.way,expression:"way"}],staticClass:"flex2 ptb10 plr10 bd",attrs:{name:"coins",id:"method"},on:{change:function(e){var s=Array.prototype.filter.call(e.target.options,function(t){return t.selected}).map(function(t){return"_value"in t?t._value:t.value});t.way=e.target.multiple?s:s[0]}}},[a("option",{attrs:{value:"-1"}},[t._v(t._s(t.$t("fat.pselect")))]),t._v(" "),a("option",{attrs:{value:"ali_pay"}},[t._v(t._s(t.$t("fat.alipay")))]),t._v(" "),a("option",{attrs:{value:"we_chat"}},[t._v(t._s(t.$t("fat.wechat")))]),t._v(" "),a("option",{attrs:{value:"bank"}},[t._v(t._s(t.$t("fat.bank")))])])]),t._v(" "),a("div",{staticClass:"flex"},[a("span",[t._v(t._s(t.$t("td.coincode"))+"：")]),t._v(" "),a("select",{directives:[{name:"model",rawName:"v-model",value:t.coin,expression:"coin"}],staticClass:"flex2 ptb10 plr10 bd",attrs:{name:"ucoins",id:"umethod"},on:{change:function(e){var s=Array.prototype.filter.call(e.target.options,function(t){return t.selected}).map(function(t){return"_value"in t?t._value:t.value});t.coin=e.target.multiple?s:s[0]}}},[a("option",{attrs:{value:"-1"}},[t._v(t._s(t.$t("fat.pselect")))]),t._v(" "),a("option",{attrs:{value:"CNY"}},[t._v("CNY")]),t._v(" "),a("option",{attrs:{value:"USD"}},[t._v("USD")]),t._v(" "),a("option",{attrs:{value:"JPY"}},[t._v("JPY")])])]),t._v(" "),a("div",{staticClass:"flex"},[a("span",[t._v(t._s(t.$t("td.price"))+"：")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.price,expression:"price"}],attrs:{type:"text",name:"",id:""},domProps:{value:t.price},on:{input:function(e){e.target.composing||(t.price=e.target.value)}}})]),t._v(" "),a("div",{staticClass:"flex"},[a("span",[t._v(t._s(t.$t("td.num"))+"：")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.total_number,expression:"total_number"}],attrs:{type:"text"},domProps:{value:t.total_number},on:{input:function(e){e.target.composing||(t.total_number=e.target.value)}}})]),t._v(" "),a("div",{staticClass:"flex"},[a("span",[t._v(t._s(t.$t("fat.minNum"))+"：")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.min_number,expression:"min_number"}],attrs:{type:"text"},domProps:{value:t.min_number},on:{input:function(e){e.target.composing||(t.min_number=e.target.value)}}})]),t._v(" "),a("div",{staticClass:"flex"},[a("span",[t._v(t._s(t.$t("fat.maxNum"))+"：")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.max_number,expression:"max_number"}],attrs:{type:"text"},domProps:{value:t.max_number},on:{input:function(e){e.target.composing||(t.max_number=e.target.value)}}})]),t._v(" "),a("div",{staticClass:"flex"},[a("span",[t._v(t._s(t.$t("lever.psw"))+"：")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.password,expression:"password"}],attrs:{type:"password"},domProps:{value:t.password},on:{input:function(e){e.target.composing||(t.password=e.target.value)}}})]),t._v(" "),a("div",{staticClass:"btn curPer",on:{click:t.fabu}},[t._v(t._s(t.$t("fat.submit")))])])]):t._e()])},staticRenderFns:[]};var l=s("VU/8")(n,r,!1,function(t){s("h2ES")},null,null);e.default=l.exports},h2ES:function(t,e){}});
//# sourceMappingURL=6.5769c7a91513516b7d64.js.map