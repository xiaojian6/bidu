$(function(){
    function slide(ele){
        var value = $(ele).val();
        $(ele).css({'background-size': value + "% 100%"})
    }
    // initDataToken({url:'/user/getuserbyaddress',type:'post'},function(data){
    //     console.log(data);
    //     $("#all_balance").html(data.message.all_balance)
    // })
    var id=get_param('id')
    $(".login_btn").click(function () {
        var address = $("#addr").val();
        // var select_money = $(".select_money").val();
        var number = parseFloat($("#number").val())
        var remarks = $("#remarks").val()
        // var all_balance = parseFloat($("#all_balance").html())
        var password = $("#password").val();
        console.log(password);

        if (address == ""){
            layer_msg("请输入地址")
            return false
        }
        if (number == ""){
            layer_msg("请输入数量")
            return false
        }
        // if (number > all_balance){
        //     layer_msg("您的余额不足")
        //     return false
        // }
        if (password == ""){
            layer_msg("请输入密码")
            return false
        }
        initDataToken({url:'/transaction/newadd',type:'post',data:{
            id:id,
            address:address,
            // select_money:select_money,
            number:number,
            remarks:remarks,
            password:password}},function(data){
                layer.open({
                    content: '转账成功',
                    btn: '我知道了',
                    shadeClose: false,
                    yes: function(){
                        window.location.href ='account_details.html?id='+id;
                    }
                });
        })
       
    })
});