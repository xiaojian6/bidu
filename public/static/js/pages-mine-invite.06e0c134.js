(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-mine-invite"],{"05b3":function(t,e,r){"use strict";var n=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-uni-view",{staticClass:"tki-qrcode"},[r("v-uni-canvas",{staticClass:"tki-qrcode-canvas",style:{width:t.cpSize+"px",height:t.cpSize+"px"},attrs:{"canvas-id":t.cid}}),r("v-uni-image",{directives:[{name:"show",rawName:"v-show",value:t.show,expression:"show"}],style:{width:t.cpSize+"px",height:t.cpSize+"px"},attrs:{src:t.result}})],1)},o=[];r.d(e,"a",function(){return n}),r.d(e,"b",function(){return o})},"0bcc":function(t,e,r){"use strict";var n=function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-uni-view",{class:["vh100",{dark:"dark"==t.theme}]},[r("v-uni-view",{staticClass:"flex column alcenter jscenter"},[r("v-uni-view",{staticClass:"mt100"},[r("v-uni-view",{staticClass:"qrimg"},[r("tki-qrcode",{ref:"qrcode",attrs:{val:t.val,size:t.size,unit:t.unit,background:t.background,foreground:t.foreground,pdground:t.pdground,icon:t.icon,iconSize:t.iconsize,lv:t.lv,onval:t.onval,loadMake:t.loadMake},on:{result:function(e){e=t.$handleEvent(e),t.qrR(e)}}})],1)],1),r("v-uni-view",{staticClass:"mtb20 tc"},[t._v("推广码："+t._s(t.code))]),r("v-uni-view",{staticClass:"tc "},[t._v("扫上面的二维码图案或输入推广码，确认注册")])],1)],1)},o=[];r.d(e,"a",function(){return n}),r.d(e,"b",function(){return o})},1982:function(t,e,r){var n=r("b638");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=r("4f06").default;o("40dcc681",n,!0,{sourceMap:!1,shadowMode:!1})},3519:function(t,e,r){"use strict";r.r(e);var n=r("83b4"),o=r.n(n);for(var i in n)"default"!==i&&function(t){r.d(e,t,function(){return n[t]})}(i);e["default"]=o.a},"41b0":function(t,e,r){"use strict";r.r(e);var n=r("4b6d"),o=r.n(n);for(var i in n)"default"!==i&&function(t){r.d(e,t,function(){return n[t]})}(i);e["default"]=o.a},"4b6d":function(t,e,r){"use strict";var n=r("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n(r("cebc")),i=r("2f62"),u=(n(r("64cc")),r("2fc1")),a=n(r("c1a9")),s={components:{tkiQrcode:a.default},data:function(){return{code:"",codeImg:"",val:"",size:300,unit:"upx",background:"#000000",foreground:"#ffffff",pdground:"#ffffff",icon:"",iconsize:40,lv:3,onval:!0,loadMake:!0}},computed:(0,o.default)({},(0,i.mapState)({theme:function(t){return t.theme}})),onLoad:function(t){this.code=t.code,this.$utils.setTheme(this.theme),uni.setNavigationBarTitle({title:this.$t("bind").tuiguang})},onShow:function(){var t=u.domain+"/mobile/#/pages/mine/register?invite_code="+this.code;console.log(t),this.val=t},methods:{qrR:function(t){}}};e.default=s},"50ed1":function(t,e,r){"use strict";r.r(e);var n=r("0bcc"),o=r("41b0");for(var i in o)"default"!==i&&function(t){r.d(e,t,function(){return o[t]})}(i);r("ec92");var u=r("2877"),a=Object(u["a"])(o["default"],n["a"],n["b"],!1,null,"9b120164",null);e["default"]=a.exports},"64cc":function(t,e,r){"use strict";var n=r("288e"),o=n(r("e814"));r("4917");var i=function(t,e){var r=236,n=17,o=t,i=a[e],u=null,s=0,l=null,v=new Array,p={},m=function(t,e){s=4*o+17,u=function(t){for(var e=new Array(t),r=0;r<t;r+=1){e[r]=new Array(t);for(var n=0;n<t;n+=1)e[r][n]=null}return e}(s),w(0,0),w(s-7,0),w(0,s-7),A(),b(),E(t,e),o>=7&&C(t),null==l&&(l=k(o,i,v)),B(l,e)},w=function(t,e){for(var r=-1;r<=7;r+=1)if(!(t+r<=-1||s<=t+r))for(var n=-1;n<=7;n+=1)e+n<=-1||s<=e+n||(u[t+r][e+n]=0<=r&&r<=6&&(0==n||6==n)||0<=n&&n<=6&&(0==r||6==r)||2<=r&&r<=4&&2<=n&&n<=4)},T=function(){for(var t=0,e=0,r=0;r<8;r+=1){m(!0,r);var n=f.getLostPoint(p);(0==r||t>n)&&(t=n,e=r)}return e},b=function(){for(var t=8;t<s-8;t+=1)null==u[t][6]&&(u[t][6]=t%2==0);for(var e=8;e<s-8;e+=1)null==u[6][e]&&(u[6][e]=e%2==0)},A=function(){for(var t=f.getPatternPosition(o),e=0;e<t.length;e+=1)for(var r=0;r<t.length;r+=1){var n=t[e],i=t[r];if(null==u[n][i])for(var a=-2;a<=2;a+=1)for(var s=-2;s<=2;s+=1)u[n+a][i+s]=-2==a||2==a||-2==s||2==s||0==a&&0==s}},C=function(t){for(var e=f.getBCHTypeNumber(o),r=0;r<18;r+=1){var n=!t&&1==(e>>r&1);u[Math.floor(r/3)][r%3+s-8-3]=n}for(r=0;r<18;r+=1){n=!t&&1==(e>>r&1);u[r%3+s-8-3][Math.floor(r/3)]=n}},E=function(t,e){for(var r=i<<3|e,n=f.getBCHTypeInfo(r),o=0;o<15;o+=1){var a=!t&&1==(n>>o&1);o<6?u[o][8]=a:o<8?u[o+1][8]=a:u[s-15+o][8]=a}for(o=0;o<15;o+=1){a=!t&&1==(n>>o&1);o<8?u[8][s-o-1]=a:o<9?u[8][15-o-1+1]=a:u[8][15-o-1]=a}u[s-8][8]=!t},B=function(t,e){for(var r=-1,n=s-1,o=7,i=0,a=f.getMaskFunction(e),l=s-1;l>0;l-=2){6==l&&(l-=1);while(1){for(var c=0;c<2;c+=1)if(null==u[n][l-c]){var h=!1;i<t.length&&(h=1==(t[i]>>>o&1));var d=a(n,l-c);d&&(h=!h),u[n][l-c]=h,o-=1,-1==o&&(i+=1,o=7)}if(n+=r,n<0||s<=n){n-=r,r=-r;break}}}},P=function(t,e){for(var r=0,n=0,o=0,i=new Array(e.length),u=new Array(e.length),a=0;a<e.length;a+=1){var s=e[a].dataCount,l=e[a].totalCount-s;n=Math.max(n,s),o=Math.max(o,l),i[a]=new Array(s);for(var h=0;h<i[a].length;h+=1)i[a][h]=255&t.getBuffer()[h+r];r+=s;var d=f.getErrorCorrectPolynomial(l),g=c(i[a],d.getLength()-1),v=g.mod(d);u[a]=new Array(d.getLength()-1);for(h=0;h<u[a].length;h+=1){var p=h+v.getLength()-u[a].length;u[a][h]=p>=0?v.getAt(p):0}}var m=0;for(h=0;h<e.length;h+=1)m+=e[h].totalCount;var w=new Array(m),y=0;for(h=0;h<n;h+=1)for(a=0;a<e.length;a+=1)h<i[a].length&&(w[y]=i[a][h],y+=1);for(h=0;h<o;h+=1)for(a=0;a<e.length;a+=1)h<u[a].length&&(w[y]=u[a][h],y+=1);return w},k=function(t,e,o){for(var i=h.getRSBlocks(t,e),u=d(),a=0;a<o.length;a+=1){var s=o[a];u.put(s.getMode(),4),u.put(s.getLength(),f.getLengthInBits(s.getMode(),t)),s.write(u)}var l=0;for(a=0;a<i.length;a+=1)l+=i[a].dataCount;if(u.getLengthInBits()>8*l)throw new Error("code length overflow. ("+u.getLengthInBits()+">"+8*l+")");u.getLengthInBits()+4<=8*l&&u.put(0,4);while(u.getLengthInBits()%8!=0)u.putBit(!1);while(1){if(u.getLengthInBits()>=8*l)break;if(u.put(r,8),u.getLengthInBits()>=8*l)break;u.put(n,8)}return P(u,i)};return p.addData=function(t){var e=g(t);v.push(e),l=null},p.isDark=function(t,e){if(t<0||s<=t||e<0||s<=e)throw new Error(t+","+e);return u[t][e]},p.getModuleCount=function(){return s},p.make=function(){m(!1,T())},p.createTableTag=function(t,e){t=t||2,e="undefined"==typeof e?4*t:e;var r="";r+='<table style="',r+=" border-width: 0px; border-style: none;",r+=" border-collapse: collapse;",r+=" padding: 0px; margin: "+e+"px;",r+='">',r+="<tbody>";for(var n=0;n<p.getModuleCount();n+=1){r+="<tr>";for(var o=0;o<p.getModuleCount();o+=1)r+='<td style="',r+=" border-width: 0px; border-style: none;",r+=" border-collapse: collapse;",r+=" padding: 0px; margin: 0px;",r+=" width: "+t+"px;",r+=" height: "+t+"px;",r+=" background-color: ",r+=p.isDark(n,o)?"#000000":"#ffffff",r+=";",r+='"/>';r+="</tr>"}return r+="</tbody>",r+="</table>",r},p.createImgTag=function(t,e,r){t=t||2,e="undefined"==typeof e?4*t:e;var n=e,o=p.getModuleCount()*t+e;return y(r,r,function(e,r){if(n<=e&&e<o&&n<=r&&r<o){var i=Math.floor((e-n)/t),u=Math.floor((r-n)/t);return p.isDark(u,i)?0:1}return 1})},p};i.stringToBytes=function(t){for(var e=new Array,r=0;r<t.length;r+=1){var n=t.charCodeAt(r);e.push(255&n)}return e},i.createStringToBytes=function(t,e){var r=function(){var r=m(t),n=function(){var t=r.read();if(-1==t)throw new Error;return t},o=0,i={};while(1){var u=r.read();if(-1==u)break;var a=n(),s=n(),f=n(),l=String.fromCharCode(u<<8|a),c=s<<8|f;i[l]=c,o+=1}if(o!=e)throw new Error(o+" != "+e);return i}(),n="?".charCodeAt(0);return function(t){for(var e=new Array,o=0;o<t.length;o+=1){var i=t.charCodeAt(o);if(i<128)e.push(i);else{var u=r[t.charAt(o)];"number"==typeof u?(255&u)==u?e.push(u):(e.push(u>>>8),e.push(255&u)):e.push(n)}}return e}};var u={MODE_NUMBER:1,MODE_ALPHA_NUM:2,MODE_8BIT_BYTE:4,MODE_KANJI:8},a={L:1,M:0,Q:3,H:2},s={PATTERN000:0,PATTERN001:1,PATTERN010:2,PATTERN011:3,PATTERN100:4,PATTERN101:5,PATTERN110:6,PATTERN111:7},f=function(){var t=[[],[6,18],[6,22],[6,26],[6,30],[6,34],[6,22,38],[6,24,42],[6,26,46],[6,28,50],[6,30,54],[6,32,58],[6,34,62],[6,26,46,66],[6,26,48,70],[6,26,50,74],[6,30,54,78],[6,30,56,82],[6,30,58,86],[6,34,62,90],[6,28,50,72,94],[6,26,50,74,98],[6,30,54,78,102],[6,28,54,80,106],[6,32,58,84,110],[6,30,58,86,114],[6,34,62,90,118],[6,26,50,74,98,122],[6,30,54,78,102,126],[6,26,52,78,104,130],[6,30,56,82,108,134],[6,34,60,86,112,138],[6,30,58,86,114,142],[6,34,62,90,118,146],[6,30,54,78,102,126,150],[6,24,50,76,102,128,154],[6,28,54,80,106,132,158],[6,32,58,84,110,136,162],[6,26,54,82,110,138,166],[6,30,58,86,114,142,170]],e=1335,r=7973,n=21522,o={},i=function(t){var e=0;while(0!=t)e+=1,t>>>=1;return e};return o.getBCHTypeInfo=function(t){var r=t<<10;while(i(r)-i(e)>=0)r^=e<<i(r)-i(e);return(t<<10|r)^n},o.getBCHTypeNumber=function(t){var e=t<<12;while(i(e)-i(r)>=0)e^=r<<i(e)-i(r);return t<<12|e},o.getPatternPosition=function(e){return t[e-1]},o.getMaskFunction=function(t){switch(t){case s.PATTERN000:return function(t,e){return(t+e)%2==0};case s.PATTERN001:return function(t,e){return t%2==0};case s.PATTERN010:return function(t,e){return e%3==0};case s.PATTERN011:return function(t,e){return(t+e)%3==0};case s.PATTERN100:return function(t,e){return(Math.floor(t/2)+Math.floor(e/3))%2==0};case s.PATTERN101:return function(t,e){return t*e%2+t*e%3==0};case s.PATTERN110:return function(t,e){return(t*e%2+t*e%3)%2==0};case s.PATTERN111:return function(t,e){return(t*e%3+(t+e)%2)%2==0};default:throw new Error("bad maskPattern:"+t)}},o.getErrorCorrectPolynomial=function(t){for(var e=c([1],0),r=0;r<t;r+=1)e=e.multiply(c([1,l.gexp(r)],0));return e},o.getLengthInBits=function(t,e){if(1<=e&&e<10)switch(t){case u.MODE_NUMBER:return 10;case u.MODE_ALPHA_NUM:return 9;case u.MODE_8BIT_BYTE:return 8;case u.MODE_KANJI:return 8;default:throw new Error("mode:"+t)}else if(e<27)switch(t){case u.MODE_NUMBER:return 12;case u.MODE_ALPHA_NUM:return 11;case u.MODE_8BIT_BYTE:return 16;case u.MODE_KANJI:return 10;default:throw new Error("mode:"+t)}else{if(!(e<41))throw new Error("type:"+e);switch(t){case u.MODE_NUMBER:return 14;case u.MODE_ALPHA_NUM:return 13;case u.MODE_8BIT_BYTE:return 16;case u.MODE_KANJI:return 12;default:throw new Error("mode:"+t)}}},o.getLostPoint=function(t){for(var e=t.getModuleCount(),r=0,n=0;n<e;n+=1)for(var o=0;o<e;o+=1){for(var i=0,u=t.isDark(n,o),a=-1;a<=1;a+=1)if(!(n+a<0||e<=n+a))for(var s=-1;s<=1;s+=1)o+s<0||e<=o+s||0==a&&0==s||u==t.isDark(n+a,o+s)&&(i+=1);i>5&&(r+=3+i-5)}for(n=0;n<e-1;n+=1)for(o=0;o<e-1;o+=1){var f=0;t.isDark(n,o)&&(f+=1),t.isDark(n+1,o)&&(f+=1),t.isDark(n,o+1)&&(f+=1),t.isDark(n+1,o+1)&&(f+=1),0!=f&&4!=f||(r+=3)}for(n=0;n<e;n+=1)for(o=0;o<e-6;o+=1)t.isDark(n,o)&&!t.isDark(n,o+1)&&t.isDark(n,o+2)&&t.isDark(n,o+3)&&t.isDark(n,o+4)&&!t.isDark(n,o+5)&&t.isDark(n,o+6)&&(r+=40);for(o=0;o<e;o+=1)for(n=0;n<e-6;n+=1)t.isDark(n,o)&&!t.isDark(n+1,o)&&t.isDark(n+2,o)&&t.isDark(n+3,o)&&t.isDark(n+4,o)&&!t.isDark(n+5,o)&&t.isDark(n+6,o)&&(r+=40);var l=0;for(o=0;o<e;o+=1)for(n=0;n<e;n+=1)t.isDark(n,o)&&(l+=1);var c=Math.abs(100*l/e/e-50)/5;return r+=10*c,r},o}(),l=function(){for(var t=new Array(256),e=new Array(256),r=0;r<8;r+=1)t[r]=1<<r;for(r=8;r<256;r+=1)t[r]=t[r-4]^t[r-5]^t[r-6]^t[r-8];for(r=0;r<255;r+=1)e[t[r]]=r;var n={glog:function(t){if(t<1)throw new Error("glog("+t+")");return e[t]},gexp:function(e){while(e<0)e+=255;while(e>=256)e-=255;return t[e]}};return n}();function c(t,e){if("undefined"==typeof t.length)throw new Error(t.length+"/"+e);var r=function(){var r=0;while(r<t.length&&0==t[r])r+=1;for(var n=new Array(t.length-r+e),o=0;o<t.length-r;o+=1)n[o]=t[o+r];return n}(),n={getAt:function(t){return r[t]},getLength:function(){return r.length},multiply:function(t){for(var e=new Array(n.getLength()+t.getLength()-1),r=0;r<n.getLength();r+=1)for(var o=0;o<t.getLength();o+=1)e[r+o]^=l.gexp(l.glog(n.getAt(r))+l.glog(t.getAt(o)));return c(e,0)},mod:function(t){if(n.getLength()-t.getLength()<0)return n;for(var e=l.glog(n.getAt(0))-l.glog(t.getAt(0)),r=new Array(n.getLength()),o=0;o<n.getLength();o+=1)r[o]=n.getAt(o);for(o=0;o<t.getLength();o+=1)r[o]^=l.gexp(l.glog(t.getAt(o))+e);return c(r,0).mod(t)}};return n}var h=function(){var t=[[1,26,19],[1,26,16],[1,26,13],[1,26,9],[1,44,34],[1,44,28],[1,44,22],[1,44,16],[1,70,55],[1,70,44],[2,35,17],[2,35,13],[1,100,80],[2,50,32],[2,50,24],[4,25,9],[1,134,108],[2,67,43],[2,33,15,2,34,16],[2,33,11,2,34,12],[2,86,68],[4,43,27],[4,43,19],[4,43,15],[2,98,78],[4,49,31],[2,32,14,4,33,15],[4,39,13,1,40,14],[2,121,97],[2,60,38,2,61,39],[4,40,18,2,41,19],[4,40,14,2,41,15],[2,146,116],[3,58,36,2,59,37],[4,36,16,4,37,17],[4,36,12,4,37,13],[2,86,68,2,87,69],[4,69,43,1,70,44],[6,43,19,2,44,20],[6,43,15,2,44,16],[4,101,81],[1,80,50,4,81,51],[4,50,22,4,51,23],[3,36,12,8,37,13],[2,116,92,2,117,93],[6,58,36,2,59,37],[4,46,20,6,47,21],[7,42,14,4,43,15],[4,133,107],[8,59,37,1,60,38],[8,44,20,4,45,21],[12,33,11,4,34,12],[3,145,115,1,146,116],[4,64,40,5,65,41],[11,36,16,5,37,17],[11,36,12,5,37,13],[5,109,87,1,110,88],[5,65,41,5,66,42],[5,54,24,7,55,25],[11,36,12],[5,122,98,1,123,99],[7,73,45,3,74,46],[15,43,19,2,44,20],[3,45,15,13,46,16],[1,135,107,5,136,108],[10,74,46,1,75,47],[1,50,22,15,51,23],[2,42,14,17,43,15],[5,150,120,1,151,121],[9,69,43,4,70,44],[17,50,22,1,51,23],[2,42,14,19,43,15],[3,141,113,4,142,114],[3,70,44,11,71,45],[17,47,21,4,48,22],[9,39,13,16,40,14],[3,135,107,5,136,108],[3,67,41,13,68,42],[15,54,24,5,55,25],[15,43,15,10,44,16],[4,144,116,4,145,117],[17,68,42],[17,50,22,6,51,23],[19,46,16,6,47,17],[2,139,111,7,140,112],[17,74,46],[7,54,24,16,55,25],[34,37,13],[4,151,121,5,152,122],[4,75,47,14,76,48],[11,54,24,14,55,25],[16,45,15,14,46,16],[6,147,117,4,148,118],[6,73,45,14,74,46],[11,54,24,16,55,25],[30,46,16,2,47,17],[8,132,106,4,133,107],[8,75,47,13,76,48],[7,54,24,22,55,25],[22,45,15,13,46,16],[10,142,114,2,143,115],[19,74,46,4,75,47],[28,50,22,6,51,23],[33,46,16,4,47,17],[8,152,122,4,153,123],[22,73,45,3,74,46],[8,53,23,26,54,24],[12,45,15,28,46,16],[3,147,117,10,148,118],[3,73,45,23,74,46],[4,54,24,31,55,25],[11,45,15,31,46,16],[7,146,116,7,147,117],[21,73,45,7,74,46],[1,53,23,37,54,24],[19,45,15,26,46,16],[5,145,115,10,146,116],[19,75,47,10,76,48],[15,54,24,25,55,25],[23,45,15,25,46,16],[13,145,115,3,146,116],[2,74,46,29,75,47],[42,54,24,1,55,25],[23,45,15,28,46,16],[17,145,115],[10,74,46,23,75,47],[10,54,24,35,55,25],[19,45,15,35,46,16],[17,145,115,1,146,116],[14,74,46,21,75,47],[29,54,24,19,55,25],[11,45,15,46,46,16],[13,145,115,6,146,116],[14,74,46,23,75,47],[44,54,24,7,55,25],[59,46,16,1,47,17],[12,151,121,7,152,122],[12,75,47,26,76,48],[39,54,24,14,55,25],[22,45,15,41,46,16],[6,151,121,14,152,122],[6,75,47,34,76,48],[46,54,24,10,55,25],[2,45,15,64,46,16],[17,152,122,4,153,123],[29,74,46,14,75,47],[49,54,24,10,55,25],[24,45,15,46,46,16],[4,152,122,18,153,123],[13,74,46,32,75,47],[48,54,24,14,55,25],[42,45,15,32,46,16],[20,147,117,4,148,118],[40,75,47,7,76,48],[43,54,24,22,55,25],[10,45,15,67,46,16],[19,148,118,6,149,119],[18,75,47,31,76,48],[34,54,24,34,55,25],[20,45,15,61,46,16]],e=function(t,e){var r={};return r.totalCount=t,r.dataCount=e,r},r={},n=function(e,r){switch(r){case a.L:return t[4*(e-1)+0];case a.M:return t[4*(e-1)+1];case a.Q:return t[4*(e-1)+2];case a.H:return t[4*(e-1)+3];default:return}};return r.getRSBlocks=function(t,r){var o=n(t,r);if("undefined"==typeof o)throw new Error("bad rs block [url=home.php?mod=space&uid=5302]@[/url] typeNumber:"+t+"/errorCorrectLevel:"+r);for(var i=o.length/3,u=new Array,a=0;a<i;a+=1)for(var s=o[3*a+0],f=o[3*a+1],l=o[3*a+2],c=0;c<s;c+=1)u.push(e(f,l));return u},r}(),d=function(){var t=new Array,e=0,r={getBuffer:function(){return t},getAt:function(e){var r=Math.floor(e/8);return 1==(t[r]>>>7-e%8&1)},put:function(t,e){for(var n=0;n<e;n+=1)r.putBit(1==(t>>>e-n-1&1))},getLengthInBits:function(){return e},putBit:function(r){var n=Math.floor(e/8);t.length<=n&&t.push(0),r&&(t[n]|=128>>>e%8),e+=1}};return r},g=function(t){for(var e=u.MODE_8BIT_BYTE,r=t,n=[],o={},i=0,a=r.length;i<a;i++){var s=[],f=r.charCodeAt(i);f>65536?(s[0]=240|(1835008&f)>>>18,s[1]=128|(258048&f)>>>12,s[2]=128|(4032&f)>>>6,s[3]=128|63&f):f>2048?(s[0]=224|(61440&f)>>>12,s[1]=128|(4032&f)>>>6,s[2]=128|63&f):f>128?(s[0]=192|(1984&f)>>>6,s[1]=128|63&f):s[0]=f,n.push(s)}n=Array.prototype.concat.apply([],n),n.length!=r.length&&(n.unshift(191),n.unshift(187),n.unshift(239));var l=n;return o.getMode=function(){return e},o.getLength=function(t){return l.length},o.write=function(t){for(var e=0;e<l.length;e+=1)t.put(l[e],8)},o},v=function(){var t=new Array,e={writeByte:function(e){t.push(255&e)},writeShort:function(t){e.writeByte(t),e.writeByte(t>>>8)},writeBytes:function(t,r,n){r=r||0,n=n||t.length;for(var o=0;o<n;o+=1)e.writeByte(t[o+r])},writeString:function(t){for(var r=0;r<t.length;r+=1)e.writeByte(t.charCodeAt(r))},toByteArray:function(){return t},toString:function(){var e="";e+="[";for(var r=0;r<t.length;r+=1)r>0&&(e+=","),e+=t[r];return e+="]",e}};return e},p=function(){var t=0,e=0,r=0,n="",o={},i=function(t){n+=String.fromCharCode(u(63&t))},u=function(t){if(t<0);else{if(t<26)return 65+t;if(t<52)return t-26+97;if(t<62)return t-52+48;if(62==t)return 43;if(63==t)return 47}throw new Error("n:"+t)};return o.writeByte=function(n){t=t<<8|255&n,e+=8,r+=1;while(e>=6)i(t>>>e-6),e-=6},o.flush=function(){if(e>0&&(i(t<<6-e),t=0,e=0),r%3!=0)for(var o=3-r%3,u=0;u<o;u+=1)n+="="},o.toString=function(){return n},o},m=function(t){var e=t,r=0,n=0,o=0,i={read:function(){while(o<8){if(r>=e.length){if(0==o)return-1;throw new Error("unexpected end of file./"+o)}var t=e.charAt(r);if(r+=1,"="==t)return o=0,-1;t.match(/^\s$/)||(n=n<<6|u(t.charCodeAt(0)),o+=6)}var i=n>>>o-8&255;return o-=8,i}},u=function(t){if(65<=t&&t<=90)return t-65;if(97<=t&&t<=122)return t-97+26;if(48<=t&&t<=57)return t-48+52;if(43==t)return 62;if(47==t)return 63;throw new Error("c:"+t)};return i},w=function(t,e){var r=t,n=e,o=new Array(t*e),i={setPixel:function(t,e,n){o[e*r+t]=n},write:function(t){t.writeString("GIF87a"),t.writeShort(r),t.writeShort(n),t.writeByte(128),t.writeByte(0),t.writeByte(0),t.writeByte(0),t.writeByte(0),t.writeByte(0),t.writeByte(255),t.writeByte(255),t.writeByte(255),t.writeString(","),t.writeShort(0),t.writeShort(0),t.writeShort(r),t.writeShort(n),t.writeByte(0);var e=2,o=a(e);t.writeByte(e);var i=0;while(o.length-i>255)t.writeByte(255),t.writeBytes(o,i,255),i+=255;t.writeByte(o.length-i),t.writeBytes(o,i,o.length-i),t.writeByte(0),t.writeString(";")}},u=function(t){var e=t,r=0,n=0,o={write:function(t,o){if(t>>>o!=0)throw new Error("length over");while(r+o>=8)e.writeByte(255&(t<<r|n)),o-=8-r,t>>>=8-r,n=0,r=0;n|=t<<r,r+=o},flush:function(){r>0&&e.writeByte(n)}};return o},a=function(t){for(var e=1<<t,r=1+(1<<t),n=t+1,i=s(),a=0;a<e;a+=1)i.add(String.fromCharCode(a));i.add(String.fromCharCode(e)),i.add(String.fromCharCode(r));var f=v(),l=u(f);l.write(e,n);var c=0,h=String.fromCharCode(o[c]);c+=1;while(c<o.length){var d=String.fromCharCode(o[c]);c+=1,i.contains(h+d)?h+=d:(l.write(i.indexOf(h),n),i.size()<4095&&(i.size()==1<<n&&(n+=1),i.add(h+d)),h=d)}return l.write(i.indexOf(h),n),l.write(r,n),l.flush(),f.toByteArray()},s=function(){var t={},e=0,r={add:function(n){if(r.contains(n))throw new Error("dup key:"+n);t[n]=e,e+=1},size:function(){return e},indexOf:function(e){return t[e]},contains:function(e){return"undefined"!=typeof t[e]}};return r};return i},y=function(t,e,r,n){for(var o=w(t,e),i=0;i<e;i+=1)for(var u=0;u<t;u+=1)o.setPixel(u,i,r(u,i));var a=v();o.write(a);for(var s=p(),f=a.toByteArray(),l=0;l<f.length;l+=1)s.writeByte(f[l]);s.flush();var c="";return c+="data:image/gif;base64,",c+=s,c},T=function(t,e){e=e||{};var r,n=e.typeNumber||4,u=e.errorCorrectLevel||"M",a=e.size||500;try{r=i(n,u||"M"),r.addData(t),r.make()}catch(l){if(n>=40)throw new Error("Text too long to encode");return gen(t,{size:a,errorCorrectLevel:u,typeNumber:n+1})}var s=(0,o.default)(a/r.getModuleCount()),f=(0,o.default)((a-r.getModuleCount()*s)/2);return r.createImgTag(s,f,a)};t.exports={createQrCodeImg:T}},"6c7b":function(t,e,r){var n=r("5ca1");n(n.P,"Array",{fill:r("36bd")}),r("9c6c")("fill")},"71d4":function(t,e,r){var n=r("fb47");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var o=r("4f06").default;o("5547ba45",n,!0,{sourceMap:!1,shadowMode:!1})},"83b4":function(t,e,r){"use strict";var n=r("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n(r("f499"));r("c5f6");var i,u=n(r("9285")),a={name:"tki-qrcode",props:{cid:{type:String,default:"tki-qrcode-canvas"},size:{type:Number,default:200},unit:{type:String,default:"upx"},show:{type:Boolean,default:!0},val:{type:String,default:""},background:{type:String,default:"#ffffff"},foreground:{type:String,default:"#000000"},pdground:{type:String,default:"#000000"},icon:{type:String,default:""},iconSize:{type:Number,default:40},lv:{type:Number,default:3},onval:{type:Boolean,default:!1},loadMake:{type:Boolean,default:!1},usingComponents:{type:Boolean,default:!0},showLoading:{type:Boolean,default:!0},loadingText:{type:String,default:"二维码生成中"}},data:function(){return{result:""}},methods:{_makeCode:function(){var t=this;this._empty(this.val)?uni.showToast({title:"二维码内容不能为空",icon:"none",duration:2e3}):i=new u.default({context:t,canvasId:t.cid,usingComponents:t.usingComponents,showLoading:t.showLoading,loadingText:t.loadingText,text:t.val,size:t.cpSize,background:t.background,foreground:t.foreground,pdground:t.pdground,correctLevel:t.lv,image:t.icon,imageSize:t.iconSize,cbResult:function(e){t._result(e)}})},_clearCode:function(){this._result(""),i.clear()},_saveCode:function(){var t=this;""!=this.result&&uni.saveImageToPhotosAlbum({filePath:t.result,success:function(){uni.showToast({title:"二维码保存成功",icon:"success",duration:2e3})}})},_result:function(t){this.result=t,this.$emit("result",t)},_empty:function(t){var e=typeof t,r=!1;return"number"==e&&""==String(t)?r=!0:"undefined"==e?r=!0:"object"==e?"{}"!=(0,o.default)(t)&&"[]"!=(0,o.default)(t)&&null!=t||(r=!0):"string"==e?""!=t&&"undefined"!=t&&"null"!=t&&"{}"!=t&&"[]"!=t||(r=!0):"function"==e&&(r=!1),r}},watch:{size:function(t,e){var r=this;t==e||this._empty(t)||(this.cSize=t,this._empty(this.val)||setTimeout(function(){r._makeCode()},100))},val:function(t,e){var r=this;this.onval&&(t==e||this._empty(t)||setTimeout(function(){r._makeCode()},0))}},computed:{cpSize:function(){return"upx"==this.unit?uni.upx2px(this.size):this.size}},mounted:function(){var t=this;this.loadMake&&(this._empty(this.val)||setTimeout(function(){t._makeCode()},0))}};e.default=a},9285:function(t,e,r){"use strict";var n=r("288e");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var o=n(r("f499"));r("6c7b"),r("c5f6");var i={};(function(){function t(t){var e,r,n;return t<128?[t]:t<2048?(e=192+(t>>6),r=128+(63&t),[e,r]):(e=224+(t>>12),r=128+(t>>6&63),n=128+(63&t),[e,r,n])}function e(e){for(var r=[],n=0;n<e.length;n++)for(var o=e.charCodeAt(n),i=t(o),u=0;u<i.length;u++)r.push(i[u]);return r}function r(t,r){this.typeNumber=-1,this.errorCorrectLevel=r,this.modules=null,this.moduleCount=0,this.dataCache=null,this.rsBlocks=null,this.totalDataCount=-1,this.data=t,this.utf8bytes=e(t),this.make()}r.prototype={constructor:r,getModuleCount:function(){return this.moduleCount},make:function(){this.getRightType(),this.dataCache=this.createData(),this.createQrcode()},makeImpl:function(t){this.moduleCount=4*this.typeNumber+17,this.modules=new Array(this.moduleCount);for(var e=0;e<this.moduleCount;e++)this.modules[e]=new Array(this.moduleCount);this.setupPositionProbePattern(0,0),this.setupPositionProbePattern(this.moduleCount-7,0),this.setupPositionProbePattern(0,this.moduleCount-7),this.setupPositionAdjustPattern(),this.setupTimingPattern(),this.setupTypeInfo(!0,t),this.typeNumber>=7&&this.setupTypeNumber(!0),this.mapData(this.dataCache,t)},setupPositionProbePattern:function(t,e){for(var r=-1;r<=7;r++)if(!(t+r<=-1||this.moduleCount<=t+r))for(var n=-1;n<=7;n++)e+n<=-1||this.moduleCount<=e+n||(this.modules[t+r][e+n]=0<=r&&r<=6&&(0==n||6==n)||0<=n&&n<=6&&(0==r||6==r)||2<=r&&r<=4&&2<=n&&n<=4)},createQrcode:function(){for(var t=0,e=0,r=null,n=0;n<8;n++){this.makeImpl(n);var o=a.getLostPoint(this);(0==n||t>o)&&(t=o,e=n,r=this.modules)}this.modules=r,this.setupTypeInfo(!1,e),this.typeNumber>=7&&this.setupTypeNumber(!1)},setupTimingPattern:function(){for(var t=8;t<this.moduleCount-8;t++)null==this.modules[t][6]&&(this.modules[t][6]=t%2==0,null==this.modules[6][t]&&(this.modules[6][t]=t%2==0))},setupPositionAdjustPattern:function(){for(var t=a.getPatternPosition(this.typeNumber),e=0;e<t.length;e++)for(var r=0;r<t.length;r++){var n=t[e],o=t[r];if(null==this.modules[n][o])for(var i=-2;i<=2;i++)for(var u=-2;u<=2;u++)this.modules[n+i][o+u]=-2==i||2==i||-2==u||2==u||0==i&&0==u}},setupTypeNumber:function(t){for(var e=a.getBCHTypeNumber(this.typeNumber),r=0;r<18;r++){var n=!t&&1==(e>>r&1);this.modules[Math.floor(r/3)][r%3+this.moduleCount-8-3]=n,this.modules[r%3+this.moduleCount-8-3][Math.floor(r/3)]=n}},setupTypeInfo:function(t,e){for(var r=n[this.errorCorrectLevel]<<3|e,o=a.getBCHTypeInfo(r),i=0;i<15;i++){var u=!t&&1==(o>>i&1);i<6?this.modules[i][8]=u:i<8?this.modules[i+1][8]=u:this.modules[this.moduleCount-15+i][8]=u;u=!t&&1==(o>>i&1);i<8?this.modules[8][this.moduleCount-i-1]=u:i<9?this.modules[8][15-i-1+1]=u:this.modules[8][15-i-1]=u}this.modules[this.moduleCount-8][8]=!t},createData:function(){var t=new h,e=this.typeNumber>9?16:8;t.put(4,4),t.put(this.utf8bytes.length,e);for(var n=0,o=this.utf8bytes.length;n<o;n++)t.put(this.utf8bytes[n],8);t.length+4<=8*this.totalDataCount&&t.put(0,4);while(t.length%8!=0)t.putBit(!1);while(1){if(t.length>=8*this.totalDataCount)break;if(t.put(r.PAD0,8),t.length>=8*this.totalDataCount)break;t.put(r.PAD1,8)}return this.createBytes(t)},createBytes:function(t){for(var e=0,r=0,n=0,o=this.rsBlock.length/3,i=new Array,u=0;u<o;u++)for(var s=this.rsBlock[3*u+0],f=this.rsBlock[3*u+1],c=this.rsBlock[3*u+2],h=0;h<s;h++)i.push([c,f]);for(var d=new Array(i.length),g=new Array(i.length),v=0;v<i.length;v++){var p=i[v][0],m=i[v][1]-p;r=Math.max(r,p),n=Math.max(n,m),d[v]=new Array(p);for(u=0;u<d[v].length;u++)d[v][u]=255&t.buffer[u+e];e+=p;var w=a.getErrorCorrectPolynomial(m),y=new l(d[v],w.getLength()-1),T=y.mod(w);g[v]=new Array(w.getLength()-1);for(u=0;u<g[v].length;u++){var b=u+T.getLength()-g[v].length;g[v][u]=b>=0?T.get(b):0}}var A=new Array(this.totalDataCount),C=0;for(u=0;u<r;u++)for(v=0;v<i.length;v++)u<d[v].length&&(A[C++]=d[v][u]);for(u=0;u<n;u++)for(v=0;v<i.length;v++)u<g[v].length&&(A[C++]=g[v][u]);return A},mapData:function(t,e){for(var r=-1,n=this.moduleCount-1,o=7,i=0,u=this.moduleCount-1;u>0;u-=2){6==u&&u--;while(1){for(var s=0;s<2;s++)if(null==this.modules[n][u-s]){var f=!1;i<t.length&&(f=1==(t[i]>>>o&1));var l=a.getMask(e,n,u-s);l&&(f=!f),this.modules[n][u-s]=f,o--,-1==o&&(i++,o=7)}if(n+=r,n<0||this.moduleCount<=n){n-=r,r=-r;break}}}}},r.PAD0=236,r.PAD1=17;for(var n=[1,0,3,2],u={PATTERN000:0,PATTERN001:1,PATTERN010:2,PATTERN011:3,PATTERN100:4,PATTERN101:5,PATTERN110:6,PATTERN111:7},a={PATTERN_POSITION_TABLE:[[],[6,18],[6,22],[6,26],[6,30],[6,34],[6,22,38],[6,24,42],[6,26,46],[6,28,50],[6,30,54],[6,32,58],[6,34,62],[6,26,46,66],[6,26,48,70],[6,26,50,74],[6,30,54,78],[6,30,56,82],[6,30,58,86],[6,34,62,90],[6,28,50,72,94],[6,26,50,74,98],[6,30,54,78,102],[6,28,54,80,106],[6,32,58,84,110],[6,30,58,86,114],[6,34,62,90,118],[6,26,50,74,98,122],[6,30,54,78,102,126],[6,26,52,78,104,130],[6,30,56,82,108,134],[6,34,60,86,112,138],[6,30,58,86,114,142],[6,34,62,90,118,146],[6,30,54,78,102,126,150],[6,24,50,76,102,128,154],[6,28,54,80,106,132,158],[6,32,58,84,110,136,162],[6,26,54,82,110,138,166],[6,30,58,86,114,142,170]],G15:1335,G18:7973,G15_MASK:21522,getBCHTypeInfo:function(t){var e=t<<10;while(a.getBCHDigit(e)-a.getBCHDigit(a.G15)>=0)e^=a.G15<<a.getBCHDigit(e)-a.getBCHDigit(a.G15);return(t<<10|e)^a.G15_MASK},getBCHTypeNumber:function(t){var e=t<<12;while(a.getBCHDigit(e)-a.getBCHDigit(a.G18)>=0)e^=a.G18<<a.getBCHDigit(e)-a.getBCHDigit(a.G18);return t<<12|e},getBCHDigit:function(t){var e=0;while(0!=t)e++,t>>>=1;return e},getPatternPosition:function(t){return a.PATTERN_POSITION_TABLE[t-1]},getMask:function(t,e,r){switch(t){case u.PATTERN000:return(e+r)%2==0;case u.PATTERN001:return e%2==0;case u.PATTERN010:return r%3==0;case u.PATTERN011:return(e+r)%3==0;case u.PATTERN100:return(Math.floor(e/2)+Math.floor(r/3))%2==0;case u.PATTERN101:return e*r%2+e*r%3==0;case u.PATTERN110:return(e*r%2+e*r%3)%2==0;case u.PATTERN111:return(e*r%3+(e+r)%2)%2==0;default:throw new Error("bad maskPattern:"+t)}},getErrorCorrectPolynomial:function(t){for(var e=new l([1],0),r=0;r<t;r++)e=e.multiply(new l([1,s.gexp(r)],0));return e},getLostPoint:function(t){for(var e=t.getModuleCount(),r=0,n=0,o=0;o<e;o++)for(var i=0,u=t.modules[o][0],a=0;a<e;a++){var s=t.modules[o][a];if(a<e-6&&s&&!t.modules[o][a+1]&&t.modules[o][a+2]&&t.modules[o][a+3]&&t.modules[o][a+4]&&!t.modules[o][a+5]&&t.modules[o][a+6]&&(a<e-10?t.modules[o][a+7]&&t.modules[o][a+8]&&t.modules[o][a+9]&&t.modules[o][a+10]&&(r+=40):a>3&&t.modules[o][a-1]&&t.modules[o][a-2]&&t.modules[o][a-3]&&t.modules[o][a-4]&&(r+=40)),o<e-1&&a<e-1){var f=0;s&&f++,t.modules[o+1][a]&&f++,t.modules[o][a+1]&&f++,t.modules[o+1][a+1]&&f++,0!=f&&4!=f||(r+=3)}u^s?i++:(u=s,i>=5&&(r+=3+i-5),i=1),s&&n++}for(a=0;a<e;a++)for(i=0,u=t.modules[0][a],o=0;o<e;o++){s=t.modules[o][a];o<e-6&&s&&!t.modules[o+1][a]&&t.modules[o+2][a]&&t.modules[o+3][a]&&t.modules[o+4][a]&&!t.modules[o+5][a]&&t.modules[o+6][a]&&(o<e-10?t.modules[o+7][a]&&t.modules[o+8][a]&&t.modules[o+9][a]&&t.modules[o+10][a]&&(r+=40):o>3&&t.modules[o-1][a]&&t.modules[o-2][a]&&t.modules[o-3][a]&&t.modules[o-4][a]&&(r+=40)),u^s?i++:(u=s,i>=5&&(r+=3+i-5),i=1)}var l=Math.abs(100*n/e/e-50)/5;return r+=10*l,r}},s={glog:function(t){if(t<1)throw new Error("glog("+t+")");return s.LOG_TABLE[t]},gexp:function(t){while(t<0)t+=255;while(t>=256)t-=255;return s.EXP_TABLE[t]},EXP_TABLE:new Array(256),LOG_TABLE:new Array(256)},f=0;f<8;f++)s.EXP_TABLE[f]=1<<f;for(f=8;f<256;f++)s.EXP_TABLE[f]=s.EXP_TABLE[f-4]^s.EXP_TABLE[f-5]^s.EXP_TABLE[f-6]^s.EXP_TABLE[f-8];for(f=0;f<255;f++)s.LOG_TABLE[s.EXP_TABLE[f]]=f;function l(t,e){if(void 0==t.length)throw new Error(t.length+"/"+e);var r=0;while(r<t.length&&0==t[r])r++;this.num=new Array(t.length-r+e);for(var n=0;n<t.length-r;n++)this.num[n]=t[n+r]}l.prototype={get:function(t){return this.num[t]},getLength:function(){return this.num.length},multiply:function(t){for(var e=new Array(this.getLength()+t.getLength()-1),r=0;r<this.getLength();r++)for(var n=0;n<t.getLength();n++)e[r+n]^=s.gexp(s.glog(this.get(r))+s.glog(t.get(n)));return new l(e,0)},mod:function(t){var e=this.getLength(),r=t.getLength();if(e-r<0)return this;for(var n=new Array(e),o=0;o<e;o++)n[o]=this.get(o);while(n.length>=r){var i=s.glog(n[0])-s.glog(t.get(0));for(o=0;o<t.getLength();o++)n[o]^=s.gexp(s.glog(t.get(o))+i);while(0==n[0])n.shift()}return new l(n,0)}};var c=[[1,26,19],[1,26,16],[1,26,13],[1,26,9],[1,44,34],[1,44,28],[1,44,22],[1,44,16],[1,70,55],[1,70,44],[2,35,17],[2,35,13],[1,100,80],[2,50,32],[2,50,24],[4,25,9],[1,134,108],[2,67,43],[2,33,15,2,34,16],[2,33,11,2,34,12],[2,86,68],[4,43,27],[4,43,19],[4,43,15],[2,98,78],[4,49,31],[2,32,14,4,33,15],[4,39,13,1,40,14],[2,121,97],[2,60,38,2,61,39],[4,40,18,2,41,19],[4,40,14,2,41,15],[2,146,116],[3,58,36,2,59,37],[4,36,16,4,37,17],[4,36,12,4,37,13],[2,86,68,2,87,69],[4,69,43,1,70,44],[6,43,19,2,44,20],[6,43,15,2,44,16],[4,101,81],[1,80,50,4,81,51],[4,50,22,4,51,23],[3,36,12,8,37,13],[2,116,92,2,117,93],[6,58,36,2,59,37],[4,46,20,6,47,21],[7,42,14,4,43,15],[4,133,107],[8,59,37,1,60,38],[8,44,20,4,45,21],[12,33,11,4,34,12],[3,145,115,1,146,116],[4,64,40,5,65,41],[11,36,16,5,37,17],[11,36,12,5,37,13],[5,109,87,1,110,88],[5,65,41,5,66,42],[5,54,24,7,55,25],[11,36,12],[5,122,98,1,123,99],[7,73,45,3,74,46],[15,43,19,2,44,20],[3,45,15,13,46,16],[1,135,107,5,136,108],[10,74,46,1,75,47],[1,50,22,15,51,23],[2,42,14,17,43,15],[5,150,120,1,151,121],[9,69,43,4,70,44],[17,50,22,1,51,23],[2,42,14,19,43,15],[3,141,113,4,142,114],[3,70,44,11,71,45],[17,47,21,4,48,22],[9,39,13,16,40,14],[3,135,107,5,136,108],[3,67,41,13,68,42],[15,54,24,5,55,25],[15,43,15,10,44,16],[4,144,116,4,145,117],[17,68,42],[17,50,22,6,51,23],[19,46,16,6,47,17],[2,139,111,7,140,112],[17,74,46],[7,54,24,16,55,25],[34,37,13],[4,151,121,5,152,122],[4,75,47,14,76,48],[11,54,24,14,55,25],[16,45,15,14,46,16],[6,147,117,4,148,118],[6,73,45,14,74,46],[11,54,24,16,55,25],[30,46,16,2,47,17],[8,132,106,4,133,107],[8,75,47,13,76,48],[7,54,24,22,55,25],[22,45,15,13,46,16],[10,142,114,2,143,115],[19,74,46,4,75,47],[28,50,22,6,51,23],[33,46,16,4,47,17],[8,152,122,4,153,123],[22,73,45,3,74,46],[8,53,23,26,54,24],[12,45,15,28,46,16],[3,147,117,10,148,118],[3,73,45,23,74,46],[4,54,24,31,55,25],[11,45,15,31,46,16],[7,146,116,7,147,117],[21,73,45,7,74,46],[1,53,23,37,54,24],[19,45,15,26,46,16],[5,145,115,10,146,116],[19,75,47,10,76,48],[15,54,24,25,55,25],[23,45,15,25,46,16],[13,145,115,3,146,116],[2,74,46,29,75,47],[42,54,24,1,55,25],[23,45,15,28,46,16],[17,145,115],[10,74,46,23,75,47],[10,54,24,35,55,25],[19,45,15,35,46,16],[17,145,115,1,146,116],[14,74,46,21,75,47],[29,54,24,19,55,25],[11,45,15,46,46,16],[13,145,115,6,146,116],[14,74,46,23,75,47],[44,54,24,7,55,25],[59,46,16,1,47,17],[12,151,121,7,152,122],[12,75,47,26,76,48],[39,54,24,14,55,25],[22,45,15,41,46,16],[6,151,121,14,152,122],[6,75,47,34,76,48],[46,54,24,10,55,25],[2,45,15,64,46,16],[17,152,122,4,153,123],[29,74,46,14,75,47],[49,54,24,10,55,25],[24,45,15,46,46,16],[4,152,122,18,153,123],[13,74,46,32,75,47],[48,54,24,14,55,25],[42,45,15,32,46,16],[20,147,117,4,148,118],[40,75,47,7,76,48],[43,54,24,22,55,25],[10,45,15,67,46,16],[19,148,118,6,149,119],[18,75,47,31,76,48],[34,54,24,34,55,25],[20,45,15,61,46,16]];function h(){this.buffer=new Array,this.length=0}r.prototype.getRightType=function(){for(var t=1;t<41;t++){var e=c[4*(t-1)+this.errorCorrectLevel];if(void 0==e)throw new Error("bad rs block @ typeNumber:"+t+"/errorCorrectLevel:"+this.errorCorrectLevel);for(var r=e.length/3,n=0,o=0;o<r;o++){var i=e[3*o+0],u=e[3*o+2];n+=u*i}var a=t>9?2:1;if(this.utf8bytes.length+a<n||40==t){this.typeNumber=t,this.rsBlock=e,this.totalDataCount=n;break}}},h.prototype={get:function(t){var e=Math.floor(t/8);return this.buffer[e]>>>7-t%8&1},put:function(t,e){for(var r=0;r<e;r++)this.putBit(t>>>e-r-1&1)},putBit:function(t){var e=Math.floor(this.length/8);this.buffer.length<=e&&this.buffer.push(0),t&&(this.buffer[e]|=128>>>this.length%8),this.length++}};var d=[];i=function(t){if(this.options={text:"",size:256,correctLevel:3,background:"#ffffff",foreground:"#000000",pdground:"#000000",image:"",imageSize:30,canvasId:t.canvasId,context:t.context,usingComponents:t.usingComponents,showLoading:t.showLoading,loadingText:t.loadingText},"string"===typeof t&&(t={text:t}),t)for(var e in t)this.options[e]=t[e];for(var n=null,i=(e=0,d.length);e<i;e++)if(d[e].text==this.options.text&&d[e].text.correctLevel==this.options.correctLevel){n=d[e].obj;break}e==i&&(n=new r(this.options.text,this.options.correctLevel),d.push({text:this.options.text,correctLevel:this.options.correctLevel,obj:n}));var u=function(t){var e=t.options;return e.pdground&&(t.row>1&&t.row<5&&t.col>1&&t.col<5||t.row>t.count-6&&t.row<t.count-2&&t.col>1&&t.col<5||t.row>1&&t.row<5&&t.col>t.count-6&&t.col<t.count-2)?e.pdground:e.foreground},a=function(t){t.showLoading&&uni.showLoading({title:t.loadingText,mask:!0});for(var e=uni.createCanvasContext(t.canvasId,t.context),r=n.getModuleCount(),o=t.size,i=t.imageSize,a=(o/r).toPrecision(4),f=(o/r).toPrecision(4),l=0;l<r;l++)for(var c=0;c<r;c++){var h=Math.ceil((c+1)*a)-Math.floor(c*a),d=Math.ceil((l+1)*a)-Math.floor(l*a),g=u({row:l,col:c,count:r,options:t});e.setFillStyle(n.modules[l][c]?g:t.background),e.fillRect(Math.round(c*a),Math.round(l*f),h,d)}if(t.image){var v=function(e,r,n,o,i,u,a,s,f){e.setLineWidth(a),e.setFillStyle(t.background),e.setStrokeStyle(t.background),e.beginPath(),e.moveTo(r+u,n),e.arcTo(r+o,n,r+o,n+u,u),e.arcTo(r+o,n+i,r+o-u,n+i,u),e.arcTo(r,n+i,r,n+i-u,u),e.arcTo(r,n,r+u,n,u),e.closePath(),s&&e.fill(),f&&e.stroke()},p=Number(((o-i)/2).toFixed(2)),m=Number(((o-i)/2).toFixed(2));v(e,p,m,i,i,2,6,!0,!0),e.drawImage(t.image,p,m,i,i)}setTimeout(function(){e.draw(!0,function(){setTimeout(function(){uni.canvasToTempFilePath({width:t.width,height:t.height,destWidth:t.width,destHeight:t.height,canvasId:t.canvasId,quality:Number(1),success:function(e){t.cbResult&&(s(e.tempFilePath)?s(e.apFilePath)?t.cbResult(e.tempFilePath):t.cbResult(e.apFilePath):t.cbResult(e.tempFilePath))},fail:function(e){t.cbResult&&t.cbResult(e)},complete:function(){uni.hideLoading()}},t.context)},t.text.length+100)})},t.usingComponents?0:150)};a(this.options);var s=function(t){var e=typeof t,r=!1;return"number"==e&&""==String(t)?r=!0:"undefined"==e?r=!0:"object"==e?"{}"!=(0,o.default)(t)&&"[]"!=(0,o.default)(t)&&null!=t||(r=!0):"string"==e?""!=t&&"undefined"!=t&&"null"!=t&&"{}"!=t&&"[]"!=t||(r=!0):"function"==e&&(r=!1),r}},i.prototype.clear=function(t){var e=uni.createCanvasContext(this.options.canvasId,this.options.context);e.clearRect(0,0,this.options.size,this.options.size),e.draw(!1,function(){t&&t()})}})();var u=i;e.default=u},b638:function(t,e,r){e=t.exports=r("2350")(!1),e.push([t.i,".wt160[data-v-9b120164]{width:%?320?%}.qring[data-v-9b120164]{border:4px solid #313131}",""])},c1a9:function(t,e,r){"use strict";r.r(e);var n=r("05b3"),o=r("3519");for(var i in o)"default"!==i&&function(t){r.d(e,t,function(){return o[t]})}(i);r("ca16");var u=r("2877"),a=Object(u["a"])(o["default"],n["a"],n["b"],!1,null,"6eaef78a",null);e["default"]=a.exports},ca16:function(t,e,r){"use strict";var n=r("71d4"),o=r.n(n);o.a},ec92:function(t,e,r){"use strict";var n=r("1982"),o=r.n(n);o.a},fb47:function(t,e,r){e=t.exports=r("2350")(!1),e.push([t.i,".tki-qrcode[data-v-6eaef78a]{position:relative}.tki-qrcode-canvas[data-v-6eaef78a]{position:fixed;top:%?-99999?%;left:%?-99999?%;z-index:-99999}",""])}}]);