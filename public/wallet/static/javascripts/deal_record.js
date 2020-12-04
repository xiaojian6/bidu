$(function(){
    // alert(123)
    var address = $("#user_info").data("address")
    var way='buy';
    if(get_all_params().type){
        way=get_all_params().type==1?'sell':'buy';
        var cur=$('.record span[data-id='+way+']')
        var index=cur.index();
        cur.addClass('hover').siblings().removeClass('hover')
        $('#con li').eq(index).show().siblings().hide();
    }
    // 获取数据
    function getData(){
        if (address != ""){
            layer_loading();
            $.ajax({
                url:laravel_api +'/acceptor/my_deal',
                type:'GET',
                data:{address:address,type:way},
                dataType:'json',
                success:function(res){
                    console.log(res)
                    layer_close();
                    render_list(res.message);
                }
            })
        }
    }
    getData();
    $('.record_box .rec-item').click(function () {
        var i = $(this).index();
        $(this).addClass('hover').siblings().removeClass('hover');
        $('#con li').eq(i).show().siblings().hide();
        way=i==0?'buy':'sell';
        console.log(way)
        getData();
    });
    
    // console.log(way)
    
    //渲染列表
    function render_list (list){
        var html="";
        for( i in list){
            html+=`<div class="record-box">
                <div class="record-l">
                    <p>${list[i].money}&nbsp;CNY</p>
                    <div style="display:${list[i].type=='buy'?'block':'none'}">交易账号：${list[i].deal_account}</div>
                    <div style="display:${list[i].type=='sell'?'block':'none'}">提现账号：${list[i].deal_account}</div>
                    <div>承兑商：${list[i].acceptor_name}</div>
                    <div >承兑商交易账号：${list[i].hes_account}</div>
                    <div style="display:${list[i].way=='bank'?'block':'none'}">交易方式：银行</div>
                    <div style="display:${list[i].way=='ali_pay'?'block':'none'}">交易方式：支付宝</div>
                    <div style="display:${list[i].way=='we_chat'?'block':'none'}">交易方式：微信</div>
                    <p style="display:${list[i].is_sure==0?'block':'none' }" class="col1">等待承兑商确认</p>
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
        way=='buy'?$('.buy').html(html):$('.sell').html(html)       
    }
    
})