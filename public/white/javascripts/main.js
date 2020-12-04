var _PROTOCOL = window.location.protocol;
var _HOST = window.location.host;
var _DOMAIN = _PROTOCOL + '//' + _HOST;
var _SERVER = _DOMAIN + "/mobile/"; //域名
var _API = _DOMAIN + "/api/";
var socket_api = _DOMAIN + ':2130';
function get_user() {
    return $.cookie("token") || 0;
}

function set_user(token) {
    var days = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 7;
    $.cookie("token", token, { expires: days, path: "/" });
}

function get_user_login() {
    return get_user() || (location.href = _SERVER+ "login.html");
}
//layer提示层
function layer_msg(content){
    if(content == ""){
        content = "请刷新重试"
    }
    layer.open({
        content: content
        ,skin: 'msg'
        ,time: 2 //2秒后自动关闭
    });
}

//layer提示层
function layer_loading(content){
    if(content == ""){
        content = "加载中"
    }
    layer.open({
        type: 2
        ,content: content
    });
}
function layer_close(){
    layer.closeAll()
}
/***
 * 获取url中所有参数
 * 返回参数键值对 对象
 */
function get_all_params() {
    var url = location.href;
    var nameValue;
    var paraString = url.substring(url.indexOf("?") + 1, url.length).split("&");
    var paraObj = {};
    for (var i = 0; nameValue = paraString[i]; i++) {
        var name = nameValue.substring(0, nameValue.indexOf("=")).toLowerCase();
        var value = nameValue.substring(nameValue.indexOf("=") + 1, nameValue.length);
        if (value.indexOf("#") > -1) {
            value = value.split("#")[0];
        }
        paraObj[name] = decodeURI(value);
    }
    return paraObj;
}

/**获取url中字段的值
 * name : 字段名
 * */
function get_param(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}
// 获取不需token的数据
function initData(params,callback){
    layer_loading();
    url=_API+params.url;
    type=params.type||'get';
    data=params.data || [];
    $.ajax({
        url,
        type,
        data,
        success:function(res){
            layer_close();
            console.log(res)
            if(res.type=='ok'){
                callback&&callback(res.message)
            }else{
                layer_msg(res.message)
            }
        }
    })
}
function initDataToken(params,callback){
    layer_loading();
    var url=_API+params.url;
    var type=params.type||'get';
    var data=params.data || [];
    var token = get_user_login();
    $.ajax({
        url,
        type,
        data,
        beforeSend:function beforeSend(request){
            request.setRequestHeader('Authorization',token)
        },
        success:function(res){
            layer_close();
            console.log(res)
            if(res.type=='ok'){
                callback&&callback(res.message)
            }else{
                layer_msg(res.message)
               if(res.type=='998'){
                  setTimeout(() => {
                    location.href='FiatDeal.html';
                  }, 1500);  
               }
               if(res.type=='999'){
                   location.href='login.html'
               }
                
                
            }
        }
    })
}
//function get_user_info(fuc) {
//		var token = get_user_login();
//		 $.ajax({
//          url: _API + "user/info",
//          type: "GET",
//          dataType: "json",
//          async: true,
//          beforeSend: function beforeSend(request) {
//              request.setRequestHeader("Authorization", token);
//          },
//          success: function success(data) {
//          	console.log(data)
//              if (data.type == 'ok') {
//					$("#ooo").css("display","block");
//					$("#login").css("display","none")
//             
//              } else {
////                  location.href = _MOBILE +"login_login";
////                  layer_msg(data.message);
////					$("#dai_fu").css("display","none")
//              }
//          }
//      });	
//	}
//图片上传js url上传地址 
//function ImgUpload(url,elm){
//  var formData = new FormData();
//  formData.append("file", $(elm)[0].files[0]);
//  $.ajax({
//      url: url,
//      type: 'post',
//      data: formData,
//      processData: false,
//      contentType: false,
//      success: function (msg) {
//          return msg.message;
//      }
//  });
//}