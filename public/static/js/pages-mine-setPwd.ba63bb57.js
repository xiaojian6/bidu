(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-mine-setPwd"],{1350:function(t,e,i){"use strict";i.r(e);var s=i("f541"),n=i.n(s);for(var a in s)"default"!==a&&function(t){i.d(e,t,function(){return s[t]})}(a);e["default"]=n.a},"152b":function(t,e,i){"use strict";var s=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},[i("v-uni-view",{staticClass:"pt20 plr20"},[i("v-uni-view",{staticClass:"mb10 mt30"},[i("v-uni-view",{staticClass:"flex bgwhite alcenter bdb_myblue "},[i("v-uni-input",{staticClass:"h40 lh40 flex1 input-uni",attrs:{type:"text",password:"",placeholder:t.$t("login").p_pwd},on:{input:function(e){e=t.$handleEvent(e),t.passwordInput1(e)}},model:{value:t.password,callback:function(e){t.password=e},expression:"password"}}),i("v-uni-image",{staticClass:"wt15 h15 ml10",attrs:{src:"/static/image/password.png"}})],1),t.verifyPwd1?i("v-uni-view",{staticClass:"ft10 pt5 chengse"},[t._v(t._s(t.$t("login").p_len))]):t._e()],1),i("v-uni-view",{staticClass:"mb10"},[i("v-uni-view",{staticClass:"flex bgwhite alcenter bdb_myblue "},[i("v-uni-input",{staticClass:"h40 lh40 flex1 input-uni",attrs:{type:"text",password:"",placeholder:t.$t("login").p_confirmPwd},on:{input:function(e){e=t.$handleEvent(e),t.passwordInput2(e)}},model:{value:t.re_password,callback:function(e){t.re_password=e},expression:"re_password"}}),i("v-uni-image",{staticClass:"wt15 h15 ml10",attrs:{src:"/static/image/password.png"}})],1),t.verifyPwd2?i("v-uni-view",{staticClass:"ft10 pt5 chengse"},[t._v(t._s(t.$t("login").p_notsame))]):t._e()],1),i("v-uni-view",{staticClass:"flex bgwhite alcenter bdb_myblue "},[i("v-uni-input",{staticClass:"h40 lh40 flex1 input-uni",attrs:{type:"text",placeholder:t.$t("login").p_invitecode},model:{value:t.invite_code,callback:function(e){t.invite_code=e},expression:"invite_code"}})],1),i("v-uni-view",{staticClass:"mt20 flex alcenter"},[i("v-uni-view",{staticClass:" flex alcenter"},[i("v-uni-checkbox",{staticStyle:{transform:"scale(0.7)",color:"'#1881d2'"},attrs:{value:"cb",checked:t.isAgree},on:{click:function(e){e=t.$handleEvent(e),t.tapChecked(e)}}}),i("v-uni-text",[t._v(t._s(t.$t("login").p_agree))])],1),i("v-uni-view",{staticClass:"ml10 blue2"},[t._v("《"+t._s(t.$t("login").p_private)+"》")])],1),i("v-uni-view",{staticClass:"mt45 bgBlue radius4 ptb10 white ft14 tc mb10",on:{click:function(e){e=t.$handleEvent(e),t.register(e)}}},[t._v(t._s(t.$t("login").p_set))])],1)],1)},n=[];i.d(e,"a",function(){return s}),i.d(e,"b",function(){return n})},"25eb4":function(t,e,i){"use strict";i.r(e);var s=i("152b"),n=i("1350");for(var a in n)"default"!==a&&function(t){i.d(e,t,function(){return n[t]})}(a);var r=i("2877"),o=Object(r["a"])(n["default"],s["a"],s["b"],!1,null,"4b46a627",null);e["default"]=o.exports},f541:function(t,e,i){"use strict";var s=i("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=s(i("cebc")),a=i("2f62"),r=i("2fc1"),o={data:function(){return{user_string:"",password:"",re_password:"",code:"",area_code:"",isAgree:!1,verifyPwd1:!1,verifyPwd2:!1,lang:"",disable:!1,load:!1,invite_code:"",downloadUrl:r.downloadUrl,codeText:this.$t("login.r_send")}},computed:(0,n.default)({},(0,a.mapState)({theme:function(t){return t.theme}})),onLoad:function(t){this.user_string=t.user_string,this.code=t.code,this.is_mobile=t.is_mobile,this.area_code=t.areaCode,this.invite_code=t.invite_code,t.invitecode&&"undefined"!=t.invitecode&&(this.invite_code=t.invitecode||""),this.lang=uni.getStorageSync("lang"),this.$utils.setTheme(this.theme),uni.setNavigationBarTitle({title:this.$t("login").p_setPwd})},methods:{send:function(){var t=this;this.disable=!0;var e=10,i=setInterval(function(){e--,e<10&&(e="0"+e),t.codeText=e+"s",e<=0&&(clearInterval(i),t.disable=!1,t.load=!1,t.codeText=t.$t("login").r_send)},1e3)},passwordInput1:function(t){t.target.value.length<6?this.verifyPwd1=!0:this.verifyPwd1=!1},passwordInput2:function(t){t.target.value!=this.password?this.verifyPwd2=!0:this.verifyPwd2=!1},tapChecked:function(){this.isAgree=!this.isAgree},register:function(){var t=this;if(!this.password)return this.$utils.showLayer(this.$t("login").p_pwd);if(this.password.length<6)return this.$utils.showLayer(this.$t("login").p_simple);if(this.password!=this.re_password)return this.$utils.showLayer(this.$t("login").p_inputagain);if(!this.isAgree)return this.$utils.showLayer(this.$t("login").p_first);var e={user_string:this.user_string,password:this.password,code:this.code,re_password:this.re_password,lang:this.lang,extension_code:this.invite_code};0==this.is_mobile?(e.type="mobile",e.country_code=this.area_code):e.type="email",this.$utils.initData({url:"user/register",data:e,type:"POST"},function(e,i){t.invite_code&&(location.href=t.downloadUrl),t.$utils.showLayer(i),setTimeout(function(){uni.reLaunch({url:"login"})})})}}};e.default=o}}]);