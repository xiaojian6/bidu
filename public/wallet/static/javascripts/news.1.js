$(function(){
    layer_loading()
    $.ajax({
        type: "get",
        url: laravel_api + "/news/list",
        data: {c_id:20},
        dataType: "json",
        success: function(data){
            layer_close()
            if (data.type == "ok"){
                var data = data.message;
                if(data.list.length > 0){
                    render_list(data.list);
                }                    
            } 
        }
    });   

    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
        var params = get_all_params() || {};
        if(scrollTop + windowHeight == scrollHeight){
            var next_page = parseInt($('#scroller ul').data("page")) + 1;
            params.page = next_page;
            console.log(params);
             $.ajax({
                type: "get",
                url: laravel_api + "/news/list",
                data: params,
                dataType: "json",
                success: function(data){
                    if (data.type == "ok"){
                        var data = data.message;
                        if(data.list.length > 0){
                            $('#scroller ul').data("page",data.page);
                            render_list(data.list);
                        }else{
                            layer.open({
                                content: "没有更多数据"
                                ,skin: 'msg'
                                ,time: 2 //2秒后自动关闭
                            });
                        }                            
                    } 
                }
           });         
        }
    }) 

    function render_list(list){
        console.log(list);
        if(list && list.length>0){
            var html = "";
            for(i in list){
                html += '<li>'
                html += '<a href="infor_details.html?id='+list[i].id+'">'
                html += '<img src="'+list[i].thumbnail +'" alt="">'
                html += '<div style="flex:1;">'
                html += '<p class="fontc_3 font16 climp2"> '+list[i].title +'</p>'
                html += '<p class="fontc_9 font12 mgt10">'+list[i].create_time+'</p>'
                html += '</div>'
                html += '</a>'
                html += '</li>'
            }

            $("#scroller ul").append(html);
        }
    }

})




