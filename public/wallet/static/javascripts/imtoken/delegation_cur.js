$(function(){

    var url='transaction_in';
    var type='in';
    var page=1;
    getData(page);
    function render_list(data,page){
        var data=data.list;
        console.log(data)
        if(data && data.length>0){
          
            var html="";
            for(var i in data){
                // 买入
                    html+=`<div class="item">
                    <span>${data[i].price}</span>
                    <span>${data[i].number}</span>
                    <span class='time'>${data[i].create_time}</span>
                    <div class="do"><a href="javascript:;" data-id="${data[i].id}" class="operation">撤销</a></div>
                </div>`  
            }
            if(page==1){
                if(url=="transaction_in"){
                    $("#buy").html(html)
                }else{
                    $('#sell').html(html)
                }
            }else{
                if(type=="transaction_in"){
                    $("#buy").append(html)
                }else{
                    $('#sell').append(html)
                }
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
        initDataToken({url:'/'+url,type:'post',data:{page}},function(res){
            render_list(res,page);
        })
    }
  
    $('.tab a').click(function(){
        var index=$(this).index();
        console.log(index,123)
        url=index==0?"transaction_in":"transaction_out";
        type=index==0?'in':'out';
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
    $(document).on('click',".do .operation",function(){
        var id=$(this).data('id');
        var obj = $(this)
        layer.open({
            content: '您确定要删除吗？'
            ,btn: ['确定', '取消']
            ,yes: function(index){
                initDataToken({url:'/transaction_del',type:'post',data:{id,type}},function(res){
                    obj.parent().parent().remove()
                })
            }
          });
    })
})