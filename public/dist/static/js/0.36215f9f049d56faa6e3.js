webpackJsonp([0],{"+FcT":function(s,t){},"q/5l":function(s,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var e=a("bOdI"),i=a.n(e),n={data:function(){var s;return s={msg:"",user:"",token:"",showConfirm:!1,showCancel:!1,hasPay:!1,showPay:!1,id:""},i()(s,"msg",{}),i()(s,"src",""),i()(s,"password",""),s},filters:{toFixeds:function(s){return(s=Number(s)).toFixed(3)}},created:function(){var s=window.localStorage.getItem("token")||"";s&&(this.token=s,this.id=this.$route.query.id||"",this.getMsg())},methods:{getMsg:function(){var s=this,t=layer.load();this.$http({url:"/api/legal_deal",params:{id:this.id},headers:{Authorization:this.token}}).then(function(a){layer.close(t),"ok"==a.data.type&&(s.msg=a.data.message,s.user=a.data.message.user_cash_info)})},getData:function(){var s=this,t=layer.load();this.$http({url:"/api/legal_deal",params:{id:this.id},headers:{Authorization:this.token}}).then(function(a){layer.close(t),"ok"==a.data.type&&(s.msg=a.data.message)})},cancel:function(){var s=this;s.showCancel=!1,layer.prompt({title:s.$t("td.pwd"),formType:1,btn:[s.$t("td.confirm"),s.$t("td.canceil")]},function(t,a){s.password=t,s.$http({url:"/api/user_legal_pay_cancel",method:"post",data:{id:s.id,password:s.password},headers:{Authorization:s.token}}).then(function(t){layer.msg(t.data.message),s.showCancel=!1,"ok"==t.data.type&&location.reload()}),layer.close(a)})},confirm:function(){var s=this;s.showConfirm=!1,layer.prompt({title:s.$t("td.pwd"),formType:1,btn:[s.$t("td.confirm"),s.$t("td.canceil")]},function(t,a){s.password=t,s.$http({url:"/api/legal_deal_sure",method:"post",data:{id:s.id,password:s.password},headers:{Authorization:s.token}}).then(function(s){layer.msg(s.data.message),"ok"==s.data.type&&setTimeout(function(){location.reload()},1e3)}).then(function(){s.showConfirm=!1}),layer.close(a)})},file:function(){var s=this,t=new FormData;t.append("file",$("#file")[0].files[0]);var a=layer.load();$.ajax({url:"/api/upload",type:"post",data:t,processData:!1,contentType:!1,success:function(t){layer.close(a),"ok"==t.type&&(s.src=t.message,s.src?s.showPay=!0:layer.msg("图片上传失败"))}})},confirmPay:function(){var s=this;s.showPay=!1,layer.prompt({title:s.$t("td.pwd"),formType:1,btn:[s.$t("td.confirm"),s.$t("td.canceil")]},function(t,a){s.password=t,s.$http({url:"/api/user_legal_pay",method:"post",data:{id:s.id,password:s.password},headers:{Authorization:s.token}}).then(function(s){layer.msg(s.data.message),"ok"==s.data.type&&setTimeout(function(){location.reload()},2e3)}).then(function(){s.showPay=!1}),layer.close(a)})},evimgs:function(s){return layer.open({type:1,area:["375px","500px"],title:"",shade:.6,anim:1,content:"<img src='"+s+"' alt='' class='openimg'>",btn:["关闭"],yes:function(s){layer.close(s)}})}}},_={render:function(){var s=this,t=s.$createElement,a=s._self._c||t;return a("div",{staticClass:"white",attrs:{id:"legal-pay-detail"}},[a("div",{staticClass:"title bg-part radius4"},[0==s.msg.is_sure?a("span",[s._v(s._s(s.$t("td.nofinish")))]):s._e(),s._v(" "),1==s.msg.is_sure?a("span",[s._v(s._s(s.$t("td.finished")))]):s._e(),s._v(" "),2==s.msg.is_sure?a("span",[s._v(s._s(s.$t("td.ceiled")))]):s._e(),s._v(" "),3==s.msg.is_sure?a("span",[s._v(s._s(s.$t("td.payed")))]):s._e(),s._v(" "),0==s.msg.is_sure?a("div",[s._v(s._s(s.$t("fat.pwaitPay")))]):s._e(),s._v(" "),1==s.msg.is_sure?a("div",[s._v(s._s(s.$t("fat.odFinish")))]):s._e(),s._v(" "),2==s.msg.is_sure?a("div",[s._v(s._s(s.$t("fat.odCeil")))]):s._e(),s._v(" "),3==s.msg.is_sure?a("div",[s._v(s._s(s.$t("fat.payedWait")))]):s._e()]),s._v(" "),a("div",{staticClass:"info bg-part ft14 radius4"},[a("div",[a("span",[s._v(s._s(s.$t("td.tradeTotal"))+"：")]),s._v(" "),a("span",[s._v(s._s(s._f("toFixeds")(s.msg.deal_money||"0.000"))+" "+s._s(s.msg.coin_code))])]),s._v(" "),0==s.msg.is_seller?a("div",{staticClass:"column"},[a("div",[a("span",[s._v(s._s(s.$t("td.seller"))+":")]),s._v(" "),"buy"==s.msg.type?a("span",[s._v(s._s(s.user.account_number))]):a("span",[s._v(s._s(s.msg.seller_name))])]),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("fat.bank"))+":")]),s._v(" "),a("span",[s._v(s._s(s.user.bank_name)+":"+s._s(s.user.bank_account))])]),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("fat.alipay"))+":")]),s._v(" "),a("span",[s._v(s._s(s.user.alipay_account))])]),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("fat.wechat"))+":")]),s._v(" "),a("span",[s._v(s._s(s.user.wechat_account))])]),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("fat.realName"))+":")]),s._v(" "),a("span",[s._v(s._s(s.user.real_name))])])]):s._e(),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("td.price"))+"：")]),s._v(" "),a("span",[s._v(s._s(s._f("toFixeds")(s.msg.price||"0.000"))+" "+s._s(s.msg.coin_code))])]),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("td.num"))+"：")]),s._v(" "),a("span",[s._v(s._s(s._f("toFixeds")(s.msg.number||"0.000")))])]),s._v(" "),"sell"==s.msg.type?a("div",[a("span",[s._v(s._s(s.$t("auth.name"))+"：")]),s._v(" "),a("span",[s._v(s._s(s.user.real_name))])]):s._e(),s._v(" "),"sell"==s.msg.type?a("div",[a("span",[s._v(s._s(s.$t("lever.rate"))+"：")]),s._v(" "),a("span",[s._v(s._s(s.msg.out_fee))])]):s._e(),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("td.placeTime"))+"：")]),s._v(" "),a("span",[s._v(s._s(s.msg.format_create_time))])]),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("td.callWay"))+"：")]),s._v(" "),a("span",[s._v(s._s("sell"==s.msg.type?s.msg.phone:s.msg.seller_phone))])]),s._v(" "),a("div",[a("span",[s._v(s._s(s.$t("td.renum"))+"：")]),s._v(" "),a("span",[s._v(s._s(s.msg.id))])]),s._v(" "),a("div",{staticClass:"btns"},[0==s.msg.is_sure&&"buy"==s.msg.type?a("div",{staticClass:"btn cancelBtn",on:{click:function(t){s.showCancel=!0}}},[s._v(s._s(s.$t("fat.odCeil")))]):s._e(),s._v(" "),3==s.msg.is_sure&&"sell"==s.msg.type?a("div",{staticClass:"btn",on:{click:function(t){s.showConfirm=!0}}},[s._v(s._s(s.$t("fat.receivePays")))]):s._e(),s._v(" "),0==s.msg.is_sure&&"buy"==s.msg.type?a("div",{staticClass:"btn imgbtn",on:{click:function(t){s.showPay=!0}}},[s._v("\n        "+s._s(s.$t("fat.imPay"))+"\n        ")]):s._e()])]),s._v(" "),s.showCancel?a("div",{staticClass:"cancel-box"},[a("div",{staticClass:"content"},[a("div",[s._v(s._s(s.$t("fat.tdCeil")))]),s._v(" "),a("div",[s._v(s._s(s.$t("fat.pCeil")))]),s._v(" "),a("div",{staticClass:"yes-no flex"},[a("div",{on:{click:function(t){s.showCancel=!1}}},[s._v(s._s(s.$t("td.canceil")))]),s._v(" "),a("div",{on:{click:s.cancel}},[s._v(s._s(s.$t("td.confirm")))])])])]):s._e(),s._v(" "),s.showConfirm?a("div",{staticClass:"confirm-box"},[a("div",{staticClass:"content"},[a("div",[s._v(s._s(s.$t("fat.receivePay")))]),s._v(" "),a("div",[s._v(s._s(s.$t("fat.payReceive")))]),s._v(" "),a("div",[s._v(s._s(s.$t("fat.badClick")))]),s._v(" "),a("div",{staticClass:"yes-no flex"},[a("div",{on:{click:function(t){s.showConfirm=!1}}},[s._v(s._s(s.$t("td.canceil")))]),s._v(" "),a("div",{on:{click:s.confirm}},[s._v(s._s(s.$t("td.confirm")))])])])]):s._e(),s._v(" "),s.showPay?a("div",{staticClass:"confirm-box"},[a("div",{staticClass:"content"},[a("div",[s._v(s._s(s.$t("fat.paySure")))]),s._v(" "),a("div",[s._v(s._s(s.$t("fat.pdopay")))]),s._v(" "),a("div",[s._v(s._s(s.$t("fat.badClick")))]),s._v(" "),a("div",{staticClass:"yes-no flex"},[a("div",{on:{click:function(t){s.showPay=!1}}},[s._v(s._s(s.$t("td.canceil")))]),s._v(" "),a("div",{on:{click:s.confirmPay}},[s._v(s._s(s.$t("td.confirm")))])])])]):s._e()])},staticRenderFns:[]};var o=a("VU/8")(n,_,!1,function(s){a("+FcT")},null,null);t.default=o.exports}});
//# sourceMappingURL=0.36215f9f049d56faa6e3.js.map