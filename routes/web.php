<?php

use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
//test
Route::post('wallet/list', 'Api\WalletController@walletList');//用户账户资产信息
//end



Route::post('/api/Independent/points', 'Api\IndependentController@points');//申请，注册，绑定；独立兑换积分页面；

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/', function () {
    return redirect('/dist');
});

Route::post('/api/lang/get', function () {
    return response()->json([
        'type' => 'ok',
        'message' => session()->get('lang'),
        'session_id' => session()->getId(),
    ]);
})->middleware(['cross']);

Route::any('/update', 'Api\DefaultController@checkUpdate')->middleware(['cross']);

Route::post('/api/lang/set', function () {
    $lang = request()->input('lang', '');
    if ($lang == '') {
        return response()->json([
            'type'=> 'error',
            'message' => 'Error: lang cannot be empty',
        ]);
    }
    session()->put('lang', $lang);
    $token = \App\Models\Token::getToken();
    if (!empty($token)) {
        \App\Models\Token::setTokenLang($lang);
    }
    return response()->json([
        'type' => 'ok',
        'message' => 'Switch lange success!(' . session()->get('lang') . ')',
        'session_id' => session()->getId(),
    ]);
})->middleware(['cross']);

Route::any('/api/market/get_current', 'Api\CurrencyController@getCurrentMarket')->middleware(['cross']);
Route::post('/api/exchange/shift_to', 'Api\UserController@shiftToByExchange')->middleware(['cross']);

//******************************api接口不需要登录的**********************
//<--LDH-->
Route::get('api/env.json', function () {
    $request = request();
    $protocol = env('SOCKET_USE_SSL', '0') ? 'https://' : 'http://';

    $result = \App\Utils\RPC::apihttp($protocol . $request->server('HTTP_HOST') . '/env.json');
    return response()->json(json_decode($result, true));
})->middleware(['cross']);//取env.json


/*
 * edit by wyl 2020-07-17
 * */
Route::get('api/user/teams', 'Api\LoginController@get_user_teams');//注册

Route::post('api/user/import', 'Api\LoginController@import');//导入会员
Route::post('api/user/login', 'Api\LoginController@login');//登录
Route::post('api/user/register', 'Api\LoginController@register');//注册
Route::post('api/user/forget', 'Api\LoginController@forgetPassword');//忘记密码
Route::post('api/user/check_mobile', 'Api\LoginController@checkMobileCode');//验证短信验证码
Route::post('api/user/check_email', 'Api\LoginController@checkEmailCode');//验证邮件验证码
Route::post('/api/news/list', 'Api\NewsController@getArticle');//获取文章列表
Route::post('/api/news/detail', 'Api\NewsController@get');//获取文章详情


Route::any('/api/news/currency_get', 'Api\NewsController@currency_get');//获取文章详情
Route::any('/api/news/all_currency', 'Api\NewsController@all_currency');//获取文章详情

Route::post('/api/news/help', 'Api\NewsController@getCategory');    //帮助中心分类
Route::post('/api/news/recommend', 'Api\NewsController@recommend');//推荐文章

Route::post('/api/news/get_invite_return_news', 'Api\NewsController@getInviteReturn');//获取邀请规则详情
Route::get('/api/get_version', 'Api\DefaultController@getVersion');//获取版本号

Route::post('/api/market/market', 'Api\MarketController@marketData');//行情数据
Route::post('/api/sms_send', 'Api\SmsController@smsSend')->middleware('throttle:30:1');//获取短信验证码
Route::post('/api/sms_mail', 'Api\SmsController@sendMail'); //获取邮箱验证码


Route::post('/api/sendMail_baocang', 'Api\SmsController@sendMail_baocang'); //获取邮箱验证码

/*
 * edit by pitt 2020-02-28
 * 获取当天充币排名前20名
 * */
