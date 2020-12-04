$(function(){
    function getWord(){
        $.ajax({
            type: "GET",
            url: node_api + "/word/chinesenew",
            data: "",
            dataType: "json",
            success: function(data){
                if(data.error == "0"){
                    $("#input_content").val(data.content)
                    setStorage('word',data.content);
                    var content = data.content.split(" ")
                    $("#span_content").html("")
                    content.forEach(function(value,i){
                        $("#span_content").append("<span>"+value+"</span>")
                    })
                }
            }
        });
    }
    
    $(".icon-xunhuan").click(function(){
        getWord()
    })
    $(".login_btn").click(function(){
        
        location.href="writeword.html";
    })
    $(function(){
        getWord()
    });
})