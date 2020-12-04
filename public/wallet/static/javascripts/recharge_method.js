$(function(){
    var paras=get_all_params();
    if(paras.m==1){
        $('.header h5').html('请选择提现方式')
    }
    var way='we_chat';
    $('.select-card').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        way=$(this).data('way');
        console.log(way)
    })
    var address=$('#user_info').data('address');
    //bank we_chat ali_pay
    function getData(url,data){
        // /acceptor/info"
        // {id:paras.id}
        $.ajax({
            url: laravel_api +url,
            data:data,
            type:"GET",
            dataType:"json",
            success:function(res){
                console.log(res)
                if (res.type == "ok" ){
                    var msg=res.message;
                    console.log(msg)
                    if(msg){
                        $('#chat_name').html('姓名：'+msg.wechat_nickname)
                        $('#chat_acc').html('账户：'+msg.wechat_account)
                    
                        if(paras.m==0){
                            $('#ali_name').html('姓名：'+msg.ali_nickname)
                            $('#ali_acc').html('账户：'+msg.ali_account)
                            $('#bank_add').html('地址：'+msg.bank_address)

                        }else{
                            $('#ali_name').html('姓名').hide();
                            $('#ali_acc').html('账户：'+msg.alipay_account)
                        }
                        // $('#bank_name').html('姓名：'+msg.bank_nickname)
                        $('#bank_acc').html('账户：'+msg.bank_account)
                        $('#bank_name2').html('银行：'+msg.bank_name)
                    }else{
                        layer_msg('您还未进行提现设置，请前往设置！')
                        setTimeout(() => {
                            location.href='user_photo.html'
                        }, 2000);
                    }
                    
                }else{
                    layer_msg(res.message)
                    return false;
                }
            }
        })
    }
    if(paras.m==0){
        getData('/acceptor/info',{id:paras.id});

    }else{
        getData('/user/cash_info',{address:address})

    }
    
    $('.btn-ok').click(function(){
        window.location.href='recharge_money.html?id='+paras.id+"&way="+way+"&m="+paras.m;
    })
})