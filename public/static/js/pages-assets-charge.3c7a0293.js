(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-assets-charge"],{"17ef":function(t,r,e){"use strict";var n=function(){var t=this,r=t.$createElement,e=t._self._c||r;return e("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},[e("v-uni-view",{staticClass:"pt20 plr15 blue"},[e("v-uni-view",{staticClass:"bgPart flex alcenter between plr15 ptb15 radius4"},[e("v-uni-text",[t._v(t._s(t.$t("assets.cur_coin")))]),e("v-uni-text",[t._v(t._s(t.coinInfo.name||"--"))])],1),e("v-uni-view",{staticClass:"mt10 bgPart radius4 ptb20 plr15 tc"},[e("v-uni-image",{staticClass:"mauto",style:{width:t.size+"px",height:t.size+"px"},attrs:{src:t.img,mode:"widthFix"}}),e("v-uni-view",{staticClass:"mt20 ft12 tc"},[t._v(t._s(t.$t("assets.addr_charge")))]),e("v-uni-view",{staticClass:"tc ft12 gray_e mt5"},[t._v(t._s(t.address))]),e("v-uni-view",{staticClass:"mt20 wt80 h30 lh30 radius4 mauto bgBlue white",on:{click:function(r){r=t.$handleEvent(r),t.fuzhi_invite(r)}}},[t._v(t._s(t.$t("assets.coypaddr")))])],1),e("v-uni-view",{staticClass:"mt20 "},[e("v-uni-view",{staticClass:"mb5"},[e("v-uni-text",{staticClass:"ft12"},[t._v(t._s(t.$t("assets.c_tip1")))]),e("v-uni-text",{staticClass:"ft12"},[t._v(t._s(t.coinInfo.name||"--"))]),e("v-uni-text",{staticClass:"ft12"},[t._v(t._s(t.$t("assets.assets"))+"，")]),e("v-uni-text",{staticClass:"ft12"},[t._v(t._s(t.$t("assets.c_tip2"))+"。")])],1),e("v-uni-view",{staticClass:"mb5"},[e("v-uni-text",{staticClass:"ft12"},[t._v(t._s(t.coinInfo.name||"--"))]),e("v-uni-text",{staticClass:"ft12"},[t._v(t._s(t.$t("assets.c_tip3"))+"。")])],1),e("v-uni-view",{staticClass:"ft12"},[t._v(t._s(t.$t("assets.c_tip4"))+"。")]),e("v-uni-view",{staticClass:"mb5"},[e("v-uni-text",{staticClass:"ft12"},[t._v(t._s(t.$t("assets.c_tip5"))+"：")]),e("v-uni-text",{staticClass:"mainnum ft12"},[t._v(t._s(t.coinInfo.min_number||"--"))]),e("v-uni-text",{staticClass:"ft12"},[t._v(t._s(t.coinInfo.name||"--"))]),t._v(",")],1),e("v-uni-view",{staticClass:"mb5 ft12"},[t._v(t._s(t.$t("assets.c_tip6"))+"。")]),e("v-uni-view",{staticClass:"mb5 ft12"},[t._v(t._s(t.$t("assets.c_tip7"))+"。")]),e("v-uni-view",{staticClass:"mb5 ft12"},[t._v(t._s(t.$t("assets.c_tip8"))+"。")])],1)],1)],1)},i=[];e.d(r,"a",function(){return n}),e.d(r,"b",function(){return i})},3163:function(t,r,e){"use strict";var n=e("288e");Object.defineProperty(r,"__esModule",{value:!0}),r.default=void 0,e("7f7f");var i=n(e("cebc")),o=e("2f62"),a=n(e("5941")),u={data:function(){return{title:"",currency:"",coinInfo:{},img:"",size:160,address:"",name:""}},computed:(0,i.default)({},(0,o.mapState)({theme:function(t){return t.theme}})),onLoad:function(t){this.currency=t.currency,this.name=t.name,uni.setNavigationBarTitle({title:this.$t("assets").charge}),this.$utils.setTheme(this.theme),this.getCoinInfo()},onPullDownRefresh:function(){this.getCoinInfo()},methods:{getCoinInfo:function(){var t=this;this.$utils.initDataToken({url:"wallet/get_info",type:"POST",data:{currency:this.currency}},function(r){t.coinInfo=r,t.getUserInfo()})},getUserInfo:function(){var t=this;this.$utils.initDataToken({url:"user/info"},function(r){t.getAddress(r.id)})},getAddress:function(t){var r=this;this.$utils.getAddressOnline({url:"walletMiddle/GetRechargeAddress",data:{user_id:t,coin_type:this.name,contract_address:this.coinInfo.contract_address}},function(t){uni.stopPullDownRefresh(),console.log(t),0==t.code&&(r.address=t.data.address,r.creatQrcode())})},fuzhi_invite:function(){},creatQrcode:function(){if(""==this.address)return!1;var t=a.default.createQrCodeImg(this.address);this.img=t}}};r.default=u},5941:function(t,r,e){"use strict";var n=e("288e"),i=n(e("e814"));function o(t,r){if("undefined"==typeof t.length)throw new Error(t.length+"/"+r);var e=function(){for(var e=0;e<t.length&&0==t[e];)e+=1;for(var n=new Array(t.length-e+r),i=0;i<t.length-e;i+=1)n[i]=t[i+e];return n}(),n={getAt:function(t){return e[t]},getLength:function(){return e.length},multiply:function(t){for(var r=new Array(n.getLength()+t.getLength()-1),e=0;e<n.getLength();e+=1)for(var i=0;i<t.getLength();i+=1)r[e+i]^=l.gexp(l.glog(n.getAt(e))+l.glog(t.getAt(i)));return o(r,0)},mod:function(t){if(n.getLength()-t.getLength()<0)return n;for(var r=l.glog(n.getAt(0))-l.glog(t.getAt(0)),e=new Array(n.getLength()),i=0;i<n.getLength();i+=1)e[i]=n.getAt(i);for(i=0;i<t.getLength();i+=1)e[i]^=l.gexp(l.glog(t.getAt(i))+r);return o(e,0).mod(t)}};return n}e("4917");var a=function(t,r){var e=236,n=17,i=t,a=f[r],u=null,s=0,l=null,d=new Array,w={},p=function(t,r){s=4*i+17,u=function(t){for(var r=new Array(t),e=0;e<t;e+=1){r[e]=new Array(t);for(var n=0;n<t;n+=1)r[e][n]=null}return r}(s),y(0,0),y(s-7,0),y(0,s-7),C(),A(),E(t,r),i>=7&&B(t),null==l&&(l=b(i,a,d)),T(l,r)},y=function(t,r){for(var e=-1;e<=7;e+=1)if(!(t+e<=-1||s<=t+e))for(var n=-1;n<=7;n+=1)r+n<=-1||s<=r+n||(u[t+e][r+n]=0<=e&&e<=6&&(0==n||6==n)||0<=n&&n<=6&&(0==e||6==e)||2<=e&&e<=4&&2<=n&&n<=4)},m=function(){for(var t=0,r=0,e=0;e<8;e+=1){p(!0,e);var n=c.getLostPoint(w);(0==e||t>n)&&(t=n,r=e)}return r},A=function(){for(var t=8;t<s-8;t+=1)null==u[t][6]&&(u[t][6]=t%2==0);for(var r=8;r<s-8;r+=1)null==u[6][r]&&(u[6][r]=r%2==0)},C=function(){for(var t=c.getPatternPosition(i),r=0;r<t.length;r+=1)for(var e=0;e<t.length;e+=1){var n=t[r],o=t[e];if(null==u[n][o])for(var a=-2;a<=2;a+=1)for(var f=-2;f<=2;f+=1)u[n+a][o+f]=-2==a||2==a||-2==f||2==f||0==a&&0==f}},B=function(t){for(var r=c.getBCHTypeNumber(i),e=0;e<18;e+=1){var n=!t&&1==(r>>e&1);u[Math.floor(e/3)][e%3+s-8-3]=n}for(e=0;e<18;e+=1){n=!t&&1==(r>>e&1);u[e%3+s-8-3][Math.floor(e/3)]=n}},E=function(t,r){for(var e=a<<3|r,n=c.getBCHTypeInfo(e),i=0;i<15;i+=1){var o=!t&&1==(n>>i&1);i<6?u[i][8]=o:i<8?u[i+1][8]=o:u[s-15+i][8]=o}for(i=0;i<15;i+=1){o=!t&&1==(n>>i&1);i<8?u[8][s-i-1]=o:i<9?u[8][15-i-1+1]=o:u[8][15-i-1]=o}u[s-8][8]=!t},T=function(t,r){for(var e=-1,n=s-1,i=7,o=0,a=c.getMaskFunction(r),f=s-1;f>0;f-=2)for(6==f&&(f-=1);;){for(var l=0;l<2;l+=1)if(null==u[n][f-l]){var g=!1;o<t.length&&(g=1==(t[o]>>>i&1));var v=a(n,f-l);v&&(g=!g),u[n][f-l]=g,i-=1,-1==i&&(o+=1,i=7)}if(n+=e,n<0||s<=n){n-=e,e=-e;break}}},M=function(t,r){for(var e=0,n=0,i=0,a=new Array(r.length),u=new Array(r.length),f=0;f<r.length;f+=1){var s=r[f].dataCount,l=r[f].totalCount-s;n=Math.max(n,s),i=Math.max(i,l),a[f]=new Array(s);for(var g=0;g<a[f].length;g+=1)a[f][g]=255&t.getBuffer()[g+e];e+=s;var v=c.getErrorCorrectPolynomial(l),h=o(a[f],v.getLength()-1),d=h.mod(v);u[f]=new Array(v.getLength()-1);for(g=0;g<u[f].length;g+=1){var w=g+d.getLength()-u[f].length;u[f][g]=w>=0?d.getAt(w):0}}var p=0;for(g=0;g<r.length;g+=1)p+=r[g].totalCount;var y=new Array(p),_=0;for(g=0;g<n;g+=1)for(f=0;f<r.length;f+=1)g<a[f].length&&(y[_]=a[f][g],_+=1);for(g=0;g<i;g+=1)for(f=0;f<r.length;f+=1)g<u[f].length&&(y[_]=u[f][g],_+=1);return y},b=function(t,r,i){for(var o=g.getRSBlocks(t,r),a=v(),u=0;u<i.length;u+=1){var f=i[u];a.put(f.getMode(),4),a.put(f.getLength(),c.getLengthInBits(f.getMode(),t)),f.write(a)}var s=0;for(u=0;u<o.length;u+=1)s+=o[u].dataCount;if(a.getLengthInBits()>8*s)throw new Error("code length overflow. ("+a.getLengthInBits()+">"+8*s+")");for(a.getLengthInBits()+4<=8*s&&a.put(0,4);a.getLengthInBits()%8!=0;)a.putBit(!1);for(;;){if(a.getLengthInBits()>=8*s)break;if(a.put(e,8),a.getLengthInBits()>=8*s)break;a.put(n,8)}return M(a,o)};return w.addData=function(t){var r=h(t);d.push(r),l=null},w.isDark=function(t,r){if(t<0||s<=t||r<0||s<=r)throw new Error(t+","+r);return u[t][r]},w.getModuleCount=function(){return s},w.make=function(){p(!1,m())},w.createTableTag=function(t,r){t=t||2,r="undefined"==typeof r?4*t:r;var e="";e+='<table style="',e+=" border-width: 0upx; border-style: none;",e+=" border-collapse: collapse;",e+=" padding: 0upx; margin: "+r+"upx;",e+='">',e+="<tbody>";for(var n=0;n<w.getModuleCount();n+=1){e+="<tr>";for(var i=0;i<w.getModuleCount();i+=1)e+='<td style="',e+=" border-width: 0upx; border-style: none;",e+=" border-collapse: collapse;",e+=" padding: 0upx; margin: 0upx;",e+=" width: "+t+"upx;",e+=" height: "+t+"upx;",e+=" background-color: ",e+=w.isDark(n,i)?"#000000":"#ffffff",e+=";",e+='"/>';e+="</tr>"}return e+="</tbody>",e+"</table>"},w.createImgTag=function(t,r,e){t=t||2,r="undefined"==typeof r?4*t:r;var n=r,i=w.getModuleCount()*t+r;return _(e,e,function(r,e){if(n<=r&&r<i&&n<=e&&e<i){var o=Math.floor((r-n)/t),a=Math.floor((e-n)/t);return w.isDark(a,o)?0:1}return 1})},w};a.stringToBytes=function(t){for(var r=new Array,e=0;e<t.length;e+=1){var n=t.charCodeAt(e);r.push(255&n)}return r},a.createStringToBytes=function(t,r){var e=function(){for(var e=p(t),n=function(){var t=e.read();if(-1==t)throw new Error;return t},i=0,o={};;){var a=e.read();if(-1==a)break;var u=n(),f=n(),s=n(),c=String.fromCharCode(a<<8|u),l=f<<8|s;o[c]=l,i+=1}if(i!=r)throw new Error(i+" != "+r);return o}(),n="?".charCodeAt(0);return function(t){for(var r=new Array,i=0;i<t.length;i+=1){var o=t.charCodeAt(i);if(o<128)r.push(o);else{var a=e[t.charAt(i)];"number"==typeof a?(255&a)==a?r.push(a):(r.push(a>>>8),r.push(255&a)):r.push(n)}}return r}};var u={MODE_NUMBER:1,MODE_ALPHA_NUM:2,MODE_8BIT_BYTE:4,MODE_KANJI:8},f={L:1,M:0,Q:3,H:2},s={PATTERN000:0,PATTERN001:1,PATTERN010:2,PATTERN011:3,PATTERN100:4,PATTERN101:5,PATTERN110:6,PATTERN111:7},c=function(){var t=[[],[6,18],[6,22],[6,26],[6,30],[6,34],[6,22,38],[6,24,42],[6,26,46],[6,28,50],[6,30,54],[6,32,58],[6,34,62],[6,26,46,66],[6,26,48,70],[6,26,50,74],[6,30,54,78],[6,30,56,82],[6,30,58,86],[6,34,62,90],[6,28,50,72,94],[6,26,50,74,98],[6,30,54,78,102],[6,28,54,80,106],[6,32,58,84,110],[6,30,58,86,114],[6,34,62,90,118],[6,26,50,74,98,122],[6,30,54,78,102,126],[6,26,52,78,104,130],[6,30,56,82,108,134],[6,34,60,86,112,138],[6,30,58,86,114,142],[6,34,62,90,118,146],[6,30,54,78,102,126,150],[6,24,50,76,102,128,154],[6,28,54,80,106,132,158],[6,32,58,84,110,136,162],[6,26,54,82,110,138,166],[6,30,58,86,114,142,170]],r=1335,e=7973,n=21522,i={},a=function(t){for(var r=0;0!=t;)r+=1,t>>>=1;return r};return i.getBCHTypeInfo=function(t){for(var e=t<<10;a(e)-a(r)>=0;)e^=r<<a(e)-a(r);return(t<<10|e)^n},i.getBCHTypeNumber=function(t){for(var r=t<<12;a(r)-a(e)>=0;)r^=e<<a(r)-a(e);return t<<12|r},i.getPatternPosition=function(r){return t[r-1]},i.getMaskFunction=function(t){switch(t){case s.PATTERN000:return function(t,r){return(t+r)%2==0};case s.PATTERN001:return function(t,r){return t%2==0};case s.PATTERN010:return function(t,r){return r%3==0};case s.PATTERN011:return function(t,r){return(t+r)%3==0};case s.PATTERN100:return function(t,r){return(Math.floor(t/2)+Math.floor(r/3))%2==0};case s.PATTERN101:return function(t,r){return t*r%2+t*r%3==0};case s.PATTERN110:return function(t,r){return(t*r%2+t*r%3)%2==0};case s.PATTERN111:return function(t,r){return(t*r%3+(t+r)%2)%2==0};default:throw new Error("bad maskPattern:"+t)}},i.getErrorCorrectPolynomial=function(t){for(var r=o([1],0),e=0;e<t;e+=1)r=r.multiply(o([1,l.gexp(e)],0));return r},i.getLengthInBits=function(t,r){if(1<=r&&r<10)switch(t){case u.MODE_NUMBER:return 10;case u.MODE_ALPHA_NUM:return 9;case u.MODE_8BIT_BYTE:return 8;case u.MODE_KANJI:return 8;default:throw new Error("mode:"+t)}else if(r<27)switch(t){case u.MODE_NUMBER:return 12;case u.MODE_ALPHA_NUM:return 11;case u.MODE_8BIT_BYTE:return 16;case u.MODE_KANJI:return 10;default:throw new Error("mode:"+t)}else{if(!(r<41))throw new Error("type:"+r);switch(t){case u.MODE_NUMBER:return 14;case u.MODE_ALPHA_NUM:return 13;case u.MODE_8BIT_BYTE:return 16;case u.MODE_KANJI:return 12;default:throw new Error("mode:"+t)}}},i.getLostPoint=function(t){for(var r=t.getModuleCount(),e=0,n=0;n<r;n+=1)for(var i=0;i<r;i+=1){for(var o=0,a=t.isDark(n,i),u=-1;u<=1;u+=1)if(!(n+u<0||r<=n+u))for(var f=-1;f<=1;f+=1)i+f<0||r<=i+f||0==u&&0==f||a==t.isDark(n+u,i+f)&&(o+=1);o>5&&(e+=3+o-5)}for(n=0;n<r-1;n+=1)for(i=0;i<r-1;i+=1){var s=0;t.isDark(n,i)&&(s+=1),t.isDark(n+1,i)&&(s+=1),t.isDark(n,i+1)&&(s+=1),t.isDark(n+1,i+1)&&(s+=1),0!=s&&4!=s||(e+=3)}for(n=0;n<r;n+=1)for(i=0;i<r-6;i+=1)t.isDark(n,i)&&!t.isDark(n,i+1)&&t.isDark(n,i+2)&&t.isDark(n,i+3)&&t.isDark(n,i+4)&&!t.isDark(n,i+5)&&t.isDark(n,i+6)&&(e+=40);for(i=0;i<r;i+=1)for(n=0;n<r-6;n+=1)t.isDark(n,i)&&!t.isDark(n+1,i)&&t.isDark(n+2,i)&&t.isDark(n+3,i)&&t.isDark(n+4,i)&&!t.isDark(n+5,i)&&t.isDark(n+6,i)&&(e+=40);var c=0;for(i=0;i<r;i+=1)for(n=0;n<r;n+=1)t.isDark(n,i)&&(c+=1);var l=Math.abs(100*c/r/r-50)/5;return e+10*l},i}(),l=function(){for(var t=new Array(256),r=new Array(256),e=0;e<8;e+=1)t[e]=1<<e;for(e=8;e<256;e+=1)t[e]=t[e-4]^t[e-5]^t[e-6]^t[e-8];for(e=0;e<255;e+=1)r[t[e]]=e;var n={glog:function(t){if(t<1)throw new Error("glog("+t+")");return r[t]},gexp:function(r){for(;r<0;)r+=255;for(;r>=256;)r-=255;return t[r]}};return n}(),g=function(){var t=[[1,26,19],[1,26,16],[1,26,13],[1,26,9],[1,44,34],[1,44,28],[1,44,22],[1,44,16],[1,70,55],[1,70,44],[2,35,17],[2,35,13],[1,100,80],[2,50,32],[2,50,24],[4,25,9],[1,134,108],[2,67,43],[2,33,15,2,34,16],[2,33,11,2,34,12],[2,86,68],[4,43,27],[4,43,19],[4,43,15],[2,98,78],[4,49,31],[2,32,14,4,33,15],[4,39,13,1,40,14],[2,121,97],[2,60,38,2,61,39],[4,40,18,2,41,19],[4,40,14,2,41,15],[2,146,116],[3,58,36,2,59,37],[4,36,16,4,37,17],[4,36,12,4,37,13],[2,86,68,2,87,69],[4,69,43,1,70,44],[6,43,19,2,44,20],[6,43,15,2,44,16],[4,101,81],[1,80,50,4,81,51],[4,50,22,4,51,23],[3,36,12,8,37,13],[2,116,92,2,117,93],[6,58,36,2,59,37],[4,46,20,6,47,21],[7,42,14,4,43,15],[4,133,107],[8,59,37,1,60,38],[8,44,20,4,45,21],[12,33,11,4,34,12],[3,145,115,1,146,116],[4,64,40,5,65,41],[11,36,16,5,37,17],[11,36,12,5,37,13],[5,109,87,1,110,88],[5,65,41,5,66,42],[5,54,24,7,55,25],[11,36,12],[5,122,98,1,123,99],[7,73,45,3,74,46],[15,43,19,2,44,20],[3,45,15,13,46,16],[1,135,107,5,136,108],[10,74,46,1,75,47],[1,50,22,15,51,23],[2,42,14,17,43,15],[5,150,120,1,151,121],[9,69,43,4,70,44],[17,50,22,1,51,23],[2,42,14,19,43,15],[3,141,113,4,142,114],[3,70,44,11,71,45],[17,47,21,4,48,22],[9,39,13,16,40,14],[3,135,107,5,136,108],[3,67,41,13,68,42],[15,54,24,5,55,25],[15,43,15,10,44,16],[4,144,116,4,145,117],[17,68,42],[17,50,22,6,51,23],[19,46,16,6,47,17],[2,139,111,7,140,112],[17,74,46],[7,54,24,16,55,25],[34,37,13],[4,151,121,5,152,122],[4,75,47,14,76,48],[11,54,24,14,55,25],[16,45,15,14,46,16],[6,147,117,4,148,118],[6,73,45,14,74,46],[11,54,24,16,55,25],[30,46,16,2,47,17],[8,132,106,4,133,107],[8,75,47,13,76,48],[7,54,24,22,55,25],[22,45,15,13,46,16],[10,142,114,2,143,115],[19,74,46,4,75,47],[28,50,22,6,51,23],[33,46,16,4,47,17],[8,152,122,4,153,123],[22,73,45,3,74,46],[8,53,23,26,54,24],[12,45,15,28,46,16],[3,147,117,10,148,118],[3,73,45,23,74,46],[4,54,24,31,55,25],[11,45,15,31,46,16],[7,146,116,7,147,117],[21,73,45,7,74,46],[1,53,23,37,54,24],[19,45,15,26,46,16],[5,145,115,10,146,116],[19,75,47,10,76,48],[15,54,24,25,55,25],[23,45,15,25,46,16],[13,145,115,3,146,116],[2,74,46,29,75,47],[42,54,24,1,55,25],[23,45,15,28,46,16],[17,145,115],[10,74,46,23,75,47],[10,54,24,35,55,25],[19,45,15,35,46,16],[17,145,115,1,146,116],[14,74,46,21,75,47],[29,54,24,19,55,25],[11,45,15,46,46,16],[13,145,115,6,146,116],[14,74,46,23,75,47],[44,54,24,7,55,25],[59,46,16,1,47,17],[12,151,121,7,152,122],[12,75,47,26,76,48],[39,54,24,14,55,25],[22,45,15,41,46,16],[6,151,121,14,152,122],[6,75,47,34,76,48],[46,54,24,10,55,25],[2,45,15,64,46,16],[17,152,122,4,153,123],[29,74,46,14,75,47],[49,54,24,10,55,25],[24,45,15,46,46,16],[4,152,122,18,153,123],[13,74,46,32,75,47],[48,54,24,14,55,25],[42,45,15,32,46,16],[20,147,117,4,148,118],[40,75,47,7,76,48],[43,54,24,22,55,25],[10,45,15,67,46,16],[19,148,118,6,149,119],[18,75,47,31,76,48],[34,54,24,34,55,25],[20,45,15,61,46,16]],r=function(t,r){var e={};return e.totalCount=t,e.dataCount=r,e},e={},n=function(r,e){switch(e){case f.L:return t[4*(r-1)+0];case f.M:return t[4*(r-1)+1];case f.Q:return t[4*(r-1)+2];case f.H:return t[4*(r-1)+3];default:return}};return e.getRSBlocks=function(t,e){var i=n(t,e);if("undefined"==typeof i)throw new Error("bad rs block [url=home.php?mod=space&uid=5302]@[/url] typeNumber:"+t+"/errorCorrectLevel:"+e);for(var o=i.length/3,a=new Array,u=0;u<o;u+=1)for(var f=i[3*u+0],s=i[3*u+1],c=i[3*u+2],l=0;l<f;l+=1)a.push(r(s,c));return a},e}(),v=function(){var t=new Array,r=0,e={getBuffer:function(){return t},getAt:function(r){var e=Math.floor(r/8);return 1==(t[e]>>>7-r%8&1)},put:function(t,r){for(var n=0;n<r;n+=1)e.putBit(1==(t>>>r-n-1&1))},getLengthInBits:function(){return r},putBit:function(e){var n=Math.floor(r/8);t.length<=n&&t.push(0),e&&(t[n]|=128>>>r%8),r+=1}};return e},h=function(t){for(var r=u.MODE_8BIT_BYTE,e=t,n=[],i={},o=0,a=e.length;o<a;o++){var f=[],s=e.charCodeAt(o);s>65536?(f[0]=240|(1835008&s)>>>18,f[1]=128|(258048&s)>>>12,f[2]=128|(4032&s)>>>6,f[3]=128|63&s):s>2048?(f[0]=224|(61440&s)>>>12,f[1]=128|(4032&s)>>>6,f[2]=128|63&s):s>128?(f[0]=192|(1984&s)>>>6,f[1]=128|63&s):f[0]=s,n.push(f)}n=Array.prototype.concat.apply([],n),n.length!=e.length&&(n.unshift(191),n.unshift(187),n.unshift(239));var c=n;return i.getMode=function(){return r},i.getLength=function(t){return c.length},i.write=function(t){for(var r=0;r<c.length;r+=1)t.put(c[r],8)},i},d=function(){var t=new Array,r={writeByte:function(r){t.push(255&r)},writeShort:function(t){r.writeByte(t),r.writeByte(t>>>8)},writeBytes:function(t,e,n){e=e||0,n=n||t.length;for(var i=0;i<n;i+=1)r.writeByte(t[i+e])},writeString:function(t){for(var e=0;e<t.length;e+=1)r.writeByte(t.charCodeAt(e))},toByteArray:function(){return t},toString:function(){var r="";r+="[";for(var e=0;e<t.length;e+=1)e>0&&(r+=","),r+=t[e];return r+"]"}};return r},w=function(){var t=0,r=0,e=0,n="",i={},o=function(t){n+=String.fromCharCode(a(63&t))},a=function(t){if(t<0);else{if(t<26)return 65+t;if(t<52)return t-26+97;if(t<62)return t-52+48;if(62==t)return 43;if(63==t)return 47}throw new Error("n:"+t)};return i.writeByte=function(n){for(t=t<<8|255&n,r+=8,e+=1;r>=6;)o(t>>>r-6),r-=6},i.flush=function(){if(r>0&&(o(t<<6-r),t=0,r=0),e%3!=0)for(var i=3-e%3,a=0;a<i;a+=1)n+="="},i.toString=function(){return n},i},p=function(t){var r=t,e=0,n=0,i=0,o={read:function(){for(;i<8;){if(e>=r.length){if(0==i)return-1;throw new Error("unexpected end of file./"+i)}var t=r.charAt(e);if(e+=1,"="==t)return i=0,-1;t.match(/^\s$/)||(n=n<<6|a(t.charCodeAt(0)),i+=6)}var o=n>>>i-8&255;return i-=8,o}},a=function(t){if(65<=t&&t<=90)return t-65;if(97<=t&&t<=122)return t-97+26;if(48<=t&&t<=57)return t-48+52;if(43==t)return 62;if(47==t)return 63;throw new Error("c:"+t)};return o},y=function(t,r){var e=t,n=r,i=new Array(t*r),o={setPixel:function(t,r,n){i[r*e+t]=n},write:function(t){t.writeString("GIF87a"),t.writeShort(e),t.writeShort(n),t.writeByte(128),t.writeByte(0),t.writeByte(0),t.writeByte(0),t.writeByte(0),t.writeByte(0),t.writeByte(255),t.writeByte(255),t.writeByte(255),t.writeString(","),t.writeShort(0),t.writeShort(0),t.writeShort(e),t.writeShort(n),t.writeByte(0);var r=2,i=u(r);t.writeByte(r);for(var o=0;i.length-o>255;)t.writeByte(255),t.writeBytes(i,o,255),o+=255;t.writeByte(i.length-o),t.writeBytes(i,o,i.length-o),t.writeByte(0),t.writeString(";")}},a=function(t){var r=t,e=0,n=0,i={write:function(t,i){if(t>>>i!=0)throw new Error("length over");for(;e+i>=8;)r.writeByte(255&(t<<e|n)),i-=8-e,t>>>=8-e,n=0,e=0;n|=t<<e,e+=i},flush:function(){e>0&&r.writeByte(n)}};return i},u=function(t){for(var r=1<<t,e=1+(1<<t),n=t+1,o=f(),u=0;u<r;u+=1)o.add(String.fromCharCode(u));o.add(String.fromCharCode(r)),o.add(String.fromCharCode(e));var s=d(),c=a(s);c.write(r,n);var l=0,g=String.fromCharCode(i[l]);for(l+=1;l<i.length;){var v=String.fromCharCode(i[l]);l+=1,o.contains(g+v)?g+=v:(c.write(o.indexOf(g),n),o.size()<4095&&(o.size()==1<<n&&(n+=1),o.add(g+v)),g=v)}return c.write(o.indexOf(g),n),c.write(e,n),c.flush(),s.toByteArray()},f=function(){var t={},r=0,e={add:function(n){if(e.contains(n))throw new Error("dup key:"+n);t[n]=r,r+=1},size:function(){return r},indexOf:function(r){return t[r]},contains:function(r){return"undefined"!=typeof t[r]}};return e};return o},_=function(t,r,e,n){for(var i=y(t,r),o=0;o<r;o+=1)for(var a=0;a<t;a+=1)i.setPixel(a,o,e(a,o));var u=d();i.write(u);for(var f=w(),s=u.toByteArray(),c=0;c<s.length;c+=1)f.writeByte(s[c]);f.flush();var l="";return l+="data:image/gif;base64,",l+f},m=function(t,r){r=r||{};var e,n=r.typeNumber||4,o=r.errorCorrectLevel||"M",u=r.size||500;try{e=a(n,o||"M"),e.addData(t),e.make()}catch(r){if(n>=40)throw new Error("Text too long to encode");return gen(t,{size:u,errorCorrectLevel:o,typeNumber:n+1})}var f=(0,i.default)(u/e.getModuleCount()),s=(0,i.default)((u-e.getModuleCount()*f)/2);return e.createImgTag(f,s,u)};t.exports={createQrCodeImg:m}},"65ee":function(t,r,e){"use strict";e.r(r);var n=e("3163"),i=e.n(n);for(var o in n)"default"!==o&&function(t){e.d(r,t,function(){return n[t]})}(o);r["default"]=i.a},cdec:function(t,r,e){"use strict";e.r(r);var n=e("17ef"),i=e("65ee");for(var o in i)"default"!==o&&function(t){e.d(r,t,function(){return i[t]})}(o);var a=e("2877"),u=Object(a["a"])(i["default"],n["a"],n["b"],!1,null,"7ec3b9a6",null);r["default"]=u.exports}}]);