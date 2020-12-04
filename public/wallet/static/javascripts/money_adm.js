$(function(){
    var address = $("#user_info").data("address");
    if (address != ""){
        layer_loading();
        $.ajax({
            type: "get",
            url: laravel_api + "/wallet/list",
            data: {
                user_id:address
            },
            dataType: "json",
            success: function(data){
                layer_close();
                console.log(data);
                if (data.type == "ok" && data.message.list.length > 0){
                    $("#total").html(data.message.total_assets);
                    $(".money_add").css("display","none");
                    if(data.message.list.length > 0){
                      render_list(data.message.list)
                    }
                } else{
                    $(".money_add").css("display","block");
                }
            }
        });
    }

   function render_list(list){
        var html = "";
       
        for(i in list){
            html+=`<li>
                <a href="#" class="item-info">
                    <img src="http://qr.liantu.com/api.php?&w=300&text=${list[i].address}" alt="二维码">
                    <div class="user_name">钱包名称：${list[i].wallet_name}</div>
                    <div class="money">
                        余额：<span>${list[i].balance}</span>
                    </div>
                    <p>${list[i].address}</p>
                </a>
                
                <div class="gn">
                    <a href="money_rechange.html">充币</a>
                    <a href="turnout.html">转账</a>
                </div>
            </li>`
            // html +='<li class="backF">';
            // html +='<div class="money_name">';
            // html +='<div style="width:74%;">';
            // html +='<h4 class="fontc_6">'+list[i].wallet_name+'</h4>';
            // html +='<p class="fontc_3 mgt10"><span class="font14 fontc_9">原生华盾：</span>'+list[i].balance+'</p>';
            // html +='<p class="fontc_3 "><span class="font14 fontc_9">消费华盾：</span>'+list[i].consumption_balance +'</p>';
            // html +='<p class="fontc_3 "><span class="font14 fontc_9">增值华盾：</span>'+list[i].increment_balance+'</p>';
            // html +='<p class="fontc_3 "><span class="font14 fontc_9">锁定华盾：</span>'+list[i].frozen_balance+'</p>';
            // html +='<p class="fontc_3 font14" style="width:120%;overflow-y:scroll;">'+list[i].address+'</p>';
            // html +='</div>';
            // html +='<img src="http://qr.liantu.com/api.php?&w=300&text='+list[i].address+'" width="80" height="80" alt="">';
            // html +='</div>';
            // html +='<div class="money_btn_box">';
            // html +='<a href="money_rechange.html">充币</a>';
            // html +='<a href="turnout.html">转账</a>';
            // html +='<a href="record.html">账单</a>';
            // html +='<a href="account_details.html">账户明细</a>';
            // html +='</div>';
            // html += '</li>';
        }
        $(".wallet_list").append(html);
    }                  
                
});


               
                    
                   
                        
                        
                        
                            
                           
                       
                   
                
           