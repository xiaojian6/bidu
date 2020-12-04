$(function () {
	var address = $("#user_info").data("address")
	if (address != "") {
		layer_loading()
		$.ajax({
			type: "post",
			url: laravel_api + "/quotation",
			data: {
				address: address
			},
			dataType: "json",
			success: function (data) {
				layer_close()
				if (data.type == "ok") {
					console.log(data)
					render_list(data.message.coin_list);
				} else {
					layer_msg(data.message)
					return false;
				}
			}
		});
	}

	    function render_list(quotes) {
			var html = "";
			
			console.log(quotes)
	        for (i in quotes) {
				// console.log(quotes[i].id );
	            var str = (quotes[i].quotes.USD.percent_change_24h).toString();
	            html += '<a href="market_details.html?id='+quotes[i].id + '" class="asset" data-id="'+quotes[i].id+'">';
	            html += '<div class="name">';
	            html += '<div class="name_1">' + quotes[i].name + '</div>';
	            html += '<div class="name_2">' + quotes[i].symbol + '</div>';
	            html += '</div>';
	            html += '<div class="price">';
	            html += '<div class="name_1">' + quotes[i].quotes.USD.price + '</div>';
	            html += '<div class="name_2"><span class="quote">成交量</span>' + quotes[i].total_supply + '</div>';
	            html += '</div>';
	            html += '<div class="rate">';
	            if (str.substr(0, 1) == "-") {
	                html += '<div>' + quotes[i].quotes.USD.percent_change_24h + '%</div>';
	            } else {
	                html += '<div class="rate_green">+' + quotes[i].quotes.USD.percent_change_24h + '%</div>';
	            }
	            html += '</div>';
	            html += '</a>';
	        }
			$(".azxc").append(html);
			var prHtml=''
			prHtml += '<a href="market_details.html" class="asset">';
			prHtml += '<div class="name">';
			prHtml += '<div class="name_1">' + 'jnb' + '</div>';
			prHtml += '<div class="name_2">' + 'JNB' + '</div>';
			prHtml += '</div>';
			prHtml += '<div class="price">';
			prHtml += '<div class="name_1"> $'+ 0.1 +'</div>';
			prHtml += '<div class="name_2"><span class="quote">成交量</span>'+ 1293873 + '</div>';
			prHtml += '</div>';
			prHtml += '<div class="rate">';
			prHtml += '<div>' + '-1.11'+'%</div>';
			prHtml += '</div>';
			prHtml += '</a>';
			// $('.azxc').prepend(prHtml)
		}
		$.ajax({
			type: "POST",
			url: laravel_api + "/historical_data",
			data: {
				address:address,
			},
			dataType: "json",
			success: function(data){
				layer_close()
				console.log(data.message)
				var day = data.message.day;
				console.log(day)
				var today=day[day.length-1].data;
				var open=today.open;
				var close=today.close;
				var rate = Math.floor(((close-open)/open) * 100)/100
				if(rate>0){
					$(".rates").addClass("rate_green");
					$(".rates").html('+'+rate+"%");

				}else{
				    $(".rates").html(rate+"%");	
				}
				
				 
			}
		});
		var result=0;
		$.ajax({
            type: "POST",
            url: laravel_api + "/transaction/deal",
            data: {
                address:address
            },
            dataType: "json",
            success: function(data){
                console.log(data)
				layer_close()
				var com= data.message.complete;
				for (let i=0;i<com.length;i++){
					// console.log(com[i].number)
					result += Number(com[i].number);
				}
				console.log(result)
				$(".volume").html(result);
				console.log(data.message.last_price)
				$(".newprice").html(data.message.last_price)
            }
		});
		
		var socket = io(socket_api);
		var uid = address;
		socket.on('connect', function(){
			socket.emit('login', uid);
		});
		// 后端推送来消息时
		socket.on('new_msg', function(msg) {
			if (msg.type =="transaction") {
				$(".newprice").html(msg.content)
			}
		})
});
