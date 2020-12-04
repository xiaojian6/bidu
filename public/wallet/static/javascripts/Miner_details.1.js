$(function(){
    var address = $("#user_info").data("address");
    
    if (id != ""){
        layer.open({
            type: 2
            ,content: '加载中'
        });
        $.ajax({
            type: "get",
            url: laravel_api + "/ltc/detail",
            data: {
                id:id,
                 user_id:address
            },
            dataType: "json",
            success: function(data){
                layer.closeAll();

                if (data.type == "ok"){
                   $("#miner_png").attr("src",data.message.thumbnail);
                    $("#miner_money").html(parseInt(data.message.price));
                    $("#miner_name").html(data.message.name);
                    // $("#miner_title").html(data.message.profile);
                    $("#miner_detail").html(data.message.detail)
                } else{
                    // layer_msg(data.message);
                    // setTimeout(function(){
                    //          window.location = "userinfo.html";
                    //     }
                    //     ,2000)
                    // return false;
                }
            }
        });
    }

    $(".gobuy_btn").click(function(){
        var select_money = $(".select_money").val();
        $.ajax({
            type: "post",
            url: laravel_api + "/ltc/buy",
            data: {
                ltc_id:id,
                user_id:address,
                select_money:select_money
            },
            dataType: "json",
            success: function(data){
                if (data.type == "ok"){
                    layer.closeAll();
                    layer_msg(data.message);
                      window.location = "userinfo.html";
                } else{
                    layer_msg(data.message);

                    return false;
                }
            }
        });
    })
    
});
               
                    
                   
                        
                        
                        
                            
                           
                       
                   
                
           