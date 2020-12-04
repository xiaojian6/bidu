$(function(){
    var address = $("#user_info").data("address");
    if (address != ""){
        layer_loading();
        $.ajax({
            type: "get",
            url: laravel_api + "/ltcbuy/list",
            data: {
                user_id:address
            },
            dataType: "json",
            success: function(data){
                layer_close();
                console.log(data);
                if (data.type == "ok"){
                    if(data.message.length > 0){
                      render_list(data.message)
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
        console.log(list)
        var html = "";
        for(i in list){
            if(list[i].price < 5000){
                html +='<li class="vip1">';
                html +='<div class="fontc_f">';
                html +='<p class="font12">'+list[i].create_time+'</p>';
                html +='<h3 class="font18">'+list[i].surplus_price+'</h3>';
                html +='</div>';
                html +='<div class="gif_icon">';
                // html +='<p class="fontc_2">矿机1000</p>';
                // html +=' <p class="font14 fontc_6">前五周享受200币/周，第六周到第20周享受100币/周。</p>';
                // html += '<img src="/images/icon/wakuan.gif" style="width:100%;" />'
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html +=' </div>';
                html +=' <div>';
                html +=' <p class="buy_miner_num">1000HWAD</p>';
                html +='<span class="btn_line">'+list[i].status_name+'</span>';
                html +=' </div>';
                html +=' </li>';
            }else{
                 html +='<li class="vip2">';
                html +='<div class="fontc_f">';
                html +='<p class="font12">'+list[i].create_time+'</p>';
                html +='<h3 class="font18">'+list[i].surplus_price+'</h3>';
                html +='</div>';
                html +='<div class="gif_icon">';
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                html += ' <span></span> '
                // html +='<p class="fontc_2">矿机10000</p>';
                // html +=' <p class="font14 fontc_6">前五周享受2000币/周，第六周到第20周享受1000币/周。</p>';
                html +=' </div>';
                html +=' <div>';
                html +=' <p class="buy_miner_num">10000HWAD</p>';
                html +='<span class="btn_line">'+list[i].status_name+'</span>';
                html +=' </div>';
                html +=' </li>';
            }
            
        }
        if(list.length<=0){
            $(".blank_box").show();
        }
        $(".myMiner1").append(html);
    }              
                   
                
});


               
                    
                   
                        
                        
                        
                            
                           
                       
                   
                
           