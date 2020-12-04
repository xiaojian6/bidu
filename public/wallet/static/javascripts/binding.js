$(function(){
    var address = $("#user_info").data("address")
    if (address != ""){
        layer_loading()
        $.ajax({
            type: "POST",
            url: laravel_api + "/user/getuserbyaddress",
            data: {
                address:address
            },
            dataType: "json",
            success: function(data){
                layer_close()
                if (data.type == "ok"){
                    $("#eth_address").val(data.message.eth_address)
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }
    $(".close_btn").click(function(){
			$("#eth_address").val(" ");
	})

    $(".login_btn").click(function () {
        var address = $("#user_info").data("address")
        var eth_address = $("#eth_address").val()

        if (address == ""){
            layer_msg("请先登录")
            return false
        }
        if (eth_address == ""){
            layer_msg("请输入以太坊账号")
            return false
        }

        $.ajax({
            type: "POST",
            url: laravel_api + "/user/update_address",
            data: {
                address:address,
                eth_address:eth_address,
            },
            dataType: "json",
            success: function(data){
                if (data.type == "ok"){
                    layer_msg("修改成功")
                    return false
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    })
});