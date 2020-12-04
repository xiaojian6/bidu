$(function(){
    var address = $("#user_info").data("address")
    if (address != ""){
        $.ajax({
            type: "POST",
            url: laravel_api + "/user/getuserbyaddress",
            data: {
                address:address
            },
            dataType: "json",
            success: function(data){
                console.log(data);
                if (data.type == "ok"){
                    $("#all_balance").html(data.message.all_balance)
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }
    $(".login_btn").click(function () {
        var user_id = $("#user_info").data("address");
        var address = $("#addr").val();
        var select_money = $(".select_money").val();
        var number = parseFloat($("#number").val())
        var remarks = $("#remarks").val()
        // var all_balance = parseFloat($("#all_balance").html())
        var password = $("#password").val();
        console.log(password);

        if (user_id == ""){
            layer_msg("请先登录")
            return false
        }
        if (address == ""){
            layer_msg("请输入地址")
            return false
        }
        if (number == ""){
            layer_msg("请输入数量")
            return false
        }
        // if (number > all_balance){
        //     layer_msg("您的余额不足")
        //     return false
        // }
        if (password == ""){
            layer_msg("请输入密码")
            return false
        }
        $.ajax({
            type: "POST",
            url: laravel_api + "/transaction/add",
            data: {
                user_id:user_id,
                address:address,
                select_money:select_money,
                number:number,
                remarks:remarks,
                password:password
            },
            dataType: "json",
            success: function(data){
                console.log(data);
                if (data.type == "ok"){
                    layer.open({
                        content: '转账成功',
                        btn: '我知道了',
                        shadeClose: false,
                        yes: function(){
                            window.location.href ="record.html";
                        }
                    });
                    return false
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    })
});