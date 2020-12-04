$(function () {
	// 请求页面数据
	initDataTokens({
		url: 'wallet/hzhistory'
	}, function (res) {
		console.log(res);
		if (res.type == 'ok') {
			var list = res.data;
			var html = '';
			if (list.length > 0) {
				$('.nodata').hide();
				for (let i in list) {
					html += '<li class="flex ftw">';
					html += '<p>'+list[i].type+'</p>';
					html += '<p>'+Number(list[i].number).toFixed(2)+'</p>';
					html += '<p>'+list[i].add_time+'</p>';
					html += '</li>';
				}
				$('.list').append(html);
			} else {
				$('.nodata').show()
			}
		}
	})
	// $.ajax({
	// 	url: _API + 'wallet/hzhistory',
	// 	type: 'get',
	// 	// data: {
	// 	// 	user_id: localStorage.getItem('user_id'),
	// 	// 	access_token: localStorage.getItem('token')
	// 	// },
	// 	// headers: {
	// 	// 	'Authorization': localStorage.getItem('token')
	// 	// },
	// 	success: function (res) {
	// 		console.log(res)
	// 	}
	// })

	$('.complete').click(function () {
		$('#mask1').show();
		$('#genre').animate({
			bottom: '0'
		}, 500);
	})

	$('#genre>ul>li>p').click(function () {
		$('#mask1').hide();
		$(this).addClass('p').siblings().removeClass('p');
		$('#genre').animate({
			bottom: '-40%'
		}, 500);
		$('.complete>span').html($(this).html());
	})
	$('.cancel').click(function () {
		$('#mask1').hide();
		$('#genre').animate({
			bottom: '-40%'
		}, 500);
	})
})