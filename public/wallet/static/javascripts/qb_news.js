$(function(){
    var id = get_param("id");
    $.ajax({
        type: "get",
        url: laravel_api + "/news/detail",
        data: {id:id},
        dataType: "json",
        success: function(data){
            if (data.type == "ok"){
                    $("#title").html(data.message.title);
                    $("#keyword").html(data.message.keyword);
                    $("#time").html(data.message.update_time);
                    $("#content").html(data.message.content);
                } else{
                    layer_msg(data.message)
                    return false;
                }
        }
    });   

   
})




