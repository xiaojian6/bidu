$(function(){
    $(".helps_box li .title").mouseover(function(){
        console.log(123)
      var contH = $(this).next().height()+60;
        $(this).parent().animate({height:contH},500);
        $(this).parent().siblings().animate({height:42},500);
        $(this).addClass("title1").parent().siblings().children("p").removeClass("title1");
    })
})