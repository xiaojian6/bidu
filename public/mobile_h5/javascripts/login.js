$(document).keydown(function (event) {
    if (event.keyCode == 13) {
        logon();
    }
});
var vue = new Vue({
    el: '#app',
    data: {
        userName: '',
        passwords: '',
        sms_code:'',
        checkboxValue: false,
        code: '',
        codeText: '获取验证码',
        codeTime: 60,
        status:false,
        cshow:false,
        countryList:[],
        city_name:'China',
        city_code:'+86',
        type:0,

    },
    mounted: function () {
        let that = this;
        that.codeText=getcode
        that.userName = that.getCookie('userName') || '';
        that.passwords = that.getCookie('passwords') || '';
        that.checkboxValue = that.getCookie('loginStute') || false;
        that.countryList = country;
        // console.log(that.countryList)
        if(issmcode){
            $('.logincode').show();
        }else{
            $('.logincode').hide();
        }
    },
    methods: {
        cityChoose(name,code){
            // console.log(name,code);
            if(this.type==0){
                this.city_name=name;
            }
            this.city_code=code;
            this.cshow=false;
            $('body').removeClass('overhide')
        },
        showcity(type){
            console.log(type)
            this.type=type;
            this.cshow=true;
            $('body').addClass('overhide')
        },
        // 密码显示或者隐藏
        shpass() {
            $("#text").toggle();
            $("#password").toggle();
            if ($("#imgs").attr('src') == 'images/accountm.png') {
                $("#imgs").attr('src', 'images/eyes.png');
            } else {
                $("#imgs").attr('src', 'images/accountm.png');
            }
        },
        passblur() {
            $("#text").val($("#password").val());
        },

        // 密码验证
        passwordConfirm() {
            var pass = $("#password").val();
            if (pass.length < 6 || pass.length > 16) {
                $("#mes2").html(plength);
                $("#sendLogin").css("background", "#8daabd");
            } else {
                $("#mes2").html("");
                $("#sendLogin").css("background", "#588fe1");
            }
        },
        // 用户验证
        userConfirm() {
            if ($('#name').val() != '' && $('#password').val().length > 5) {
                $("#sendLogin").css("background", "#588fe1")
            } else {
                $("#sendLogin").css("background", "#8daabd")
            }
        },
        // 点击登录
        logon() {
            let that = this;
            that.userName = $('#name').val();
            that.passwords = $('#password').val();
            if (!that.userName) {
                layer_msg(paccount);
                return false;
            }
            if (!that.passwords) {
                layer_msg(pinpwd);
                return false;
            } else if (that.passwords.length < 6) {
                layer_msg(ptpwd);
                return false;
            }
            var code=JSON.parse(window.localStorage.getItem("socketPort")).smcode || '';
            if(!that.sms_code &&code){
                layer_msg(pyan);
                return false;
            }
            if (that.checkboxValue) {
                that.setCookie('userName', that.userName, 7);
                that.setCookie('passwords', that.passwords, 7);
                that.setCookie('loginStute', that.checkboxValue, 7);
            } else {
                that.setCookie('userName', '', 7);
                that.setCookie('passwords', '', 7);
                that.setCookie('loginStute', false, 7);
            }
            initDatas({
                url: 'user/login',
                data: {
                    user_string: that.userName,
                    password: that.passwords,
                    sms_code: that.sms_code,
                    country_code:that.city_code,
                },
                type: 'post'
            }, function (res) {
                console.log(res);
                if (res.type == 'ok') {
                    localStorage.setItem('city_code',that.city_code);
                    layer_msg(lgsuccess)
                    set_user(res.message, 7);
                    setTimeout(function () {
                        window.location.href = "index.html";
                    }, 500)
                } else {
                    layer_msg(data.message);

                }
            });
        },
        selectInput(val) {
            let that = this;
            console.log(that.checkboxValue);
        },
        setCookie(c_name, value, expiredays) {
            localStorage.setItem(c_name,value);
            // var exdate = new Date()
            // exdate.setDate(exdate.getDate() + expiredays)
            // document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString())
        },
        getCookie(c_name) {
            // if (document.cookie.length > 0) {
            //     var c_start = document.cookie.indexOf(c_name + "=")
            //     if (c_start != -1) {
            //         c_start = c_start + c_name.length + 1
            //         var c_end = document.cookie.indexOf(";", c_start)
            //         if (c_end == -1) c_end = document.cookie.length
            //         return unescape(document.cookie.substring(c_start, c_end))
            //     }
            // }
            return localStorage.getItem(c_name) || ''
        },
        // 获取验证码
        getCodes() {
            let that = this;
            var reg = /^1[345678]\d{9}$/;
            var emreg =/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
            var url='sms_send'
            if (that.userName == '') {
                layer_msg(paccount);
                return false;
            } else {
                if(emreg.test(that.userName)){
                  url='sms_mail'
                }else{
                    url='sms_send'
                }
               $('.code').attr('disabled','disabled');
                $.ajax({
                    type: "post",
                    url: _API + url,
                    data: {
                        user_string: that.userName,
                        country_code:that.city_code,
                        scene:'login'
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                        if (data.type == 'ok') {
                            setInterval(function(){
                                if(that.codeTime ==0){
                                    $('.code').removeAttr('disabled');
                                    countdown = 60;
                                    that.codeText=getcode
                                }else{
                                    $('.code').attr('disabled','disabled');
                                    that.codeTime--;
                                    that.codeText =that.codeTime+'s';
                                }
                            },1000)
                            layer_msg(data.message);
                        } else {
                            $('.code').removeAttr('disabled');
                            layer_msg(data.message);
                        }
                    }
                });
            }

        },
        // settime() {
        //     if (countdown == 0) {
        //         generate_code.attr("disabled", false);
        //         generate_code.val("发送");
        //         $("#mbtain,#fbtain").css('color', '#5890bd');
        //         countdown = 60;
        //         return false;
        //     } else {
        //         $("#mbtain,#fbtain").attr("disabled", true);
        //         $("#mbtain,#fbtain").css('color', '#b6bfc4');
        //         generate_code.val("重新发送(" + countdown + ")");
        //         countdown--;
        //     }
        //     setTimeout(function () {
        //         settime();
        //     }, 1000);
        // }


    }
});