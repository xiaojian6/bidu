$(function(){
    var address = $("#user_info").data("address")
    console.log(address)
    var paras=get_all_params();
    var tab=paras.m || 0;
    console.log(paras,tab)
    var id=''
    var way=paras.way;
    var data=''
    if(tab==0){
        $('#oranger a').eq(0).addClass('hover').siblings().removeClass('hover');
        $('.charge').show();
        $('.out').hide();
        if(way){
            console.log(123)
            $('.charge .way-info').show().prev().hide();
        }else{
            console.log(1234)
            $('.charge .way-info').hide().prev().show();
        }
        console.log(way,tab)

        tab=0;
    }else{
        $('#oranger a').eq(1).addClass('hover').siblings().removeClass('hover');
        $('.out').show();
        $('.charge').hide();
        if(way){
            $('.out .way-info').show().prev().hide();
        }else{
            $('.out .way-info').hide().prev().show();
        }
        tab=1;
    }
   function showChargeMethod(data,way){
       console.log(data,way,tab)
        if(tab>=0){
            console.log(tab)
            var show=tab==0?$('.charge .way-info'):$('.out .way-info');
            console.log(data,tab,way)
            // show.show().prev().hide();
            if(tab==0){
                switch(way){
                    //bank we_chat ali_pay
                    case 'bank':
                    console.log('bank')
                    show.children('p').eq(0).html(data.bank_name );
                    show.children('p').eq(1).html(data.bank_account);
                    break;
                    case 'ali_pay':
                    console.log('ali')
                    show.children('p').eq(0).html(data.ali_nickname );
                    show.children('p').eq(1).html(data.ali_account);
                    break;
                    case "we_chat":
                    console.log('chat')
                    show.children('p').eq(0).html(data.wechat_nickname );
                    show.children('p').eq(1).html(data.wechat_account);
                    default:
                    console.log('test')
                }
            }else{
                if(data && data.user_cashinfo){
                    var mydata=data.user_cashinfo;
                    switch(way){
                        //bank we_chat ali_pay
                        case 'bank':
                        console.log('bank')
                        show.children('p').eq(0).html(mydata.bank_name );
                        show.children('p').eq(1).html(mydata.bank_account);
                        break;
                        case 'ali_pay':
                        console.log('ali')
                        show.children('p').eq(0).html('支付宝账户');
                        show.children('p').eq(1).html(mydata.alipay_account);
                        break;
                        case "we_chat":
                        console.log('chat')
                        show.children('p').eq(0).html(mydata.wechat_nickname );
                        show.children('p').eq(1).html(mydata.wechat_account);
                        default:
                        console.log('test')
                    }
                }else{
                    // layer_msg(' 您未进行提现设置')
                    // setTimeout(() => {
                    //     location.href='user_photo.html'
                    // }, 1000);
                }
                
            }
            
        }
   }
    $('#oranger a').click(function () {
        var i = $(this).index();
        $(this).addClass('hover').siblings().removeClass('hover');
        $('#tablea .box').eq(i).show().siblings().hide();
        tab=$(this).data('way');
        console.log(tab)
        // $('.chargeWay').attr('href','recharge_method.html?id='+paras.id+'&m='+tab);
    });
    $('#out').click(function(){
        $(this).attr('href','recharge_method.html?id='+paras.id+'&m='+1);
    })
    $('#charge').click(function(){
        // console.log(tab)
        // $('.charge .input[name=moblie]').val()
        // setStorage('charge',{moblie:$('.charge .input[name=moblie]').val(),money:$('.charge .input[name=money]').val()})
        $(this).attr('href','recharge_method.html?id='+paras.id+'&m='+0);
    })
    $('.out input[name=moblie]').keyup(function(){
        console.log($(this).val(),'aaaa11')
        setStorage('out',{moblie:$(this).val(),money:$('.out input[name=money]').val()})
    })
    $('.out input[name=money]').keyup(function(){
        setStorage('out',{moblie:$('.out input[name=moblie]').val(),money:$(this).val()})
    })
    $('.charge input[name=moblie]').keyup(function(){
        console.log($(this).val(),'aaaa11')
        setStorage('charge',{moblie:$(this).val(),money:$('.charge input[name=money]').val()})
    })
    $('.charge input[name=money]').keyup(function(){
        setStorage('charge',{moblie:$('.charge input[name=moblie]').val(),money:$(this).val()})
    })
    $('.out input[name=moblie]').val(getStorage('out')&&getStorage('out').moblie)
    $('.out input[name=money]').val(getStorage('out')&&getStorage('out').money)
    $('.charge input[name=moblie]').val(getStorage('charge')&&getStorage('charge').moblie)
    $('.charge input[name=money]').val(getStorage('charge')&&getStorage('charge').money)
   function getData(url,data){
    //    /acceptor/info
    // {id:paras.id,address:address}
    $.ajax({
        url: laravel_api +url,
        data:data,
        type:"GET",
        dataType:"json",
        success:function(res){
            if (res.type == "ok" ){
                console.log(res.message)
                var data=res.message;
                $('.under-name').html(res.message.name);
                $(".allbalance").html(res.message.user_balance); 
                id=data.id;
                showChargeMethod(data,way)
            }else{
                layer_msg(res.message);
                return false;
            }
        }
    })
   }
//    if(tab==0){
    getData('/acceptor/info',{id:paras.id,address:address});
//        console.log(tab)
//    }else{
//        console.log(tab)
//     getData('/user/cash_info',{address:address});
//    }
    $('.charge .btnok a').click(function(){
        var moblie=$('.charge input[name="moblie"]').val();
        var code =$('.charge input[name="code"]').val();
        var money=$('.charge input[name="money"]').val();
        var account=$('.charge input[name="account"]').val();
        // var method=way;
        
        if(!moblie){
            layer_msg('请输入正确的手机号');
            return false;
        }
        // if(!code){
        //     layer_msg('验证码错误');
        //     return false;
        // }
        
        if(!way){
            layer_msg('请选择充值方式');
            return false;
        }
        // if(!account){
        //     layer_msg('请输入您的交易账号');
        //     return false;
        // }
        if(!money){
            layer_msg('请输入充值金额');
            return false;
        }
        $.ajax({
            url:laravel_api+"/acceptor/deal",
            type:"POST",
            dataType:'json',
            data:{acceptor_id:id,address:address,mobile:moblie,way:way,type:'buy',money:money},
            success:function(res){
                if(res.type=='ok'){
                    layer_msg(res.message);
                    setTimeout(() => {
                        location.href='deal_record.html?type=0'
                    }, 1000);
                }else{
                    layer_msg(res.message+'，请完善！')
                    setTimeout(() => {
                        location.href='user_photo.html'
                    }, 2000);
                }
            }
        })
    })
    $('.out .btnok a').click(function(){
        var moblie=$('.out input[name="moblie"]').val();
        var code =$('.out input[name="code"]').val();
        var money=$('.out input[name="money"]').val();
        var account=$('.out input[name="account"]').val();
        // var method=way;
        if(!moblie){
            layer_msg('请输入正确的手机号');
            return false;
        }
        // if(!code){
        //     layer_msg('验证码错误');
        //     return false;
        // }
        
        if(!way){
            layer_msg('请选择提现方式');
            return false;
        }
        // if(!account){
        //     layer_msg('请输入您的交易账号');
        //     return false;
        // }
        if(!money){
            layer_msg('请输入提现金额');
            return false;
        }
        console.log($('.allbalance').html())
        if(money>$('.allbalance').html()){
            layer_msg('账户余额不足，请重新输入！');
            return false;
        }
        $.ajax({
            url:laravel_api+"/acceptor/deal",
            type:"POST",
            dataType:'json',
            data:{acceptor_id:id,address:address,mobile:moblie,way:way,type:'sell',money:money},
            success:function(res){
                if(res.type=='ok'){
                    layer_msg(res.message);
                    setTimeout(() => {
                        location.href='deal_record.html?type=1'
                    }, 1000);
                }else{
                    layer_msg(res.message)
                }
            }
        })
    })
    // $('.sendCode').click(function(){
    //     $.ajax({
    //         url:laravel_api +'/sms_send',
    //         type:'POST',
    //         data:{mobile:mobile},
    //         dataType:'json',
    //         success:function(res){
    //             console.log(res)
    //         }
    //     })
    // })
})