Route::get('/api/get_rechageLists', 'Api\AccountController@get_rechage_order_lists');//获取当日空投排名
Route::get('/api/air_drop_config', 'Api\AccountController@air_drop_config');//获取当日空投排名
//Route::get('/api/air_drop_test', 'Api\AccountController@air_drop_test');//空投测试
//Route::post('/api/make_airdrop', 'Api\AccountController@make_airdrop');//空投兑现
//Route::post('/api/locked_release', 'Api\AccountController@locked_release');//锁仓释放
//Route::get('/api/check_airdrop', 'Api\AccountController@check_airdrop');//自动开始空投
Route::get('/api/git_pse_by_usdt_banlance', 'Api\AccountController@git_pse_by_usdt_banlance');//根据USDT赠送余额

//存币生息
Route::get('/api/fixed/get_currency_lists', 'Api\FixeddepositController@get_currency_lists');//币种列表
Route::get('api/fixed/get_deposit_percent', 'Api\FixeddepositController@get_deposit_percent');//获取利率
Route::post('api/fixed/fixed_deposit_opreation', 'Api\FixeddepositController@fixed_deposit_opreation');//存币生息
Route::post('api/fixed/get_deposit_lists', 'Api\FixeddepositController@get_deposit_lists');//获取记录
Route::get('api/fixed/get_rechange_config', 'Api\FixeddepositController@get_rechange_config');//获取锁仓配置
Route::get('api/fixed/trans_to_lock_income', 'Api\FixeddepositController@trans_to_lock_income');//充值锁仓
Route::get('api/fixed/locked_wallet_released', 'Api\FixeddepositController@locked_wallet_released');//充值锁仓

//活期解锁
//Route::get('api/fixed/unlock_current_deposit', 'Api\FixeddepositController@unlock_current_deposit');
//活期转出
Route::post('api/fixed/ex_current_deposit_record', 'Api\FixeddepositController@ex_current_deposit_record');
//定期生息-到期返回
//Route::get('api/fixed/calculate_fixed_income', 'Api\FixeddepositController@update_fixed_deposit');
//获取用户总资产收益（定期）
Route::post('api/fixed/get_user_fixed_deposit_total', 'Api\FixeddepositController@get_user_fixed_deposit_total');
//获取用户总资产收益（活期）
Route::post('api/fixed/get_user_current_deposit_total', 'Api\FixeddepositController@get_user_current_deposit_total');





Route::any('/api/upload', 'Api\DefaultController@upload');//上传图片接口
Route::any('/api/base64_upload', 'Api\DefaultController@base64ImageUpload')->middleware(['cross']);;//base64上传图片接口
Route::post('/api/transaction/legal_list', 'Api\TransactionController@legalList');//法币交易市场
Route::get('/api/seller_list', 'Api\SellerController@lists');//商家列表

Route::get('api/legal_deal_platform', 'Api\LegalDealController@legalDealPlatform')->middleware(['demo_limit']); //商家发布法币交易信息列表
Route::get('api/c2c_deal_platform', 'Api\C2cDealController@legalDealPlatform')->middleware(['demo_limit']); //用户发布c2c法币交易信息列表

Route::get('api/currency/list', 'Api\CurrencyController@lists');//币种列表
Route::get('api/currency/quotation', 'Api\CurrencyController@quotation');//币种列表带行情
Route::any('api/currency/quotation_new', 'Api\CurrencyController@newQuotation'); //币种列表带行情(支持交易对)
Route::any('api/currency/quotation_new_lever', 'Api\CurrencyController@newQuotation_lever'); //币种列表带行情(支持交易对)
Route::any('api/currency/plates', 'Api\CurrencyController@plates'); //币种版块
Route::post('api/deal/info', 'Api\CurrencyController@dealInfo');//行情详情

Route::post('api/deal/market_k', 'Api\CurrencyController@market_k');//行情详情  测试接口


//Route::any('api/currency/market_day', 'Api\CurrencyController@market_day');//当天行情
//Route::any('api/currency/new_timeshar', 'Api\CurrencyController@newTimeshars')->middleware(['cross']); //K线分时数据，对接tradeingview
Route::any('api/currency/new_timeshar', 'Api\CurrencyController@klineMarket')->middleware(['cross']);



