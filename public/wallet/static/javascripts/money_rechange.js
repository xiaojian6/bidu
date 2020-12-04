$(function(){
    var paras=get_all_params();
    initDataToken({url:'/new/money/rechange?currency_id='+paras.currency_id},function(data){
            console.log(data);
            // if (data.type == "ok"){
                console.log(data);
                $("#addr").html(data.company_eth_address)
                $(".rechange_ewm").attr("src","http://qr.liantu.com/api.php?&w=300&text="+data.company_eth_address);
                var content = $("#addr").val();
                var clipboard = new ClipboardJS('.copy', {
                    text: function() {
                        return content;
                    }
                });
                clipboard.on('success', function(e) {
                    layer_msg("复制成功");
                });
            
                clipboard.on('error', function(e) {
                    layer_msg("请重新复制");
                });
            // } else{
            //     layer_msg(data)
            //     return false;
            // }
       
    })
    
})
    
