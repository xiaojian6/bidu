$(function(){
    var user_id = $("#user_info").data("address");
    var user_level = 0;
    if (user_id != ""){
        layer_loading()
        $.ajax({
            type: "POST",
            url: laravel_api + "/user/getuserbyaddress",
            data: {
                address:user_id
            },
            dataType: "json",
            success: function(data){

                layer_close()
                if (data.type == "ok"){
                    user_level = data.message.vip
                    if(data.message.vip == 1){

                        $(".grade li span:nth-child(2)").addClass("vip_state");
                        $(".vip1 .vip_grade_btn").addClass("grade_ash");

                    }else if(data.message.vip == 2){
                        $(".grade li span").addClass("vip_state");
                        $(".vip-sj .vip_grade_btn").addClass("grade_ash");
                        $(".vip_grade").hide()
                    }
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }


     
     $(".vip-sj ul").on("click","li",function(){
         if($(this).find(".vip_grade_btn").hasClass('grade_ash')){
            layer_msg("您已经是此会员，不需要在购买")
         }else{
             $(this).siblings().find(".vip_grade_btn").removeClass("grade_act");
         $(this).find(".vip_grade_btn").addClass("grade_act");
         }
     })

     
     console.log(vip_pass);
     $(".vip_grade").click(function(){
         var vip_pass = $("#vip_pass").val();
         for(var i=0;i<$(".vip-sj ul li").length;i++){
            var vip = $(".grade_act").attr("data-vip");
         }
         if (vip != 1 && vip != 2){
             layer_msg("请选择会员等级")
             return false;
         }
         if (vip == user_level){
             layer_msg("您已经是此会员，不需要在购买")
             return false;
         }
            layer_loading()
            console.log(vip);
            $.ajax({
                type: "POST",
                url: laravel_api + "/user/vip",
                data: {
                    user_id:user_id,
                    vip:vip,
                    password:vip_pass
                },
                dataType: "json",
                success: function(data){
                    layer_close();
                   if(data.type == "ok"){
                        layer_msg(data.message); 
                        setTimeout(function(){
                                window.location = "userinfo.html";
                            }
                            ,2000)  
                   } else{
                       layer_msg(data.message); 
                   }                
                }
            });
        
     })
    
});