Route::any('api/currency/show_new_timeshar', 'Api\CurrencyController@klineMarket_show')->middleware(['cross']);//绘制50条数据k线



//K线分时数据，对接tradeingview
Route::any('api/currency/kline_market', 'Api\CurrencyController@klineMarket')->middleware(['cross']); //K线分时数据，对接tradeingview
Route::any('api/currency/timeshar', 'Api\CurrencyController@timeshar');//分时
Route::any('api/currency/write_kline', 'Api\CurrencyController@writeEsearchKline')->middleware(['cross']); //K线分时数据，对接tradeingview
Route::any('api/currency/fifteen_minutes', 'Api\CurrencyController@fifteen_minutes');//15分钟
Route::any('api/currency/market_hour', 'Api\CurrencyController@market_hour');//一个小时
Route::any('api/currency/four_hour', 'Api\CurrencyController@four_hour');//4个小时

Route::any('api/currency/five_minutes', 'Api\CurrencyController@five_minutes');//5分钟
Route::any('api/currency/thirty_minutes', 'Api\CurrencyController@thirty_minutes');//30分钟
Route::any('api/currency/one_week', 'Api\CurrencyController@one_week');//一周
Route::any('api/currency/one_month', 'Api\CurrencyController@one_month');//一个月

Route::get('api/currency/lever', 'Api\CurrencyController@lever');//行情详情
Route::any('api/user/into_users', 'Api\UserController@into_users');//导入用户
Route::any('api/user/into_tra', 'Api\UserController@into_tra');//美丽链转入的接口(imc)
Route::any('api/user/e_pwd', 'Api\UserController@e_pwd');//修改密码
Route::any('api/currency/update_date', 'Api\CurrencyController@update_date');//测试
Route::any('user/walletaddress', 'Api\UserController@walletaddress');//钱包地址

Route::any('/test555', 'Api\PrizePoolController@test555');


Route::get('api/kline', 'Api\MarketController@test');//行情详情
Route::get('api/getLtcKMB', 'Api\WalletController@getLtcKMB');

Route::post('api/getNode','Api\DefaultController@getNode');//节点关系
Route::any('api/block', 'Api\CommonController@block');



//释放登录才能看接口
Route::post('api/transaction/deal', 'Api\TransactionController@deal');//deal
Route::post('api/transaction_in', 'Api\TransactionController@TransactionInList');
Route::post('api/transaction_out', 'Api\TransactionController@TransactionOutList');
Route::post('api/transaction_complete', 'Api\TransactionController@TransactionCompleteList');
Route::post('api/lever/deal', 'Api\LeverController@deal'); //杠杆deal

Route::any('api/recommended', 'Api\CurrencyController@recommended');//上传头像

Route::any('api/user/redis_test', 'Api\RedisController@redis_test');//redis测试

//Route::post('api/user/insert', 'Api\UserDrawAdressController@user_update');//redis测试
Route::get('api/user/find', 'Api\UserDrawAdressController@user_find');//redis测试

//测试
Route::get('api/lever/test', 'Api\LeverController@test'); //杠杆deal

