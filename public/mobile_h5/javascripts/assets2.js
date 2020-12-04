var vue = new Vue({
	el: '#app',
	data: {
		Lists: [],
		type: 'trade',
		swiperSlide: function () {},
		datas: {},
		types: 2,
		url: 'tradeAccount.html?id=',
		tradeCount: 0,
		leverCount: 0,
		legelCount: 0,
		show:true,
		hideit:'*****',
		canuse:"可用",
		frezz:"冻结",
		conversion:'折合'
	},
	mounted: function () {
		let that = this;
		let text = '';
		that.listAjax(text);
		that.swipers();
		that.canuse=canuse;
		that.frezz=frezz;
		that.conversion=conversion;
	},
	filters: {
		numbers: function (value) {
			value = Number(value);
			return value;
		},
		toFixedTwo: function(val){
			val = Number(val);
			return val.toFixed(8);
		}
	},
	methods: {
		changeLgs(){
			var lang =  window.localStorage.getItem('language') || '';
			console.log(lang)
			if(lang==''){
				lang = 'zh';
				window.localStorage.setItem('language','zh')
			}
			$("[data-localize]").localize("text", {
				pathPrefix: "/mobile/lang",
				language: lang
			});
			console.log('aaaa')
		 },
		// 头部轮播图
		search() {
			let that = this;
			let text = $('.search_text').val();
			that.listAjax(text);
		},
		listAjax(texts) {
			let that = this;
			initDataTokens({
				url: 'wallet/list',
				data: {
					currency_name: texts
				},
				type: 'post'
			}, function (res) {
				console.log(res);
				if (res.type == 'ok') {
					if(texts == ''){
						that.datas = res.message;
					}
					that.tradeCount = Number(res.message.change_wallet.totle).toFixed(2);
					that.leverCount = Number(res.message.lever_wallet.totle).toFixed(2);
					that.legelCount = Number(res.message.legal_wallet.totle).toFixed(2);
					if (that.type == 'lever') {
						that.Lists = res.message.lever_wallet.balance;
						that.types = 0;
					}else if(that.type == 'trade'){
						that.Lists = res.message.change_wallet.balance;
						that.types=2;
					}else {
						that.Lists = res.message.legal_wallet.balance;
						that.types = 1;
					}
					console.log(that.Lists);
					
					
				}

			})
		},
		// 头部轮播图切换
		swipers() {
			let that = this;
			that.swiperSlide = new Swiper('.mycontainer', {
				slidesPerView: 'auto',
				on: {
					transitionEnd: function () {
						$('.search_text').val('');
						current = that.swiperSlide.snapIndex;
						console.log(that.swiperSlide);
						i = current;
						if (current == 0) {
							that.types = 2;
							that.type = 'trade';
							that.Lists = that.datas.change_wallet.balance;
						}else if(current==1){
							// that.types = 0;
							// that.type = 'lever';
							// that.Lists = that.datas.lever_wallet.balance;
							that.types = 1;
							that.Lists = that.datas.legal_wallet.balance;
							console.log(that.Lists);
							that.type = 'legal';
						}else {
							// that.types = 1;
							// that.Lists = that.datas.legal_wallet.balance;
							// console.log(that.Lists);
							// that.type = 'legal';
						}

					},
				},
			});
		},
		tabClick(options) {
			$('.search_text').val('');
			let that = this;
			that.type = options;
			if (options == 'trade') {
				that.swiperSlide.slideTo(0);
				that.types = 2;
				that.Lists = that.datas.change_wallet.balance;
			}else if(options == 'lever'){
				// that.swiperSlide.slideTo(1);
				// that.types = 0;
				// that.Lists = that.datas.lever_wallet.balance;
			}else {
				// that.swiperSlide.slideTo(2);
				that.swiperSlide.slideTo(1);
				that.types = 1;
				that.Lists = that.datas.legal_wallet.balance;
			}
		},
		// 链接跳转
		links(options){
			let that = this;
            if(that.type == 'lever'){
				window.location.href = 'leverAccount.html?id=' + options + '&type=0';
			}else if(that.type == 'trade'){
				window.location.href = 'tradeAccount.html?id=' + options + '&type=2';
			}else{
				window.location.href = 'legalAccount.html?id=' + options + '&type=1';
			}
		},
		hide(){
			this.show=!this.show;
		}

	}
});
