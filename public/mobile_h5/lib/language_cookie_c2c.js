// var API = window.location.protocol + '//' + window.location.host + "/api/";
var API = "http://http://www.bibex.org" + "/api/";

function setLang(lang) {
    setLocal('language', lang);
    $.ajax({
        url: API + "lang/set",
        type: "POST",
        dataType: "json",
        data: {
            lang: lang
        },
        async: true,
        success: function (res) {
            window.location.href = 'index.html';
        }
    });
}
function setLocal(name, value) {
    window.localStorage.setItem(name, value)
}

function getLocal(name) {
    return window.localStorage.getItem(name) || '';
}

function changeLg() {
    var lang = getLocal('language') || '';
    if (lang == '') {
        lang = 'hk';
        setLocal('language', 'hk');
    }
    $("[data-localize]").localize("text", {
//      pathPrefix: "http://www.icn.im/mobile/lang",
        pathPrefix: "../lang",
        language: lang
    });

}
$(function () {
    changeLg();
})
var ts = {
    zh: {
        fatbalance: '商家余额',
        voltxt: '24H量',
        buy: '买入',
        sell: '卖出',
        revoke: '撤销',
        times: '倍',
        hand: '手',
        phand: '请输入购买手数',
        domore: '做多',
        doless: '做空',
        makemore: "(做多)",
        makeless: "(做空)",
        sure: '确定',
        ceil: '取消',
        ttype: '类型',
        entrustPrice: '委托价',
        oprice: "开仓价",
        tnowprice: "当前价",
        zyprice: "止盈价",
        zsprice: "止损价",
        canbond: "可用保证金",
        optime: "开仓时间",
        pgtime: "平仓时间",
        znum: "折合数量",
        gprice: "隔夜费",
        pcang: "平仓",
        revoke: "撤单",
        fee: "手续费",
        bond: "保证金",
        setzhi: '设置止盈止损',
        sureOd: '确认下单',
        ppwd: '请输入交易密码',
        loss: '盈亏',
        setloss: '设置止盈止损',
        expectProfit: '预期盈利',
        expectLoss: '预期亏损',
        allClose: '全部平仓',
        moreClose: '只平多单',
        nullClose: '只平空单',
        sureClose: '确认平仓？',
        thanZearo: '设置的值必须大于0',
        listin: '挂单中',
        tdin: '交易中',
        closein: '平仓中',
        closed: '已平仓',
        revoked: '已撤销',
        revokeOrder: '确认撤销订单么？',
        sureping: '确定平仓？',
        thanzone: '设置的值必须大于0',
        risk: '风险率',
        nomore: '没有更多了',
        nodata: '暂无记录',
        more: '加载更多',
        canuse: '可用',
        frezz: '冻结',
        conversion: '折合',
        tprice: '单价',
        tnum: '数量',
        buyin: '购买',
        sellout: '出售',
        finished: '已完成',
        ceiled: '已取消',
        payed: '已付款',
        nofinish: '未完成',
        waitpay: '待付款',
        buyer: '买家',
        seller: '卖家',
        recever: '收款方',
        time: '时间',
        ttotal: '交易总额',
        withdraw: '撤回',
        see: '查看订单',
        pwaitPay: '请等待买家付款',
        odFinish: '订单已完成',
        odCeil: '订单已取消',
        odPay: '已付款,请仔细查看支付信息',
        buynum: '购买数量',
        sellnum: '卖出数量',
        all: '全部',
        allbuy: '全部买入',
        allsell: '全部卖出',
        tlimit: '限额',
        register_time: '注册时间',
        fiatSub: '所属法币',
        shoper_balance: '商家余额',
        enterShop: '进入店铺',
        abnormal: '异常',
        absure: '确定标记为异常吗？',
        sureceil: '您确定要撤销吗？',
        getcode:'获取验证码',
        plength:'密码的长度在6~16位',
        paccount:'请输入账号！',
        pinpwd:'请输入密码！',
        ptpwd:'请输入正确的密码！',
        pyan:'请输入验证码！',
        lgsuccess:'登录成功',
        pmobile:'请输入手机号',
        mnot:'输入的邮箱不符合规则',
        tsend:'发送',
        pzpwd:'请填写正确交易密码',
        ptnum:'请输入数量',
        pload:'匹配中,请稍等...',
        pthands:'请选择或者输入手数',
        ptprice:'请输入价格',
        pznum:'手数请输入正整数',
        pnomore:'输入的手数值不能高于',
        pnoless:'输入的手数值不能低于',
        pchange:'该交易对已下架,请重新切换交易对',
        pmethod:'请先选择支付方式',
        ptpwds:'请输入交易密码',
        pmin:'请先输入最小交易量',
        pmax:'请先输入最大交易量',
        nothan:'最大交易量不能小于最小交易量',
        prname:'请输入真实姓名！',
        pid:'请输入身份证件号！',
        pimg:'请上传身份证件照片！',
        pmsg:'请输入信息',
        twonot:'两次密码输入不一致',
        copys:'复制成功',
        pcopy:'请重新复制',
        pdel:'确定删除吗？',
        dels:'删除成功',
        delno:'删除失败',
        pbtotal:'请输入欲购买法币总额',
        pbnum:'请输入欲购买数量',
        pstotal:'请输入欲出售法币总额',
        psnum:'请输入欲出售数量',
        lbuy:'立即购买',
        lsell:'立即出售',
        hnum:'请输入划转数量',
        shua:'是否确认划转？',
        you:"以当前最优价格交易",
        counting:'计算中',
        todoit:'操作',
        pcoin:'请选择货币单位',
        minNUm:"最小提币数量"
    },
    en: {
        fatbalance: 'Merchant balance',
        voltxt: '24H Vol',
        buy: 'Buy',
        sell: 'Sell',
        revoke: 'Revoke',
        times: "times",
        hand: 'hand',
        phand: "Number of purchases",
        domore: 'Long',
        doless: 'Short',
        makemore: "(Long)",
        makeless: "(Short)",
        sure: 'Sure',
        ceil: 'Cancel',
        ttype: 'type',
        entrustPrice: 'commission price',
        oprice: "Opening price",
        tnowprice: "current price",
        zyprice: "stop earnings price",
        zsprice: "stop-loss price",
        canbond: "available margin",
        pptie: "Opening time",
        optime: 'Open Time',
        pgtime: "Closing time",
        znum: "Converted quantity",
        gprice: "Overnight charges",
        pcang: "Close",
        revoke: "Withdrawal",
        fee: "Service Charge",
        bond: "Bond",
        setzhi: 'Set stop loss stop loss',
        sureOd: 'confirm order',
        ppwd: 'Fund password',
        setloss: 'Set stop loss stop loss',
        expectProfit: 'expected profit',
        expectLoss: 'expected loss',
        allClose: 'All closing',
        moreClose: 'Only flat more than one',
        nullClose: 'Only empty single',
        sureClose: 'Confirm the position? ',
        thanZearo: 'The value set must be greater than 0',
        listin: 'Pending order',
        tdin: 'in the transaction',
        closein: 'Close the position',
        closed: 'Closed',
        revoked: 'revoked',
        revokeOrder: 'Are you sure to cancel the order? ',
        sureping: 'Determine the closure?',
        thanzone: 'The value set must be greater than 0',
        risk: 'risk rate',
        nomore: 'No more',
        nodata: 'No record',
        more: 'Load more',
        canuse: 'available',
        frezz: 'freeze',
        conversion: 'Folding',
        tprice: 'Unit Price',
        tnum: 'number',
        buyin: 'Purchase',
        sellout: 'Sell',
        finished: 'Completed',
        ceiled: 'Cancelled',
        payed: 'Paid',
        nofinish: 'incomplete',
        waitpay: 'Pending payment',
        buyer: 'Buyer',
        seller: 'seller',
        recever: 'Payee',
        time: 'time',
        ttotal: 'total',
        withdraw: 'withdraw',
        see: 'View order',
        pwaitPay: 'Please wait for the buyer to pay',
        odFinish: 'The order has been completed',
        odCeil: 'The order has been cancelled',
        odPay: 'Payment has been made. Please check the payment information carefully.',
        buynum: 'Purchase quantity',
        sellnum: 'Quantity sold',
        all: 'all',
        allbuy: 'Buy all',
        allsell: 'Sell all',
        tlimit: 'Quota',
        register_time: 'Register time',
        fiatSub: 'Legal tender',
        shoper_balance: 'Merchant balance',
        enterShop: 'Enter shop',
        abnormal: 'abnormal',
        absure: "Are you sure it's marked as an exception?",
        sureceil: 'Are you sure you want to revoke it?',
        getcode:'send',
        paccount:'Please enter your account! ',
        pinpwd:'Please enter your password! ',
        ptpwd:'Please enter the correct password! ',
        pyan:'Please enter the verification code! ',
        lgsuccess:'Log on Successfully',
        pmobile:'Please enter your mobile number',
        mnot:'Input mailboxes do not conform to the rules',
        tsend:'Send',
        pzpwd:'Please fill in the correct transaction password',
        ptnum:'Please fill in the quantity',
        pload:'Matching, wait a minute...',
        pthands:'Please select or enter the number of hands',
        ptprice:'Please enter the price',
        pznum:'Please enter a positive integer for the number of hands',
        Pnomore:'Input hand values should not be higher than',
        pnoless:'Input hand value must not be lower than',
        pchange:'The trading pair is off the shelf, please re-switch the trading pair',
        pmethod:'Please choose the payment method first',
        ptpwds:'Please enter the transaction password',
        pmin:'Please enter the minimum transaction volume first.',
        pmax:'Please enter the maximum volume first',
        nothan:'Maximum trading volume should not be less than minimum trading volume',
        prname:'Please enter your real name! ',
        pid:'Please enter your ID number! ',
        pimg:'Please upload ID photos! ',
        pPmsg:'Please enter information',
        twonot:'inconsistent password input twice',
        copys:'Copy Successfully',
        pcopy:'Please reproduce',
        pdel:'Are you sure you want to delete it? ',
        sels:'Delete Successfully',
        selno:'Delete failed',
        pbtotal:'Please enter the total amount of French currency you want to buy',
        pbnum:'Please enter the quantity you want to buy',
        pstotal:'Please enter the total amount of French currency you want to sell',
        psnum:'Please enter the quantity you want to sell',
        lbuy:'Buy now',
        lsell:'For immediate sale',
        hnum:'Please enter the number of transitions',
        shua:'Do you confirm the transfer?',
        you:"Optimal market price",
        counting:'In calculation',
        todoit:'operation',
        pcoin:'Please choose monetary unit.',
        minNUm:"Minimum withdrawal amount"
    
    },
    hk: {
        fatbalance: '商家餘額',
        voltxt: '24H量',
        buy: '買入',
        sell: '賣出',
        revoke: '撤銷',
        times: '倍',
        hand: '手',
        phand: '請輸入購買手數',
        domore: '做多',
        doless: '做空',
        makemore: '（做多）',
        makeless: '（做空）',
        sure: '確認',
        ceil: '取消',
        ttype: '類型',
        entrustPrice: '委託價',
        oprice: '開倉價',
        tnowprice: '當前價',
        zyprice: '止盈價',
        zsprice: '止損價',
        canbond: '可用保證金',
        optime: '開倉時間',
        pgtime: '平倉時間',
        znum: '折合數量',
        gprice: '隔夜費',
        pcang: '平倉',
        revoke: '撤單',
        fee: '手續費',
        bond: '保證金',
        setzhi: '設定止盈止損',
        sureOd: '確認下單',
        ppwd: '請輸入交易密碼',
        loss: '盈虧',
        setloss: '設定止盈止損',
        expectProfit: '預期盈利',
        expectLoss: '預期虧損',
        allClose: '全部平倉',
        moreClose: '只平多單',
        nullClose: '只平空單',
        sureClose: '確認平倉？',
        thanZearo: '設定的值必須大於0',
        listin: '掛單中',
        tdin: '交易中',
        closein: '平倉中',
        closed: '已平倉',
        revoked: '已撤銷',
        revokeOrder: '確認撤銷訂單麼？',
        sureping: '確定平倉？',
        thanzone: '設定的值必須大於0',
        risk: '風險率',
        nomore: '沒有更多了',
        nodata: '暫無記錄',
        more: '加載更多',
        canuse: '可用',
        frezz: '凍結',
        conversion: '折合',
        tprice: '單價',
        tnum: '數量',
        buyin: '購買',
        sellout: '出售',
        finished: '已完成',
        ceiled: '已取消',
        payed: '已付款',
        nofinish: '未完成',
        waitpay: '待付款',
        buyer: '買家',
        seller: '賣家',
        recever: '收款方',
        time: '時間',
        ttotal: '交易總額',
        withdraw: '撤回',
        see: '查看訂單',
        pwaitPay: '請等待買家付款',
        odFinish: '訂單已完成',
        odCeil: '訂單已取消',
        odPay: '已付款,請仔細查看支付資訊',
        buynum: '購買數量',
        sellnum: '賣出數量',
        all: '全部',
        allbuy: '全部買入',
        allsell: '全部賣出',
        tlimit: '限額',
        register_time: '註冊時間',
        fiatSub: '所屬法幣',
        shoper_balance: '商家餘額',
        enterShop: '進入店鋪',
        abnormal: '异常',
        absure: '確定標記為异常嗎？',
        sureceil: '您確定要撤銷嗎？',
        getcode:'獲取驗證碼',
        plength:'密碼的長度在6~16比特',
        paccount:'請輸入帳號！',
        pinpwd:'請輸入密碼！',
        ptpwd:'請輸入正確的密碼！',
        pyan:'請輸入驗證碼！',
        lgsuccess:'登入成功',
        pmobile:'請輸入手機號',
        mnot:'輸入的郵箱不符合規則',
        tsend:'發送',
        pzpwd:'請填寫正確交易密碼',
        ptnum:'請填寫數量',
        pload:'匹配中,請稍等…',
        pthands:'請選擇或者輸入手數',
        ptprice:'請輸入價格',
        pznum:'手數請輸入正整數',
        pnomore:'輸入的手數值不能高於',
        pnoless:'輸入的手數值不能低於',
        pchange:'該交易對已下架,請重新切換交易對',
        pmethod:'請先選擇支付方式',
        ptpwds:'請輸入交易密碼',
        pmin:'請先輸入最小交易量',
        pmax:'請先輸入最大交易量',
        nothan:'最大交易量不能小於最小交易量',
        prname:'請輸入真實姓名！',
        pid:'請輸入身份證件號！',
        pimg:'請上傳身份證件照片！',
        pmsg:'請輸入資訊',
        twonot:'兩次密碼輸入不一致',
        copys:'複製成功',
        pcopy:'請重新複製',
        pdel:'確定删除嗎？',
        dels:'删除成功',
        delno:'删除失敗',
        pbtotal:'請輸入欲購買法幣總額',
        pbnum:'請輸入欲購買數量',
        pstotal:'請輸入欲出售法幣總額',
        psnum:'請輸入欲出售數量',
        lbuy:'立即購買',
        lsell:'立即出售',
        hnum:'請輸入劃轉數量',
        shua:'是否確認劃轉？',
        you:"以當前最優價格交易",
        counting:'計算中',
        todoit:'操作',
        pcoin:'請選擇貨幣單位',
        minNUm:"最小提幣數量"

    },
    jp: {
        fatbalance: '私の店舗',
        voltxt: '24H量',
        buy:"買い入れる",
        sell:"売り出す",
        revoke: '撤退する',
        times: '倍',
        hand: '手',
        phand: '購入手数を入力してください',
        domore: '多め',
        doless: '空っぽ',
        makemore: "(多め)",
        makeless: "(空っぽ)",
        sure: '確認する',
        ceil: '取り消す',
        ttype: 'タイプ',
        entrustPrice: '委託価格',
        oprice: "開倉価",
        tnowprice: "現在の価格",
        zyprice: "値下がり",
        zsprice: "止损价",
        canbond: "価格を損なう",
        optime: "開倉時間",
        pgtime: "平倉時間",
        znum: "交換数",
        gprice: "夜分費",
        pcang: "平倉",
        revoke: "撤退する",
        fee: "手数料",
        bond: "保証金",
        setzhi: '損益を防ぐため設置する',
        sureOd: 'ご注文確認お願いいたします',
        ppwd: '資金パスワードを入力',
        loss: '損益',
        setloss: '損益を防ぐため設置する',
        expectProfit: '黒字を予期する',
        expectLoss: '赤字を予期する',
        allClose: '全部平倉',
        moreClose: 'ただ多めの片手を平らげます',
        nullClose: '先に売って平倉を購入します',
        sureClose:'平倉を確認する？',
        thanZearo:'設定する値は必ず0より大きい',
        listin:'掛け値中',
        tdin:'取引中',
        closein:'平倉中',
        closed:'すでに平倉',
        revoked:'取り消す',
        revokeOrder:'注文の取り消すことをご確認してください？',
        sureping: '平倉を確定しますか？',
        thanzone: '設定する値は0より大きい',
        risk: 'リスク率',
        nomore: 'もっとない',
        nodata: '記録がない',
        more: 'アップロード',
        canuse: '利用可能',
        frezz: '凍結',
        conversion: '換算',
        tprice: '単価',
        tnum: '数量',
        buyin: '購入する',
        sellout: '売り出す',
        finished: '完成した',
        ceiled: 'キャンセルした',
        payed: '支払済み',
        nofinish: '未完成',
        waitpay: '支払いを待つ',
        buyer: '買い手',
        seller: '家を売る',
        recever: '受取方',
        time: '時間',
        ttotal: '取引総額',
        withdraw: '撤回する',
        see: '注文書を調べる',
        pwaitPay: 'お金を買うのを待っていてください',
        odFinish: '注文が完了しました',
        odCeil: '注文がキャンセルされました',
        odPay: 'お支払いになりましたので、お支払いの情報をよくチェックしてください。',
        buynum: '購入数量',
        sellnum: '売数を売る',
        all: 'すべて',
        allbuy: '全部買い入れる',
        allsell: 'すべて売る',
        tlimit: '限度額',
        register_time: '登録時間',
        fiatSub: '所属法元',
        shoper_balance: '商家の残高',
        enterShop: '店に入る',
        abnormal: '異常',
        absure: '確定マークは異常ですか？',
        sureceil: 'キャンセルしますか？',
        getcode:'送信する',
        plength:'パスワードの長さは6～16位',
        paccount:'アカウントを入力してください！',
        pinpwd:'パスワードを入力してください！',
        ptpwd:'正しいパスワードを入力してください！',
        pyan:'検証コードを入力してください！',
        lgsuccess:'ログイン成功',
        pmobile:'携帯番号を入力してください',
        mnot:'入力したメールはルールに合わない',
        tsend:'送信する',
        pzpwd:'正しい取引のパスワードを記入してください',
        ptnum:'数量を記入してください',
        pload:'整合中、少々お待ちください。',
        pthands:'手の数を選択または入力してください',
        ptprice:'価格を入力してください',
        pznum:'手の数を正の整数に入力してください',
        pnomore:'入力した手の数値が高い',
        pnoless:'入力された手の数値を下回ることはできません',
        pchange:'このトランザクションについては、キャンセルしてください。',
        pmethod:'支払方法を選択してください',
        ptpwds :'取引パスワードを入力してください',
        pmin:'最小取引量を先に入力してください',
        pmax:'最後に最大の取引量を入力してください',
        nothan:'最大取引量が最小取引量より小さいことはできません',
        prname:'真実の名前を入力してください！',
        pid:'身分証番号を入力してください！',
        pimg:'身分証の写真をアップロードしてください！',
        pmsg:'情報を入力してください',
        twonot:'2回のパスワード入力が一致しません',
        copys:'コピー成功',
        pcopy:'再コピーしてください',
        pdel:'削除しますか？',
        dels:'削除成功',
        delno:'失敗を削除する',
        pbtotal:'法元の総額を入力して購入してください',
        pbnum:'購入欲の数を入力してください。',
        pstotal:'法元の総額を入力して販売してください',
        psnum:'数量の販売数を入力してください',
        lbuy:'すぐに購入する',
        lsell:'即売にする',
        hnum:'クランクを入力してください',
        shua:'是否确チェックを確認しますか？认划转？',
        you:"現在の最適な価格で取引",
        counting:'計算中',
        todoit:'操作する',
        pcoin:'通貨単位を選択してください',
        minNUm:"最小貨幣数"
    }
};
var lg = getLocal('language') || '';

