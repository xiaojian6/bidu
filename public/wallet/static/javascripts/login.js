$(function(){
    $('#account_number').val( getStorage('username'));
    // $("#password").val(getStorage('username')&&getStorage('username').pwd)
    $(".login_btn").click(function () {
                    
        var account_number = $("#account_number").val() ;
        var password = $("#password").val()
        if (account_number == ""){
            layer_msg("请输入账号")
            return false;
        }
        if (password == ""){
            layer_msg("请输入密码")
            return false;
        }
        $.ajax({
            type: "POST",
            url: laravel_api + "/user/login",
            data: {
                user_string:account_number,
                password:password
            },
            dataType: "json",
            success: function(data){
                console.log(data)

                if (data.type == "ok"){
                   layer_loading();
                   setStorage('username',account_number)
                   set_user(data.message,7)
                   layer_msg('登录成功！')
                    // $("#user_id").val(data.message)
                    // $("#form_content").submit();
                    setTimeout(() => {
                        location.href="userinfo.html"
                    }, 1500);
                    
                } else{
                    alert(data.message)
                    return false;
                }
                // setTimeout(() => {
                //     layer_timeout();
                // }, 5000);
            },error:function(err){
                layer_timeout();
            }
        });
    })
    $('.register').click(function(){
        location.href='register.html'
    })
});