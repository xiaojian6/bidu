$(function(){
    initDataToken({url:'/user/info'},function(res){
        $('.user_name').html(res.account_number)
    })
    initDataToken01({url:'/new/walletList'},function(res){
        if(res.type=='ok'){
            $(".add").css("display","none");
        }else{
            $(".add").css("display","block");
        }
        var wallet=res.message.wallet;
        $(".about_money").html(res.message.total_cny);
        // $(".user_name").html(message.account_number);
        // $(".wallet").html(message.address);
        $("#total").html("总资产（￥"+res.message.total_cny+")");
        if(wallet&&wallet.length > 0){
            render_list(wallet)
        }
      })
    
    function render_list(list){
        var html = "";
        for(i in list){
           html+=` <li>
                <a href="wallet_account.html?id=${list[i].id}&m=${list[i].balance}&l=${list[i].lock_balance}&name=${list[i].name}">
                    <div class="asset_left">
                        <div class="asset_img">
                            <img src="${list[i].logo}" alt="">
                        </div>
                        <div class="asset_name">${list[i].name}</div>
                    </div>
                     <div class="asset_money">
                            <div><span>${list[i].balance} </span></div>
                            <p><span>${list[i].lock_balance} </span>(锁仓)</p>
                        </div>
                    </a>
                </li>
            `
           
            }
            $(".list").append(html);
        }
});