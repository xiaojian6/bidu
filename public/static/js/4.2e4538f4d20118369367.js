webpackJsonp([4],{"2Cbi":function(t,s,e){"use strict";Object.defineProperty(s,"__esModule",{value:!0});var i={data:function(){return{list:[],filterPms:{type:"none",id:"",page:1,isSure:"none"}}},filters:{toFixeds:function(t){return(t=Number(t)).toFixed(3)}},created:function(){var t=window.localStorage.getItem("token")||"";t&&(this.token=t,this.filterPms.id=this.$route.query.id||"",this.getList())},methods:{getList:function(){var t=this,s=arguments.length>0&&void 0!==arguments[0]&&arguments[0],e={};s||(this.filterPms.page=1),e.currency_id=this.filterPms.id,e.page=this.filterPms.page,"none"!=this.filterPms.type&&(e.type=this.filterPms.type),"none"!=this.filterPms.isSure&&(e.is_sure=this.filterPms.isSure);var i=layer.load();this.$http({url:"/api/legal_user_deal",params:e,headers:{Authorization:this.token}}).then(function(e){if(layer.close(i),"ok"==e.data.type){var a=e.data.message;s?a.data.length?t.list=t.list.concat(a.data):layer.msg(t.$t("td.nomore")):t.list=a.data,a.data.length&&(t.filterPms.page+=1)}})}}},a={render:function(){var t=this,s=t.$createElement,e=t._self._c||s;return e("div",{staticClass:"white",attrs:{id:"legal-record"}},[e("div",{staticClass:"title bgf8"},[t._v(t._s(t.$t("fat.fatdLog")))]),t._v(" "),e("div",{staticClass:"filter-box ft14 bgf8"},[e("div",[e("span",[t._v(t._s(t.$t("fat.tdType"))+"：")]),t._v(" "),e("span",{class:{select:"sell"==t.filterPms.type},on:{click:function(s){t.filterPms.type="sell",t.getList()}}},[t._v(t._s(t.$t("td.buy")))]),t._v(" "),e("span",{class:{select:"buy"==t.filterPms.type},on:{click:function(s){t.filterPms.type="buy",t.getList()}}},[t._v(t._s(t.$t("td.sell")))])]),t._v(" "),e("div",[e("span",[t._v(t._s(t.$t("fat.odStatus"))+"：")]),t._v(" "),e("span",{class:{select:0==t.filterPms.isSure},on:{click:function(s){t.filterPms.isSure=0,t.getList()}}},[t._v(t._s(t.$t("td.nofinish")))]),t._v(" "),e("span",{class:{select:1==t.filterPms.isSure},on:{click:function(s){t.filterPms.isSure=1,t.getList()}}},[t._v(t._s(t.$t("td.finished")))]),t._v(" "),e("span",{class:{select:2==t.filterPms.isSure},on:{click:function(s){t.filterPms.isSure=2,t.getList()}}},[t._v(t._s(t.$t("td.ceiled")))])])]),t._v(" "),e("ul",{staticClass:"ft14 bgf8"},t._l(t.list,function(s,i){return e("li",{key:i},[e("div",{staticClass:"flex li-t"},[e("div",{staticClass:"ft14 bold cblue"},["sell"==s.type?e("span",[t._v(t._s(t.$t("td.buy")))]):e("span",[t._v(t._s(t.$t("td.sell")))]),t._v(" "),e("span",[t._v(t._s(s.currency_name))])]),t._v(" "),e("div",{staticClass:"status"},[0==s.is_sure&&"sell"==s.type?e("router-link",{attrs:{to:{path:"/legalPay",query:{id:s.id}}}},[t._v(t._s(t.$t("td.nofinish"))+" >")]):0==s.is_sure&&"buy"==s.type?e("router-link",{attrs:{to:{path:"/legalPayDetail",query:{id:s.id}}}},[t._v(t._s(t.$t("td.nofinish"))+" >")]):1==s.is_sure?e("router-link",{attrs:{to:{path:"/legalPayDetail",query:{id:s.id}}}},[t._v(t._s(t.$t("td.finished"))+" >")]):2==s.is_sure?e("router-link",{staticClass:"ceilColor",attrs:{to:{path:"/legalPayDetail",query:{id:s.id}}}},[t._v(t._s(t.$t("td.ceiled"))+" >")]):e("router-link",{attrs:{to:{path:"/legalPayDetail",query:{id:s.id}}}},[t._v(t._s(t.$t("td.payed"))+" >")])],1)]),t._v(" "),e("div",{staticClass:"flex li-b"},[e("div",[e("span",[t._v(t._s(t.$t("td.time")))]),t._v(" "),e("span",[t._v(t._s(s.create_time))])]),t._v(" "),e("div",[e("span",[t._v(t._s(t.$t("td.num")))]),t._v(" "),e("span",[t._v(t._s(t._f("toFixeds")(s.number||"0.000")))])]),t._v(" "),e("div",[e("span",[t._v(t._s(t.$t("td.tradeTotal"))+"（"+t._s(s.currency_name)+")")]),t._v(" "),e("span",[t._v(t._s(t._f("toFixeds")(s.deal_money||"0.000")))])])])])})),t._v(" "),t.list.length?e("div",{staticClass:"more",on:{click:function(s){t.getList(!0)}}},[t._v(t._s(t.$t("td.more")))]):e("div",{staticClass:"nomore"},[t._v(t._s(t.$t("td.nomore")))])])},staticRenderFns:[]};var l=e("VU/8")(i,a,!1,function(t){e("aSrx")},null,null);s.default=l.exports},aSrx:function(t,s){}});
//# sourceMappingURL=4.2e4538f4d20118369367.js.map