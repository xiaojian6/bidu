webpackJsonp([10],{S4Ta:function(e,t){},fEWD:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var n={name:"allmarket",data:function(){return{token:"",current:0,topnow:1,sortindex:"",direction:!0,quoList:[],selectedName:"",typeList:[{type:this.$t("market.selfmarket")},{type:this.$t("market.allmarket")}],classtify:[{text:this.$t("market.symbol"),key:"at"},{text:this.$t("market.newprice"),key:"now_price"},{text:this.$t("market.change"),key:"change"},{text:this.$t("market.highprice"),key:""},{text:this.$t("market.lowprice"),key:""},{text:this.$t("market.vol"),key:""}],marketList:[],myAdd:[],fiat_convert_cny:""}},filters:{toFixeds:function(e){return(e=Number(e)).toFixed(2)}},created:function(){this.token=window.localStorage.getItem("token")||"",window.localStorage.getItem("current")&&(this.current=window.localStorage.getItem("current")),this.token?(this.topnow=1,this.getMyAdd()):(this.topnow=1,this.getData()),this.connect()},beforeCreate:function(){document.querySelector("html").setAttribute("style","background-color:#fff;")},beforeDestroy:function(){document.querySelector("html").removeAttribute("style")},methods:{changeType:function(e){this.topnow=e},changeLegal:function(e,t){this.current=e,this.selectedName=t,window.localStorage.setItem("current",this.current),window.localStorage.setItem("selectedName",this.selectedName),this.getData()},godeal:function(e,t,a,n,i,o,s,c,r,l){var d=t+"/"+i;window.localStorage.setItem("downUp",o),window.localStorage.setItem("legal_id_cur",a),window.localStorage.setItem("shareNum",s),window.localStorage.setItem("legal_id",n),window.localStorage.setItem("currency_id",a),window.localStorage.setItem("currency_name",t),window.localStorage.setItem("legal_name",i),window.localStorage.setItem("index1",this.current),window.localStorage.setItem("index2",e),window.localStorage.setItem("symbol",d),window.localStorage.setItem("dealDownUp",o),window.localStorage.setItem("dealLegalId",n),window.localStorage.setItem("dealCurrencyId",a),window.localStorage.setItem("dealCurrencyName",t),window.localStorage.setItem("dealLegalName",i),window.localStorage.setItem("dealIndex1",this.current),window.localStorage.setItem("dealIndex2",e),window.localStorage.setItem("dealSymbol",d),this.$router.push({path:"/dealCenter"})},getData:function(){var e=this,t=this;this.axios.get("/api/currency/quotation_new",{}).then(function(a){t.fiat_convert_cny=a.data.message[t.current].fiat_convert_cny;var n=a.data.message;e.myAdd.length&&n.forEach(function(t,a){e.myAdd.forEach(function(e,a){t.id==e.legal_id&&(t.quotation.find(function(t){return t.currency_id==e.currency_id}).added=!0)})}),t.quoList=n;for(var i=[],o=0;o<n.length;o++)i[o]=n[o].quotation;var s=i[0][0];t.marketList=i,window.localStorage.getItem("selectedName")?t.selectedName=window.localStorage.getItem("selectedName"):(t.selectedName=s.legal_name,window.localStorage.setItem("selectedName",t.selectedName))}).catch(function(e){e.response})},connect:function(){var e=this;e.$socket.emit("login",localStorage.getItem("user_id")),e.$socket.on("daymarket",function(t){"daymarket"==t.type&&e.selectedName&&e.selectedName==t.legal_name&&e.marketList.forEach(function(e,a){var n=e.findIndex(function(e,a){return e.currency_id==t.currency_id&&e.legal_id==t.legal_id});-1!=n&&(e[n].now_price=t.now_price,e[n].change=t.change,e[n].high=t.high,e[n].low=t.low)})})},sortData:function(e,t){this.direction=e===this.sortindex&&!this.direction,this.sortindex=e;var a=t,n=1==this.direction?"up":"down";this.marketList[this.current].sort(function(e,t){return"at"==a?"up"==n?e.currency_name.charCodeAt()-t.currency_name.charCodeAt():t.currency_name.charCodeAt()-e.currency_name.charCodeAt():"up"==n?e[a]-t[a]:t[a]-e[a]})},addDelete:function(e,t,a){var n=this;this.token?this.$http({url:"/api/user/addzixuan",method:"post",data:{currency_id:t,legal_id:a},headers:{Authorization:this.token}}).then(function(t){"add"==e?layer.msg("添加自选"+t.data.message):layer.msg("删除自选"+t.data.message),n.getMyAdd()}):layer.msg("请先登录")},getMyAdd:function(){var e=this;this.token?this.$http({url:"/api/user/zixuan_list",headers:{Authorization:this.token}}).then(function(t){if("ok"==t.data.type){var a=t.data.message;e.myAdd=a,e.getData()}}):"zh"==this.$i18n.locale?layer.msg("请先登录"):layer.msg("Please sign in")}}},i={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"allmarket"},[a("div",{staticClass:"container_nav"},[a("p",{staticClass:"tc white ft16"},[e._v(e._s(e.$t("market.exchange")))])]),e._v(" "),a("div",{staticClass:"innerbox"},[a("div",{staticClass:"inner_box"},[a("div",{staticClass:"nav_top_top flex ft14"},e._l(e.typeList,function(t,n){return a("p",{class:["pointer",{select:e.topnow==n}],on:{click:function(t){e.changeType(n)}}},[0==n?a("i",{staticClass:"iconfont icon-pingjiaxingxing",staticStyle:{color:"#596a7a"}}):e._e(),e._v("\n          "+e._s(t.type)+"\n        ")])})),e._v(" "),a("div",{directives:[{name:"show",rawName:"v-show",value:1==e.topnow,expression:"topnow==1"}],staticClass:"nav_top flex"},e._l(e.quoList,function(t,n){return a("p",{class:["pointer",{select:e.current==n}],on:{click:function(a){e.changeLegal(n,t.name)}}},[e._v(e._s(t.name))])})),e._v(" "),a("div",{staticClass:"coinbox ft12 rbom"},[a("div",{staticClass:"flex classfity txtgray"},[e._l(e.classtify,function(t,n){return a("div",{class:["flex alcenter",{jscenter:0!=n}],on:{click:function(a){e.sortData(n,t.key)}}},[a("span",[e._v(e._s(t.text))]),e._v(" "),""!=t.key?a("p",{staticClass:"flex column between"},[a("span",{class:["up",{sort:n===e.sortindex&&e.direction}]}),e._v(" "),a("span",{class:["down",{sort:n===e.sortindex&&!e.direction}]})]):e._e()])}),e._v(" "),a("div")],2),e._v(" "),1==e.topnow?a("ul",e._l(e.marketList,function(t,n){return e.current==n?a("li",e._l(t,function(t,n){return 1==t.is_display?a("div",{staticClass:"flex coinlist alcenter curPer"},[a("p",{staticClass:"flex alcenter"},[t.added?a("span",{staticClass:"iconfont ft14 yellow icon-pingjiaxingxing",on:{click:function(a){e.addDelete("del",t.currency_id,t.legal_id)}}}):a("span",{staticClass:"iconfont ft14 gray icon-pingjiaxingxing",on:{click:function(a){e.addDelete("add",t.currency_id,t.legal_id)}}}),e._v(" "),a("span",{staticClass:"ml5"},[e._v(e._s(t.currency_name))])]),e._v(" "),a("p",[a("span",[e._v(e._s(t.now_price))]),e._v(" "),a("span",{staticStyle:{"font-size":"12px"}},[e._v("≈ "+e._s((t.now_price*e.fiat_convert_cny).toFixed(4))+" CNY")])]),e._v(" "),a("p",{class:["tl","change","green",{red:t.change<0}]},[t.change>0?a("span",[e._v("\n                  +"+e._s(e._f("toFixeds")(t.change||"0.00"))+"%\n                ")]):a("span",[e._v("\n                  "+e._s(e._f("toFixeds")(t.change||"0.00"))+"%\n                ")])]),e._v(" "),a("p",[e._v(e._s(t.high||0))]),e._v(" "),a("p",[e._v(e._s(t.low||0))]),e._v(" "),a("p",[e._v(e._s(t.volume))]),e._v(" "),a("p",[a("span",{staticClass:"deal",on:{click:function(a){e.godeal(n,t.currency_name,t.currency_id,t.legal_id,t.legal_name,t.change,t.lever_share_num,t.high,t.low,t.volume)}}},[e._v(e._s(e.$t("jc.gotd")))])])]):e._e()})):e._e()})):a("ul",e._l(e.marketList,function(t,n){return a("li",e._l(t,function(t,n){return 1==t.is_display&&t.added?a("div",{staticClass:"flex coinlist alcenter curPer"},[a("p",{staticClass:"flex alcenter"},[a("span",{staticClass:"iconfont ft14 yellow icon-pingjiaxingxing",on:{click:function(a){e.addDelete("del",t.currency_id,t.legal_id)}}}),e._v(" "),a("span",{staticClass:"ml5"},[e._v(e._s(t.currency_name)+"/"+e._s(t.legal_name))])]),e._v(" "),a("p",[e._v(e._s(t.now_price))]),e._v(" "),a("p",{class:["tl","change","green",{red:t.change<0}]},[a("span",[e._v(e._s(e._f("toFixeds")(t.change||"0.00"))+"% ")])]),e._v(" "),a("p",[e._v(e._s(t.high||0))]),e._v(" "),a("p",[e._v(e._s(t.low||0))]),e._v(" "),a("p",[e._v(e._s(t.volume))]),e._v(" "),a("p")]):e._e()}))}))])])])])},staticRenderFns:[]};var o=a("VU/8")(n,i,!1,function(e){a("S4Ta")},"data-v-111a2e1c",null);t.default=o.exports}});
//# sourceMappingURL=10.71740ed7bdd70b890d56.js.map