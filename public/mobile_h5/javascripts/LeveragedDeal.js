	//===============币种======================
	var token = get_user_login();
	var res_msg, legal_name, currency_name, legal_id, currency_id, cny, ustd_price, share_num, transactionFee, spread, bond,cname,fiat_convert_cny;
	var setValue = 0;
	var buySet;
	var selectStatus = 1;
	var minShare = 1;
	var maxShare = 0;
	var vue = new Vue({
		el: '#app',
		data: {
			List: [],
			name: '',
			id: '',
			twoNum: '1',
			has_address_num: '',
			selectList: [],
			tabList: [],
			multList: [],


		},
		mounted: function () {
			let that = this;
			//法币、币种数据请求（左侧内容）
			initDataTokens({
				url: 'currency/quotation_new'
			}, that.currencyLeversuccess);
		},
		methods: {
			// 法币、币种数据请求成功函数
			currencyLeversuccess(res) {
				let that = this;
				if (res.type == 'ok') {
					var currency_items = '';
					var legal_items = '';
					res_msg = res.message;

					// 默认是第一个币种
					if (res_msg.length > 0) {
						that.selectList = res_msg;
						that.tabList = res_msg[0];
						let datas = res_msg[0];
						var index = 0;
						var leverData = getlocal_storage('lever');
						if (leverData) {
							currency_id = leverData.currency_id;
							legal_id = leverData.legal_id;
						}
                        var inx=0;
						$.each(res_msg, function (k, v) {
							if(legal_id&&legal_id==v.id){
								inx=k;
								currency_items += `
									<li class="currency_name side side2" data-id='${v.id}'>${v.name}</li>
								`;
							}else{
								inx=0;
								currency_items += `
									<li class="currency_name" data-id='${v.id}'>${v.name}</li>
								  `;
							}
				            if(k==inx){
								
							
							$.each(res_msg[k].quotation, function (m, n) {
								if (res_msg[k].quotation[m].currency_id == get_param('id2')) {
									index = m;
								} else if (currency_id == res_msg[k].quotation[m].currency_id) {
									index = m;
								}
								if (legal_id==n.legal_id) {
									if (n.is_display == 1) {
										if(currency_id==n.currency_id&&legal_id==n.legal_id){
											share_num = Number(n.lever_share_num).toFixed(2);//取当前币种的share_num  sjw
											legal_items += `
												<li class="legal_name bg_active" data-name="${n.currency_name}/${n.legal_name}" data-legal="${n.legal_id}" data-currency="${n.currency_id}" data-num="${n.lever_share_num}" data-spread="${n.spread}" data-fee="${n.lever_trade_fee}">
													<strong>${n.currency_name}</strong>
													<p class="${n.change >= 0 ? 'gre' : 'fontC'}">
														<span>${Number(n.now_price).toFixed(4) || '0.00'}</span>
														<span class="pull-right">${Number(n.change).toFixed(2) || '0.000'}%</span>
													</p>
												</li>
												`
										}else{
											legal_items += `
												<li class="legal_name" data-name="${n.currency_name}/${n.legal_name}" data-legal="${n.legal_id}" data-currency="${n.currency_id}" data-num="${n.lever_share_num}" data-spread="${n.spread}" data-fee="${n.lever_trade_fee}">
													<strong>${n.currency_name}</strong>
													<p class="${n.change >= 0 ? 'gre' : 'fontC'}">
														<span>${Number(n.now_price).toFixed(4) || '0.00'}</span>
														<span class="pull-right">${Number(n.change).toFixed(2) || '0.000'}%</span>
													</p>
												</li>
												`
										}
										
									}
								}else{
									if (n.is_display == 1) {
										legal_items += `
											<li class="legal_name" data-name="${n.currency_name}/${n.legal_name}" data-legal="${n.legal_id}" data-currency="${n.currency_id}" data-num="${n.lever_share_num}" data-spread="${n.spread}" data-fee="${n.lever_trade_fee}">
												<strong>${n.currency_name}</strong>
												<p class="${n.change >= 0 ? 'gre' : 'fontC'}">
													<span>${Number(n.now_price).toFixed(4) || '0.00'}</span>
													<span class="pull-right">${Number(n.change).toFixed(2) || '0.000'}%</span>
												</p>
											</li>
											`
									}	
								}
							})
						}

						});
						$('.tabs').html(currency_items);
						$('.ul').html(legal_items);
						//初始化法币、币种渲染
						var leverData = getlocal_storage('lever');
						if (get_param('id1')) {//傳值
							currency_id = get_param('id2');
							currency_name = get_param('name2');
							legal_id = get_param('id1');
							legal_name = get_param('name1');
							share_num = Number(res_msg[0].quotation[index].lever_share_num).toFixed(2);
							transactionFee = get_param('fee');
							spread = get_param('spread');
							console.log(transactionFee,spread)
							$('.share-num').text(share_num);
						} else {
							if (leverData) {//本地
								currency_id = leverData.currency_id;
								currency_name = leverData.currency_name;
								legal_id = leverData.legal_id;
								legal_name = leverData.legal_name;
								transactionFee = leverData.transactionFee;
								spread = leverData.spread;
								if (!leverData.share_num) {//sjw
									share_num = Number(res_msg[0].quotation[index].lever_share_num).toFixed(2);
								} else {
									// share_num = leverData.share_num;
									share_num = share_num;
								}
								$('.share-num').text(share_num);
							} else {
								console.log(datas)
								//默認
								currency_id = datas.quotation[0].currency_id;
								currency_name = datas.quotation[0].currency_name;
								legal_id = datas.quotation[0].legal_id;
								legal_name = datas.quotation[0].legal_name;
								share_num = Number(res_msg[0].quotation[0].lever_share_num).toFixed(2);
								spread = res_msg[0].quotation[index].spread;
								transactionFee = res_msg[0].quotation[index].lever_trade_fee;
								$('.share-num').text(share_num);
								$('.tabs li').eq(0).addClass('side side2');
								$('.ul li').eq(index).addClass('bg_active');
							}
						}

					
						
						$('.trade-name').text(currency_name + '/' + legal_name);
						$('.use_legal').text(currency_name);
						$('.use_currency').text(legal_name);
						$('.share-name').text(currency_name);
						//交易数据请求
						get_lever_data();
					}



				}
			},

		}
	});


	var set;
	scoket();
	upPrice();
	marsketLists();
	closeLever();

	function get_lever_data() {
		window.type = getlocal_storage('levertype') || 'buy';
		if (window.type == 'sell') {
			setTimeout(() => {
				$('.sell').trigger('click')
			}, 50);
			$('.num span:nth-child(2)').add('active');
		} else {
			$('.num span:nth-child(2)').add('actives');
			setTimeout(() => {
				$('.buy').trigger('click')
			}, 50);

		}
		// layer_loading();
		initDataTokens({
			url: 'lever/deal',
			data: {
				legal_id: legal_id,
				currency_id: currency_id
			},
			type: 'post'
		}, leverDealSuccess);
	}
	// 切換法幣
    $('#sideColumn>ol>li').click(function(){
		$(this).addClass('side').siblings().removeClass('side');
		var index = $(this).index();
		$('.ul').hide().eq(index).show();

	});	
	// 交易数据请求成功函数
	function leverDealSuccess(res) {
		let that = this;
		layer_close();
		$('.market-value').text('0.0000');
		$('.bond').text('0.0000');
		$('.transaction-fee').text('0.0000');
		var datas = res.message;
		if (res.type == 'ok') {
			$('.num span, .num input.share-input').remove();
			$('.use_legal_num').text(Number(datas.user_lever).toFixed(4) + legal_name);
			$('.use_currency_num').text(Number(datas.all_levers).toFixed(4));
			$('.last_price').text(datas.last_price);
			minShare = datas.lever_share_limit.min;
			maxShare = datas.lever_share_limit.max;
			//倍数赋值
			var mult = '';
			var multiples = datas.multiple.muit;
			that.multList = datas.multiple.muit;
			var shareHtml = `<input class="share-input" type="number" placeholder="${phand}" />`;
			var shareList = datas.multiple.share;


			for (var i in multiples) {
				mult += '<span data-id="' + multiples[i].value + '">' + multiples[i].value +times+ '</span>';
			};
			$('.multiple_sel').html(mult);
			window.type = getlocal_storage('levertype') || 'buy';
			if (window.type == 'sell') {
				$('.multiple_sel span:first-child').addClass('active');
			} else {
				$('.multiple_sel span:first-child').addClass('actives');
			}
			for (let i in shareList) {
				shareHtml += '<span data-id="' + shareList[i].value + '">' + shareList[i].value +hand+ '</span>'
			}
			$('.num').append(shareHtml);
			//当前委托
			var complete_list = '';

			$.each(datas.my_transaction, function (k, v) {
				complete_list += `
					<li class="clearfix" style="padding:5px 0;" data-id="${v.id}">
						<span class="width1 tc fl">${v.type == 1 ? buy: sell}</span>
						<span class="width2 tc fl">${v.time}</span>
						<span class="width2 tc fl">${Number(v.price).toFixed(4)}</span>
						<span class="width2 tc fl">${Number(v.number).toFixed(2)}</span>
						<a class='pingcang white tc width1 fl' data-id='${v.id}'>${pcang}</a>
					</li>
					`
			});
			$('.conplete_list').html(complete_list);
			if (datas.my_transaction.length == 0) {
				$('.no_record').css('display', 'block')
			} else {
				$('.no_record').css('display', 'none')
			}
			//买入卖出数据渲染
			//卖出数据
			var sell_list = '';
			var sellOut = datas.lever_transaction.out.reverse();
			var arr = [];
			for (i in sellOut) {
				arr.push(sellOut[i]);
			}
			var len = arr.length;
			$.each(sellOut, function (i, j) {
				let num = len - Number(i);
				sell_list += `
							<div class="flex alcenter around snum pb10 pd5">
								<div class="flex1 tl gray ft12">${num} </div>
								<div class="red flex2 tr ft12 mr5 vprice">${Number(j.price).toFixed(4)} </div>
								<div class="flex2 tr gray ft12">${Number(j.number).toFixed(2)} </div>
							</div>  
							`
			});
			$('.sell_out').html(sell_list);
			//买入数据
			var buy_list = '';
			var buyOut = datas.lever_transaction.in
			$.each(buyOut, function (i, j) {
				if (i < 5) {
					buy_list += `
					<div class="flex alcenter around snum pb10 pd5">
						<div class="flex1 tl gray ft12">${i + 1}</div>
						<div class="gre flex2 tr ft12 mr5 vprice">${Number(j.price).toFixed(4)}</div>
						<div class="flex2 tr gray ft12">${Number(j.number).toFixed(2)}</div>
					</div>  
					`
				}

			});
			$('.buyIn').html(buy_list);
			//最新价格
			cny = datas.ExRAte - 0 || 1;
			console.log(datas,cny)
			ustd_price = datas.ustd_price;
			var new_price = datas.last_price - 0;

			$('.new_price').text(Number(datas.last_price).toFixed(4));
			$('.cny_price').html((new_price * cny).toFixed(2))
			$('.control input').val($('.new_price').text())
			console.log($('.control input').val())

		} else {
			layer_msg(res.message)
		}
	}	
	//买入、卖出
	$('.buy_sell').click(function () {
		window.type = getlocal_storage('levertype') || 'buy';
		var textValue = /^[0-9]*$/;
		if ($('.share-input').val() == '') {
			layer_msg(pthands);
			return false;
		}
		if (selectStatus == 0) {
			if ($('.control input').val() == '') {
				layer_msg(ptprice);
				return false;
			}
		}
		// if (!textValue.test($('.share-input').val())) {//判断是否是正整数
		// 	layer_msg('请输入数字');
		// 	return false;
		// } else 
		// if ($('.share-input').val() > maxShare) {
		// 	layer_msg(pnomore + maxShare);
		// 	return false;
		// } else if ($('.share-input').val() < minShare) {
		// 	layer_msg(pnoless + minShare);
		// 	return false;
		// }
		if (window.type == 'buy') {
			if ($('.bg_active').data('legal') != '' && $('.bg_active').data('currency') != '') {
				var data = {
					share: $('.share-input').val(),
					multiple: $('.multiple_sel .actives').attr('data-id'),
					legal_id: $('.bg_active').data('legal'),
					currency_id: $('.bg_active').data('currency'),
					type: window.type == 'buy' ? 1 : 2,
					status: selectStatus,
					target_price: $('.control input').val(),
				}
			} else {
				layer_msg(pchange)
			}
		} else {
			if ($('.bg_active').data('legal') != '' && $('.bg_active').data('currency') != '') {
				var data = {
					share: $('.share-input').val(),
					multiple: $('.multiple_sel .active').attr('data-id'),
					legal_id: $('.bg_active').data('legal'),
					currency_id: $('.bg_active').data('currency'),
					type: window.type == 'buy' ? 1 : 2,
					status: selectStatus,
					target_price: $('.control input').val(),
				}
			} else {
				layer_msg(pchange)
			}
		}

		layer.open({
			type: 1,
			title: sureOd,
			style: 'width: 90%;  border:none;',
			content: '<ul class="comfirm-modal"><li><p class="name"></p></li><li><p>'+ttype+'</p><p class="type"></p></li><li><p>'+hand+'</p><p class="share"></p></li><li><p>'+times+'</p><p class="muit"></p></li><li><p>'+bond+'</p><p class="bondPrice"></p></li><li><p>'+fee+'</p><p class="tradeFree"></p></li><li><input type="password" class="pwd" placeholder="'+ppwd+'" /></li></ul>',
			btn: [sure, ceil],
			yes: function (index) {
				var password=$('.pwd').val();
				if(password.length<=0){
					return layer_msg(pzpwd)
				}else{
					data.password=password;
				}
				initDataTokens({
					url: 'lever/submit',
					type: 'post',
					data: data
				}, function (res) {
					layer_msg(res.message);
					if (res.type == 'ok') {
						clearInterval(t);
						if (selectStatus == 0) {
							location.href = 'leverList.html';
						} else {
							setTimeout(function () {
								$('.positions').addClass('active');
								$('.place').removeClass('active');
								$('#place').hide();
								$('#header ul').hide();
								$('.position').show();
								$('.stop-trade').show();
								$('.position-list').html('');
								$('.pwd').val('');
								positions();
							}, 500)
							var token = get_user_login();
						}
					}
				})


			},
			success: function () {
				$('.comfirm-modal .name').text($('.trade-name').text());
				if (data.type == 1) {
					$('.comfirm-modal .type').text(domore);
				} else {
					$('.comfirm-modal .type').text(doless);
				}
				$('.comfirm-modal .share').text(data.share);
				$('.comfirm-modal .muit').text(data.multiple);
				$('.comfirm-modal .bondPrice').text($('.bond').text());
				$('.comfirm-modal .tradeFree').text($('.transaction-fee').text());
			}
		})
	})
	//点击平仓操作
	$('body').on('click', '.pingcang', function () {
		var ids = $(this).data('id');
		layer.open({
			anim: 'up',
			content: sureping,
			btn: [sure, ceil],
			yes: function (index) {
				initDataTokens({
					url: 'lever/close',
					type: 'post',
					data: {
						id: ids
					}
				}, function (res) {
					layer_msg(res.message)
					window.location.reload();
				})
			}
		});
	})
	//点击法币切换
	$('body').on('click', '.currency_name', function () {
		var i = $(this).index();
		$(this).addClass('side side2').siblings().removeClass('side side2');
		var legal_items = '';
		$.each(res_msg, function (k, v) {
			if (i == k) {
				$.each(res_msg[i].quotation, function (m, n) {
					if (n.is_display == 1) {
						legal_items +=
							`
                        <li class="legal_name" data-name="${n.currency_name}/${n.legal_name}" data-legal="${n.legal_id}" data-currency="${n.currency_id}" data-num="${n.lever_share_num}"  data-spread="${n.spread}" data-fee="${n.lever_trade_fee}">
                            <strong>${n.currency_name}</strong>
                            <p class="${n.change >= 0 ? 'gre' : 'fontC'}">
                                <span>${n.now_price || '0.00'}</span>
                                <span class="${n.change.substring(0,1) == '+' ? 'gre pull-right' : 'fontC pull-right'}">${(n.change-0).toFixed(2) || '0.000'}%</span>
                            </p>
                        </li>
                        `
					}
				})
			}
		})
		$('.ul').html(legal_items);
	});
	//币种切换
	$('body').on('click', '.legal_name', function () {
		$(this).addClass('bg_active').siblings().removeClass('bg_active');
		legal_id = $(this).data('legal');
		legal_name = $('.side2').text();
		currency_id = $(this).data('currency');
		currency_name = $('.bg_active strong').text();
		share_name = Number($(this).data('num')).toFixed(2);
		transactionFee = $(this).data('fee');
		spread = $(this).data('spread');
		// $('.legal_title').text(currency_name);
		// $('.currency_title').text(legal_name);
		$('.trade-name').text(currency_name + '/' + legal_name);
		$('.use_legal').text(currency_name);
		$('.use_currency').text(legal_name);
		$('.share-num').text(share_name);
		$('.share-name').text(currency_name);
		close();
		setlocal_storage('lever', {
			"currency_name": currency_name,
			"currency_id": currency_id,
			"legal_name": legal_name,
			"legal_id": legal_id,
			"share_num": share_name,
			"transactionFee":transactionFee,
			"spread":spread //sjw
		});
		setlocal_storage('levertype', 'buy')
		//交易数据请求
		get_lever_data();
		$('.position-list').html('');
		positions();
	});

	//点击全部跳转
	$('.all').click(function () {
		location.href = 'Entrust.html?legal_id=' + $('.bg_active').data('legal') + '&currency_id=' + $('.bg_active').data(
			'currency') + '&legal_name=' + $('.side2').text() + '&currency_name=' + $('.bg_active strong').text();
	})
	//左侧导航关闭封装
	function close() {
		$('body').css('overflow', 'auto');
		$('#Limited').show();
		$('#mask1').hide();
		$('#sideColumn').animate({
			left: '-80%'
		}, 1000);
	}
	//socket连接封装
	function scoket() {
		$.ajax({
			url: _API + "user/info",
			type: "GET",
			dataType: "json",
			async: true,
			beforeSend: function beforeSend(request) {
				request.setRequestHeader("Authorization", token);
			},
			success: function success(data) {
				if (data.type == 'ok') {
					var socket = io(socket_api);
					socket.emit('login', data.message.id);
					// 后端推送来消息时
					socket.on('market_depth', function (msg) {
						var legalId = $('.bg_active').data('legal');
						var currencyId = $('.bg_active').data('currency');
						if (msg.type == 'market_depth') {
							if (legalId == msg.legal_id && currencyId == msg.currency_id) {
								var sell_list = '';
								var buy_list = '';
								var buyIn = msg.bids.slice(0,5);
                                var out = msg.asks.reverse().slice(0,5);
								$.each(out, function (k, v) {
									var num = (out.length - 0) - Number(k);
									sell_list +=
										`
                                    <div class="flex alcenter around snum pb10 pd5" onclick='getnum(${Number(v[0]).toFixed(4)})'>
                                        <div class="flex1 tl gray ft12">${num}</div>
                                        <div class="red flex2 tl vprice ft12" style="margin-right:5px;">${Number(v[0]).toFixed(4)}</div>
                                        <div class="flex2 tr gray ft12">${Number(v[1]).toFixed(2)}</div>
                                    </div>
                                    `
								});
								$.each(buyIn, function (k, v) {
									if (k < 5) {
										buy_list +=
											`
                                    <div class="flex alcenter around snum pb10 pd5" onclick='getnum(${Number(v[0]).toFixed(4)})'>
                                        <div class="flex1 tl gray ft12">${k + 1}</div>
                                        <div class="gre flex2 tl  vprice ft12" style="margin-right:5px;">${Number(v[0]).toFixed(4)}</div>
                                        <div class="flex2 tr gray ft12">${Number(v[1]).toFixed(2)}</div>
                                    </div>
                                    `
									}
								});
								$('.sell_out').html(sell_list);
								$('.buyIn').html(buy_list);
							}
						}

					})
				}
			}
		});
	}

	// 更新当前价
	function upPrice() {
		$.ajax({
			url: _API + "user/info",
			type: "GET",
			dataType: "json",
			async: true,
			beforeSend: function beforeSend(request) {
				request.setRequestHeader("Authorization", token);
			},
			success: function success(data) {
				console.log(data);
				if (data.type == 'ok') {
					var socket = io(socket_api);
					socket.emit('login', data.message.id);
					// 后端推送来消息时
					socket.on('kline', function (msg) {
						if (msg.type == 'kline') {
							var symbols = $('.trade-name').text();
							if (symbols == msg.symbol) {
								$('.new_price').text((msg.close - 0).toFixed(4));
								console.log(cny);
								$('.cny_price').html((msg.close * cny).toFixed(2))
							}
						}

					})
				}
			}
		});
	}
	// 持仓socket
	function positionSocket() {
		$.ajax({
			url: _API + "user/info",
			type: "GET",
			dataType: "json",
			async: true,
			beforeSend: function beforeSend(request) {
				request.setRequestHeader("Authorization", token);
			},
			success: function success(data) {
				//console.log(data);
				if (data.type == 'ok') {
					var socket = io(socket_api);
					socket.emit('login', data.message.id);
					// 后端推送来消息时d
					socket.on('lever_trade', function (msg) {
						//console.log(msg)
						if (msg.type == 'lever_trade') {
							if(legal_id == msg.legal_id){
								$('.fengxian').text(risk+' '+ (msg.hazard_rate - 0).toFixed(2) + '%');
								$('.total-pro').text((msg.profits_all - 0).toFixed(4));
								if (msg.profits_all > 0) {
									$('.total-pro').addClass('green').removeClass('red');
								} else {
									$('.total-pro').addClass('red').removeClass('green');
								}
							}
							
							//var datas = JSON.parse(msg.trades_all);
							var datas = msg.trades_all;
							var dataList = $('.position-list li').length;
							if (datas.length > 0) {
								for (let i in datas) {
									if (datas[i].currency == currency_id && datas[i].legal == legal_id) {
										for (var j = 0; j < dataList; j++) {
											if (datas[i].id == $('.position-list li').eq(j).attr('data-id')) {
												if (datas[i].profits < 0) {
													$('.position-list li').eq(j).find('.position-header p').text(Number(datas[i].profits).toFixed(4));
													$('.position-list li').eq(j).find('.position-header p').addClass('red').removeClass('green');
												} else {
													$('.position-list li').eq(j).find('.position-header p').text('+' + Number(datas[i].profits).toFixed(4));
													$('.position-list li').eq(j).find('.position-header p').addClass('green').removeClass('red');
												}
												$('.position-list li').eq(j).find('.precent-price').text(Number(datas[i].update_price).toFixed(4));
												$('.position-list li').eq(j).find('.profit_price').text(Number(datas[i].target_profit_price).toFixed(4));
												$('.position-list li').eq(j).find('.loss_price').text(Number(datas[i].stop_loss_price).toFixed(4));
											}
										}
									}
								}
								// if(datas.length == dataList) {
								// 	for(let i in datas) {
								// 		if (datas[i].currency == currency_id && datas[i].legal == legal_id) {
								// 			positionsList +=
								// 			`
								// 				<li class=" pl10 ptb15 pr10 mt10" data-id="${datas[i].id}">
								// 				<div class="clearfix position-header bdt">
								// 				<h3 class="fl">
								// 				<span>${datas[i].type == 1 ? buy : sell}</span>
								// 				<span>${tabName} x${datas[i].share}${hand}</span>
								// 				<span>(No.${datas[i].id})</span>
								// 				</h3>
								// 				<p class="fr mt5 ${datas[i].profits > 0 ? 'green' : 'red'}">${datas[i].profits > 0? "+" + Number(datas[i].profits).toFixed(4) : Number(datas[i].profits).toFixed(4)}</p>
								// 				</div>
								// 			<div class="content clearfix bdt mt10 pb10">
								// 				<div class="fl">
								// 					<p>${oprice}</p>
								// 					<p class="ftColor mt5">${Number(datas[i].price).toFixed(4)}</p>
								// 					<p class="mt10">${zyprice}</p>
								// 					<p class="ftColor mt5">${Number(datas[i].target_profit_price).toFixed(4)}</p>
								// 				</div>
								// 				<div class="fl tc">
								// 					<p>${tnowprice}</p>
								// 					<p class="ftColor mt5">${Number(datas[i].update_price).toFixed(4)}</p>
								// 					<p class="mt10">${zsprice}</p>
								// 					<p class="ftColor mt5">${Number(datas[i].stop_loss_price).toFixed(4)}</p>
								// 				</div>
								// 				<div class="fl tc">
								// 					<p>${bond}</p>
								// 					<p class="ftColor mt5">${Number(datas[i].caution_money).toFixed(4)}</p>
								// 					<p class="mt10">${optime}</p>
								// 					<p class="ftColor mt5">${datas[i].time}</p>
								// 				</div>
								// 				<div class="fl tr">
								// 					<p>${fee}</p>
								// 					<p class="ftColor mt5">${Number(datas[i].trade_fee).toFixed(4)}</p>
								// 					<p class="mt10">${gprice}</p>
								// 					<p class="ftColor mt5">${Number(datas[i].overnight_money).toFixed(4)}</p>
								// 				</div>
								// 			</div>
								// 			<div class="tr">
								// 				<button type="button" class="stop-price" data-id="${datas[i].id}" data-price="${Number(datas[i].target_profit_price).toFixed(4)}" data-prices="${Number(datas[i].stop_loss_price).toFixed(4)}" data-present="${Number(datas[i].price).toFixed(4)}" data-type="${datas[i].type}" data-num="${datas[i].share}">${setzhi}</button>
								// 				<button type="button" class="pingcang" data-id="${datas[i].id}">${pcang}</button>
								// 			</div>
								// 		</li>
								// 						`
								// 		}
										
								// 	}
								// 	$('.position-list').html(positionsList);
								// }
							}
						}

					})
				}
			}
		});
	}
	function marsketLists() {
		$.ajax({
			url: _API + "user/info",
			type: "GET",
			dataType: "json",
			async: true,
			beforeSend: function beforeSend(request) {
				request.setRequestHeader("Authorization", token);
			},
			success: function success(data) {
				if (data.type == 'ok') {
					var socket = io(socket_api);
						socket.emit('login', data.message.id);
						// 后端推送来消息时
						socket.on('daymarket', function(msg) {
							var list = $('.ul li').length;
							if (msg.type == 'daymarket') {
								for(let i = 0; i < list; i++){
									var legalId = $('.ul li').eq(i).data('legal');
									var currencyId = $('.ul li').eq(i).data('currency');
									if(legalId == msg.legal_id && currencyId == msg.currency_id){
										$('.ul li').eq(i).find('p span:first-child').text(Number(msg.now_price).toFixed(4))
										$('.ul li').eq(i).find('p span:last-child').text(Number(msg.change).toFixed(2) + '%');
										if(msg.change < 0){
											$('.ul li').eq(i).find('p').removeClass('gre').addClass('fontC')
										}else{
											$('.ul li').eq(i).find('p').removeClass('fontC').addClass('gre')
										}
									}
								}
							}
						});
				}
			}
		});
	}
	function closeLever() {
		$.ajax({
			url: _API + "user/info",
			type: "GET",
			dataType: "json",
			async: true,
			beforeSend: function beforeSend(request) {
				request.setRequestHeader("Authorization", token);
			},
			success: function success(data) {
				if (data.type == 'ok') {
					var socket = io(socket_api);
					socket.emit('login', data.message.id);
					// 后端推送来消息时
					socket.on('lever_closed', function (msg) {
						if (msg.type == 'lever_closed') {
							var arr = msg.id;
							var dataList = $('.position-list li').length;
							var dataList2 = $('.conplete_list  li').length;
								for(var i = 0; i < arr.length;i++){
									for(var j = 0; j < dataList;j++){
										if($('.position-list li').eq(j).attr('data-id') == arr[i]){
											$('.position-list li').eq(j).remove();
											dataList--;
										}
									}
								}
								for(var i = 0; i < arr.length;i++){
									for(var j = 0; j < dataList2;j++){
										if($('.conplete_list li').eq(j).attr('data-id') == arr[i]){
											$('.conplete_list li').eq(j).remove();
											dataList2--;
										}
									}
								}
							
						}
					});
				}
			}
		});
	}
	var t;
	//下单
	$('.place').click(function () {
		$(this).addClass('active');
		$('.positions').removeClass('active');
		$('#place').show();
		$('#header ul').show();
		$('.position').hide();
		$('.stop-trade').hide();
		clearInterval(t);
		window.location.reload();
	})
	//持仓
	var page = 1;

	$('.positions').click(function () {
		clearInterval(buySet);
		$(this).addClass('active');
		$('.place').removeClass('active');
		$('#place').hide();
		$('#header ul').hide();
		$('.position').show();
		$('.stop-trade').show();
		positions();
		var token = get_user_login();
	})


	function positions() {
		// $('.position-name').text($('.use_currency').text() + '：' + $('.use_currency_num').text())
		positionSocket();
		initDataTokens({
			url: 'lever/dealall',
			type: 'post',
			data: {
				legal_id: $('.bg_active').data('legal'),
				currency_id: $('.bg_active').data('currency'),
				page: 1,
				limit: 10
			}
		}, function (res) {
			if (res.type == 'ok') {
				var positionsList = '';
				var datas = res.message.order.data;
				$('.position-name').text(legal_name + '：' + Number(res.message.balance).toFixed(4));
				$('.fengxian').text(risk+' '+ res.message.hazard_rate + '%');
				$('.total-pro').text(Number(res.message.profits_total).toFixed(4));
				var tabName = $('.trade-name').text();
				if (res.message.profits_total > 0) {
					$('.total-pro').addClass('green').removeClass('red');
				} else {
					$('.total-pro').addClass('red').removeClass('green');
				}
				if (datas.length > 0) {
					for (i in datas) {
						positionsList +=
							`
                        <li class="bgColor pl10 ptb15 pr10 mt10" data-id="${datas[i].id}">
                        <div class="clearfix position-header bdt">
                        <h3 class="fl">
                        <span>${datas[i].type == 1 ? buy : sell}</span>
                        <span>${tabName} x${datas[i].share}${hand}</span>
                        <span>(No.${datas[i].id})</span>
                        </h3>
                        <p class="fr mt5 ${datas[i].profits > 0 ? 'green' : 'red'}">${datas[i].profits > 0? "+" + Number(datas[i].profits).toFixed(4) : Number(datas[i].profits).toFixed(4)}</p>
                        </div>
					<div class="content clearfix bdt mt10 pb10">
						<div class="fl">
							<p>${oprice}</p>
							<p class="ftColor mt5">${Number(datas[i].price).toFixed(4)}</p>
							<p class="mt10">${zyprice}</p>
							<p class="ftColor mt5">${Number(datas[i].target_profit_price).toFixed(4)}</p>
						</div>
						<div class="fl tc">
							<p>${tnowprice}</p>
							<p class="ftColor mt5 precent-price">${Number(datas[i].update_price).toFixed(4)}</p>
							<p class="mt10">${zsprice}</p>
							<p class="ftColor mt5">${Number(datas[i].stop_loss_price).toFixed(4)}</p>
						</div>
						<div class="fl tc">
							<p>${bond}</p>
							<p class="ftColor mt5">${Number(datas[i].caution_money).toFixed(4)}</p>
							<!--<p class="mt10">${pgtime}</p>-->
							
							<p class="mt10">${optime}</p>
							<p class="ftColor mt5">${datas[i].time}</p>
						</div>
						<div class="fl tr">
							<p>${fee}</p>
							<p class="ftColor mt5">${Number(datas[i].trade_fee).toFixed(4)}</p>
							<p class="mt10">${gprice}</p>
							<p class="ftColor mt5">${Number(datas[i].overnight_money).toFixed(4)}</p>
						</div>
					</div>
                    <div class="tr">
                        <button type="button" class="stop-price" data-id="${datas[i].id}" data-price="${Number(datas[i].target_profit_price).toFixed(4)}" data-prices="${Number(datas[i].stop_loss_price).toFixed(4)}" data-present="${Number(datas[i].price).toFixed(4)}" data-type="${datas[i].type}" data-num="${datas[i].share}">${setzhi}</button>
						<button type="button" class="pingcang" data-id="${datas[i].id}">${pcang}</button>
					</div>
				</li>
                    `
					}
					$('.position-list').html(positionsList);
				} else {
					$('.more').text('');
					$('.no_records').show();
					$('.position-list').html('');
				}
			}
		})
	}

	function calculation(bond, type, share, muit) {
		layer.open({
			type: 2,
			content: counting,
			shadeClose: false
		})
		var prices = parseFloat(bond * spread / 100).toFixed(4);
		var pricesTotal = 0;
		if (type == 'sell') {
			pricesTotal = parseFloat(bond - prices).toFixed(4);
		} else {
			pricesTotal = parseFloat(bond + prices).toFixed(4);
		}
		var shareNum = parseFloat($('.share-num').text()).toFixed(4);
		var totalPrice = parseFloat(pricesTotal * share * shareNum).toFixed(4);
		var bonds = parseFloat((totalPrice - 0) / (muit - 0)).toFixed(4);
		var tradeFree = parseFloat(totalPrice * transactionFee / 100).toFixed(4);
		var marketPrice = parseFloat(bond * share * shareNum).toFixed(4);
		if (marketPrice == 'NaN') {
			$('.market-value').text('≈ 0.0000' + legal_name);
		} else {
			$('.market-value').text('≈ ' + marketPrice + " " + legal_name);
		}

		if (bonds == "NaN") {
			$('.bond').text('≈ 0.0000' + legal_name);
		} else {
			$('.bond').text('≈ ' + bonds + " " + legal_name);
		}
		if (tradeFree == "NaN") {
			$('.transaction-fee').text('≈ 0.0000' + legal_name);
		} else {
			$('.transaction-fee').text('≈ ' + tradeFree + " " + legal_name);
		}
		setTimeout(function () {
			layer_close();
		}, 500)
	}
	//选择倍数
	$('body').on('click', '.multiple_sel span', function () {
		window.type = getlocal_storage('levertype') || 'buy';
		var muitNums = $(this).attr('data-id');
		if (window.type == 'sell') {
			$('.multiple_sel span').removeClass('actives');
			$('.multiple_sel span').removeClass('active');
			$(this).addClass('active');

		} else {
			$('.multiple_sel span').removeClass('actives');
			$('.multiple_sel span').removeClass('active');
			$(this).addClass('actives');
		}
		if (selectStatus == 0) {
			if ($('.control input').val() != '') {
				if ($('.share-input').val() != '') {
					var bond = parseFloat($('.control input').val()).toFixed(4);
					var share = parseFloat($('.share-input').val()).toFixed(4);
					var muitNum = parseFloat(muitNums).toFixed(4);
					calculation(bond, window.type, share, muitNum);
				}
			}
		} else {
			if ($('.share-input').val() != '') {
				var bond = parseFloat($('.new_price').text()).toFixed(4);
				var share = parseFloat($('.share-input').val()).toFixed(4);
				var muitNum = parseFloat(muitNums).toFixed(4);
				calculation(bond, window.type, share, muitNum);
			}
		}

	});
	// 选择手数
	$('body').on('click', '.num span', function () {
		window.type = getlocal_storage('levertype') || 'buy';
		var shares = $(this).attr('data-id');
		$('.share-input').val(shares);
		if (selectStatus == 0) {
			if ($('.control input').val() != '') {
				if ($('.share-input').val() != '') {
					var bond = parseFloat($('.control input').val()).toFixed(4);
					var share = parseFloat(shares).toFixed(4);
					var muitNum = 0;
					if (window.type == 'sell') {
						muitNum = parseFloat($('.multiple_sel .active').attr('data-id')).toFixed(4);
					} else {
						muitNum = parseFloat($('.multiple_sel .actives').attr('data-id'))
					}
					calculation(bond, window.type, share, muitNum);
				}
			}
		} else {
			if ($('.share-input').val() != '') {
				var bond = parseFloat($('.new_price').text()).toFixed(4);
				var share = parseFloat(shares).toFixed(4);
				var muitNum = 0;
				if (window.type == 'sell') {
					muitNum = parseFloat($('.multiple_sel .active').attr('data-id')).toFixed(4);
				} else {
					muitNum = parseFloat($('.multiple_sel .actives').attr('data-id'))
				}
				calculation(bond, window.type, share, muitNum);
			}
		}
		if (window.type == 'sell') {
			$('.num span').removeClass('active');
			$('.num span').removeClass('actives');
			$(this).addClass('active');
		} else {
			$('.num span').removeClass('active');
			$('.num span').removeClass('actives');
			$(this).addClass('actives');
		}

	})
	// 输入手数
	// 选择手数
	$('body').on('input', '.share-input', function () {
		window.type = getlocal_storage('levertype') || 'buy';
		var shares = $(this).val();
		$('.num span').removeClass('active actives');
		var textValue = /^[0-9]*$/;
		$('.market-value').text('≈ 0.0000' + legal_name);
		$('.bond').text('≈ 0.0000' + legal_name);
		$('.transaction-fee').text('≈ 0.0000' + legal_name);
		// if (!textValue.test(shares)) {//判断是否是正整数
		// 	layer_msg('请输入数字');
		// 	return false;
		// } else 
		// if (shares < minShare) {
		// 	layer_msg(pnoless + minShare);
		// 	return false;
		// } else {
		// 	if (maxShare > 0) {
		// 		if (shares > maxShare) {
		// 			layer_msg(pnomore + maxShare);
		// 			return false;
		// 		}
		// 	}
		// }
		if (selectStatus == 0) {
			if ($('.control input').val() != '') {
				if ($('.share-input').val() != '') {
					var bond = parseFloat($('.control input').val()).toFixed(4);
					var share = parseFloat(shares).toFixed(4);
					var muitNum = 0;
					if (window.type == 'sell') {
						muitNum = parseFloat($('.multiple_sel .active').attr('data-id')).toFixed(4);
					} else {
						muitNum = parseFloat($('.multiple_sel .actives').attr('data-id'))
					}
					calculation(bond, window.type, share, muitNum);
				} else {
					$('.market-value').text('≈ 0.0000' + legal_name);
					$('.bond').text('≈ 0.0000' + legal_name);
					$('.transaction-fee').text('≈ 0.0000' + legal_name);
				}
			} else {
				$('.market-value').text('≈ 0.0000' + legal_name);
				$('.bond').text('≈ 0.0000' + legal_name);
				$('.transaction-fee').text('≈ 0.0000' + legal_name);
			}
		} else {
			if ($('.share-input').val() != '') {
				var bond = parseFloat($('.new_price').text()).toFixed(4);
				var share = parseFloat(shares).toFixed(4);
				var muitNum = 0;
				if (window.type == 'sell') {
					muitNum = parseFloat($('.multiple_sel .active').attr('data-id')).toFixed(4);
				} else {
					muitNum = parseFloat($('.multiple_sel .actives').attr('data-id'))
				}
				calculation(bond, window.type, share, muitNum);
			} else {
				$('.market-value').text('≈ 0.0000' + legal_name);
				$('.bond').text('≈ 0.0000' + legal_name);
				$('.transaction-fee').text('≈ 0.0000' + legal_name);
			}
		}
	});

	// 输入价格
	$('body').on('input', '.control input', function () {
		price_have();
		// window.type = getlocal_storage('levertype') || 'buy';
		// // $('.num span').removeClass('active actives');
		// if (selectStatus == 0) {
		// 	if ($('.control input').val() != '') {
		// 		if ($('.share-input').val() != '') {
		// 			var bond = parseFloat($('.control input').val()).toFixed(4);
		// 			var share = parseFloat($('.share-input').val()).toFixed(4);
		// 			var muitNum = 0;
		// 			if (window.type == 'sell') {
		// 				muitNum = parseFloat($('.multiple_sel .active').attr('data-id')).toFixed(4);
		// 			} else {
		// 				muitNum = parseFloat($('.multiple_sel .actives').attr('data-id'))
		// 			}
		// 			calculation(bond, window.type, share, muitNum);
		// 		} else {
		// 			$('.market-value').text('≈ 0.0000' + legal_name);
		// 			$('.bond').text('≈ 0.0000' + legal_name);
		// 			$('.transaction-fee').text('≈ 0.0000' + legal_name);
		// 		}
		// 	} else {
		// 		$('.market-value').text('≈ 0.0000' + legal_name);
		// 		$('.bond').text('≈ 0.0000' + legal_name);
		// 		$('.transaction-fee').text('≈ 0.0000' + legal_name);
		// 	}
		// } else {
		// 	if ($('.share-input').val() != '') {
		// 		var bond = parseFloat($('.new_price').text()).toFixed(4);
		// 		var share = parseFloat($('.share-input').val()).toFixed(4);
		// 		var muitNum = 0;
		// 		if (window.type == 'sell') {
		// 			muitNum = parseFloat($('.multiple_sel .active').attr('data-id')).toFixed(4);
		// 		} else {
		// 			muitNum = parseFloat($('.multiple_sel .actives').attr('data-id'))
		// 		}
		// 		calculation(bond, window.type, share, muitNum);
		// 	} else {
		// 		$('.market-value').text('≈ 0.0000' + legal_name);
		// 		$('.bond').text('≈ 0.0000' + legal_name);
		// 		$('.transaction-fee').text('≈ 0.0000' + legal_name);
		// 	}
		// }
	});
	
	function price_have(){
		window.type = getlocal_storage('levertype') || 'buy';
		// $('.num span').removeClass('active actives');
		if (selectStatus == 0) {
			if ($('.control input').val() != '') {
				if ($('.share-input').val() != '') {
					var bond = parseFloat($('.control input').val()).toFixed(4);
					var share = parseFloat($('.share-input').val()).toFixed(4);
					var muitNum = 0;
					if (window.type == 'sell') {
						muitNum = parseFloat($('.multiple_sel .active').attr('data-id')).toFixed(4);
					} else {
						muitNum = parseFloat($('.multiple_sel .actives').attr('data-id'))
					}
					calculation(bond, window.type, share, muitNum);
				} else {
					$('.market-value').text('≈ 0.0000' + legal_name);
					$('.bond').text('≈ 0.0000' + legal_name);
					$('.transaction-fee').text('≈ 0.0000' + legal_name);
				}
			} else {
				$('.market-value').text('≈ 0.0000' + legal_name);
				$('.bond').text('≈ 0.0000' + legal_name);
				$('.transaction-fee').text('≈ 0.0000' + legal_name);
			}
		} else {
			if ($('.share-input').val() != '') {
				var bond = parseFloat($('.new_price').text()).toFixed(4);
				var share = parseFloat($('.share-input').val()).toFixed(4);
				var muitNum = 0;
				if (window.type == 'sell') {
					muitNum = parseFloat($('.multiple_sel .active').attr('data-id')).toFixed(4);
				} else {
					muitNum = parseFloat($('.multiple_sel .actives').attr('data-id'))
				}
				calculation(bond, window.type, share, muitNum);
			} else {
				$('.market-value').text('≈ 0.0000' + legal_name);
				$('.bond').text('≈ 0.0000' + legal_name);
				$('.transaction-fee').text('≈ 0.0000' + legal_name);
			}
		}
	}
	// 点击自动填充价格
	$('body').on('click','.snum',function(){
		var nprice=$(this).find('.vprice').html();
		$('.control input').val(nprice);
		price_have();
	})
	function getnum(num){
		// var num= $(this).find('.vprice').html();
		console.log(num)
		$('.control input').val(num);
		price_have();
	}
	// 加载更多
	$('.more').click(function () {
		page++;
		initDataTokens({
			url: 'lever/dealall',
			type: 'post',
			data: {
				legal_id: $('.bg_active').data('legal'),
				currency_id: $('.bg_active').data('currency'),
				page: page,
				limit: 10
			}
		}, function (res) {
			if (res.type == 'ok') {
				var positionsList = '';
				var datas = res.message.order.data;
				$('.position-name').text(legal_name + '：' + Number(res.message.balance).toFixed(4));
				$('.fengxian').text(risk+' ' + res.message.hazard_rate + '%');
				$('.total-pro').text(Number(res.message.profits_total).toFixed(4));
				var tabName = $('.trade-name').text();
				if (res.message.profits_total > 0) {
					$('.total-pro').addClass('green').removeClass('red');
				} else {
					$('.total-pro').addClass('red').removeClass('green');
				}
				if (datas.length > 0) {
					for (let i in datas) {
						positionsList +=
							`
                        <li class="bgColor pl10 ptb15 pr10 mt10" data-id="${datas[i].id}">
                        <div class="clearfix position-header bdt">
                        <h3 class="fl">
                        <span>${datas[i].type == 1 ? buy : sell}</span>
                        <span>${tabName} x${datas[i].share}${hand}</span>
                        <span>(No.${datas[i].id})</span>
                        </h3>
                        <p class="fr mt5 ${datas[i].profits > 0 ? 'green' : 'red'}">${datas[i].profits > 0? "+" + Number(datas[i].profits).toFixed(4) : Number(datas[i].profits).toFixed(4)}</p>
                        </div>
					<div class="content clearfix bdt mt10 pb10">
						<div class="fl">
							<p>${oprice}</p>
							<p class="ftColor mt5">${Number(datas[i].price).toFixed(4)}</p>
							<p class="mt10">${zyprice}</p>
							<p class="ftColor mt5">${Number(datas[i].target_profit_price).toFixed(4)}</p>
						</div>
						<div class="fl tc">
							<p>${tnowprice}</p>
							<p class="ftColor mt5 precent-price">${Number(datas[i].update_price).toFixed(4)}</p>
							<p class="mt10">${zsprice}</p>
							<p class="ftColor mt5">${Number(datas[i].stop_loss_price).toFixed(4)}</p>
						</div>
						<div class="fl tr">
							<p>${bond}</p>
							<p class="ftColor mt5">${Number(datas[i].caution_money).toFixed(4)}</p>
							<!--<p class="mt10">${pgtime}</p>-->
							
							<p class="mt10">${optime}</p>
							<p class="ftColor mt5">${datas[i].time}</p>
						</div>
					</div>
                    <div class="tr">
                        <button class="stop-price" data-id="${datas[i].id}" data-price="${Number(datas[i].target_profit_price).toFixed(4)}" data-prices="${Number(datas[i].stop_loss_price).toFixed(4)}" data-present="${Number(datas[i].price).toFixed(4)}" data-type="${datas[i].type}" data-num="${datas[i].share}">${setloss}</button>
						<button type="button" class="pingcang" data-id="${datas[i].id}">${pcang}</button>
					</div>
				</li>
                    `
					}
					$('.position-list').append(positionsList);
				} else {
					$('.more').text(nomore);
					$('.more').attr('disabled', 'disabled');
				}
			}
		})
	})
	// 止盈止损
	$('body').on('click', '.stop-price', function () {
		var indexs = $('.stop-price').index(this);
		// 止盈价
		var prices = Number($('.position-list li').eq(indexs).find('.content div:nth-child(1) p:last-child').text()).toFixed(2);
		// 止损价
		var reducePrice = Number($('.position-list li').eq(indexs).find('.content div:nth-child(2) p:last-child').text()).toFixed(2);
		// 当前价
		var present = Number($('.position-list li').eq(indexs).find('.content div:nth-child(2) p:nth-child(2)').text()).toFixed(2);
		// 开仓价
		var openPrice = Number($('.position-list li').eq(indexs).find('.content div:nth-child(1) p:nth-child(2)').text()).toFixed(2);
		// 买入盈利
		var pricesAdd = Number(Number(prices) - Number(openPrice)).toFixed(2);
		// 买入亏损
		var pricesReduce = Number(Number(openPrice) - Number(reducePrice)).toFixed(2);
		// 卖出盈利
		var sellPrice = Number(Number(openPrice) - Number(prices)).toFixed(2);
		// 卖出亏损
		var sellLoss = Number(Number(reducePrice) - Number(openPrice)).toFixed(2);
		var ids = $(this).data('id');
		var type = $(this).data('type');
		// 手数
		var share = Number($(this).data('num')).toFixed(2);
		if (type == 1) {
			layer.open({
				type: 1,
				title: setzhi,
				style: 'width: 80%;  border:none;',
				content: '<div class="modals"><div style="width:90%;margin:0 auto;"><span style="display:inline-block;line-height:30px;position:relative;top:-10px;">止盈价格：</span><p style="height:30px;line-height:30px;border:1px solid #e5e5e5;border-radius:2px;width:70%;display:inline-block;" class="clearfix"> <span class="reduce fl" style="border-right:1px solid #e5e5e5;font-size:40px;display:inline-block;width:20%;text-align:center;"><i style="font-weight:normal;position:relative;top:-3px;">-</i></span><input style="width:58%;line-height:28px;text-align:center;color:#333;" class="prices fl" type="text" /><span class="add fr" style="border-left:1px solid #e5e5e5;font-size:40px;display:inline-block;width:20%;text-align:center;">+</span></p></div><p class="yl" style="width:100%;text-align:center;margin:10px 0;">预期盈利：<span style="color:#5786D2;"></span></p><div style="width:90%;margin:0 auto;"><span style="display:inline-block;line-height:30px;position:relative;top:-10px;">止损价格：</span><p class="clearfix" style="height:30px;line-height:30px;border:1px solid #e5e5e5;border-radius:2px;width:70%;display:inline-block;"><span class="reduce fl" style="font-size:40px;display:inline-block;width:20%;text-align:center;border-right:1px solid #e5e5e5;"><i style="font-weight:normal;position:relative;top:-3px;">-</i></span><input style="width:58%;line-height:28px;text-align:center;color:#333;" class="reduce-price fl" type="text" /><span class="add fr" style="font-size:40px;display:inline-block;width:20%;text-align:center;border-left:1px solid #e5e5e5;">+</span></p></div><p class="ks" style="width:100%;text-align:center;margin:10px 0;">'+expectLoss+'：<span style="color:#5786D2;"></span></p></div>',
				btn: [sure, ceil],
				yes: function (index) {
					var value1 = $('.prices').val();
					var value2 = $('.reduce-price').val();
					initDataTokens({
						url: 'lever/setstop',
						type: 'post',
						data: {
							id: ids,
							target_profit_price: value1,
							stop_loss_price: value2,
						}
					}, function (res) {
						layer_msg(res.message);
						if (res.type == 'ok') {
							$('.position-list li').eq(indexs).find('.content div:nth-child(1) p:nth-child(4)').text(Number(value1).toFixed(4));
							$('.position-list li').eq(indexs).find('.content div:nth-child(2) p:nth-child(4)').text(Number(value2).toFixed(4));
						}
					})
				},
				success: function () {
					if (prices > 0) {
						$('.prices').val(prices);
						$('.yl span').text(Number((pricesAdd - 0) * (share - 0)).toFixed(2));
					} else {
						$('.prices').val(present);
						$('.yl span').text('0.00');
					}
					if (reducePrice > 0) {
						$('.reduce-price').val(reducePrice);
						$('.ks span').text(Number((pricesReduce - 0) * (share - 0)).toFixed(2));
					} else {
						$('.reduce-price').val(present);
						$('.ks span').text('0.00');
					}

					$('.add').click(function () {
						var value = Number(Number($(this).parent().find('input').val()) + 0.01).toFixed(2);
						$(this).parent().find('input').val(value);
						if ($(this).parent().find('input').hasClass('prices')) {
							var ylValue = Number(value - openPrice).toFixed(2);
							ylValue = (ylValue - 0) * (share - 0);
							$('.yl span').text(Number(ylValue).toFixed(2))
						} else {
							var zsValue = Number(openPrice - value).toFixed(2);
							zsValue = (zsValue - 0) * (share - 0);
							$('.ks span').text(Number(zsValue).toFixed(2));
						}

					})
					$('.reduce').click(function () {
						var value = Number(Number($(this).parent().find('input').val()) - 0.01).toFixed(2);
						$(this).parent().find('input').val(value);
						if ($(this).parent().find('input').hasClass('prices')) {
							var ylValue = Number(value - openPrice).toFixed(2);
							ylValue = (ylValue - 0) * (share - 0)
							$('.yl span').text(Number(ylValue).toFixed(2))
						} else {
							var zsValue = Number(openPrice - value).toFixed(2);
							zsValue = (zsValue - 0) * (share - 0)
							$('.ks span').text(Number(zsValue).toFixed(2));
						}
					})
					$('input').on('input', function () {
						if ($(this).hasClass('prices')) {
							if ($(this).val() != '') {
								var pricesAdd = Number(Number($(this).val()) - openPrice).toFixed(2);
								pricesAdd = (pricesAdd - 0) * (share - 0);
								if (pricesAdd < 0) {
									$('.yl span').text('0.00')
								} else {
									$('.yl span').text(Number(pricesAdd).toFixed(2))
								}
							} else {
								$('.yl span').text('0.00')
							}
						} else {
							if ($(this).val() != '') {
								var zsValue = Number(openPrice - Number($(this).val())).toFixed(2);
								zsValue = (zsValue - 0) * (share - 0)
								if (zsValue <= 0) {
									$('.ks span').text('0.00');
								} else {
									$('.ks span').text(Number(zsValue).toFixed(2));
								}
							} else {
								$('.ks span').text('0.00');
							}
						}
					})
				}
			});
		} else {
			// 卖出
			layer.open({
				type: 1,
				title: setzhi,
				style: 'width: 80%;  border:none;',
				content: '<div class="modals"><div style="width:90%;margin:0 auto;"><span style="display:inline-block;line-height:30px;position:relative;top:-10px;">止盈价格：</span><p style="height:30px;line-height:30px;border:1px solid #e5e5e5;border-radius:2px;width:70%;display:inline-block;" class="clearfix"> <span class="reduce fl" style="border-right:1px solid #e5e5e5;font-size:40px;display:inline-block;width:20%;text-align:center;"><i style="font-weight:normal;position:relative;top:-3px;">-</i></span><input style="width:58%;line-height:28px;text-align:center;color:#333;" class="prices fl" type="text" /><span class="add fr" style="border-left:1px solid #e5e5e5;font-size:40px;display:inline-block;width:20%;text-align:center;">+</span></p></div><p class="yl" style="width:100%;text-align:center;margin:10px 0;">预期盈利：<span style="color:#5786D2;"></span></p><div style="width:90%;margin:0 auto;"><span style="display:inline-block;line-height:30px;position:relative;top:-10px;">止损价格：</span><p class="clearfix" style="height:30px;line-height:30px;border:1px solid #e5e5e5;border-radius:2px;width:70%;display:inline-block;"><span class="reduce fl" style="font-size:40px;display:inline-block;width:20%;text-align:center;border-right:1px solid #e5e5e5;"><i style="font-weight:normal;position:relative;top:-3px;">-</i></span><input style="width:58%;line-height:28px;text-align:center;color:#333;" class="reduce-price fl" type="text" /><span class="add fr" style="font-size:40px;display:inline-block;width:20%;text-align:center;border-left:1px solid #e5e5e5;">+</span></p></div><p class="ks" style="width:100%;text-align:center;margin:10px 0;">预期亏损：<span style="color:#5786D2;"></span></p></div>',
				btn: [sure, ceil],
				yes: function (index) {
					var value1 = $('.prices').val();
					var value2 = $('.reduce-price').val();
					initDataTokens({
						url: 'lever/setstop',
						type: 'post',
						data: {
							id: ids,
							target_profit_price: value1,
							stop_loss_price: value2,
						}
					}, function (res) {
						layer_msg(res.message);
						if (res.type == 'ok') {
							$('.position-list li').eq(indexs).find('.content div:nth-child(1) p:nth-child(4)').text(Number(value1).toFixed(4));
							$('.position-list li').eq(indexs).find('.content div:nth-child(2) p:nth-child(4)').text(Number(value2).toFixed(4));
						}
					})
					// if (value2 > present || value2 < reducePrice) {
					// 	layer_msg('止损价设置错误，设置范围' + reducePrice + '~' + present);
					// } else {

					// }
				},
				success: function () {
					if (reducePrice > 0) {
						$('.reduce-price').val(reducePrice);
						$('.yl span').text(Number((sellPrice - 0) * (share - 0)).toFixed(2));
					} else {
						$('.prices').val(present);
						$('.yl span').text('0.00');
					}
					if (prices > 0) {
						$('.prices').val(prices);
						$('.ks span').text(Number((sellLoss - 0) * (share - 0)).toFixed(2));
					} else {
						$('.reduce-price').val(present);
						$('.ks span').text('0.00');
					}


					$('.add').click(function () {
						var value = Number(Number($(this).parent().find('input').val()) + 0.01).toFixed(2);
						$(this).parent().find('input').val(value);
						if ($(this).parent().find('input').hasClass('prices')) {
							var ylValue = Number(openPrice - value).toFixed(2);
							ylValue = (ylValue - 0) * (share - 0);
							$('.yl span').text(Number(ylValue).toFixed(2))
						} else {
							var zsValue = Number(value - openPrice).toFixed(2);
							zsValue = (zsValue - 0) * (share - 0);
							$('.ks span').text(Number(zsValue).toFixed(2));
						}

					})
					$('.reduce').click(function () {
						var value = Number(Number($(this).parent().find('input').val()) - 0.01).toFixed(2);
						$(this).parent().find('input').val(value);
						if ($(this).parent().find('input').hasClass('prices')) {
							var ylValue = Number(openPrice - value).toFixed(2);
							ylValue = (ylValue - 0) * (share - 0);
							$('.yl span').text(Number(ylValue).toFixed(2))
						} else {
							var zsValue = Number(value - openPrice).toFixed(2);
							zsValue = (zsValue - 0) * (share - 0);
							$('.ks span').text(Number(zsValue).toFixed(2));
						}
					})
					$('input').on('input', function () {
						if ($(this).hasClass('prices')) {
							if ($(this).val() != '') {
								var pricesAdd = Number(openPrice - Number($(this).val())).toFixed(2);
								pricesAdd = (pricesAdd - 0) * (share - 0);
								if (pricesAdd < 0) {
									$('.yl span').text('0.00')
								} else {
									$('.yl span').text(Number(pricesAdd).toFixed(2))
								}
							} else {
								$('.yl span').text('0.00')
							}

						} else {
							if ($(this).val() != '') {
								var zsValue = Number(Number($(this).val()) - openPrice).toFixed(2);
								zsValue = (zsValue - 0) * (share - 0);
								if (zsValue <= 0) {
									$('.ks span').text('0.00');
								} else {
									$('.ks span').text(Number(zsValue).toFixed(2));
								}
							} else {
								$('.ks span').text('0.00');
							}
						}
					})
				}
			});
		}

	})

	// 跳转k线页面
	$('.lever-detail').click(function () {
		window.location.href = 'dataMap.html?legal_id=' + $('.bg_active').data('legal') + '&currency_id=' + $('.bg_active').data(
			'currency') + '&symbol=' + $('.trade-name').text() + '&spread=' + $('.bg_active').data('spread') + '&fee='+ $('.bg_active').data('fee')+ '&cny='+ cny;
	})
	// 跳转交易记录页面
	$('.recordList').click(function () {
		window.location.href = 'leverList.html?legal_id=' + $('.bg_active').data('legal') + '&currency_id=' + $('.bg_active').data(
			'currency') + '&symbol=' + $('.trade-name').text();
	})

	// 一键平仓
	$('body').on('click', '.total-btn', function () {
		var indexs = 0;
		layer.open({
			type: 1,
			title: sureClose,
			style: 'width: 90%;  border:none;',
			content: '<div class="stop-modal"><span class="active">'+allClose+'</span><span>'+moreClose+'</span><span>'+nullClose+'</span></div>',
			btn: [sure, ceil],
			yes: function (index) {
				initDataTokens({
					url: 'lever/batch_close',
					type: 'post',
					data: {
						type: indexs
					}
				}, function (res) {
					layer_msg(res.message);
					if (res.type == 'ok') {
						setTimeout(function () {
							clearInterval(t);
							$('.position-list').html('');
							positions();
						}, 500)
						
					}
				})

			},
			success: function () {
				$('.stop-modal span').click(function () {
					indexs = $('.stop-modal span').index(this);
					$(this).addClass('active').siblings().removeClass('active');
				})
			}
		})
	})

	// 选择交易类型
	$('.select-price span').click(function () {
		$(this).addClass('active').siblings().removeClass('active');
		selectStatus = $(this).attr('data-id');
		$('.market-value').text('≈ 0.0000' + legal_name);
		$('.bond').text('≈ 0.0000' + legal_name);
		$('.transaction-fee').text('≈ 0.0000' + legal_name);
		// $('.control input').val('');
		$('.control input').val($('.new_price').text())
		$('.share-input').val('');
		$('.num span').removeClass('active actives');
		if (selectStatus == 0) {
			$('.control1').addClass('hide');
			$('.control').removeClass('hide');
			// $('.lever-total').removeClass('hide')

		} else {
			$('.control').addClass('hide');
			$('.control1').removeClass('hide');
			// $('.lever-total').addClass('hide')
		}
	})