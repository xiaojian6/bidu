@extends('admin._layoutNew')

@section('page-head')
    <style>
        .hide {
            display: none;
        }
    </style>
@endsection

@section('page-content')
    <form class="layui-form" action="">
        <div class="layui-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">基础参数</li>
                <li>提币参数</li>
                <li>链上参数</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <div class="layui-form-item">
                        <label class="layui-form-label">币种名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->name}}">
                        </div>
                        <div class="layui-form-mid layui-word-aux">请确保在交易所中该币种名称是惟一的</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">克隆币种行情</label>
                        <div class="layui-input-inline">
                            <input type="text" name="clone_currency_k"  autocomplete="off" placeholder="克隆行情的币种" class="layui-input" value="{{$result->clone_currency_k}}">
                        </div>
                        <div class="layui-form-mid layui-word-aux">将填入的币种的火币行情作为该币种火币行情</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">排序</label>
                        <div class="layui-input-inline">
                            <input type="number" class="layui-input" id="sort" name="sort" value="{{$result->sort}}" placeholder="排序为升序">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">币种功能类型</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="is_legal" title="法币交易" value="1" lay-skin="primary" @if($result->is_legal ==1) checked @endif>
                            <input type="checkbox" name="is_change" title="币币交易" value="1" lay-skin="primary" @if($result->is_change ==1) checked @endif>
                            <input type="checkbox" name="is_lever" title="合约交易" value="1" lay-skin="primary" @if($result->is_lever ==1) checked @endif>
                            <input type="checkbox" name="is_match" title="撮合交易" value="1" lay-skin="primary" @if($result->is_match ==1) checked @endif>
                        </div>
                        <div class="layui-form-mid layui-word-aux"></div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">币种 logo</label>
                        <div class="layui-input-block">
                            <button class="layui-btn" type="button" id="upload_test">选择图片</button>
                            <br>
                            <img src="@if(!empty($result->logo)){{$result->logo}}@endif" id="img_thumbnail" class="thumbnail" style="display: @if(!empty($result->logo)){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                            <input type="hidden" name="logo" id="thumbnail" value="@if(!empty($result->logo)){{$result->logo}}@endif">
                        </div>
                    </div>
                </div>

                <div class="layui-tab-item">
                    <div class="layui-form-item">
                        <label class="layui-form-label">提币数量</label>
                        <div class="layui-input-inline">
                            <input type="number" class="layui-input" name="min_number" value="{{$result->min_number}}" placeholder="最小数量">
                        </div>
                        <div class="layui-form-mid">-</div>
                        <div class="layui-input-inline">
                            <input type="number" class="layui-input" name="max_number" value="{{$result->max_number}}" placeholder="最大数量">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">提币费率</label>
                        <div class="layui-input-inline">
                            <input type="number" class="layui-input" name="rate" value="{{$result->rate}}" placeholder="百分比">
                        </div>
                        <div class="layui-form-mid">%</div>
                    </div>
                </div>

                <div class="layui-tab-item">
                    <div class="layui-form-item">
                        <label class="layui-form-label">基于币种</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-verify="required">
                                <option value="btc" @if($result->type =='btc') selected @endif>BTC</option>
                                <option value="usdt" @if($result->type =='usdt') selected @endif>USDT</option>
                                <option value="eth" @if($result->type =='eth') selected @endif>ETH</option>
                                <option value="erc20" @if($result->type =='erc20') selected @endif>ERC20</option>
                                <option value="xrp" @if($result->type =='xrp') selected @endif>XRP</option>
                                <option value="xrp" @if($result->type =='eostoken') selected @endif>EOSTOKEN</option>
                            </select>
                        </div>
                        <div class="layui-form-mid layui-word-aux">新添加币种必须指定正确所属区块链币种</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">用户钱包</label>
                        <div class="layui-input-inline">
                            <select name="make_wallet" lay-verify="required">
                                <option value="-1">请选择</option>
                                <option value="0" @if($result->make_wallet == 0) selected @endif>不生成</option>
                                <option value="1" @if($result->make_wallet == 1) selected @endif>接口生成</option>
                                <option value="2" @if($result->make_wallet == 2) selected @endif>继承总账号</option>
                            </select>
                        </div>
                        <div class="layui-form-mid layui-word-aux">用户钱包生成策略</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">总账号</label>
                        <div class="layui-input-block">
                            <input type="text" name="total_account"  autocomplete="off" placeholder="运营者的钱包地址" class="layui-input" value="{{$result->total_account}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">私钥</label>
                        <div class="layui-input-block">
                            <input type="password" name="key"  autocomplete="off" placeholder="运营者的钱包私钥" class="layui-input" value="{{$result->key}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">合约地址</label>
                        <div class="layui-input-block">
                            <input type="text" name="contract_address"  autocomplete="off" placeholder="仅ERC20代币才需要填写" class="layui-input" value="{{$result->contract_address}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">发币位数</label>
                        <div class="layui-input-inline">
                            <input type="text" name="decimal_scale"  autocomplete="off" placeholder="" class="layui-input" value="{{$result->decimal_scale ?? 18}}">
                        </div>
                        <div class="layui-form-mid layui-word-aux">请务必保证与区域链上小数位一致</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">链上手续费</label>
                        <div class="layui-input-block">
                            <div class="layui-input-inline">
                                <input type="text" name="chain_fee"  autocomplete="off" placeholder="" class="layui-input" value="{{$result->chain_fee ?? 0 }}">
                            </div>
                            <div class="layui-form-mid layui-word-aux">链上归拢、提币的手续费</div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">安全验证码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="verificationcode" placeholder="" autocomplete="off" class="layui-input">
                        </div>
                        <button type="button" class="layui-btn layui-btn-primary" id="get_code">获取验证码</button>
                    </div>
                </div>
            </div>
        </div>

        <input id="currency_id" type="hidden" name="id" value="{{$result->id}}">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script>
        layui.use(['upload', 'form', 'laydate', 'element', 'layer'], function () {
            var upload = layui.upload
                ,form = layui.form
                ,layer = layui.layer
                ,$ = layui.$
                ,laydate = layui.laydate
                ,index = parent.layer.getFrameIndex(window.name)
                ,element = layui.element
            var currency_id = $('#currency_id').val()
                ,currency_name = $('input[name=name]').val()
            var uploadInst = upload.render({
                elem: '#upload_test' //绑定元素
                ,url: '{{URL("api/upload")}}' //上传接口
                ,done: function(res){
                    //上传完毕回调
                    if (res.type == "ok"){
                        $("#thumbnail").val(res.message)
                        $("#img_thumbnail").show()
                        $("#img_thumbnail").attr("src",res.message)
                    } else{
                        alert(res.message)
                    }
                }
                ,error: function(){
                    //请求异常回调
                }
            });
            //监听提交
            form.on('submit(demo1)', function(data){
                var data = data.field;
                $.ajax({
                    url:'{{url('admin/currency_add')}}'
                    ,type:'post'
                    ,dataType:'json'
                    ,data : data
                    ,success:function(res){
                        if(res.type=='error'){
                            layer.msg(res.message);
                        }else{
                            parent.layer.close(index);
                            parent.window.location.reload();
                        }
                    }
                });
                return false;
            });
            //遍历打入ETH
            $('#transfer_eth').click(function () {
                layer.confirm('请确保总账号有足够量的ETH,确定要向[' + currency_name + ']币种所有钱包打入ETH吗?', {
                    title: 'ETH转入操作确认'
                }, function () {
                    $.ajax({
                        url: '{{url('admin/ajax/artisan')}}',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            currency_id: currency_id,
                            type: 'transfer_eth',
                        },
                        success: function (res) {
                            layer.msg(res.message);
                        }
                    });
                });
                return false;
            });
            //余额归拢
            $('#collect_token').click(function () {
                layer.confirm('<p>请确保要归拢的钱包有足够量的ETH,确定要对[' + currency_name + ']币种做归拢操作吗?', {
                    title: '归拢操作确认'
                }, function () {
                    $.ajax({
                        url: '{{url('admin/ajax/artisan')}}',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            currency_id: currency_id,
                            type: 'collect_token',
                        },
                        success: function (res) {
                            layer.msg(res.message);
                        }
                    });
                });
                return false;
            });
            //获取验证码
            $('#get_code').click(function () {
                var that_btn = $(this);
                $.ajax({
                    url: '/admin/safe/verificationcode'
                    ,type: 'GET'
                    ,success: function (res) {
                        if (res.type == 'ok') {
                            that_btn.attr('disabled', true);
                            that_btn.toggleClass('layui-btn-disabled');
                        }
                        layer.msg(res.message, {
                            time: 3000
                        });
                    }
                    ,error: function () {
                        layer.msg('网络错误');
                    }
                });
            });
        });
    </script>

@endsection
