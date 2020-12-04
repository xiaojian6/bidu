$(function(){
    initDataToken({url:'/user/info'},function(res){
        $('.user_name').html(res.account_number)
        if(res.is_acceptor == 0){
            $("#none").css("display", "none")
        }else if(res.is_acceptor == 1){
            $("#none").css("display", "block")
        }
    })
    
});