
$(function(){
    var address = $("#user_info").data("address")
    var qu='buy';
    getData();
    $('#record span').click(function () {
        var i = $(this).index();
        $(this).addClass('hover').siblings().removeClass('hover');
        $('#con li').eq(i).show().siblings().hide();
        qu=i==0?'buy':'sell';
        console.log(qu)
        getData();
    });
    console.log(qu)
    // 获取数据
    function getData(){
        if (address != ""){
            layer_loading();
            $.ajax({
                url:laravel_api +'/acceptor/deal_do',
                type:'GET',
                data:{address:address,type:qu},
                dataType:'json',
                success:function(res){
                    layer_close();
                    console.log(res)
                    hover_list(res.message,);
                }
            })
        }
    }
   
    $("#con").on('click','.testquit',function(){
        var id=$(this).attr('data-id');
        $.ajax({
               url:laravel_api +'/acceptor/do_sure',
               type:'post',
               data:{address:address,id:id},
               dataType:'json',
               success: function(data){
                if (data.type == "ok"){
                   layer_msg(data.message)
                   setTimeout(function(){
                       window.location.reload();
                   }, 1000);      
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        })
})
$("#con").on('click','.remoll',function(){
    var id=$(this).attr('data-id');
    $.ajax({
           url:laravel_api +'/acceptor/do_cancel',
           type:'post',
           data:{address:address,id:id},
           dataType:'json',
           success: function(data){
            if (data.type == "ok"){
               layer_msg(data.message)
               setTimeout(function(){
                   window.location.reload();
               }, 1000);
            } else{
                layer_msg(data.message)
                return false;
            }
        }
    })
})
    //渲染列表
    function hover_list (list){
            var html='';
            for( i in list){
                html+=`<div class="record-box">
                    <div class="record-l">
                        <p>${list[i].money}&nbsp;CNY</p>
                        <div>真实姓名：${list[i].real_name}</div>
                        <div>承兑商：${list[i].acceptor_name}</div>
                        <div style="display:${list[i].way=='bank'?'block':'none'}">交易方式：银行(${list[i].type=='buy' ?list[i].hes_account:list[i].deal_account})</div>
                        <div style="display:${list[i].way=='ali_pay'?'block':'none'}">交易方式：支付宝(${list[i].type=='buy' ?list[i].hes_account:list[i].deal_account})</div>
                        <div style="display:${list[i].way=='we_chat'?'block':'none'}">交易方式：微信(${list[i].type=='buy' ?list[i].hes_account:list[i].deal_account})</div>
                        <p style="display:${list[i].is_sure==0?'block':'none' }" class="col1">等待承兑商确认
                           <a class="testquit" data-id="${list[i].id}" style="z-index: 19;">确认</a> <a class="remoll" data-id="${list[i].id}" style="z-index: 19;">取消</a>
                        </p>
                        <p style="display:${list[i].is_sure==1?'block':'none'}" class="col1">已确认</p>
                        <p style="display:${list[i].is_sure==2?'block':'none'}" class="col2">已拒绝</p>
                        
                    </div>
                    <div class="record-r">
                        <p>${list[i].type=='buy'?'充值':'提现'}</p>
                        <p>承兑时间：${list[i].create_time}</p>
                        <p style="display:${list[i].is_sure>0?'block':'none' }">确认时间：${list[i].update_time}</p>
                    </div>
                </div>
            `
            }  
            qu=='buy'?$('.buy').html(html):$('.sell').html(html)
        }    
})

