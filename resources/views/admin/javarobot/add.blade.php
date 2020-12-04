@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">卖方账号</label>
                <div class="layui-input-inline">
                    <input type="text" name="sell_account" lay-verify="required" autocomplete="off" placeholder="卖方账号" class="layui-input" value="{{$result->sell_account ?? ''}}">
                </div>
                <label class="layui-form-label">买方账号</label>
                <div class="layui-input-inline">
                    <input type="text" name="buy_account" lay-verify="required" autocomplete="off" placeholder="买方账号" class="layui-input" value="{{$result->buy_account ?? ''}}">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">交易币</label>
                <div class="layui-input-inline">
                    <select name="currency_id" lay-filter="" lay-search>
                        <option value=""></option>
                        @if(!empty($currencies))
                        @foreach($currencies as $currency)
                        <option value="{{$currency->id}}" @if($currency->id == ($result->currency_id ?? 0)) selected @endif>{{$currency->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <label class="layui-form-label">法币</label>
                <div class="layui-input-inline">
                    <select name="legal_id" lay-filter="">
                        <option value=""></option>
                        @if(!empty($currencies))
                        @foreach($legals as $legal)
                            <option value="{{$legal->id}}" @if($legal->id == ($result->legal_id ?? 0)) selected @endif>{{$legal->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">初始价格</label>
                <div class="layui-input-inline">
                    <input type="text" name="init_price" lay-verify="required" autocomplete="off" placeholder="无行情时取此价格" class="layui-input" value="{{$result->init_price ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
                <label class="layui-form-label">涨幅权重</label>
                <div class="layui-input-inline">
                    <input type="text" name="up_weight" lay-verify="required" autocomplete="off" placeholder="涨幅权重" class="layui-input" value="{{$result->up_weight ?? ''}}">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">价格精度</label>
                <div class="layui-input-inline">
                    <input type="text" name="price_precision" lay-verify="required" autocomplete="off" placeholder="价格精度" class="layui-input" value="{{$result->price_precision ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
                <label class="layui-form-label">数量精度</label>
                <div class="layui-input-inline">
                    <input type="text" name="number_precision" lay-verify="required" autocomplete="off" placeholder="数量精度" class="layui-input" value="{{$result->number_precision ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">价格浮动下限</label>
                <div class="layui-input-inline">
                    <input type="text" name="min_range" lay-verify="required" autocomplete="off" placeholder="价格浮动下限" class="layui-input" value="{{$result->min_range ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
                <label class="layui-form-label">价格浮动上限</label>
                <div class="layui-input-inline">
                    <input type="text" name="max_range" lay-verify="required" autocomplete="off" placeholder="价格浮动上限" class="layui-input" value="{{$result->max_range ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>
        </div>


        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">数量随机下限</label>
                <div class="layui-input-inline">
                    <input type="text" name="min_number" lay-verify="required" autocomplete="off" placeholder="数量随机下限" class="layui-input" value="{{$result->min_number ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
                <label class="layui-form-label">数量随机上限</label>
                <div class="layui-input-inline">
                    <input type="text" name="max_number" lay-verify="required" autocomplete="off" placeholder="数量随机上限" class="layui-input" value="{{$result->max_number ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">最小下单频率</label>
                <div class="layui-input-inline">
                    <input type="text" name="min_need_second" lay-verify="required" autocomplete="off" placeholder="不能低于10" class="layui-input" value="{{$result->min_need_second ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
                <label class="layui-form-label">最大下单频率</label>
                <div class="layui-input-inline">
                    <input type="text" name="max_need_second" lay-verify="required" autocomplete="off" placeholder="最大下单频率" class="layui-input" value="{{$result->max_need_second ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">最大买入用户单量</label>
                <div class="layui-input-inline">
                    <input type="text" name="buy_num" lay-verify="required" autocomplete="off" placeholder="最大买入用户单量" class="layui-input" value="{{$result->buy_num ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
                <label class="layui-form-label">最大卖出用户单量</label>
                <div class="layui-input-inline">
                    <input type="text" name="sell_num" lay-verify="required" autocomplete="off" placeholder="最大卖出用户单量" class="layui-input" value="{{$result->sell_num ?? ''}}">
                </div>
                <div class="layui-form-mid layui-word-aux"></div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">是否撮合用户卖单</label>
                <div class="layui-input-block">
                    <input type="radio" name="buy_deal_user" value="1" title="是"
                        @if (isset($result->buy_deal_user) && $result->buy_deal_user == 1)
                        checked="checked"
                        @endif
                    >
                    <input type="radio" name="buy_deal_user" value="0" title="否"
                        @if (!isset($result->buy_deal_user) || $result->buy_deal_user == 0)
                        checked="checked"
                        @endif
                    >
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">是否撮合用户买单</label>
                <div class="layui-input-block">
                   <input type="radio" name="sell_deal_user" value="1" title="是"
                        @if (isset($result->sell_deal_user) && $result->sell_deal_user == 1)
                        checked="checked"
                        @endif
                   >
                   <input type="radio" name="sell_deal_user" value="0" title="否"
                        @if (!isset($result->sell_deal_user) || $result->sell_deal_user == 0)
                        checked="checked"
                        @endif
                   >
                </div>
            </div>
        </div>
        
        <input type="hidden" name="id" value="{{$result->id ?? ''}}">
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


        layui.use(['form','laydate'],function () {
            var form = layui.form
                ,$ = layui.jquery
                ,laydate = layui.laydate
                ,index = parent.layer.getFrameIndex(window.name);
            //监听提交
            form.on('submit(demo1)', function(data){
                var data = data.field;
                $.ajax({
                    url:'{{url('admin/javarobot/add')}}'
                    ,type:'post'
                    ,dataType:'json'
                    ,data : data
                    ,success:function(res){
                        if(res.type=='error') {
                            layer.msg(res.message);
                        }else{
                            parent.layer.close(index);
                            parent.window.location.reload();
                        }
                    }
                });
                return false;
            });
        });
    </script>

@endsection