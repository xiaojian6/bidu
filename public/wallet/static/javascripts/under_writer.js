
$(function(){
    $('.bt li').click(function () {
        var that = this;
        $(".current .tab-sj").each(function(index,value){
                if($(that).index()==index){
                    $(value).show();
                    $(value).siblings().hide();
                }
            })
    });
    $('.bt li').click(function(){
        var i = $(this).index();  
            $(this).addClass('hover').siblings().removeClass('hover'); 
    });
    var address = $("#user_info").data("address")
    if (address != ""){
        layer_loading()
        $.ajax({
            url: laravel_api +"/acceptor/list",
            type:"GET",
            dataType:"json",
            success:function(res){
                layer_close();
                if (res.type == "ok" ){
                    console.log(res.message)
                    render_list(res.message);
                }else{
                    layer_msg(res.message)
                    return false;
                }
            }
        })
    }
    function render_list(underList) {
        var html='';

        for(var i in underList){
            html+=`<div class="sj">
            <a href="recharge_money.html?id=${underList[i].id}">
                <div class="sj_1">
                    <div class="top">
                        <p class="p1">用户id：${underList[i].user_id}</p>
                        <span class="p2">供应商：${underList[i].name}</span>
                        <ul class="clear xing">
                            <li>
                                <img src="../images/imtoken/xing.png" alt="">
                                <img src="../images/imtoken/xing.png" alt="">
                                <img src="../images/imtoken/xing.png" alt="">
                                <img src="../images/imtoken/xing.png" alt="">
                                <img src="../images/imtoken/xing.png" alt="">
                            </li>
                        </ul>
                    </div>
                    <table>
                        <tbody>
                            <tr>
                                <td>成交总额</td>
                                <td>充值额度</td>
                                <td>充值费率</td>
                            </tr>
                            <tr>
                                <td>30000</td>
                                <td>${underList[i].recharge_amount}</td>
                                <td>${underList[i].recharge_rate}%</td>
                            </tr>
                            <tr>
                                <td>成交笔数</td>
                                <td>提现额度</td>
                                <td>提现费率</td>
                            </tr>
                            <tr>
                                <td>30</td>
                                <td>${underList[i].cash_amount}</td>
                                <td>${underList[i].cash_rate}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </a> 
            </div>`
        }
        $('.tab-sj').append(html)
    }
})