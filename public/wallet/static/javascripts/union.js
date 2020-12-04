$(function(){
    var address = $("#user_info").data("address");
    var user_id = address;var alliancenode_id;
    var password ;
    if (address != ""){
        layer_loading();
        $.ajax({
            type: "POST",
            url: laravel_api + "/alliance_node",
            data: {
                address:address
            },
            dataType: "json",
            success: function(data){
                layer_close();
                console.log(data);
                if (data.type == "ok"){
                    if(data.message.length > 0){
                      render_list(data.message)
                    }
                } else{
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }

    // $(window).scroll(function(){
    //     var scrollTop = $(this).scrollTop();
    //     var scrollHeight = $(document).height();
    //     var windowHeight = $(this).height();
    //     var params = get_all_params() || {};
    //     if(scrollTop + windowHeight == scrollHeight){
    //         var next_page = parseInt($('#data_ul').data("page")) + 1;
    //         console.log($('#data_ul').data("page"));
    //         params.address = address;
    //         params.page = next_page;
    //         console.log(params)
    //         if (address != ""){
    //         $.ajax({
    //             type: "POST",
    //             url: laravel_api + "/transaction/list",
    //             data: params,
    //             dataType: "json",
    //             success: function(data){
    //                 if (data.type == "ok"){
    //                     if(data.message.data.length > 0){
    //                         $('#data_ul').data("page",data.message.page);
    //                         render_list(data.message);
    //                     }else{
    //                         layer.open({
    //                             content: "没有更多数据"
    //                             ,skin: 'msg'
    //                             ,time: 2 
    //                         });
    //                     }                            
    //                 } 
    //             }
    //         })   
    //         }      
    //     }
    // }) 

    function render_list(list){
        var html = "";

        for(i in list){
            
            html += '<li class="union_item">';
            html += '<h3>'+list[i].name+'</h3>';
            html += '<div class="clearfix" style="padding:.6rem 1rem;">';
            html += ' <p class="font18 font_bold">'+list[i].price+'HWAD</p>';
            html += '<p class="font14 fontc_6">'+list[i].detail+'</p>';
            html += '<a href="javascript:;" class="gobuy_btn fr" data-id="'+list[i].id+'">立即购买</a>';
            html += '</div>';
            html += ' </li>';
        }
        $(".union_box").append(html);
    }

    $(".union_box").on("click",".gobuy_btn",function(){
        var alliancenode_id = $(this).attr("data-id");
        layer.open({
            content: ' <span>密码：</span><input  type="password" id="password" name="password" value="" placeholder="请输入钱包密码"  />'
            ,btn: ['确认']
            ,yes: function(index, layero){
                layer_loading();
                 password = $("#password").val();
                 if( password == ""){
                      layer_close();
                     layer_msg("密码不能为空")
                 }else{
                    $.ajax({
                        type: "POST",
                        url: laravel_api + "/alliance/add",
                        data: {
                            user_id:user_id,
                            alliancenode_id,
                            password:password
                        },
                        dataType: "json",
                        success: function(data){
                            layer_close();
                            if (data.type == "ok"){
                                layer_msg(data.message);
                                setTimeout(function(){
                                    window.location = "money_adm.html";
                                },1000)
                            } else{
                                layer_msg(data.message)
                                return false;
                            }
                        }
                    });
                 }
            }
        }); 
    })
     
});

