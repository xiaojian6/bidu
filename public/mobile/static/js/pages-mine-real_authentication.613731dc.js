(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-mine-real_authentication"],{"148d":function(t,i,a){"use strict";a.r(i);var e=a("538e"),s=a("bff2");for(var n in s)"default"!==n&&function(t){a.d(i,t,(function(){return s[t]}))}(n);var c,u=a("f0c5"),l=Object(u["a"])(s["default"],e["b"],e["c"],!1,null,"0e66c6f9",null,!1,e["a"],c);i["default"]=l.exports},"538e":function(t,i,a){"use strict";var e,s=function(){var t=this,i=t.$createElement,a=t._self._c||i;return a("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},[a("v-uni-view",{staticClass:"flex bgwhite alcenter radius4 pl10 pt10 bdb_blue3"},[a("v-uni-text",[t._v(t._s(t.$t("authentication.name"))+"：")]),a("v-uni-input",{staticClass:"h40 lh40 pr10 tr flex1 input-uni",attrs:{type:"text",placeholder:t.$t("collect.p_name")},model:{value:t.name,callback:function(i){t.name=i},expression:"name"}})],1),a("v-uni-view",{staticClass:"flex bgwhite alcenter radius4 pl10 mt10 bdb_blue3"},[a("v-uni-text",[t._v(t._s(t.$t("collect.cardno"))+"：")]),a("v-uni-input",{staticClass:"h40 lh40 pr10 tr flex1 input-uni",attrs:{type:"text",placeholder:t.$t("collect.p_cardno")},model:{value:t.card_id,callback:function(i){t.card_id=i},expression:"card_id"}})],1),a("v-uni-view",{staticClass:"mt10 plr10"},[t._v(t._s(t.$t("collect.up_card")))]),a("v-uni-view",{staticClass:"flex mt10 mywrap between"},[a("v-uni-view",{staticClass:"w48 ptb5 plr5 bd_dashed radius4 tc mb10",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.uploadImg(1)}}},[t.hasUp1?a("v-uni-image",{staticClass:"w95",staticStyle:{"max-height":"100px"},attrs:{src:t.img1,mode:"widthFix"}}):a("v-uni-view",{},[a("v-uni-image",{staticClass:"wt80 h80",attrs:{src:t.img}}),a("v-uni-view",{staticClass:"mt10 tc"},[t._v(t._s(t.$t("collect.up_cardz")))])],1)],1),a("v-uni-view",{staticClass:"w48 ptb5 plr5 bd_dashed radius4 tc mb10",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.uploadImg(2)}}},[t.hasUp2?a("v-uni-image",{staticClass:"w95 ",staticStyle:{"max-height":"100px"},attrs:{src:t.img2,mode:"widthFix"}}):a("v-uni-view",{},[a("v-uni-image",{staticClass:"wt80 h80",attrs:{src:t.img}}),a("v-uni-view",{staticClass:"mt10 tc"},[t._v(t._s(t.$t("collect.up_cardf")))])],1)],1),a("v-uni-view",{staticClass:"w48 ptb5 plr5 bd_dashed radius4 tc mb10",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.uploadImg(3)}}},[t.hasUp3?a("v-uni-image",{staticClass:"w95",staticStyle:{"max-height":"100px"},attrs:{src:t.img3,mode:"widthFix"}}):a("v-uni-view",{},[a("v-uni-image",{staticClass:"wt80 h80",attrs:{src:t.img}}),a("v-uni-view",{staticClass:"mt10 tc"},[t._v(t._s(t.$t("collect.up_cardhand")))])],1)],1)],1),a("v-uni-view",{staticClass:"mt30 h40 lh40 tc white bgBlue radius28 ft14 w90 mauto",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.confirm.apply(void 0,arguments)}}},[t._v(t._s(t.$t("login.e_confrim")))])],1)},n=[];a.d(i,"b",(function(){return s})),a.d(i,"c",(function(){return n})),a.d(i,"a",(function(){return e}))},6102:function(t,i,a){"use strict";(function(t){var e=a("ee27");Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0;var s=e(a("f3f3")),n=a("2f62"),c=a("ee03"),u={data:function(){return{name:"",card_id:"",img:"/static/image/addImg.png",hasUp1:!1,hasUp2:!1,hasUp3:!1,img1:"",img2:"",img3:""}},computed:(0,s.default)({},(0,n.mapState)({theme:function(t){return t.theme}})),onLoad:function(){this.$utils.setTheme(this.theme),uni.setNavigationBarTitle({title:this.$t("authentication").renzheng})},methods:{uploadImg:function(i){t("log",c.domain," at pages\\mine\\real_authentication.vue:70");var a=this;uni.chooseImage({count:1,sizeType:["compressed"],success:function(e){var s=e.tempFilePaths;uni.uploadFile({url:"/api/upload",filePath:s[0],name:"file",formData:{user:"test"},success:function(e){t("log",typeof e.data," at pages\\mine\\real_authentication.vue:88");var s=JSON.parse(e.data);if(t("log",s," at pages\\mine\\real_authentication.vue:90"),"ok"==s.type){var n="img"+i,c="hasUp"+i;t("log",i,n," at pages\\mine\\real_authentication.vue:94"),a[n]=s.message,a[c]=!0}}})}})},confirm:function(){var t=this;return this.name?this.card_id?this.img1?this.img2?this.img3?void this.$utils.initDataToken({url:"user/real_name",type:"POST",data:{name:this.name,card_id:this.card_id,front_pic:this.img1,reverse_pic:this.img2,hand_pic:this.img3}},(function(i,a){t.$utils.showLayer(a),setTimeout((function(){uni.navigateBack({delta:1})}),1500)})):this.$utils.showLayer(this.$t("collect.up_cardhand")):this.$utils.showLayer(this.$t("collect.up_cardf")):this.$utils.showLayer(this.$t("collect.up_cardz")):this.$utils.showLayer(this.$t("collect.p_cardno")):this.$utils.showLayer(this.$t("collect.p_name"))}}};i.default=u}).call(this,a("0de9")["log"])},bff2:function(t,i,a){"use strict";a.r(i);var e=a("6102"),s=a.n(e);for(var n in e)"default"!==n&&function(t){a.d(i,t,(function(){return e[t]}))}(n);i["default"]=s.a}}]);