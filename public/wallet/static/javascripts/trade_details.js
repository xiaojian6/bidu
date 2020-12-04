$(function(){
    var address = $("#user_info").data("address")
    console.log(address)
    if (id != ""){
        layer.open({
            type: 2
            ,content: '加载中'
        });
        $.ajax({
            type: "POST",
            url: laravel_api + "/transaction/info",
            data: {
                id:id
            },
            dataType: "json",
            success: function(data){
                if (data.type == "ok"){
                    layer.closeAll();
                    $(".from_user").html(data.message.from_account)
                    $(".to_user").html(data.message.to_account)
                    if(data.message.from_address == address){
                        $(".number").html(-data.message.number);
                    }else{
                         $(".number").html(data.message.number);
                    }
                    
                    $(".from_user_address").html(data.message.from_address)
                    $(".to_user_address").html(data.message.to_address)
                    $(".remarks").html(data.message.remarks)
                    $(".time").html(data.message.time)
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }
});