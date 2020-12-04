var swiper = new Swiper('.indexban', {
	slidesPerView: 3,
	spaceBetween: 30,
	pagination: {
			el: '.swiper-pagination',
			clickable: true,
	},

});

$(".demos").bootstrapNews({
	newsPerPage: 1,
	autoplay: true,
	pauseOnHover: true,
	navigation: false,
	direction: 'down',
	newsTickerInterval: 2500,
	onToDo: function () {}
});
//===============公告列表======================
$.ajax({
	type: 'post',
	url: _API + 'news/list',
	dataType: 'json',
	data: {
			c_id: 4
	},
	success: function (data) {
			console.log(data)
			var html = '';
			var title;
			for (i = 0; i < data.message.list.length; i++) {
					html += "<li class='news-item'><a href='journalism.html?id=" + data.message.list[i].id +
							"'>" + data.message.list[i].title + "</a></li>";
			};
			$('.demos').html(html);
	}
});
//======================新闻列表======================
$.ajax({
	type: 'post',
	url: _API + 'news/list',
	dataType: 'json',
	data: {
			c_id: 5
	},
	success: function (data) {
			console.log(data);
			var ban = data.message.list
			var html = '';
			//var title;
			for (i = 0; i < ban.length; i++) {
					var ner = ban[i];
					html +=
							"<div class='banlide swiper-slide swiper-slide-center none-effect'><a href='journalism.html?id=" +
							ner.id + "'><img src='" + ner.thumbnail +
							"'/></a><div class='layer-mask'></div></div>";

			};
			$('#ban').html(html);
			init();
	}
});

function init() {
	setTimeout(function () {
			var swiper = new Swiper('.indexban', {
					autoplay: true,
					speed: 500,
					loop: true,
					autoplayDisableOnInteraction: false,
					centeredSlides: true,
					slidesPerView: 1.25,
					pagination: '.indexper',
					paginationClickable: true,
					pagination: {
							el: '.banper',
							clickable: true,
					},
					onInit: function (swiper) {
							swiper.slides[2].className = "banlide swiper-slide-active";
					},
					breakpoints: {
							668: {
									slidesPerView: 1.25,
							}
					}
			})
	}, 0);
}
var cny = 1,
	pb_price = 1;
//===========我的资产==============   
var token = get_user();
var legalcny, legalusdt, dealcny, dealusdt;
if (token) {
	$('.assets').hide();
	$('.assetlogin').show();
	//================资产信息==================
	var token1 = get_user_login();
	$.ajax({
			type: 'post',
			url: _API + 'wallet/list',
			dataType: 'json',
			beforeSend: function beforeSend(request) {
					request.setRequestHeader("Authorization", token1);
			},
			success: function (data) {
					console.log(data);
					cny = data.message.pb_price - 0 || 1;
					dealcny = (data.message.lever_wallet.totle - 0).toFixed(2);
					dealusdt = (dealcny * cny).toFixed(2);
					legalcny = (data.message.legal_wallet.totle - 0).toFixed(2);
					legalusdt = (legalcny * cny).toFixed(2);
					console.log(dealcny, legalcny)
					$('#dealcny').html(dealcny)
					$('#dealusdt').html(dealusdt)
					// $('#legalcny').html(legalcny)
					// $('#legalusdt').html(legalusdt)

			}
	});
} else {
	$('.assetlogin').hide();
	$('.assets').show();
	$('.assets>a').html('去登录').attr('href', 'login.html');
}
$("#imgs").click(function () {
	var el = $('#dealcny');
	var el1 = $('#dealusdt');
	var el2 = $('#legalcny');
	var cl = $('#legalusdt');
	if ($("#imgs").attr('src') == 'images/nosee.jpg') {
			$("#imgs").attr('src', 'images/eyes.png');
			el.html(dealcny);
			el1.html(dealusdt);
			el2.html(legalcny);
			cl.html(legalusdt);
	} else {
			$("#imgs").attr('src', 'images/nosee.jpg');

			el.html("*****");
			el1.html("*****");
			el2.html("*****");
			cl.html("*****");
	}
})
$('.onc').click(function () {
	layer_msg("暂未开放")
})

function compare(prop) {
	return function (obj1, obj2) {
			var val1 = obj1[prop] - 0;
			var val2 = obj2[prop] - 0;
			if (val1 < val2) {
					return 1;
			} else if (val1 > val2) {
					return -1;
			} else {
					return 0;
			}
	}
}
$(function () {
	intervalData(true);
	setInterval(function () {
			intervalData(false)
	}, 6000)

	function intervalData(isFisrt) {
			$.ajax({
					url: _API + 'currency/quotation',
					type: 'get',
					dataType: 'json',
					beforeSend: function beforeSend(request) {
							request.setRequestHeader("Authorization", token);
					},
					success: function (res) {
							if (res.type == 'ok') {
									var data = res.message;
									var html = ''
									var usdtData = data.find((item) => item.id == 4).quotation;
									console.log(usdtData, 234)
									for (var i in usdtData) {
											html +=
													`<li class="swiper-slide">
															<a href="dataMap.html?legal_id=${usdtData[i].legal_id}&currency_id=${usdtData[i].currency_id}&symbol=${usdtData[i].name}/PB">
															<small class="usdt">${usdtData[i].name}/PB</small>
															<h4 style="color:${usdtData[i].change>0?'#00bd80':'#E56C41'}">${(usdtData[i].now_price-0).toFixed(2) || '0.00'}<span>${usdtData[i].change || '0.00'}%</span></h4>
															<!-- <small class="cny">≈${(usdtData[i].now_price*cny).toFixed(2) || '0.00'}CNY</small> --> </a>
													</li>`
									}
									$('.trade_list').html(html)
									var swiper = new Swiper('.trade', {
											slidesPerView: 3,
											spaceBetween: 30,
											pagination: {
													el: '.swiper-pagination',
													clickable: true,
											},
									});
									var phHtml = ''
									var phData = [];
									for (var i = 0; i < data.length; i++) {
											var quotation = data[i].quotation;
											for (var j = 0; j < quotation.length; j++) {
													var item = quotation[j];
													item['legal_name'] = data[i].name;
													item['usdt_price'] = data[i].pb_price - 0;
													phData.push(item);

											}
									}
									data.find((item) => item.id == 4).quotation;
									phData = phData.filter((item) => item.change != null)
									phData = phData.sort(compare('change'))
									for (var i in phData) {
											var usdtprice = phData[i].usdt_price - 0;
											if (i >= 10) {
													break;
											}
											phHtml +=
													`
													<li><a href="dataMap.html?legal_id=${phData[i].legal_id}&currency_id=${phData[i].currency_id}&symbol=${phData[i].name}/${phData[i].legal_name}">
															<small>${i}</small>
															<b>${phData[i].name}/${phData[i].legal_name}<br /><span>24H量 ${((phData[i].volume || 0) - 0).toFixed(0)}</span></b>
															<b>${phData[i].now_price || '0.00'}<br /><span>￥${(Number(phData[i].now_price) * Number(usdtprice) * Number(cny)).toFixed(2)|| '0.00'}</span></b>
															<button class="btn btn-default" style="background:${(phData[i].change - 0) >= 0 ? '#02BF85' : '#E56C41'}">${phData[i].change ? phData[i].change : '+0.000'}%</button>
															</a>
													</li>
											`
									}
									$('#performer').html(phHtml)
							}
					}
			})

	}
})