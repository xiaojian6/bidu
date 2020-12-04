$(function(){
    var address = $("#user_info").data("address")
    if (address != ""){
        layer_loading()
        $.ajax({
            type: "get",
            url: laravel_api + "/index",
            data: {
                address:address
            },
            dataType: "json",
            success: function(data){
                
                layer_close()
                console.log(data);
                if (data.type == "ok" ){
                        render_list(data.message.coin_list);
                }else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }

    function render_list(list){
        var html = "";
        for(i in list){
            var str =(list[i].quotes.USD.percent_change_24h).toString();
           html += '<li>';
           html += '<div class="coin_trade" style="width:36%;">';
           html += '<img src="/images/hdl/icon_ytb.png" class="coin_icon" width="32" height="32" alt="">';
           html += '<p style="margin-left:6px;">';
           html += '<span style="display:block;">'+list[i].symbol+'</span>';
           html += '<span class="font13 fontc_9">$'+list[i].total_supply+'</span>';
           html += '</p>';
           html += '</div>';
           html += '<div style="width:32%;">';
           html += '<p>';
           html += '<span class="font14" style="display: block">$'+list[i].quotes.USD.price+'</span>';
        //    html += '<span class="font14 fontc_9">￥3028.00</span>';
           html += '</p>';
           html += '</div>';
           html += '<div style="width:32%;">';
           if(str.substr(0,1) == "-" ){
                 html += '<span class="coin_updown coin_down">'+list[i].quotes.USD.percent_change_24h+'%</span>';
           }else{
               html += '<span class="coin_updown">+'+list[i].quotes.USD.percent_change_24h+'%</span>';
           }
          
           html += '</div>';
           html += '</li>';
           html += '';
        }
        $(".coin_tbody").append(html);
    }                
});