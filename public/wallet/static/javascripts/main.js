var _PROTOCOL = window.location.protocol;
var _HOST = window.location.host;
var _DOMAIN = _PROTOCOL + '//' + _HOST;
var _SERVER = _DOMAIN + "/wallet/"; //域名
var laravel_api =_DOMAIN+ "/api"
// var socket_api = "http://newtrading.mobile369.com:2120";
var socket_api = _DOMAIN+':2130';
var node_api = "http://47.92.171.137:3000"
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
//获取字符串长度
function strlen(str){
    var len = 0;
    for (var i=0; i<str.length; i++) {
        var c = str.charCodeAt(i);
        //单字节加1
        if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) {
            len++;
        }
        else {
            len+=2;
        }
    }
    return len;
}
function layer_timeout(msg){
    if(msg==''){
        msg='网络错误，请稍后重试！'
    }
    layer.open({
        content:msg,
        skin:'msg',
        time:5,
    })
}
// 询问提示框
function layer_confirm(con,callback){
    var con=con||'确定要删除吗？'
    layer.open({
        content: con
        ,btn: ['确定', '取消']
        ,yes: function(index){
          layer.close(index);
          callback&&callback();
        }
      });
}
function layer_confirm2(con,callback){
    var con=con||'确定要删除吗？'
    layer.open({
        content: con
        ,btn: ['确定']
        ,yes: function(index){
          layer.close(index);
          callback&&callback();
        }
      });
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

/** 返回上一页 */
    $(".backPage").click(function(){
        history.back(-1);
    })

/* 交易页面开发中。。。。*/
$("#trade_nav").click(function(){
    layer_msg("功能还在开发中.....")
})

// 设置缓存
function setStorage(key,value){
    window.localStorage.setItem(key,JSON.stringify(value))
}
function getStorage(key){
   return JSON.parse(window.localStorage.getItem(key))
}
function removeStorage(key){
    window.localStorage.removeItem(key);
}
$('.quit_btn').click(function(){
    removeStorage('userinfo');
})
function set_user(token) {
    var days = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 7;
    $.cookie("token", token, { expires: days, path: "/" });
}

function get_user_login() {
    return get_user() || (location.href = "../login.html");
}
function get_user() {
    return $.cookie("token") || 0;
}
// 获取不需token的数据
function initData(params,callback){
    // layer_loading();
    url=laravel_api+params.url;
    type=params.type||'get';
    data=params.data || [];
    $.ajax({
        url,
        type,
        data,
        success:function(res){
            // layer_close();
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
    var url=laravel_api+params.url;
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
                if(res.type=='997'){
                    setTimeout(() => {
                      location.href='adding.html';
                    }, 1500);  
                 }
               if(res.type=='998'){
                  setTimeout(() => {
                    location.href='authentication.html';
                  }, 1500);  
               }
               if(res.type=='999'){
                   location.href='login.html'
               }
                
                
            }
        }
    })
}
function initDataToken01(params,callback){
    layer_loading();
    var url=laravel_api+params.url;
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
                callback&&callback(res)
            }else{
                callback&&callback(res)
                layer_msg(res.message)
                if(res.type=='997'){
                    setTimeout(() => {
                      location.href='adding.html';
                    }, 1500);  
                 }
               if(res.type=='998'){
                  setTimeout(() => {
                    location.href='authentication.html';
                  }, 1500);  
               }
               if(res.type=='999'){
                   location.href='login.html'
               }
                
                
            }
        }
    })
}