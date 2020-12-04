$(function(){
    var id=get_param('id');
    initDataToken({url:'/account/newlist',type:'post',data:{id:id}},function(msg){
        if(msg.data&&msg.data.length > 0){
            $('#data_ul').data("page",msg.page);
            render_list(msg)
        }else{
            layer_msg('没有数据...')
        }
    })
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        if(scrollTop + windowHeight == scrollHeight){
            var next_page = parseInt($('#data_ul').data("page")) + 1;
            console.log($('#data_ul').data("page"));
            var params={};
            params.id = id;
            params.page = next_page;
          
            initDataToken({url:'/account/newlist',type:'post',data:params},function(msg){
                if(msg.data&&msg.data.length > 0){
                    $('#data_ul').data("page",msg.page);
                    render_list(msg)
                }else{
                    layer_msg('没有更多数据...')
                }
            })
              
        }
    }) 

    function render_list(list){
        console.log(list)
        var item = list.data;
        var user_id =list.user_id;
        if(item && item.length>0){
            var html = "";
            for(i in item){
                console.log(item[i].info);
                html += '<li >'
                html += '<div>'
                html += '<div class="">'
                html += '<div >'
                html += '<p class="fontc_3 mgb10">'+item[i].info+'</p><div class="flex">'
                html += '<p class="font12 fontc_9">'+item[i].created_time+'</p>'
                html += '<span class="fontc_hdl mgr10">'+item[i].value+'</span>'
                html += '</div></div></div>'
           
                
                html += '</div>'
                html += '</li>'
            }
            $("#data_ul").append(html);
        }
    }
});

