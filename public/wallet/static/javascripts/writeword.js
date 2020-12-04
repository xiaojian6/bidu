function randomsort(a, b) {
    return Math.random()>.5 ? -1 : 1;
    //用Math.random()函数生成0~1之间的随机数与0.5比较，返回-1或1
}
$(function(){
    
    var word_content = getStorage('word')
    console.log(word_content)
    $("#input_content").val(word_content);
    if(word_content != ""){
        var word = word_content.split(" ")
        word.sort(randomsort);
        $(".show_word").html("")
        word.forEach(function(value,i){
            $(".show_word").append("<li>"+value+"</li>")
        })
    }

    $(".show_word").on("click","li",function(){
        if($(this).hasClass("active")){

        }else{
            $(this).addClass("active")
            $("#write_word").append('<span class="write_one">'+$(this).html()+'</span>')
        }
    });
     $("#write_word").on("click","span",function(){
        var text = $(this).html();
        $(this).remove();
        $(".show_word li").each(function(){
            if(text == $(this).html()){
                $(this).removeClass("active");
            }
        })
    })
    $(".login_btn").click(function(){
        var write_word = ""
        $("#write_word span").each(function (i) {
            if ( i == 0 ){
                write_word = $(this).html()
            } else{
                write_word = write_word + " " + $(this).html()
            }
        })
        // $("#form_content").submit()
        // return false;
        if (word_content == write_word){
           location.href='creatAccount.html'
        } else{
            layer_msg("顺序错误")
        }

    })
});