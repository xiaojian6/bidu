(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-mine-mine"],{"04d3":function(t,e,i){"use strict";var a=i("8efb"),s=i.n(a);s.a},"21d3":function(t,e,i){"use strict";i.r(e);var a=i("42dd"),s=i.n(a);for(var n in a)"default"!==n&&function(t){i.d(e,t,function(){return a[t]})}(n);e["default"]=s.a},"42dd":function(t,e,i){"use strict";var a=i("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0,i("7f7f");var s=a(i("cebc"));i("c5f6");i("504a");var n=i("2f62"),o=a(i("c309")),r={components:{uniPopup:o.default},data:function(){return{isFinger:"0",token:"",info:"",fingerBool:0,head:"../../static/image/head.png"}},filters:{toFixed2:function(t,e){return t=Number(t),t.toFixed(2)}},computed:(0,s.default)({},(0,n.mapState)({theme:function(t){return t.theme}})),onLoad:function(){uni.getStorageSync("token")&&!uni.getStorageSync("isFinger")&&(this.isFinger=uni.getStorageSync("fingerBool"),this.isFinger&&uni.hideTabBar())},onShow:function(){console.log(uni.getStorageSync("fingerBool")),this.fingerBool=uni.getStorageSync("fingerBool"),this.changeFooter(),this.getUserInfo(),this.token=uni.getStorageSync("token"),this.$utils.setTheme(this.theme)},methods:{fingerFuc:function(){var t=this;"Android"==plus.os.name&&(this.type="fingerAlert"),plus.fingerprint.isSupport()?(plus.fingerprint.isEnrolledFingerprints()||plus.nativeUI.toast("当前设备未录入指纹！"),plus.fingerprint.authenticate(function(e){"Android"==plus.os.name&&(t.type=""),uni.setStorageSync("isFinger",!0),t.isFinger=0,uni.showTabBar()},function(e){plus.nativeUI.toast("指纹匹配失败"),"Android"==plus.os.name&&(t.type="",plus.fingerprint.cancel())},{message:"授权登录"})):plus.nativeUI.toast("此设备不支持指纹设置！")},fingerCancel:function(){this.type="",plus.fingerprint.cancel()},changeFooter:function(){uni.setTabBarItem({index:0,text:this.$t("market.home")}),uni.setTabBarItem({index:1,text:this.$t("market.market")}),uni.setTabBarItem({index:2,text:this.$t("trade.bibi")}),uni.setTabBarItem({index:3,text:this.$t("assets.lever")}),uni.setTabBarItem({index:4,text:this.$t("assets.assets")})},switchChangeFace:function(){var t=this;console.log(t.faceBool),0==t.faceBool?t.faceBool=1:t.faceBool=0},switchChangeFinger:function(){var t=this;console.log(t.fingerBool),0==t.fingerBool?t.fingerBool=1:t.fingerBool=0,plus.fingerprint.isSupport()?(plus.fingerprint.isEnrolledFingerprints()||(plus.nativeUI.toast("当前设备未录入指纹！"),0==t.fingerBool?t.fingerBool=1:t.fingerBool=0),"Android"==plus.os.name&&plus.nativeUI.showWaiting("指纹匹配中..."),plus.fingerprint.authenticate(function(e){"Android"==plus.os.name&&plus.nativeUI.closeWaiting(),plus.nativeUI.toast("指纹匹配成功"),console.log(t.fingerBool),uni.setStorageSync("fingerBool",t.fingerBool)},function(e){"Android"==plus.os.name&&(plus.nativeUI.closeWaiting(),plus.fingerprint.cancel()),plus.nativeUI.toast("指纹匹配失败"),console.log(t.fingerBool),0==t.fingerBool?t.fingerBool=1:t.fingerBool=0},{message:"指纹授权登录"})):(plus.nativeUI.toast("此设备不支持指纹设置！"),0==t.fingerBool?t.fingerBool=1:t.fingerBool=0)},switchChange:function(t){console.log(t.target.value);var e="light"==this.theme?"dark":"light";uni.setStorageSync("theme",e),this.$store.dispatch("changeTheme",e),this.$utils.setTheme(e),"dark"==e?uni.setTabBarStyle({color:"#a2a6a5",selectedColor:"#1881d2",backgroundColor:"#16263e",borderStyle:"black"}):uni.setTabBarStyle({color:"#8b97a0",selectedColor:"#238ee1",backgroundColor:"#ffffff",borderStyle:"black"})},uploadImg:function(){var t=this;uni.chooseImage({count:1,sizeType:["compressed"],success:function(e){var i=e.tempFilePaths;uni.uploadFile({url:"/api/upload",filePath:i[0],name:"file",formData:{user:"test"},success:function(e){console.log(typeof e.data);var i=JSON.parse(e.data);if("ok"==i.type){var a=i.message;console.log(a),t.uphead(a)}}})}})},uphead:function(t){var e=this;this.$utils.initDataToken({url:"user/head_pic",data:{head_portrait:t},type:"post"},function(i,a){e.head=t})},getUserInfo:function(){var t=this;this.$utils.initDataToken({url:"user/info",data:{},type:"get"},function(e,i){uni.stopPullDownRefresh(),t.info=e,e.head_portrait&&(t.head=e.head_portrait)})},logout:function(){var t=this;this.$utils.initDataToken({url:"user/logout",data:{},type:"post"},function(e,i){uni.removeStorageSync("token"),t.$utils.showLayer(i),uni.redirectTo({url:"/pages/mine/login"})})}}};e.default=r},4690:function(t,e,i){"use strict";i.r(e);var a=i("c2fd"),s=i.n(a);for(var n in a)"default"!==n&&function(t){i.d(e,t,function(){return a[t]})}(n);e["default"]=s.a},"64cc":function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}],staticClass:"uni-mask",style:{top:t.offsetTop+"px"},on:{click:function(e){e=t.$handleEvent(e),t.hide(e)},touchmove:function(e){e.stopPropagation(),e.preventDefault(),e=t.$handleEvent(e),t.moveHandle(e)}}}),i("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}],staticClass:"uni-popup",class:["uni-popup-"+t.position,"uni-popup-"+t.mode]},[t._v(t._s(t.msg)),t._t("default"),"middle"===t.position&&"insert"===t.mode?i("v-uni-view",{staticClass:" uni-icon uni-icon-close",class:{"uni-close-bottom":"bottom"===t.buttonMode,"uni-close-right":"right"===t.buttonMode},on:{click:function(e){e=t.$handleEvent(e),t.closeMask(e)}}}):t._e()],2)],1)},s=[];i.d(e,"a",function(){return a}),i.d(e,"b",function(){return s})},"72c8":function(t,e,i){"use strict";var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{class:["vh100",{"dark bgpartDark":"dark"==t.theme}]},[i("v-uni-view",{staticClass:"status_bar"},[i("v-uni-view",{staticClass:"top_view"})],1),i("v-uni-view",{staticClass:"pt30 pb20 plr20"},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-view",{},[i("v-uni-image",{staticClass:"h50 wt50",staticStyle:{"border-radius":"50%"},attrs:{src:t.head,mode:""},on:{click:function(e){e=t.$handleEvent(e),t.uploadImg()}}})],1),t.token?t._e():i("v-uni-navigator",{staticClass:"flex alcenter between pl20",attrs:{url:"/pages/mine/login","open-type":"navigate"}},[i("v-uni-view",[i("v-uni-view",{staticClass:"ft20 mb5"},[t._v(t._s(t.$t("home.p_login")))]),i("v-uni-view",{staticClass:"ft14"},[t._v(t._s(t.$t("home.welcome")))])],1),i("v-uni-view",[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/mores.png"}})],1)],1),i("v-uni-view",{staticClass:"flex alcenter between pl20"},[i("v-uni-view",[i("v-uni-view",{staticClass:"ft20 mb5"},[t._v(t._s(t.info.account))]),i("v-uni-view",{staticClass:"ft14"},[t._v("UID:"+t._s(t.info.uid))])],1)],1)],1)],1),i("v-uni-view",{staticClass:"pb50"},[i("v-uni-view",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3"},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/shops.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("home.dark")))])],1),i("v-uni-switch",{attrs:{checked:"dark"==t.theme},on:{change:function(e){e=t.$handleEvent(e),t.switchChange(e)}}})],1),i("v-uni-view",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3"},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/shops.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("home.fingerprint")))])],1),i("v-uni-switch",{attrs:{checked:1==t.fingerBool},on:{change:function(e){e=t.$handleEvent(e),t.switchChangeFinger(e)}}})],1),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/assets/assets"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/footer/assets1.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("assets.assets")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/mine/userCenter"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/personal.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("authentication.person")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),t.info.is_seller?i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/legal/store"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/shops.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("home.myshop")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1):t._e(),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb27",attrs:{url:"/pages/mine/invite?code="+t.info.extension_code}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/share.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("home.myshare")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/mine/security"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/sucerty.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("login.security")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/assets/bindMentionAddress"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/address.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("bind.bindAddr")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/mine/collect_money"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/receivables.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("collect.method")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/home/news"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/personal.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("home.news")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/mine/lang"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/exchange.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("home.lang")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),i("v-uni-navigator",{staticClass:"flex alcenter ptb15 between plr20 ft14 bdb_blue3",attrs:{url:"/pages/mine/about"}},[i("v-uni-view",{staticClass:"flex alcenter"},[i("v-uni-image",{staticClass:"wt20 h20",attrs:{src:"/static/image/account_about_image.png"}}),i("v-uni-text",{staticClass:"ml10"},[t._v(t._s(t.$t("about.abt")))])],1),i("v-uni-image",{staticClass:"wt15 h15",attrs:{src:"/static/image/mores.png"}})],1),i("v-uni-view",{staticClass:"mt30 plr20"},[i("v-uni-button",{staticClass:"ft14",attrs:{type:"primary",size:"default"},on:{click:function(e){e=t.$handleEvent(e),t.logout(e)}}},[t._v(t._s(t.$t("home.logout")))])],1)],1),1==t.isFinger?i("v-uni-view",{staticClass:"finger_bg"},[i("v-uni-view",{staticClass:"fingerBut",on:{click:function(e){e=t.$handleEvent(e),t.fingerFuc(e)}}},[i("v-uni-image",{attrs:{src:"../../static/image/finger.png",mode:""}}),i("v-uni-view",{},[t._v("点击唤醒验证")])],1),i("uni-popup",{attrs:{show:"fingerAlert"===t.type,position:"middle",mode:"fixed"}},[i("v-uni-view",{staticClass:"uni-center center-box"},[i("v-uni-view",{staticClass:"msg_tit"},[t._v("请验证指纹")]),i("v-uni-view",{staticStyle:{width:"100%","text-align":"center",padding:"25upx 0 0 0"}},[i("v-uni-image",{staticClass:"msg_img",attrs:{src:"../../static/image/fingerprint.png",mode:""}})],1),i("v-uni-view",{staticClass:"msg_content",staticStyle:{width:"100%","text-align":"center"}},[t._v("使用指纹查看重要信息")]),i("v-uni-view",{staticClass:"msg_but"},[i("v-uni-button",{attrs:{type:"default"},on:{click:function(e){e=t.$handleEvent(e),t.fingerCancel(e)}}},[t._v("取消")])],1)],1)],1)],1):t._e()],1)},s=[];i.d(e,"a",function(){return a}),i.d(e,"b",function(){return s})},"75d1":function(t,e,i){"use strict";i.r(e);var a=i("72c8"),s=i("21d3");for(var n in s)"default"!==n&&function(t){i.d(e,t,function(){return s[t]})}(n);var o=i("2877"),r=Object(o["a"])(s["default"],a["a"],a["b"],!1,null,"d0f8e19e",null);e["default"]=r.exports},"8efb":function(t,e,i){var a=i("a155");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var s=i("4f06").default;s("9812dcd0",a,!0,{sourceMap:!1,shadowMode:!1})},a155:function(t,e,i){e=t.exports=i("2350")(!1),e.push([t.i,'.uni-mask[data-v-52f9f47a]{position:fixed;z-index:101;top:0;right:0;bottom:0;left:0;background-color:rgba(0,0,0,.3)}.uni-popup[data-v-52f9f47a]{position:fixed;z-index:101;background-color:#e1e1ea}.uni-popup-middle[data-v-52f9f47a]{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;-ms-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;top:50%;left:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}.uni-popup-middle.uni-popup-insert[data-v-52f9f47a]{min-width:%?380?%;min-height:%?380?%;max-width:100%;max-height:80%;-webkit-transform:translate(-50%,-65%);-ms-transform:translate(-50%,-65%);transform:translate(-50%,-65%);background:none;-webkit-box-shadow:none;box-shadow:none}.uni-popup-middle.uni-popup-fixed[data-v-52f9f47a]{border-radius:%?10?%;padding:%?30?%}.uni-close-bottom[data-v-52f9f47a],.uni-close-right[data-v-52f9f47a]{position:absolute;bottom:%?-180?%;text-align:center;border-radius:50%;color:#f5f5f5;font-size:%?60?%;font-weight:700;opacity:.8;z-index:-1}.uni-close-right[data-v-52f9f47a]{right:%?-60?%;top:%?-80?%}.uni-close-bottom[data-v-52f9f47a]:after{content:"";position:absolute;width:0;border:1px #f5f5f5 solid;top:%?-200?%;bottom:%?56?%;left:50%;-webkit-transform:translate(-50%);-ms-transform:translate(-50%);transform:translate(-50%);opacity:.8}.uni-popup-top[data-v-52f9f47a]{top:0;left:0;width:100%;height:%?100?%;line-height:%?100?%;text-align:center}.uni-popup-bottom[data-v-52f9f47a]{left:0;bottom:0;width:100%;min-height:%?100?%;line-height:%?100?%;text-align:center}',""])},c2fd:function(t,e,i){"use strict";Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={name:"uni-popup",props:{show:{type:Boolean,default:!1},position:{type:String,default:"middle"},mode:{type:String,default:"insert"},msg:{type:String,default:""},h5Top:{type:Boolean,default:!1},buttonMode:{type:String,default:"bottom"}},data:function(){var t=0;return t=this.h5Top?0:44,{offsetTop:t}},watch:{h5Top:function(t){console.log(t),this.offsetTop=t?44:0}},methods:{hide:function(){"insert"===this.mode&&"middle"===this.position||this.$emit("hidePopup")},closeMask:function(){"insert"===this.mode&&this.$emit("hidePopup")},moveHandle:function(){}}};e.default=a},c309:function(t,e,i){"use strict";i.r(e);var a=i("64cc"),s=i("4690");for(var n in s)"default"!==n&&function(t){i.d(e,t,function(){return s[t]})}(n);i("04d3");var o=i("2877"),r=Object(o["a"])(s["default"],a["a"],a["b"],!1,null,"52f9f47a",null);e["default"]=r.exports}}]);