function getlg(keys) {
    var keys = keys;
    // console.log(lg)
    if (lg == 'jp') {
        return ts.jp[keys];
    } else if (lg == 'en') {
        return ts.en[keys];
    } else if (lg == 'zh') {
        return ts.zh[keys];
    } else {
        return ts.hk[keys];
    }
}
var buy = getlg('buy');
var sell = getlg('sell');
var revoke = getlg('revoke');
var times = getlg('times');
var hand = getlg('hand');
var phand = getlg('phand');
var domore = getlg('domore');
var doless = getlg('doless');
var makemore = getlg('makemore');
var makeless = getlg('makeless');
var sure = getlg('sure');
var ceil = getlg('ceil');
var ttype = getlg('ttype');
var oprice = getlg('oprice');
var tnowprice = getlg('tnowprice');
var zyprice = getlg('zyprice');
var zsprice = getlg('zsprice');
var canbond = getlg('canbond');
var pptie = getlg('pptie');
var pgtime = getlg('pgtime');
var znum = getlg('znum');
var gprice = getlg('gprice');
var revoke = getlg('revoke');
var fee = getlg('fee');
var bond = getlg('bond');
var setzhi = getlg('setzhi');
var sureOd = getlg('sureOd');
var ppwd = getlg('ppwd');
var setloss = getlg('setloss');
var expectProfit = getlg('expectProfit');
var expectLoss = getlg('expectLoss');
var allClose = getlg('allClose');
var moreClose = getlg('moreClose');
var nullClose = getlg('nullClose');
var sureClose = getlg('sureClose');
var thanZearo = getlg('thanZearo');
var listin = getlg('listin');
var tdin = getlg('tdin');
var closein = getlg('closein');
var closed = getlg('closed');
var revoked = getlg('revoked');
var revokeOrder = getlg('revokeOrder');
var sureping = getlg('sureping');
var thanzone = getlg('thanzone');
var risk = getlg('risk');
var more = getlg('more');
var nomore = getlg('nomore');
var pcang = getlg('pcang');
var optime = getlg('optime');
var nodata = getlg('nodata');
var entrustPrice = getlg(' entrustPrice');
var canuse = getlg('canuse');
var frezz = getlg('frezz');
var conversion = getlg('conversion');
var buyin = getlg('buyin');
var sellout = getlg('sellout');
var tnum = getlg('tnum');
var tprice = getlg('tprice');
var finished = getlg('finished');
var ceiled = getlg('ceiled');
var payed = getlg('payed');
var nofinish = getlg('nofinish');
var buyer = getlg('buyer');
var seller = getlg('seller');
var time = getlg('time');
var ttotal = getlg('ttotal');
var withdraw = getlg('withdraw');
var see = getlg('see');
var pwaitPay = getlg('pwaitPay');
var odFinish = getlg('odFinish');
var odCeil = getlg('odCeil');
var odPay = getlg('odPay');
var recever = getlg('recever');
var buynum = getlg('phand');
var sellnum = getlg('sellnum');
var all = getlg('all');
var allbuy = getlg('allbuy');
var allsell = getlg('allsell');
var tlimit = getlg('tlimit');
var waitpay = getlg('waitpay');
var register_time = getlg('register_time');
var fiatSub = getlg('fiatSub');
var shoper_balance = getlg('shoper_balance');
var enterShop = getlg('enterShop');
var abnormal = getlg('abnormal');
var absure = getlg('absure');
var sureceil = getlg('sureceil');
var getcode=getlg('getcode');
var plength=getlg('plength');
var paccount=getlg('paccount');
var pinpwd=getlg('pinpwd');
var ptpwd=getlg('ptpwd');
var pyan=getlg('pyan');
var lgsuccess=getlg('lgsuccess');
var pmobile=getlg('pmobile');
var mnot=getlg('mnot');
var tsend=getlg('tsend');
var pzpwd=getlg('pzpwd');
var ptnum=getlg('ptnum');
var pload=getlg('pload');
var pthands=getlg('pthands');
var ptprice=getlg('ptprice');
var pznum=getlg('pznum');
var pnomore=getlg('pnomore');
var pnoless=getlg('pnoless');
var pchange=getlg('pchange');
var pmethod=getlg('pmethod');
var ptpwds=getlg('ptpwds');
var min=getlg('min');
var prname=getlg('prname');
var pid=getlg('pid');
var pimg=getlg('pimg');
var pmsg=getlg('pmsg');
var twonot=getlg('twonot');
var copys=getlg('copys');
var pcopy=getlg('pcopy');
var pdel=getlg('pdel');
var dels=getlg('dels');
var delno=getlg('delno');
var pbtotal=getlg('pbtotal');
var pstotal=getlg('pstotal');
var pbnum=getlg('pbnum');
var psnum=getlg('psnum');
var lbuy=getlg('lbuy');
var lsell=getlg('lsell');
var hnum=getlg('hnum');
var shua=getlg('shua');
var you=getlg('you');
var counting=getlg('counting');
var todoit=getlg('todoit');
var pcoin=getlg('pcoin');
// var phand=getlg('phand');
// var phand=getlg('phand');
// var phand=getlg('phand');
// var phand=getlg('phand');
// var phand=getlg('phand');
// var phand=getlg('phand');
// var phand=getlg('phand');

