$(function(){
    var paras=get_all_params();
    console.log(paras)
    var id=paras.id;
    $("#money").html(paras.m);
    $("#lock").html(paras.l);
    $("#m_name").html(paras.name);
    // $("#id").html(paras.id);
    $('#rechange').click(function(){
        window.location.href='money_rechange.html?currency_id='+id;
    })
    $('.tibi').click(function(){
        location.href="mentionMoney.html?id="+id;
    })
    $('.transfer').click(function(){
        window.location.href='asset2_details.html?id='+id;
    })
    $('.collet').click(function(){
        window.location.href='asset_collect.html?id='+id;
    })
    $('.detail').click(function(){
        window.location.href='account_details.html?id='+id;
    })
})