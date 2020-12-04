$(function() {
	//=================li标签切换=====================
	$('#record>div>table').eq(0).show();
	
	$('ul>li').click(function(){
		$(this).addClass('borb').siblings().removeClass('borb');
		var index = $(this).index();
		$('#record>div>table').hide().eq(index).show();
		var length = $('#record>div>table').eq(index).find('tr').length;
		for(var i=0;i<length;i++) {
			var the = $('#record>div>table').eq(index).find('tr').eq(i).find('td').eq(1);
			if(the.html() == '买入'){
				the.addClass('g');
			}else if(the.html() == '卖出'){
				the.addClass('p');
			}			
		}

	});
	//=====================================
	$('#bottom>dl').click(function(){
		if($(this).hasClass('act') == false){
			$(this).addClass('act');
			$(this).find('dt>img').attr('src','images/collect1.png').parent('dt').next('dd').html('已收藏')
		}else{
			$(this).removeClass('act');
			$(this).find('dt>img').attr('src','images/collect2.png').parent('dt').next('dd').html('添加自选')
		}
		
	});
	
});

