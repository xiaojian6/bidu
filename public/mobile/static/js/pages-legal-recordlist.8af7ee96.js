(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-legal-recordlist"],{"32ab":function(t,e,i){"use strict";var s=i("ac8b"),a=i.n(s);a.a},"3fb0":function(t,e,i){"use strict";(function(t){var s=i("ee27");i("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=s(i("f3f3")),n=i("2f62"),c={data:function(){return{id:"",show:!1,type:"",statu:"",orderlist:[],page:1,hasMore:!0,over:!1}},onLoad:function(t){this.id=t.id,this.$utils.setTheme(this.theme)},onShow:function(){this.getData()},computed:(0,a.default)({},(0,n.mapState)({theme:function(t){return t.theme}})),methods:{getData:function(){var e=this,i=this.type?this.type:"",s=this.statu||0===this.statu?this.statu:"";e.$utils.initDataToken({url:"legal_user_deal",data:{page:e.page,currency_id:e.id,type:i,is_sure:s}},(function(i){var s=i.data;uni.stopPullDownRefresh(),e.orderlist=1==e.page?s:e.orderlist.concat(s),e.over=!0,e.hasMore=!(i.page>=i.pages),t("log",e.hasMore," at pages\\legal\\recordlist.vue:126")}))},back:function(){uni.navigateBack()},goDetail:function(t,e){uni.navigateTo({url:"/pages/legal/order?id="+t})},detail:function(){uni.navigateTo({url:"/pages/legal/finished"})},isshow:function(){this.show=!this.show},changetype:function(t){this.type=this.type==t?this.type=!this.type:this.type=t},changestatu:function(e){this.statu=this.statu===e?this.statu=!this.statu:this.statu=e,t("log",this.statu," at pages\\legal\\recordlist.vue:150")},reset:function(){this.statu="",this.type="",this.show=!this.show,this.page=1,this.hasMore=!0,this.over=!1,this.getData()},confirm:function(){this.show=!1,this.page=1,this.hasMore=!0,this.over=!1,this.getData()},onPullDownRefresh:function(){this.page=1,this.getData()},onReachBottom:function(){this.hasMore&&(this.page++,this.getData())}}};e.default=c}).call(this,i("0de9")["log"])},5800:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAYCAYAAADpnJ2CAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyFpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMTQyIDc5LjE2MDkyNCwgMjAxNy8wNy8xMy0wMTowNjozOSAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo2MURBOEVDRkUwRkUxMUU5QTRGMURDRDEwOEQxODY5OSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo2MURBOEVEMEUwRkUxMUU5QTRGMURDRDEwOEQxODY5OSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjYxREE4RUNERTBGRTExRTlBNEYxRENEMTA4RDE4Njk5IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjYxREE4RUNFRTBGRTExRTlBNEYxRENEMTA4RDE4Njk5Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+Fegg9wAAAKVJREFUeNrU1sEKgCAMBmDp6ermoehQT6+PYWAKKhIWW9uMBv9Fwg/E5pSClw6xKVNe9N6jAq01xMX9U6wkeMVijBTYwlw6XnZwaWBHyFZ/xAWCMC4QjHGAKIwKojEK+Ap7C+oGJpHSoWwHrDSMQX1QvY40tsMxo/PNpdklLg0Jpf74aJSjtaFQruYNRjmfJxD66wf4CbW9hygjDdZjoqGMiacAAwAY94Qw+qTyYQAAAABJRU5ErkJggg=="},"838c":function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABxElEQVRYR+3WsUtbQRwH8O/vpY9CoJMF/QNcMkjI3W0OrSKC2EFLBenQyUy61Llb54LUQVy6iUihUJdMxUHHlxsa0gTe0KHQoVDwQR7EpLyfHEQI0uEu79GDkpsPvh/ud/e7H8HzIs/5mAKsTiCKosdBELzIsuxUKZUUWTYrgNZ6n5nfAfhaLpeXK5XK76IQVoB2uz3X7/cvAcwDiInoqRDiZxEIK4AJ6nQ6M2maXhDRAjP/CMPwSbVa/Z4XYQ0wQd1u91Gv12sQ0SKAX0S0JIT4lgfhBDBBcRw/TJLknIhWAVwDWJFSNidFOANMEDM/0FqfAXgOIAWwJqU0d8R5TQQYIUhrfQygDuCGiNaFEF9cBRMD7oKiKHpLRG+YeVgqlbZqtdpnF0RugAlrNpt7AN6b6gB4JaU8sUUUAjBhWuttZjbBxMy7SqkjG0RhgNFJPGPmT0QUBkGwYVOO/wcwVoIAwGsp5cE/K8HYJQQR7QghPtiEmz25SzD2DP8AeKmU+mgbngvAzPcb0aYQouESPjHAayv2+hl5/Y69DiStVmt2MBhceRvJvA+l5gSGw2E9y7JDL2O569t22Z+7E7qE/W3vFHALsaDXIQTuHAkAAAAASUVORK5CYII="},"8af9":function(t,e,i){"use strict";i.r(e);var s=i("fd1d"),a=i("d9c7");for(var n in a)"default"!==n&&function(t){i.d(e,t,(function(){return a[t]}))}(n);i("32ab");var c,l=i("f0c5"),o=Object(l["a"])(a["default"],s["b"],s["c"],!1,null,"e27eb46e",null,!1,s["a"],c);e["default"]=o.exports},ac8b:function(t,e,i){var s=i("d720");"string"===typeof s&&(s=[[t.i,s,""]]),s.locals&&(t.exports=s.locals);var a=i("4f06").default;a("2e46e972",s,!0,{sourceMap:!1,shadowMode:!1})},d720:function(t,e,i){var s=i("24fb");e=s(!1),e.push([t.i,"uni-page-body[data-v-e27eb46e]{background-color:#fff}.sx_show[data-v-e27eb46e]{position:fixed;top:calc(0px + %?100?%);width:100%;left:0;right:0;bottom:0;background-color:rgba(0,0,0,.7)}.sx_box[data-v-e27eb46e]{background-color:#fff}.select uni-view[data-v-e27eb46e]{width:30%;height:%?60?%;margin-bottom:%?20?%;text-align:center;margin:%?10?% %?10?% %?20?%;color:#999;background:#f7f7fb;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.select .active[data-v-e27eb46e]{background:none;border:1px solid #217dc1;color:#217dc1;border-radius:%?4?%}body.?%PAGE?%[data-v-e27eb46e]{background-color:#fff}",""]),t.exports=e},d9c7:function(t,e,i){"use strict";i.r(e);var s=i("3fb0"),a=i.n(s);for(var n in s)"default"!==n&&function(t){i.d(e,t,(function(){return s[t]}))}(n);e["default"]=a.a},de2b:function(t,e){t.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgEAYAAAAj6qa3AAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAgY0hSTQAAeiYAAICEAAD6AAAAgOgAAHUwAADqYAAAOpgAABdwnLpRPAAAAAZiS0dEAAAAAAAA+UO7fwAAAAlwSFlzAAAASAAAAEgARslrPgAAAwtJREFUaN7tl11IU2Ecxp//zHJ9EBV9YCAVCLX5kSJRXQRRSUQJZU0jYmE4MLR1o24usrU5FkEIM4JGdNFFubOgD9iMiurCGAw/UrQw7ZOFLJtezOSszbeL2o0RZ2s7Hqnzu37fP8/z4z0v7wFkZGT+Z0howRG/bVvf4YcP6RIO4FxpqdSBE8aHb1Q5NMS28BaFKj+fc5tJ7Y5EZi5TCA6KQEtdSqXUfZJGRzeYnzGQmhvQxGJ/WiYoYLoOXVim00EJL9VNTUndS5BjyKb90SgqYg0oP3GC4zQaTSoC7nQ3OfO7X7/GYzyB3WCQup8QbB+2IGS3u3LP6gs6fD6h9cKfwC9cAWNm3laHgzWSGSqvV+qivxWvwV0609ODo/wmxV6LJdF9CQsAiIgYgz3mmb5WXY0VdA49oZDUxaEFh+c8jyuKg2y7Vvunyy4NAn7CcWd3bT4VCOAD8lhlba3U/bGWaemzycS5DcYCa39/stuTFhDH5TH2Fi64dYuZoaWO27dnvfgICA2dnawlN/tVUWvr3475awFxIhv57Gh9TQ2eYQ9GPn0Svfh9dGLp5CRbT20Zi4VvedEF3CMzFdHEBM7TI2yoqkIFWtDHmFj92QGoFRv1es5tDKjdw8OpzhN8CSZLxXLb6j6vw8F2Q4+1abwjwuSiqw8euDzG3vyrZWXpGpvyCZhJ1ld+dMne+nqmg4WaBwZSHtjBGskyNsYWZTQpvuh06c6b9hMQp0LZkvMyWFzMIjSf5vt8KEcVPmZmJh+QAKbRtLuMTQWFHJfunGk/AXHap0wfC1d1d7Moe8OW2mxJD3DAj6M3b4pVXHQBcYKIIASrFYtxCO/D4YSDHZ9eQ3dNJrHziS7gOZlpJ0WjcMGJdd+/J7qPyrNO8hPi/3yJLmCuIwuQOoDUyAKkDiA1c09AMVbCPDgYfZzz9u278fF/X0ADrFgfDMJDBBgMrCvr+qSvpCTVv7xEmTdbPdlTvMAFv58WIoLLKhVrplGsuHgxXLloh7LR6fSGTxtz23geor35ZGRkZH7nB5hDH/VOo5RcAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTA4LTMxVDE3OjUwOjQzKzA4OjAw1lTZuwAAACV0RVh0ZGF0ZTptb2RpZnkAMjAxOC0wOC0zMVQxNzo1MDo0MyswODowMKcJYQcAAABVdEVYdHN2ZzpiYXNlLXVyaQBmaWxlOi8vL2hvbWUvYWRtaW4vaWNvbi1mb250L3RtcC9pY29uX2hlY3pwMHRmZndpLyVFNyVBRCU5QiVFOSU4MCU4OS5zdmeNxtg8AAAAAElFTkSuQmCC"},fd1d:function(t,e,i){"use strict";var s,a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("v-uni-view",{class:["vh100 bgParti",{dark:"dark"==t.theme}]},[s("v-uni-view",{staticClass:"status_bar"},[s("v-uni-view",{staticClass:"top_view"})],1),s("v-uni-view",{staticClass:"header fixed flex alcenter between plr15 bg_legal "},[s("v-uni-view",[s("v-uni-image",{staticClass:"wt20 h20",attrs:{src:i("5800"),mode:"aspectFit"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.back()}}})],1),s("v-uni-view",[s("v-uni-image",{staticClass:"wt20 h20",attrs:{src:i("de2b"),mode:"aspectFit"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.isshow()}}})],1)],1),s("v-uni-view",{staticClass:"pt50"},[s("v-uni-view",{staticClass:"h50"},[s("v-uni-text",{staticClass:"white ft24 plr15 bold"},[t._v(t._s(t.$t("legal.list")))])],1),s("v-uni-view",[t._l(t.orderlist,(function(e,a){return s("v-uni-view",{key:a,staticClass:"gray75 flex column w100 plr15 ptb15 bdb27",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.goDetail(e.id,e.is_sure)}}},[s("v-uni-view",{staticClass:"flex between"},[s("v-uni-view",{staticClass:"white flex alcenter ft13 bold"},[s("v-uni-text",{class:["blue21 mr5 ft14",{redColor:"buy"==e.type}]},[t._v(t._s("sell"==e.type?t.$t("legal.buy"):t.$t("legal.sell")))]),s("v-uni-text",[t._v(t._s(e.currency_name))])],1),s("v-uni-view",{staticClass:"flex alcenter"},[0==e.is_sure?s("v-uni-text",{},[t._v(t._s(t.$t("store.notdone")))]):t._e(),1==e.is_sure?s("v-uni-text",{},[t._v(t._s(t.$t("store.done")))]):t._e(),2==e.is_sure?s("v-uni-text",{},[t._v(t._s(t.$t("trade.has_cancel")))]):t._e(),3==e.is_sure?s("v-uni-text",{},[t._v(t._s(t.$t("trade.has_pay")))]):t._e(),s("v-uni-image",{staticClass:"wt15 h15",attrs:{src:i("838c"),mode:"aspectFit"}})],1)],1),s("v-uni-view",{staticClass:"flex between mt10"},[s("v-uni-view",{staticClass:"flex column flexstart"},[s("v-uni-text",[t._v(t._s(t.$t("trade.time")))]),s("v-uni-text",{staticClass:"b7c ft13 mt5"},[t._v(t._s(e.create_time))])],1),s("v-uni-view",{staticClass:"flex column flexstart"},[s("v-uni-text",[t._v(t._s(t.$t("trade.num"))+"("+t._s(e.currency_name)+")")]),s("v-uni-text",{staticClass:"b7c ft13 mt5"},[t._v(t._s(e.number))])],1),s("v-uni-view",{staticClass:"flex column flexend"},[s("v-uni-text",[t._v(t._s(t.$t("legal.allmoney"))+"("+t._s(e.coin_code)+")")]),s("v-uni-text",{staticClass:"b7c ft13 mt5"},[t._v(t._s(e.deal_money))])],1)],1),s("v-uni-view",{staticClass:"flex mt10"},[s("v-uni-text",{staticClass:"ft14 white"},[t._v(t._s(e.seller_name))])],1)],1)})),s("v-uni-view",{class:["tc pt30 pt100 pb100 hidden",{block:0==t.orderlist.length&&t.over}]},[s("v-uni-image",{staticClass:"h50 wt50",attrs:{src:"/static/image/nodata.png"}}),s("v-uni-view",{staticClass:"gray7"},[t._v(t._s(t.$t("home.norecord")))])],1),s("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:!t.hasMore&&t.orderlist.length>10,expression:"!hasMore && orderlist.length>10"}],staticClass:"tc gray7 ptb20"},[t._v(t._s(t.$t("home.nomore")))])],2)],1),s("v-uni-view",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}],staticClass:"sx_show",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.isshow()}}},[s("v-uni-view",{staticClass:"sx_box",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e)}}},[s("v-uni-view",{staticClass:"pt10 plr10 ft10 "},[s("v-uni-text",{staticClass:"gray75 ft14"},[t._v(t._s(t.$t("trade.dealtype")))]),s("v-uni-view",{staticClass:"select deal_type mt10  mb10 flex"},[s("v-uni-view",{class:"sell"==t.type?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changetype("sell")}}},[t._v(t._s(t.$t("legal.buy")))]),s("v-uni-view",{class:"buy"==t.type?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changetype("buy")}}},[t._v(t._s(t.$t("legal.sell")))])],1),s("v-uni-text",{staticClass:"gray75 ft14"},[t._v(t._s(t.$t("store.orderstatus")))]),s("v-uni-view",{staticClass:"select deal_statu mt10 mb10 flex wraps"},[s("v-uni-view",{class:0===t.statu?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changestatu(0)}}},[t._v(t._s(t.$t("store.notdone")))]),s("v-uni-view",{class:1===t.statu?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changestatu(1)}}},[t._v(t._s(t.$t("store.done")))]),s("v-uni-view",{class:2===t.statu?"active":"",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changestatu(2)}}},[t._v(t._s(t.$t("trade.has_cancel")))])],1)],1),s("v-uni-view",{staticClass:"flex alcenter bdt_f0"},[s("v-uni-view",{staticClass:"flex1 tc ptb10 reset  gray75",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.reset()}}},[t._v(t._s(t.$t("store.chongzhi")))]),s("v-uni-view",{staticClass:"active flex1 tc ptb10 bgBlue2 white confirm",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.confirm.apply(void 0,arguments)}}},[t._v(t._s(t.$t("login.e_confrim2")))])],1)],1)],1)],1)},n=[];i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return n})),i.d(e,"a",(function(){return s}))}}]);