$(function(){
    var currency_id=1,legal_id=3;
    var current_balance,legal_balance;
    // btc
    initDataToken({url:'/wallet/detail',type:'post',data:{type:'change',currency:currency_id}},function(res){
        console.log(res)
        current_balance=res.change_balance-0 ||0;
        $('.jnb-balance').html(current_balance)
    })
    initDataToken({url:'/wallet/detail',type:'post',data:{type:'change',currency:legal_id}},function(res){
        console.log(res)
        legal_balance=res.change_balance-0 ||0;
        $('.cny-balance').html(legal_balance)
    })
    // 初始化数据
    initDataToken({url:'/transaction/deal',type:'post',data:{currency_id,legal_id}},function(res){
        var outdata =res.out
        var indata =res.in
        var complete =res.complete
        $(".last-price").html(res.last_price)
        $(".cny-balance").html(res.user_cny)
        if(outdata && outdata.length>0){
            for(i in outdata){
                $(".seller_table").append('<div class="con"><span>'+outdata[i].price+'</span><span>'+outdata[i].number+'</span></div>')
            }
        }

        if(indata && indata.length>0){
            for(i in indata){
                $(".buy_table").append('<div class="con"><span>'+indata[i].price+'</span><span>'+indata[i].number+'</span></div>')
            }
        }
        if(complete && complete.length>0){
            for(i in complete){
                $(".complete_list").append('<div class="con"><span>'+complete[i].create_time+'</span><span>'+complete[i].price+'</span><span>'+complete[i].number+'</span></div>')
            }
        }
    })
 

   
    function socket(){
        initDataToken({url:'/user/info'},function(res){
            console.log(res,123)
        var socket = io(socket_api);
        socket.emit('login', res.id);
        // 后端推送来消息时
        socket.on('transaction', function(msg) {
            console.log(msg)
            if (msg.type =="transaction") {
                $(".last-price").html(msg.last_price)
                var indata = JSON.parse(msg.in)
                if(indata && indata.length>0){
                    $(".buy_table").html(
                        `<div class="title">
                            <span>买价</span>
                            <span>数量</span>
                        </div>   `
                    )
                    for(i in indata){ 
                        $(".buy_table").append('<div class="con"><span>'+indata[i].price+'</span><span>'+indata[i].number+'</span></div>')
                    }
                }
    
                var outdata = JSON.parse(msg.out)
                if(outdata && outdata.length>0){
                    $(".seller_table").html(
                        `<div class="title">
                            <span>卖价</span>
                            <span>数量</span>
                        </div>   `
                    )
                    for(i in outdata){
                        $(".seller_table").append('<div class="con"><span>'+outdata[i].price+'</span><span>'+outdata[i].number+'</span></div>')
                    }
                }
            }
        })
    })
   
}
socket();
    $(".tablea").find(".box:first").show(); //为每个BOX的第一个元素显示        
    $("#oranger a").on("click", function () { //给a标签添加事件  
        var index = $(this).index(); //获取当前a标签的个数  
        var id=$(this).data('id');
        if(index<=1){
            $('.box').eq(0).show().siblings().hide();
            $('#'+id).show().siblings('.box-l').hide();
        }else{
            $('.box').eq(index--).show().siblings().hide();
        }
        // $(this).parent().next().find(".box").hide().eq(index).show(); //返回上一层，在下面查找css名为box隐藏，然后选中的显示  
        $(this).addClass("hover").siblings().removeClass("hover"); //a标签显示，同辈元素隐藏  
    })
    $('#buy-price').keyup(function(){
        $('#buy-total').val($(this).val()*$('#buy-num').val())
    })
    $('#buy-num').keyup(function(){
        $('#buy-total').val($(this).val()*$('#buy-price').val())
    })
    $('#sell-price').keyup(function(){
        $('#sell-total').val($(this).val()*$('#sell-num').val())
    })
    $('#sell-num').keyup(function(){
        $('#sell-total').val($(this).val()*$('#sell-price').val())
    })
    $('.plus').click(function(){
        var $input=$(this).parent().find('input');
        var value=$input.val() || 0;
        value=Number(value)+1;
        console.log(value)
        $input.val(value);
        $('#buy-total').val($('#buy-price').val()*$('#buy-num').val())
        $('#sell-total').val($('#sell-price').val()*$('#sell-num').val())
    })
    $('.plus2').click(function(){
        var $input=$(this).parent().find('input');
        var value=$input.val() || 0;
        
        $input.val(++value);
        $('#buy-total').val($('#buy-price').val()*$('#buy-num').val())
        $('#sell-total').val($('#sell-price').val()*$('#sell-num').val())
    })
    $('.minus').click(function(){
        var $input=$(this).parent().find('input');
        var value=$input.val() || 0;
        console.log(value)
        if(value>=1){
            $input.val(--value);
            return;
        }
        $input.val(0);
        $('#buy-total').val($('#buy-price').val()*$('#buy-num').val())
        $('#sell-total').val($('#sell-price').val()*$('#sell-num').val())
    })
    // 买入
    $('#buyIn').click(function () {
        var price=$('#buy-price').val();
        var num=$('#buy-num').val();
        if(!price){
            layer_msg("请输入价格！");
            return;
        }
        if(!num){
            layer_msg('请输入数量！');
            return;
        }
        initDataToken({url:'/transaction/walletIn',type:'post',
            data:{ 
                legal_id,
                currency_id,
                price:price,
                num:num,}
            },function(res){
                layer_msg(res)
                // $('#buy-price').val(0)
                $('#buy-num').val(0)
                $('#buy-total').val(0)
            }
        )
        
    })
    // 卖出
    $('#sellOut').click(function () {
        var price=$('#sell-price').val();
        var num=$('#sell-num').val();
        if(!price){
            layer_msg("请输入价格！");
            return;
        }
        if(!num){
            layer_msg('请输入数量！');
            return;
        }
        initDataToken({url:'/transaction/walletOut',type:'post',
            data:{ 
                legal_id,
                currency_id,
                price:price,
                num:num,}
            },function(res){
                layer_msg(res)
                // $('#sell-price').val()
                $('#sell-num').val(0)
                $('#sell-total').val(0)
            }
        )
    })
});