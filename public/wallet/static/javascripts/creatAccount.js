$(function(){
    $('.ckb').click(function(){
        $(this).toggleClass('active');
    })
    $('.btn-in').click(function(){
        layer_msg('功能即将开放...');
        return false;
    })
    $(".login_btn").click(function () {
        var content = getStorage('word');
        $("#input_content").val(content)
        var user_id = $("#user_id").val()
        var wallet_name = $("#wallet_name").val()
        var password = $("#password").val()
        var password_prompt = $("#password_prompt").val()
        var memorizing_words = $("#input_content").val()

        if(content == ""){
            layer_msg("请刷新重试")
            return false;
        }
        if(wallet_name == ""){
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
        if(password_prompt == ""){
            layer_msg("请确认密码")
            return false;
        }
        if(password != password_prompt){
            layer_msg("两次输入的密码不一致")
            return false;
        }
        if(!$('.ckb').hasClass('active')){
            layer_msg('请选择阅读协议');
            return false;
        }
        $.ajax({
            type: "GET",
            url: node_api + "/word/generateeth",
            data: {
                content:content,
                password:password
            },
            dataType: "json",
            success: function(datas){
                if(datas.error == 0){
                    $("#address").val(datas.content)
                    initDataToken({url:'/wallet_add',type:'post',data:{address:datas.content,
                        contentbtc:datas.contentbtc,
                        password:password,
                        password_prompt:password_prompt,
                        memorizing_words:memorizing_words}},function(data){
                            layer_msg('钱包创建成功！')
                            setTimeout(() => {
                                location.href='index.html'
                            }, 1500);
                    })
                }else{
                    layer_msg(datas.content)
                    return false;
                }
            }
        })
        
    })
});