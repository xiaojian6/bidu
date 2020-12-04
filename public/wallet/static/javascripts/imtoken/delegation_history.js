$(function(){
    var page=1;
    getData(page);
    function render_list(res,page){        
        var data=res.list;
        console.log(data,res)
        if(data && data.length>0){
            var buyHTML="";
            var sellHTML="";
            for(var i in data){
                // 买入
                if(data[i].type=='in'){
                    buyHTML+=`<div class="item">
                        <span>${data[i].price}</span>
                        <span>${data[i].number}</span>
                        <span class='time'>${data[i].time}</span>
                    </div>`
                }else{
                    sellHTML+=`<div class="item">
                        <span>${data[i].price}</span>
                        <span>${data[i].number}</span>
                        <span class='time'>${data[i].time}</span>
                    </div>`
                }
            }
            if(page==1){
                $("#buy").html(buyHTML)
                $('#sell').html(sellHTML)
            }else{
                $("#buy").append(buyHTML)
                $('#sell').append(sellHTML)
            }            
        }else{
            layer.open({
                content: "没有更多数据"
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
        }  
    } 
    function getData(page){
        initDataToken({url:'/transaction_complete',type:'post',data:{page}},function(res){
            render_list(res,page);
        })
    }
    $('.tab a').click(function(){
        var index=$(this).index();
        type=index==0?"in":"out";
        console.log(index)
        $(this).addClass('hover').siblings().removeClass('hover')
        $('.delLists .delCons').eq(index).show().siblings('.delCons').hide();
        getData(page);
    })
    $(window).scroll(function(){
        var scrollTop = $(this).scrollTop();
        var scrollHeight = $(document).height();
        var windowHeight = $(this).height();
       
        if(scrollTop + windowHeight == scrollHeight){
            ++page;
            console.log(page);
            getData(page);    
        }
    }) 
})