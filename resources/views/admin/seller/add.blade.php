@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
<form class="layui-form" action="">

    <div class="layui-tab">
        <ul class="layui-tab-title">
            <li class="layui-this">基础资料</li>
            <li>收款信息</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">

                <div class="layui-form-item">
                    <label class="layui-form-label">交易账号</label>
                    <div class="layui-input-block">
                        <input type="text" name="account_number" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->account_number}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->name}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商家电话</label>
                    <div class="layui-input-block">
                        <input type="text" name="mobile" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->mobile}}">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">选择法币</label>
                    <div class="layui-input-block">
                        <select name="currency_id" lay-filter="type">
                            @foreach($currencies as $currency)
                            <option value="{{$currency->id}}" @if($result->currency_id == $currency->id) selected @endif>{{$currency->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">商家余额</label>
                    <div class="layui-input-block">
                        <input type="number" class="layui-input" id="reserve_time" name="seller_balance" value="{{$result->seller_balance}}">
                    </div>
                </div>

            </div>
            <div class="layui-tab-item">

                <div class="layui-collapse" lay-accordion>
                    <div class="layui-colla-item">
                        <h2 class="layui-colla-title">微信</h2>
                        <div class="layui-colla-content">
                            <div class="layui-form-item">
                                <label class="layui-form-label">微信昵称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="wechat_nickname" autocomplete="off" placeholder="" class="layui-input" value="{{$result->wechat_nickname}}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">微信账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="wechat_account" autocomplete="off" placeholder="" class="layui-input" value="{{$result->wechat_account}}">
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label">微信收款码</label>
                                <div class="layui-input-block">
                                    <button class="layui-btn upload_btn" type="button">选择图片</button>
                                    <br>
                                    <img src="@if(!empty($result->wechat_collect)){{$result->wechat_collect}}@endif" class="thumbnail" style="display: @if(!empty($result->wechat_collect)){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                                    <input type="hidden" class="thumbnail_input" name="wechat_collect" id="wechat_collect" value="@if(!empty($result->wechat_collect)){{$result->wechat_collect}}@endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-colla-item">
                        <h2 class="layui-colla-title">支付宝</h2>
                        <div class="layui-colla-content">
                            <div class="layui-form-item">
                                <label class="layui-form-label">支付宝昵称</label>
                                <div class="layui-input-block">
                                    <input type="text" name="ali_nickname" autocomplete="off" placeholder="" class="layui-input" value="{{$result->ali_nickname}}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">支付宝账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="ali_account" autocomplete="off" placeholder="" class="layui-input" value="{{$result->ali_account}}">
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label">支付宝收款码</label>
                                <div class="layui-input-block">
                                    <button class="layui-btn upload_btn" type="button">选择图片</button>
                                    <br>
                                    <img src="@if(!empty($result->alipay_collect)){{$result->alipay_collect}}@endif" class="thumbnail" style="display: @if(!empty($result->alipay_collect)){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                                    <input type="hidden" class="thumbnail_input" name="alipay_collect" id="alipay_collect" value="@if(!empty($result->alipay_collect)){{$result->alipay_collect}}@endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-colla-item">
                        <h2 class="layui-colla-title">银行卡</h2>
                        <div class="layui-colla-content">
                            <div class="layui-form-item">
                                <label class="layui-form-label">选择银行</label>
                                <div class="layui-input-block">
                                    <select name="bank_id" lay-filter="type">
                                        @foreach($banks as $bank)
                                        <option value="{{$bank->id}}" @if($result->bank_id == $bank->id) selected @endif>{{$bank->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">开户行</label>
                                <div class="layui-input-block">
                                    <input type="text" name="bank_address" autocomplete="off" placeholder="" class="layui-input" value="{{$result->bank_address}}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">银行账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="bank_account" autocomplete="off" placeholder="" class="layui-input" value="{{$result->bank_account}}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <input type="hidden" name="id" value="{{$result->id}}">
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
    layui.use(['form', 'laydate', 'element', 'upload'], function() {
        var element = layui.element,
            form = layui.form,
            $ = layui.jquery,
            laydate = layui.laydate,
            upload = layui.upload,
            index = parent.layer.getFrameIndex(window.name);
            uploadInst = upload.render({
                elem: '.upload_btn' //绑定元素
                ,url: '/api/upload' //上传接口
                ,done: function(res, index, upload) {
                    //上传完毕回调
                    if (res.type == "ok") {
                        var img = $(this.item).nextAll('img.thumbnail')
                        img.next(".thumbnail_input").val(res.message);
                        img.attr("src",res.message).show();
                    } else{
                        alert(res.message)
                    }
                }
                ,error: function(){
                    //请求异常回调
                }
            }); 
        //监听提交
        form.on('submit(demo1)', function(data) {
            var data = data.field;
            $.ajax({
                url: '/admin/seller_add',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(res) {
                    if (res.type == 'error') {
                        layer.msg(res.message);
                    } else {
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