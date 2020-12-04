$(function(){
    var cod = get_param("cod");
    if(cod != "" && cod != undefined){
            $("#parent_id").val(cod);
    }
    $(".login_btn").click(function () {
        
        var account_number = $("#account_number").val()
        var password = $("#password").val()
        var confirm_password = $("#confirm_password").val()
        var parent_id = $("#parent_id").val()

        // if(content == ""){
        //     layer_msg("请刷新重试")
        //     return false;
        // }
        
        if(account_number == ""){
            layer_msg("请输入账号")
            return false;
        }
        if(password == ""){
            layer_msg("请输入密码")
            return false;
        }
        if(strlen(password) < 6){
            layer_msg("密码过于简单")
            return false;
        }
        if(confirm_password == ""){
            layer_msg("请确认密码")
            return false;
        }
        if(password != confirm_password){
            layer_msg("两次输入的密码不一致")
            return false;
        }
        layer_loading();
        $.ajax({
            type: "POST",
            url: laravel_api + "/user/walletRegister",
            data: {
                // address:data.content,
                password:password,
                account_number:account_number,
                parent_id:parent_id
            },
            dataType: "json",
            success: function(data){
                layer_close();
                if(data.type == "ok"){
                    layer_msg('注册成功')
                    setStorage('username',account_number)
                    setTimeout(() => {
                        location.href='login.html'
                    }, 1500);
                }else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    })
});