//<--LDHend-->
//******************************api接口需要登录的**********************
Route::group(['prefix' => 'api', 'middleware' => ['check_api']], function () {

    Route::post('/user/insert', 'Api\UserDrawAdressController@user_update');
    Route::any('/user/head_pic', 'Api\UserController@head_pic');//上传头像



    Route::any('/user/transtate_history', 'Api\UserController@transtate_history');//比比交易返佣历史显示




    Route::any('/user/addzixuan', 'Api\UserController@addzixuan');//自选
    Route::any('/user/zixuan_list', 'Api\UserController@zixuan_list');//自选

    //退出登录
    Route::any('/user/logout', 'Api\UserController@logout');
    //通证兑换
    Route::any('/candy/candyshow', 'Api\UserController@candyshow');//通证显示
    Route::any('/candy/candy_tousdt', 'Api\UserController@candy_tousdt');//通证兑换

    Route::any('/candy/candyhistory', 'Api\PrizePoolController@candyhistory'); //通证奖励记录
    Route::any('/candy/candy_tousdthistory', 'Api\PrizePoolController@candy_tousdthistory'); //通证兑换usdt记录

    Route::any('/profits/show_profits', 'Api\AccountController@show_profits'); //盈亏返还记录

    //个人中心//<--LDH-->
    Route::post('user/cash_info', 'Api\UserController@cashInfo')->middleware(['demo_limit']);//个人收款信息
    Route::post('user/cash_save', 'Api\UserController@saveCashInfo')->middleware(['demo_limit']);//添加修改收款方式
    Route::post('/checkpassword', 'Api\UserController@checkPayPassword');//验证法币交易密码
    //反馈建议
    Route::post('/feedback/list', 'Api\FeedBackController@myFeedBackList');//反馈信息列表
    Route::post('/feedback/detail', 'Api\FeedBackController@feedBackDetail');//反馈信息内容，包括回复信息
    Route::post('/feedback/add', 'Api\FeedBackController@feedBackAdd');//添加反馈信息
    //安全中心
    Route::post('safe/safe_center', 'Api\UserController@safeCenter');//安全中心绑定信息
    Route::post('safe/gesture_add', 'Api\UserController@gesturePassAdd');//添加手势密码
    Route::post('safe/gesture_del', 'Api\UserController@gesturePassDel');//删除手势密码
    Route::post('safe/update_password', 'Api\UserController@updatePayPassword');//修改交易密码
    Route::post('safe/mobile', 'Api\UserController@setMobile');//绑定电话
    Route::post('safe/email', 'Api\UserController@setEmail'); //绑定邮箱
    //资产
    Route::post('wallet/list', 'Api\WalletController@walletList');//用户账户资产信息
    Route::post('wallet/detail', 'Api\WalletController@getWalletDetail');//用户账户资产详情
    Route::post('wallet/change', 'Api\WalletController@changeWallet')->middleware(['demo_limit']);//账户划转
    Route::post('wallet/transfer', 'Api\WalletController@accountTransfer')->middleware(['demo_limit']);//账户划转
    Route::any('wallet/hzhistory', 'Api\WalletController@hzhistory');//账户历史记录

    Route::post('wallet/get_info', 'Api\WalletController@getCurrencyInfo');//获取提币信息
    Route::post('wallet/get_address', 'Api\WalletController@getAddressByCurrency');//获取提币地址
    Route::post('wallet/out', 'Api\WalletController@postWalletOut')->middleware(['demo_limit', 'validate_user_locked', 'lever_hold_check', 'check_pay_password:withdraw']);//提交提币信息
    Route::post('wallet/get_in_address', 'Api\WalletController@getWalletAddressIn')->middleware(['demo_limit']);//充币地址
    Route::any('wallet/legal_log', 'Api\WalletController@legalLog');//财务记录
    Route::any('wallet/out_log', 'Api\WalletController@walletOutLog');//提币记录

    //交易记录
//    Route::post('transaction_in', 'Api\TransactionController@TransactionInList');
//    Route::post('transaction_out', 'Api\TransactionController@TransactionOutList');
//    Route::post('transaction_complete', 'Api\TransactionController@TransactionCompleteList');
    Route::post('transaction_del', 'Api\TransactionController@TransactionDel');//取消交易

    //<--LDHend-->


    Route::get('/test', 'Api\UserController@test');


    Route::get('/index', 'Api\DefaultController@index');
    // Route::get('/get_version','Api\DefaultController@getVersion');
    //发送短信

    Route::post('/user/vip', 'Api\UserController@vip');

    Route::any('/user_match/list', 'Api\UserMatchController@lists');
    Route::any('/user_match/add', 'Api\UserMatchController@add');
    Route::any('/user_match/del', 'Api\UserMatchController@del');

    Route::post('/historical_data', 'Api\DefaultController@historicalData');

    Route::post('/quotation', 'Api\DefaultController@quotation');
    Route::post('/quotation/info', 'Api\DefaultController@quotationInfo');

    Route::post('/transaction/revoke', 'Api\TransactionController@revoke');//撤销委托

    Route::post('/transaction/entrust', 'Api\TransactionController@entrust');//当前委托
    Route::post('/transaction/entrustlog', 'Api\TransactionController@entrustlog');//历史委托
//    Route::post('/transaction/deal', 'Api\TransactionController@deal');//deal
    Route::post('/transaction/in', 'Api\TransactionController@in')->middleware(['check_user', 'validate_user_locked', 'check_pay_password:match']);//买入
    Route::post('/transaction/out', 'Api\TransactionController@out')->middleware(['check_user', 'validate_user_locked', 'check_pay_password:match']);//卖出

//    Route::post('/lever/deal', 'Api\LeverController@deal'); //杠杆deal
    Route::post('/lever/dealall', 'Api\LeverController@dealAll'); //杠杆全部
//    Route::post('/lever/submit', 'Api\LeverController@submit')->middleware(['validate_user_locked', 'check_pay_password:lever']); //杠杆下单



    Route::post('/lever/submit', 'Api\LeverController@submit')->middleware(['validate_user_locked']); //杠杆下单

    Route::post('/lever/close', 'Api\LeverController@close'); //杠杆平仓
    Route::post('/lever/cancel', 'Api\LeverController@cancelTrade'); //撤销委托(取消)
    Route::post('/lever/batch_close', 'Api\LeverController@batchCloseByType'); //一键平仓
    Route::post('/lever/setstop', 'Api\LeverController@setStopPrice'); //设置止盈止损价
    Route::post('/lever/my_trade', 'Api\LeverController@myTrade'); //我的交易记录

    Route::post('/false/data', 'Api\DefaultController@falseData');//虚拟数据
    Route::post('/data/graph', 'Api\DefaultController@dataGraph');//数据图

    Route::get('/money/exit', 'Api\WalletController@moneyExit');
    Route::post('/money/exit', 'Api\WalletController@doMoneyExit');

    Route::get('/money/rechange', 'Api\WalletController@moneyRechange');
    Route::post('/wallet/add', 'Api\WalletController@add');
    // Route::get('/wallet/list','Api\WalletController@list');
    Route::get('/wallet/lista', 'Api\WalletController@lista');

    Route::post('/t/add', 'Api\TransactionController@tadd');//提交交易

    Route::post('/account/list', 'Api\AccountController@list');//账目明细
    Route::post('/transaction/add', 'Api\TransactionController@add');//提交交易
    Route::post('/transaction/list', 'Api\TransactionController@list');//交易列表
    Route::post('/transaction/info', 'Api\TransactionController@info');//交易详情

    Route::post('/user/update_address', 'Api\UserController@updateAddress');//更新地址
    Route::post('/user/getuserbyaddress', 'Api\UserController@getUserByAddress');//根据地址获取用户信息

    Route::post('/user/chat', 'Api\UserController@sendchat');//发送聊天
    Route::post('/user/chatlist', 'Api\UserController@chatlist');//发送聊天

    Route::post('legal_send', 'Api\LegalDealController@postSend')->middleware(['demo_limit', 'validate_user_locked', 'check_pay_password:otc']); //商家发布法币交易信息
    Route::get('legal_deal_info', ['uses' => 'Api\LegalDealController@legalDealSendInfo', 'middleware' => ['check_user', 'demo_limit']]); //法币交易信息详情
    Route::post('do_legal_deal', ['uses' => 'Api\LegalDealController@doDeal', 'middleware' => ['check_user', 'demo_limit', 'validate_user_locked', 'check_pay_password:otc']]); //法币交易信息详情
    Route::get('legal_seller_deal', ['uses' => 'Api\LegalDealController@sellerLegalDealList', 'middleware' => ['check_user', 'demo_limit']]); //法币交易商家端交易列表
    Route::get('legal_user_deal', ['uses' => 'Api\LegalDealController@userLegalDealList', 'middleware' => ['check_user', 'demo_limit']]); //法币交易用户端交易列表
    Route::get('seller_info', 'Api\LegalDealController@sellerInfo')->middleware(['demo_limit']); //商家详情信息
    Route::get('seller_trade', 'Api\LegalDealController@tradeList')->middleware(['demo_limit']); //商家交易

    Route::get('legal_deal', ['uses' => 'Api\LegalDealController@legalDealInfo', 'middleware' => ['check_user']])->middleware(['demo_limit']); //交易详情信息
    Route::post('user_legal_pay', ['uses' => 'Api\LegalDealController@userLegalDealPay', 'middleware' => ['check_user']])->middleware(['demo_limit', 'check_pay_password:otc']); //法币交易用户确认付款
    Route::post('user_legal_pay_cancel', ['uses' => 'Api\LegalDealController@userLegalDealCancel', 'middleware' => ['check_user']])->middleware(['demo_limit', 'check_pay_password:otc']); //法币交易用户取消订单
    Route::get('my_seller', ['uses' => 'Api\LegalDealController@mySellerList', 'middleware' => ['check_user']])->middleware(['demo_limit']); //我的商铺
    Route::get('legal_send_deal_list', ['uses' => 'Api\LegalDealController@legalDealSellerList', 'middleware' => ['check_user']])->middleware(['demo_limit']); //发布交易列表
    Route::post('legal_deal_sure', ['uses' => 'Api\LegalDealController@doSure', 'middleware' => ['check_user']])->middleware(['demo_limit', 'check_pay_password:otc']); //商家确认收款
    Route::post('legal_deal_user_sure', ['uses' => 'Api\LegalDealController@userDoSure', 'middleware' => ['check_user']])->middleware(['demo_limit', 'check_pay_password:otc']); //用户确认收款
    Route::post('back_send', ['uses' => 'Api\LegalDealController@backSend', 'middleware' => ['check_user']])->middleware(['demo_limit', 'check_pay_password:otc']); //商家撤回发布
    Route::post('error_send', ['uses' => 'Api\LegalDealController@errorSend', 'middleware' => ['check_user']])->middleware(['demo_limit', 'check_pay_password:otc']); //商家撤回异常发布


    Route::post('user/invite_list', 'Api\UserController@inviteList')->middleware(['demo_limit']);//邀请返佣榜单
    Route::get('user/invite', 'Api\UserController@invite')->middleware(['demo_limit']);//我的邀请

    Route::post('user/my_invite_list', 'Api\UserController@myInviteList')->middleware(['demo_limit']);//我的邀请会员列表
    Route::post('user/my_account_return', 'Api\UserController@myAccountReturn')->middleware(['demo_limit']);//我的邀请返佣列表
    Route::get('user/my_poster', 'Api\UserController@posterBg')->middleware(['demo_limit']);//我的专属海报
    Route::get('user/my_share', 'Api\UserController@share')->middleware(['demo_limit']);//邀请好友分享

    //钱包地址
    //Route::any('user/walletaddress','Api\UserController@walletaddress');

    Route::get('user/info', 'Api\UserController@info');//我的
    Route::get('user/center', 'Api\UserController@userCenter');//个人中心
    Route::post('user/real_name', 'Api\UserController@realName')->middleware(['demo_limit']);//身份认证
    Route::get('user/logout', 'Api\UserController@logout');//退出登录
    Route::post('user/setaccount', 'Api\UserController@setAccount')->middleware(['demo_limit']);//设置法币交易账号

    Route::get('/wallet/currencylist', 'Api\WalletController@currencyList');//币种列表
    Route::post('/wallet/addaddress', 'Api\WalletController@addAddress');//添加提币地址
    Route::post('/wallet/deladdress', 'Api\WalletController@addressDel');//删除提币地址

    Route::get('/transaction/checkinout', 'Api\TransactionController@checkInOut');//验证法币交易购买 出售按钮
    Route::get('/user/into_tra_log', 'Api\UserController@into_tra_log');//用户转入记录

    //钱包需要的接口
    Route::post('wallet/ltcSend', 'Api\WalletController@ltcSend')->middleware(['demo_limit']);//
    Route::post('wallet_add', 'Api\WalletOneController@add');//
    Route::get('new/walletList', 'Api\WalletOneController@walletList');//
    Route::get('new/money/rechange', 'Api\WalletOneController@moneyRechange');
    Route::post('account/newlist', 'Api\WalletOneController@accountList');
    Route::post('transaction/newadd', 'Api\WalletOneController@walletChange');
    Route::post('get/userinfo', 'Api\WalletOneController@getInfo');

    //c2c交易
    Route::post('c2c_send', ['uses' => 'Api\C2cDealController@postSend', 'middleware' => ['check_user', 'validate_user_locked', 'demo_limit', 'check_pay_password:c2c']]); //用户发布交易信息
    Route::get('c2c_deal_info', ['uses' => 'Api\C2cDealController@legalDealSendInfo', 'middleware' => ['check_user']])->middleware(['demo_limit']); //c2c法币交易信息详情
    Route::post('c2c/do_legal_deal', ['uses' => 'Api\C2cDealController@doDeal', 'middleware' => ['check_user', 'demo_limit', 'validate_user_locked', 'check_pay_password:c2c']]); //法币交易信息详情
    Route::post('wallet/real_name', 'Api\UserController@walletRealName');//钱包身份认证
    Route::get('c2c/seller_info', 'Api\C2cDealController@sellerInfo')->middleware(['demo_limit']); //用户c2c店铺详情信息
    Route::get('c2c/seller_trade', 'Api\C2cDealController@tradeList')->middleware(['demo_limit']); //我的发布交易列表
    Route::get('c2c_seller_deal', ['uses' => 'Api\C2cDealController@sellerLegalDealList', 'middleware' => ['check_user', 'demo_limit']]); //法币交易商家端交易列表
    Route::get('c2c_user_deal', ['uses' => 'Api\C2cDealController@userLegalDealList', 'middleware' => ['check_user', 'demo_limit']]); //法币交易用户端交易列表
    Route::get('c2c_deal', ['uses' => 'Api\C2cDealController@legalDealInfo', 'middleware' => ['demo_limit', 'check_user']]); //交易详情信息
    Route::post('c2c/user_legal_pay_cancel', ['uses' => 'Api\C2cDealController@userLegalDealCancel', 'middleware' => ['check_user', 'demo_limit', 'check_pay_password:c2c']]); //C2C交易用户取消订单
    Route::post('c2c/user_legal_pay', ['uses' => 'Api\C2cDealController@userLegalDealPay', 'middleware' => ['check_user', 'demo_limit', 'check_pay_password:c2c']]); //C2c交易用户确认付款
    Route::post('c2c/legal_deal_sure', ['uses' => 'Api\C2cDealController@doSure', 'middleware' => ['check_user', 'demo_limit', 'check_pay_password:c2c']]); //C2C发布者确认收款
    Route::post('c2c/legal_deal_user_sure', ['uses' => 'Api\C2cDealController@userDoSure', 'middleware' => ['check_user', 'demo_limit', 'check_pay_password:c2c']]); //C2C用户确认收款
    Route::post('c2c/back_send', ['uses' => 'Api\C2cDealController@backSend', 'middleware' => ['check_user', 'demo_limit', 'check_pay_password:c2c']]); //C2C撤回发布
    Route::get('c2c/legal_send_deal_list', ['uses' => 'Api\C2cDealController@legalDealSellerList', 'middleware' => ['check_user', 'demo_limit']]); //发布交易列表

    //添加代理商时用户的授权码
    Route::get('user/authorization_code', 'Api\UserController@authCode');
});

Route::post('api/user/walletRegister', 'Api\LoginController@walletRegister');//钱包注册
Route::get('api/ltcGet', 'Api\WalletController@ltcGet');//钱包获取交易所的转账
Route::get('new/walletList', 'Api\WalletOneController@walletList');//
