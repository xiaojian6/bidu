(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-mine-real_authentication"],{6882:function(t,i,e){"use strict";e.r(i);var a=e("d6d0"),s=e.n(a);for(var n in a)"default"!==n&&function(t){e.d(i,t,function(){return a[t]})}(n);i["default"]=s.a},"6ebe":function(t,i,e){"use strict";var a=function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},[e("v-uni-view",{staticClass:"flex bgwhite alcenter radius4 pl10 pt10 bdb_blue3"},[e("v-uni-text",[t._v(t._s(t.$t("authentication.name"))+"：")]),e("v-uni-input",{staticClass:"h40 lh40 pr10 tr flex1 input-uni",attrs:{type:"text",placeholder:t.$t("collect.p_name")},model:{value:t.name,callback:function(i){t.name=i},expression:"name"}})],1),e("v-uni-view",{staticClass:"flex bgwhite alcenter radius4 pl10 mt10 bdb_blue3"},[e("v-uni-text",[t._v(t._s(t.$t("collect.cardno"))+"：")]),e("v-uni-input",{staticClass:"h40 lh40 pr10 tr flex1 input-uni",attrs:{type:"text",placeholder:t.$t("collect.p_cardno")},model:{value:t.card_id,callback:function(i){t.card_id=i},expression:"card_id"}})],1),e("v-uni-view",{staticClass:"mt10 plr10"},[t._v(t._s(t.$t("collect.up_card")))]),e("v-uni-view",{staticClass:"flex mt10 mywrap between"},[e("v-uni-view",{staticClass:"w48 ptb5 plr5 bd_dashed radius4 tc mb10",on:{click:function(i){i=t.$handleEvent(i),t.uploadImg(1)}}},[t.hasUp1?e("v-uni-image",{staticClass:"w95",staticStyle:{"max-height":"100px"},attrs:{src:t.img1,mode:"widthFix"}}):e("v-uni-view",{},[e("v-uni-image",{staticClass:"wt80 h80",attrs:{src:t.img}}),e("v-uni-view",{staticClass:"mt10 tc"},[t._v(t._s(t.$t("collect.up_cardz")))])],1)],1),e("v-uni-view",{staticClass:"w48 ptb5 plr5 bd_dashed radius4 tc mb10",on:{click:function(i){i=t.$handleEvent(i),t.uploadImg(2)}}},[t.hasUp2?e("v-uni-image",{staticClass:"w95 ",staticStyle:{"max-height":"100px"},attrs:{src:t.img2,mode:"widthFix"}}):e("v-uni-view",{},[e("v-uni-image",{staticClass:"wt80 h80",attrs:{src:t.img}}),e("v-uni-view",{staticClass:"mt10 tc"},[t._v(t._s(t.$t("collect.up_cardf")))])],1)],1),e("v-uni-view",{staticClass:"w48 ptb5 plr5 bd_dashed radius4 tc mb10",on:{click:function(i){i=t.$handleEvent(i),t.uploadImg(3)}}},[t.hasUp3?e("v-uni-image",{staticClass:"w95",staticStyle:{"max-height":"100px"},attrs:{src:t.img3,mode:"widthFix"}}):e("v-uni-view",{},[e("v-uni-image",{staticClass:"wt80 h80",attrs:{src:t.img}}),e("v-uni-view",{staticClass:"mt10 tc"},[t._v(t._s(t.$t("collect.up_cardhand")))])],1)],1)],1),e("v-uni-view",{staticClass:"mt30 h40 lh40 tc white bgBlue radius28 ft14 w90 mauto",on:{click:function(i){i=t.$handleEvent(i),t.confirm(i)}}},[t._v(t._s(t.$t("login.e_confrim")))])],1)},s=[];e.d(i,"a",function(){return a}),e.d(i,"b",function(){return s})},b88a:function(t,i,e){"use strict";e.r(i);var a=e("6ebe"),s=e("6882");for(var n in s)"default"!==n&&function(t){e.d(i,t,function(){return s[t]})}(n);var c=e("2877"),l=Object(c["a"])(s["default"],a["a"],a["b"],!1,null,"4e01d65e",null);i["default"]=l.exports},d6d0:function(t,i,e){"use strict";var a=e("288e");Object.defineProperty(i,"__esModule",{value:!0}),i.default=void 0,e("7f7f");var s=a(e("cebc")),n=e("2f62"),c=e("2fc1"),l={data:function(){return{name:"",card_id:"",img:"/static/image/addImg.png",hasUp1:!1,hasUp2:!1,hasUp3:!1,img1:"",img2:"",img3:""}},computed:(0,s.default)({},(0,n.mapState)({theme:function(t){return t.theme}})),onLoad:function(){this.$utils.setTheme(this.theme),uni.setNavigationBarTitle({title:this.$t("authentication").renzheng})},methods:{uploadImg:function(t){console.log(c.domain);var i=this;uni.chooseImage({count:1,sizeType:["compressed"],success:function(e){var a=e.tempFilePaths;uni.uploadFile({url:"/api/upload",filePath:a[0],name:"file",formData:{user:"test"},success:function(e){console.log(typeof e.data);var a=JSON.parse(e.data);if(console.log(a),"ok"==a.type){var s="img"+t,n="hasUp"+t;console.log(t,s),i[s]=a.message,i[n]=!0}}})}})},confirm:function(){var t=this;return this.name?this.card_id?this.img1?this.img2?this.img3?void this.$utils.initDataToken({url:"user/real_name",type:"POST",data:{name:this.name,card_id:this.card_id,front_pic:this.img1,reverse_pic:this.img2,hand_pic:this.img3}},function(i,e){t.$utils.showLayer(e),setTimeout(function(){uni.navigateBack({delta:1})},1500)}):this.$utils.showLayer(this.$t("collect.up_cardhand")):this.$utils.showLayer(this.$t("collect.up_cardf")):this.$utils.showLayer(this.$t("collect.up_cardz")):this.$utils.showLayer(this.$t("collect.p_cardno")):this.$utils.showLayer(this.$t("collect.p_name"))}}};i.default=l}}]);