<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>交易后台管理</title>
    <link rel="stylesheet" href="{{URL("admin/plugins/layui/css/layui.css")}}" media="all" />
    <link rel="stylesheet" href="{{URL("admin/plugins/font-awesome/css/font-awesome.min.css")}}" media="all" />
    <link rel="stylesheet" href="{{URL("admin/src/css/app.css")}}" media="all" />
    <link rel="stylesheet" href="{{URL("admin/src/css/themes/default.css")}}" media="all" id="skin" kit-skin />
</head>

<body class="kit-theme">
<div class="layui-layout layui-layout-admin kit-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">
            {{--<img src="/images/logo.png" alt="" style="height: 48px;">--}}
        </div>
        <div class="layui-logo kit-logo-mobile">K</div>
        {{--<ul class="layui-nav layui-layout-left kit-nav">--}}
            {{--<li class="layui-nav-item"><a href="javascript:;">控制台</a></li>--}}
            {{--<li class="layui-nav-item"><a href="javascript:;">商品管理</a></li>--}}
            {{--<li class="layui-nav-item"><a href="javascript:;" id="pay"><i class="fa fa-gratipay" aria-hidden="true"></i> 捐赠我</a></li>--}}
            {{--<li class="layui-nav-item">--}}
                {{--<a href="javascript:;">其它系统</a>--}}
                {{--<dl class="layui-nav-child">--}}
                    {{--<dd><a href="javascript:;">邮件管理</a></dd>--}}
                    {{--<dd><a href="javascript:;">消息管理</a></dd>--}}
                    {{--<dd><a href="javascript:;">授权管理</a></dd>--}}
                {{--</dl>--}}
            {{--</li>--}}
        {{--</ul>--}}
        <ul class="layui-nav layui-layout-right kit-nav">
            <li class="layui-nav-item">
                <a href="javascript:;">
                    <i class="layui-icon">&#xe63f;</i> 皮肤
                </a>
                <dl class="layui-nav-child skin">
                    <dd><a href="javascript:;" data-skin="default" style="color:#393D49;"><i class="layui-icon">&#xe658;</i> 默认</a></dd>
                    <dd><a href="javascript:;" data-skin="orange" style="color:#ff6700;"><i class="layui-icon">&#xe658;</i> 橘子橙</a></dd>
                    <dd><a href="javascript:;" data-skin="green" style="color:#00a65a;"><i class="layui-icon">&#xe658;</i> 原谅绿</a></dd>
                    <dd><a href="javascript:;" data-skin="pink" style="color:#FA6086;"><i class="layui-icon">&#xe658;</i> 少女粉</a></dd>
                    <dd><a href="javascript:;" data-skin="blue.1" style="color:#00c0ef;"><i class="layui-icon">&#xe658;</i> 天空蓝</a></dd>
                    <dd><a href="javascript:;" data-skin="red" style="color:#dd4b39;"><i class="layui-icon">&#xe658;</i> 枫叶红</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;">
                   {{$admin->username}}
                </a>
                <!-- <dl class="layui-nav-child">
                    <dd><a href="javascript:;" kit-target data-options="{url:'basic.html',icon:'&#xe658;',title:'基本资料',id:'966'}"><span>基本资料</span></a></dd>
                    <dd><a href="javascript:;">安全设置</a></dd>
                </dl> -->
            </li>
            <li class="layui-nav-item"><a href="login.html"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a></li>
        </ul>
    </div>

    <div class="layui-side layui-bg-black kit-side">
        <div class="layui-side-scroll">
            <div class="kit-side-fold"><i class="fa fa-navicon" aria-hidden="true"></i></div>
            <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
            <ul class="layui-nav layui-nav-tree" lay-filter="kitNavbar" kit-navbar>

                <li class="layui-nav-item ">
                    <a class="" href="javascript:;"><i class="layui-icon layui-icon-username" aria-hidden="true"></i><span> 用户管理</span></a>
                    <dl class="layui-nav-child">

                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/user/user_index',icon:'&#xe770;',title:'用户管理',id:'5'}">
                                <i class="layui-icon">&#xe770;</i>
                                <span> 用户管理</span>
                            </a>
                        </dd>
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/user/real_index',icon:'&#xe65d;',title:'实名认证',id:'6'}">
                                <i class="layui-icon">&#xe65d;</i>
                                <span> 实名认证</span>
                            </a>
                        </dd>
                        <!-- <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/jcintegral/exchange', icon:'&#xe735;',title:'积分兑换',id:'20'}">
                                <i class="layui-icon">&#xe735;</i>
                                <span> 积分兑换</span>
                            </a>
                        </dd> -->
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-table" aria-hidden="true"></i><span> 撮合交易</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/in',icon:'&#xe658;',title:'正在挂买',id:'13'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>正在挂买</span>
                            </a>
                        </dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/out',icon:'&#xe658;',title:'正在挂卖',id:'12'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>正在挂卖</span>
                            </a>
                        </dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/complete',icon:'&#xe658;',title:'完成记录',id:'14'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>完成记录</span>
                            </a>
                        </dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-chart" aria-hidden="true"></i><span> 合约交易</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/Leverdeals/Leverdeals_show',icon:'&#xe658;',title:'交易记录',id:'32'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>交易记录</span>
                            </a>
                        </dd>
                    </dl>

                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/hazard/total',icon:'&#xe658;',title:'用户风险率',id:'28'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>用户风险率</span>
                            </a>
                        </dd>
                    </dl>
                    <!-- <dl class="layui-nav-child" style="display:none">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/levertolegal/index',icon:'&#xe658;',title:'划转审核',id:'2'}">
                                <i class="layui-icon">&#xe658;</i><span> 杠杆划转审核</span>
                            </a>
                        </dd>
                    </dl> -->
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/levermultiple/index',icon:'&#xe658;',title:'杠杆手数倍数',id:'30'}">
                                <i class="layui-icon">&#xe658;</i><span> 手数倍数设置</span>
                            </a>
                        </dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-chart-screen" aria-hidden="true"></i><span> C2C交易</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/c2c',icon:'&#xe658;',title:'C2C交易需求',id:'25'}">
                                <i class="layui-icon">&#xe658;</i><span> C2C交易需求</span>
                            </a>
                        </dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/c2c_deal',icon:'&#xe658;',title:'C2C交易记录',id:'26'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>交易记录</span>
                            </a>
                        </dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="fa fa-plug" aria-hidden="true"></i><span> 法币交易</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/seller',icon:'&#xe658;',title:'商家管理',id:'11'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>商家管理</span>
                            </a>
                        </dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/legal',icon:'&#xe658;',title:'交易需求',id:'18'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>交易需求</span>
                            </a>
                        </dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/legal_deal',icon:'&#xe658;',title:'交易需求',id:'19'}">
                                <i class="layui-icon">&#xe658;</i>
                                <span>法币交易</span>
                            </a>
                        </dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-headset" aria-hidden="true"></i><span>机器人管理</span></a>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/javarobot/index', icon:'&#xe60e;', title:'机器人管理', id:'39'}">
                                <i class="layui-icon">&#xe60e;</i>
                                <span>机器人列表</span>
                            </a>
                        </dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-date" aria-hidden="true"></i><span> 资产管理</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/account/account_index', icon:'&#xe658;', title:'财务流水',id:'9'}"><i class="layui-icon">&#xe658;</i><span> 资产明细</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/wallet/index', icon:'&#xe613;', title:' 钱包统计',id:'50'}"><i class="layui-icon">&#xe613;</i><span> 钱包明细</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/poundage_index', icon:'&#xe63c;', title:'手续费',id:'40'}"><i class="layui-icon">&#xe63c;</i><span> 手续费明细</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/account/recharge',icon:'&#xe659;',title:'充币明细',id:'15'}">
                                <i class="layui-icon">&#xe659;</i>
                                <span> 充币明细</span>
                            </a>
                        </dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd>
                            <a href="javascript:;" kit-target data-options="{url:'/admin/cashb',icon:'&#xe65e;',title:'提币审核',id:'17'}">
                                <i class="layui-icon">&#xe65e;</i>
                                <span> 提币列表</span>
                            </a>
                        </dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-list" aria-hidden="true"></i><span> 新闻管理</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/news_index',icon:'&#xe658;',title:'新闻管理',id:'8'}"><i class="layui-icon">&#xe658;</i><span> 内容管理</span></a></dd>
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/currency/news_index',icon:'&#xe658;',title:'币种简介',id:'555'}"><i class="layui-icon">&#xe658;</i><span> 币种简介</span></a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-group" aria-hidden="true"></i><span> 管理员权限</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/manager/manager_index',icon:'&#xe658;',title:'后台管理员',id:'4'}"><i class="layui-icon">&#xe658;</i><span> 后台管理员</span></a></dd>
                            <dd><a href="javascript:;" kit-target data-options="{url:'/admin/manager/manager_roles',icon:'&#xe658;',title:'角色管理',id:'3'}"><i class="layui-icon">&#xe658;</i><span> 角色管理</span></a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-util" aria-hidden="true"></i><span>系统管理</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/setting/index',icon:'&#xe658;',title:'基础设置', id:'2'}"><i class="layui-icon">&#xe658;</i><span> 基础设置</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/currency',icon:'&#xe659;',title:'币种管理', id:'16'}"><i class="layui-icon">&#xe659;</i><span> 币种管理</span></a></dd>
                    </dl>


                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/label',icon:'&#xe659;',title:'标签管理', id:'255'}"><i class="layui-icon">&#xe659;</i><span> 标签管理</span></a></dd>
                    </dl>

                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/app_version',icon:'&#xe658;',title:'APP版本管理', id:'116'}"><i class="layui-icon">&#xe658;</i><span> APP版本管理</span></a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-release" aria-hidden="true"></i><span>空投管理</span></a>

                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/airdrop/index',icon:'&#xe609;',title:'空投配置', id:'666'}"><i class="layui-icon">&#xe609;</i><span> 空投配置</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/airdrop/addair',icon:'&#xe609;',title:'空投充值', id:'123456'}"><i class="layui-icon">&#xe609;</i><span> 空投充值</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/airdrop/air_list',icon:'&#xe609;',title:'空投列表', id:'777'}"><i class="layui-icon">&#xe609;</i><span> 空投列表</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/airdrop/lock',icon:'&#xe609;',title:'锁仓比例', id:'888'}"><i class="layui-icon">&#xe609;</i><span> 锁仓比例</span></a></dd>
                    </dl>
                </li>

                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-fonts-strong" aria-hidden="true"></i><span>存币生息</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/deposit/index',icon:'&#xe62b;',title:'存币记录', id:'223344'}"><i class="layui-icon">&#xe62b;</i><span> 存币记录</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/deposit/extract_index',icon:'&#xe62b;',title:'提币记录', id:'223345'}"><i class="layui-icon">&#xe62b;</i><span> 提币记录</span></a></dd>
                    </dl>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/deposit/coin_index',icon:'&#xe62b;',title:'提现币种设置', id:'223346'}"><i class="layui-icon">&#xe62b;</i><span> 提现币种设置</span></a></dd>
                    </dl>
                </li>
                <li class="layui-nav-item ">
                    <a href="javascript:;"><i class="layui-icon layui-icon-date" aria-hidden="true"></i><span>锁仓管理</span></a>
                    <dl class="layui-nav-child">
                        <dd><a href="javascript:;" kit-target data-options="{url:'/admin/lockup/index',icon:'&#xe658;',title:'锁仓配置', id:'223347'}"><i class="layui-icon">&#xe658;</i><span> 锁仓配置</span></a></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-body" id="container">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;"><i class="layui-icon layui-anim layui-anim-rotate layui-anim-loop">&#xe63e;</i> 请稍等...</div>
    </div>

