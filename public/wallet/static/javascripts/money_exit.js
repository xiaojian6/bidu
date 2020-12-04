$(function(){
    var address = $("#user_info").data("address")
    if (address != ""){
        $.ajax({
            type: "get",
            url: laravel_api + "/money/exit",
            data: {
                user_id:address
            },
            dataType: "json",
            success: function(data){
                if (data.type == "ok"){
                    console.log(data.message);
                   $("#available").html(data.message.wallet.available);
                   $("#rate_exc").val(data.message.rate_exchange)
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }

    $("#extract_btn").click(function(){
        var extract_addr = $("#extract_addr").val();
        var extract_num = $("#extract_num").val();
        console.log(extract_num)
        $.ajax({
            type: "POST",
            url: laravel_api + "/money/exit",
            data: {
                // user_id:address
            },
            dataType: "json",
            success: function(data){
                console.log(data);
                
            }
        });
    })
   

    
});