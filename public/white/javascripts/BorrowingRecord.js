$(function(){	
	$('.complete').click(function(){
		$('#mask1').show();
		$('#genre').animate({
			bottom:'0'
		},500);
	})
	
	$('#genre>ul>li>p').click(function(){
		$('#mask1').hide();
		$(this).addClass('p').siblings().removeClass('p');
		$('#genre').animate({
			bottom:'-40%'
		},500);
		$('.complete>span').html($(this).html());
	})
	$('.cancel').click(function(){
		$('#mask1').hide();
		$('#genre').animate({
			bottom:'-40%'
		},500);
	})
	
	
	
	
	
	
	
	
	
	
	
	
	
})
