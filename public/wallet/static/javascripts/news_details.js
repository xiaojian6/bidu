$(function(){
    var id = get_param("id");
    $.ajax({
        type: "get",
        url: laravel_api + "/news/detail",
        data: {id:id},
        dataType: "json",
        success: function(data){
            console.log(data)
            if (data.type == "ok"){
                    $("#title").html(data.message.title);
                    $("#keyword").html(data.message.keyword);
                    $("#time").html(data.message.update_time);
                    $(".content p").html(data.message.content);
                    $('#img').attr('src',data.message.thumbnail)
                } else{
                    layer_msg(data.message)
                    return false;
                }
        }
    });   

   
})




