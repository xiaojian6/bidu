(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-home-news"],{"39eb":function(t,a,e){"use strict";var i,n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{staticClass:"uni-load-more"},[e("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:"loading"===t.status&&t.showIcon,expression:"status === 'loading' && showIcon"}],staticClass:"uni-load-more__img"},[e("v-uni-view",{staticClass:"load1 load"},[e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}})],1),e("v-uni-view",{staticClass:"load2 load"},[e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}})],1),e("v-uni-view",{staticClass:"load3 load"},[e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}}),e("v-uni-view",{staticClass:"uni-load-view_wrapper",style:{background:t.color}})],1)],1),e("v-uni-text",{staticClass:"uni-load-more__text",style:{color:t.color}},[t._v(t._s("more"===t.status?t.contentText.contentdown:"loading"===t.status?t.contentText.contentrefresh:t.contentText.contentnomore))])],1)},o=[];e.d(a,"b",(function(){return n})),e.d(a,"c",(function(){return o})),e.d(a,"a",(function(){return i}))},"3c77":function(t,a,e){"use strict";e.r(a);var i=e("80ce"),n=e("47ea");for(var o in n)"default"!==o&&function(t){e.d(a,t,(function(){return n[t]}))}(o);var r,d=e("f0c5"),l=Object(d["a"])(n["default"],i["b"],i["c"],!1,null,"401f95d2",null,!1,i["a"],r);a["default"]=l.exports},"47ea":function(t,a,e){"use strict";e.r(a);var i=e("a227"),n=e.n(i);for(var o in i)"default"!==o&&function(t){e.d(a,t,(function(){return i[t]}))}(o);a["default"]=n.a},"4e16":function(t,a,e){var i=e("e7b7");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=e("4f06").default;n("04980985",i,!0,{sourceMap:!1,shadowMode:!1})},"80ce":function(t,a,e){"use strict";var i,n=function(){var t=this,a=t.$createElement,e=t._self._c||a;return e("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},[e("v-uni-view",{staticClass:"plr10 ptb10"},[t._l(t.list,(function(a,i){return e("v-uni-navigator",{key:i,staticClass:" plr8 bdb_blue3 ptb20",attrs:{url:"/pages/home/newsDetail?id="+a.id}},[e("v-uni-view",{staticClass:"ft14 color1 ellipsis"},[t._v(t._s(a.title))])],1)})),t.show?e("v-uni-view",{staticClass:"tc mtb20 blue4 ft12"},[t._v(t._s(t.more))]):t._e()],2)],1)},o=[];e.d(a,"b",(function(){return n})),e.d(a,"c",(function(){return o})),e.d(a,"a",(function(){return i}))},a227:function(t,a,e){"use strict";var i=e("ee27");e("99af"),Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var n=i(e("f3f3")),o=e("2f62"),r=i(e("c313")),d={data:function(){return{list:[],page:1,more:this.$t("home").up,show:!0}},components:{uniLoadMore:r.default},computed:(0,n.default)({},(0,o.mapState)({theme:function(t){return t.theme}})),onLoad:function(){uni.setNavigationBarTitle({title:this.$t("home").news}),this.$utils.setTheme(this.theme),this.getList()},methods:{getList:function(){var t=this;uni.showLoading(),this.$utils.initData({url:"news/list",data:{page:this.page},type:"post"},(function(a,e){uni.hideLoading(),1==t.page&&a.list.length<=a.count&&(t.show=!1),t.list=t.list.concat(a.list),a.list.length>0?(t.more=t.$t("home").up,t.show=!0):t.more=t.$t("home").nomore}))}},onReachBottom:function(){this.status="loading",this.page++,this.getList()}};a.default=d},aef2:function(t,a,e){"use strict";Object.defineProperty(a,"__esModule",{value:!0}),a.default=void 0;var i={name:"UniLoadMore",props:{status:{type:String,default:"more"},showIcon:{type:Boolean,default:!0},color:{type:String,default:"#777777"},contentText:{type:Object,default:function(){return{contentdown:"上拉显示更多",contentrefresh:"正在加载...",contentnomore:"没有更多数据了"}}}},data:function(){return{}}};a.default=i},c313:function(t,a,e){"use strict";e.r(a);var i=e("39eb"),n=e("e566");for(var o in n)"default"!==o&&function(t){e.d(a,t,(function(){return n[t]}))}(o);e("ffc5");var r,d=e("f0c5"),l=Object(d["a"])(n["default"],i["b"],i["c"],!1,null,"b1d75f28",null,!1,i["a"],r);a["default"]=l.exports},e566:function(t,a,e){"use strict";e.r(a);var i=e("aef2"),n=e.n(i);for(var o in i)"default"!==o&&function(t){e.d(a,t,(function(){return i[t]}))}(o);a["default"]=n.a},e7b7:function(t,a,e){var i=e("24fb");a=i(!1),a.push([t.i,'@charset "UTF-8";\r\n/**\r\n * 这里是uni-app内置的常用样式变量\r\n *\r\n * uni-app 官方扩展插件及插件市场（https://ext.dcloud.net.cn）上很多三方插件均使用了这些样式变量\r\n * 如果你是插件开发者，建议你使用scss预处理，并在插件代码中直接使用这些变量（无需 import 这个文件），方便用户通过搭积木的方式开发整体风格一致的App\r\n *\r\n */\r\n/**\r\n * 如果你是App开发者（插件使用者），你可以通过修改这些变量来定制自己的插件主题，实现自定义主题功能\r\n *\r\n * 如果你的项目同样使用了scss预处理，你也可以直接在你的 scss 代码中使用如下变量，同时无需 import 这个文件\r\n */\r\n/* 颜色变量 */\r\n/* 行为相关颜色 */\r\n/* 文字基本颜色 */\r\n/* 背景颜色 */\r\n/* 边框颜色 */\r\n/* 尺寸变量 */\r\n/* 文字尺寸 */\r\n/* 图片尺寸 */\r\n/* Border Radius */\r\n/* 水平间距 */\r\n/* 垂直间距 */\r\n/* 透明度 */\r\n/* 文章场景相关 */.uni-load-more[data-v-b1d75f28]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;height:%?80?%;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.uni-load-more__text[data-v-b1d75f28]{font-size:%?28?%;color:#999}.uni-load-more__img[data-v-b1d75f28]{height:24px;width:24px;margin-right:10px}.uni-load-more__img > .load[data-v-b1d75f28]{position:absolute}.uni-load-more__img > .load .uni-load-view_wrapper[data-v-b1d75f28]{width:6px;height:2px;border-top-left-radius:1px;border-bottom-left-radius:1px;background:#999;position:absolute;opacity:.2;-webkit-transform-origin:50%;transform-origin:50%;-webkit-animation:load-data-v-b1d75f28 1.56s ease infinite;animation:load-data-v-b1d75f28 1.56s ease infinite}.uni-load-more__img > .load .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(1){-webkit-transform:rotate(90deg);transform:rotate(90deg);top:2px;left:9px}.uni-load-more__img > .load .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(2){-webkit-transform:rotate(180deg);transform:rotate(180deg);top:11px;right:0}.uni-load-more__img > .load .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(3){-webkit-transform:rotate(270deg);transform:rotate(270deg);bottom:2px;left:9px}.uni-load-more__img > .load .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(4){top:11px;left:0}.load1[data-v-b1d75f28],\r\n.load2[data-v-b1d75f28],\r\n.load3[data-v-b1d75f28]{height:24px;width:24px}.load2[data-v-b1d75f28]{-webkit-transform:rotate(30deg);transform:rotate(30deg)}.load3[data-v-b1d75f28]{-webkit-transform:rotate(60deg);transform:rotate(60deg)}.load1 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(1){-webkit-animation-delay:0s;animation-delay:0s}.load2 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(1){-webkit-animation-delay:.13s;animation-delay:.13s}.load3 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(1){-webkit-animation-delay:.26s;animation-delay:.26s}.load1 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(2){-webkit-animation-delay:.39s;animation-delay:.39s}.load2 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(2){-webkit-animation-delay:.52s;animation-delay:.52s}.load3 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(2){-webkit-animation-delay:.65s;animation-delay:.65s}.load1 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(3){-webkit-animation-delay:.78s;animation-delay:.78s}.load2 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(3){-webkit-animation-delay:.91s;animation-delay:.91s}.load3 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(3){-webkit-animation-delay:1.04s;animation-delay:1.04s}.load1 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(4){-webkit-animation-delay:1.17s;animation-delay:1.17s}.load2 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(4){-webkit-animation-delay:1.3s;animation-delay:1.3s}.load3 .uni-load-view_wrapper[data-v-b1d75f28]:nth-child(4){-webkit-animation-delay:1.43s;animation-delay:1.43s}@-webkit-keyframes load-data-v-b1d75f28{0%{opacity:1}100%{opacity:.2}}',""]),t.exports=a},ffc5:function(t,a,e){"use strict";var i=e("4e16"),n=e.n(i);n.a}}]);