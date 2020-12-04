$(function(){
    var address = $("#user_info").data("address");
    if (address != ""){
        layer_loading();
        $.ajax({
            type: "get",
            url: laravel_api + "/ltc/list",
            data: {
                address:address
            },
            dataType: "json",
            success: function(data){
                layer_close();
                console.log(data);
                if (data.type == "ok"){
                    if(data.message.length > 0){
                      render_list(data.message)
                      $('.blank_box').hide();
                    }else{
                        $('.blank_box').show();
                    }
                } else{
                    layer_msg(data.message)
                    return false;
                }
            },error:function(){
                layer_close();
                layer_msg("网络请求错误！")
                $(".blank_box").show();
            }
        });
    }

    function render_list(list){
        var html = "";
        for(i in list){
            if(list[i].price < 5000){
                html += ' <li class="vip1_bg">';
                html += '<span class="code">矿机1000</span>';
                html += ' <div class="kj_list">';
                html += ' <div class="vip1_sm">';
                html += '<h1>1000 HWAD</h1>';
                html += '<div class="white">';
                html += '<p>激活999币成为VIP1，用户可以每天租一台1000币矿机，前五周享受200币/周，第六周到第20周享受100币/周。</p>';
                html += ' <em><a href="vipUp.html" class="fontc_yellow">激活VIP1></a></em>';
                html += '</div>';
                html += '<div class="vip_shop">';
                html += '<span><a class="fontc_f" href="/users/Miner_details?id='+list[i].id+'">立即租赁</a></span>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</a>';
                html += '</li>';
            }else{
                 html += ' <li class="vip2_bg">';
                html += '<span class="code">矿机10000</span>';
                html += ' <div class="kj_list">';
                html += ' <div class="vip1_sm">';
                html += '<h1>10000HWAD</h1>';
                html += '<div class="white">';
                html += '<p>激活9999币成为VIP2，用户可以每天租一台10000币矿机，前五周享受2000币/周，第六周到第20周享受1000币/周。</p>';
                html += '<em> <a href="vipUp.html" class="fontc_hdl">激活VIP2></a></em>';
                html += '</div>';
                html += '<div class="vip_shop">';
                html += '<span><a class="fontc_f" href="/users/Miner_details?id='+list[i].id+'">立即租赁</a></span>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
                html += '</a>';
                html += '</li>';
            }
           
        }
        if(list.length<=0){
            $(".blank_box").show();
        }
        $(".miner_item").append(html);
    }
});


               
                    
                   
                        
                        
                        
                            
                           
                       
                   
                
           