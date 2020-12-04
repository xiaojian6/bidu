var _PROTOCOL = "http:";
var _HOST = "demo.m88mo.cn";
var _DOMAIN = _PROTOCOL + '//' + _HOST;
var _SERVER = _DOMAIN + "/mobile/"; //域名
var _API = _DOMAIN + "/api/";
var socket_api = _DOMAIN + ':' + localStorage.getItem('socketPort');
if (window.location.pathname != '/mobile/leverList.html') {
    localStorage.setItem('leverStatus', '');
}
if (window.location.pathname != '/mobile/LeveragedDeals.html') {
    localStorage.setItem('htmlStatus', '');
}
$('input').blur(function () {
    setTimeout(function () {
        document.body.scrollTop = document.body.scrollHeight;
    }, 300);
})
$('select').change(function () {
    setTimeout(function () {
        document.body.scrollTop = document.body.scrollHeight;
    }, 300);
})
$('.header .back').click(function () {
    // alert(-1);
    history.back();
})
function get_user() {
    return plus.storage.getItem("token") || 0;
}

function set_user(token) {
    var days = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 7;
    $.cookie("token", token, {
        expires: days,
        path: "/"
    });
}

function get_user_login() {
    return get_user() || (location.href = _SERVER + "login.html");
}
//layer提示层
function layer_msg(content) {

    if (content == "") {
        content = "请刷新重试"
    }
    layer.msg(content)
    // layer.open({
    //     content: content
    //     ,skin: 'msg'
    //     ,time: 2 //2秒后自动关闭
    // });
}

//layer提示层
function layer_loading(content) {
    if (content == "") {
        content = "加载中"
    }
    layer.load(2);
}

function layer_close() {
    layer.closeAll()
}
// 询问提示框
function layer_confirm2(con, data, callback) {
    // var con = con || '确定要删除吗？'
    // var btnText = data.text || '确定'
    // var titles = data.title || ''
    // layer.confirm(con, {
    //     title: titles
    // }, function (index) {
    //     layer.close(index);
    //     callback && callback();
    // })
    layer.open({
        type: 1,
        title: '<img class="modal-logo" src="images/modal-logo.png" alt="">',
        skin: 'loads-btn btn-text',
        area: ['70%', 'auto'],
        content: con,
        btn: ['取消', '确认'],
        btn2: function (index) {
            layer.close(index);
            callback && callback();

        }
    });
    // layer.open({
    //     title:titles,
    //     content: con
    //     ,btn: [btnText, '取消']
    //     ,yes: function(index){
    //       layer.close(index);
    //       callback&&callback();
    //     }
    //   });
}

function layer_confirm(con, callback) {
    var con = con || '确定要删除吗？';
    layer.open({
        type: 1,
        title: '<img class="modal-logo" src="images/modal-logo.png" alt="">',
        skin: 'loads-btn btn-text',
        area: ['70%', 'auto'],
        content: con,
        btn: ['取消', '确认'],
        btn2: function (index) {
            layer.close(index);
            callback && callback();

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
// 获取不需token的数据
function initData(params, callback) {
    // layer_loading();
    url = _API + params.url;
    type = params.type || 'get';
    data = params.data || [];
    $.ajax({
        url,
        type,
        data,
        success: function (res) {
            layer_close();
            if (res.type == 'ok') {
                callback && callback(res.message)
            } else {
                layer_msg(res.message)
            }
        }
    })
}
function initDataToken(params, callback) {
    // layer_loading();
    var url = _API + params.url;
    var type = params.type || 'get';
    var data = params.data || [];
    var token = getlocal_storage('token');
    $.ajax({
        url,
        type,
        data,
        beforeSend: function beforeSend(request) {
            request.setRequestHeader('AUTHORIZATION', token)
        },
        success: function (res) {
            layer_close();
			console.log(token);
			alert(token.data);
            if (res.type == 'ok') {
                callback && callback(res.message)
            } else {
                layer_msg(res.message)
            }
        }
    })
}

function setlocal_storage(str, data) {
    localStorage.setItem(str, JSON.stringify(data));
}

function getlocal_storage(str) {
	return plus.storage.getItem(str);
    // return JSON.parse(localStorage.getItem(str));
}
//时间戳转换时间
function timestampToTime(timestamp) {
    var date = new Date(timestamp * 1000); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
    var Y = date.getFullYear() + '-';
    var M = (date.getMonth() + 1 < 10 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1) + '-';
    var D = date.getDate() > 10 ? date.getDate() + ' ' : ('0' + date.getDate()) + ' ';
    var h = date.getHours() > 10 ? date.getHours() + ':' : ('0' + date.getHours()) + ':';
    var m = date.getMinutes() > 10 ? date.getMinutes() + ':' : ('0' + date.getMinutes()) + ':';
    var s = date.getSeconds() > 10 ? date.getSeconds() : ('0' + date.getSeconds());
    return Y + M + D + h + m + s;
}

function iTofixed(num, fractionDigits) {
    num = num.toString();
    return num.substring(0, num.indexOf(".") + fractionDigits);
}