</div>
<!-- <script type="text/javascript">
      var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");
      document.write(unescape("%3Cspan id='cnzz_stat_icon_1264021086'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s22.cnzz.com/z_stat.php%3Fid%3D1264021086%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));
  </script> -->
<script src="{{URL("admin/plugins/layui/layui.js")}}"></script>
<script>
    var message;
    layui.config({
        base: 'src/js/',
        version: '1.0.1'
    }).use(['app', 'message'], function() {
        var app = layui.app,
            $ = layui.jquery,
            layer = layui.layer;
        //将message设置为全局以便子页面调用
        message = layui.message;
        //主入口
        app.set({
            type: 'iframe'
        }).init();
        $('#pay').on('click', function() {
            layer.open({
                title: false,
                type: 1,
                content: '<img src="/src/images/pay.png" />',
                area: ['500px', '250px'],
                shadeClose: true
            });
        });
        $('dl.skin > dd').on('click', function() {
            var $that = $(this);
            var skin = $that.children('a').data('skin');
            switchSkin(skin);
        });
        var setSkin = function(value) {
                layui.data('kit_skin', {
                    key: 'skin',
                    value: value
                });
            },
            getSkinName = function() {
                return layui.data('kit_skin').skin;
            },
            switchSkin = function(value) {
                var _target = $('link[kit-skin]')[0];
                _target.href = _target.href.substring(0, _target.href.lastIndexOf('/') + 1) + value + _target.href.substring(_target.href.lastIndexOf('.'));
                setSkin(value);

            },
            initSkin = function() {
                var skin = getSkinName();
                switchSkin(skin === undefined ? 'default' : skin);
            }();
    });
</script>
</body>

</html>
