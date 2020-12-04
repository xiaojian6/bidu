$(function(){
    var address = $("#user_info").data("address")
    if (address != ""){
        layer_loading()
        initDataToken(
            {url:"/get/userinfo",type:'post'},
            function(data){
            console.log(data);
            var code = "http://"+window.location.host+"/register.html?cod="+data.account_number;
            $(".invite_ewm").attr("src","http://qr.liantu.com/api.php?&w=300&text="+code)
            $("#code").html(data.account_number);
           
            console.log(code);
            $("#share_btn").click(function(){
                $(".mask_box").css("display","block");
                $(".share_icon").removeClass("hide");
            })
            $(".mask_box").click(function(){
                $(this).css("display","none");
                $(".share_icon").addClass("hide");
            })
            window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"http://www.huoma.redcreatword.html?cod="+data.account_number,"bdMini":"1","bdMiniList":["weixin","sqq"],"bdPic":"","bdStyle":"1","bdUrl":"http://www.huoma.redcreatword.html?cod="+data.account_number,"bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
       
    })
        // $.ajax({
        //     type: "POST",
        //     url: laravel_api + "/user/getuserbyaddress",
        //     data: {
        //         address:address
        //     },
        //     dataType: "json",
        //     success: function(data){
                
        //         layer_close();
        //         if (data.type == "ok"){
        //              var code = "http://"+window.location.host+"register.html?cod="+data.phone;
        //             $(".invite_ewm").attr("src","http://qr.liantu.com/api.php?&w=300&text="+code)
        //             $("#code").html(data.phone);
                   
        //             console.log(code);
        //             $("#share_btn").click(function(){
        //                 $(".mask_box").css("display","block");
        //                 $(".share_icon").removeClass("hide");
        //             })
        //             $(".mask_box").click(function(){
        //                 $(this).css("display","none");
        //                 $(".share_icon").addClass("hide");
        //             })
        //             window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"http://www.huoma.redcreatword.html?cod="+data.phone,"bdMini":"1","bdMiniList":["weixin","sqq"],"bdPic":"","bdStyle":"1","bdUrl":"http://www.huoma.redcreatword.html?cod="+data.phone,"bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
        //         } else{
        //             layer_msg(data)
        //             return false;
        //         }
                
        //     }
        // });
    }
});