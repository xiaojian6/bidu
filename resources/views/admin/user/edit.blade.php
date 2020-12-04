@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
<form class="layui-form" action="">
    <div class="layui-tab">
        <ul class="layui-tab-title">
            <li class="layui-this">基础信息</li>
            <li>用户资料</li>
            <li>收款信息</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <div class="layui-form-item">
                    <label class="layui-form-label">账号</label>
                    <div class="layui-input-block">
                        <input type="text" name="account_number" autocomplete="off" placeholder="" class="layui-input" value="{{$result->account_number}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">手机号</label>
                    <div class="layui-input-block">
                        <input type="text" name="phone" autocomplete="off" placeholder="" class="layui-input" value="{{$result->phone}}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-block">
                        <input type="text" name="email" autocomplete="off" placeholder="" class="layui-input" value="{{$result->email}}" disabled>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-block">
                        <input type="text" name="password" autocomplete="off" placeholder="" class="layui-input" value="">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">交易密码</label>
                    <div class="layui-input-block">
                        <input type="text" name="pay_password" autocomplete="off" placeholder="" class="layui-input" value="">
                    </div>
                </div>
            </div>
            <div class="layui-tab-item">
                <div class="layui-form-item">
                    <label class="layui-form-label">真实姓名</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" autocomplete="off" placeholder="" class="layui-input" value="{{$result->name ?? ''}}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">身份证号</label>
                    <div class="layui-input-block">
                        <input type="text" name="card_id" autocomplete="off" placeholder="" class="layui-input" value="{{$result->card_id ?? ''}}" @if(empty($result->card_id)) disabled @endif>
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
                                    <input type="text" name="wechat_nickname" autocomplete="off" placeholder="" class="layui-input" value="{{$cashinfo->wechat_nickname}}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">微信账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="wechat_account" autocomplete="off" placeholder="" class="layui-input" value="{{$cashinfo->wechat_account}}">
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label">微信收款码</label>
                                <div class="layui-input-block">
                                    <button class="layui-btn upload_btn" type="button">选择图片</button>
                                    <br>
                                    <img src="@if(!empty($cashinfo->wechat_collect)){{$cashinfo->wechat_collect}}@endif" class="thumbnail" style="display: @if(!empty($cashinfo->wechat_collect)){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                                    <input type="hidden" class="thumbnail_input" name="wechat_collect" id="wechat_collect" value="@if(!empty($cashinfo->wechat_collect)){{$cashinfo->wechat_collect}}@endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-colla-item">
                        <h2 class="layui-colla-title">支付宝</h2>
                        <div class="layui-colla-content">
                            <div class="layui-form-item">
                                <label class="layui-form-label">支付宝账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="alipay_account" autocomplete="off" placeholder="" class="layui-input" value="{{$cashinfo->alipay_account}}">
                                </div>
                            </div>
                            <div class="layui-form-item layui-form-text">
                                <label class="layui-form-label">支付宝收款码</label>
                                <div class="layui-input-block">
                                    <button class="layui-btn upload_btn" type="button">选择图片</button>
                                    <br>
                                    <img src="@if(!empty($cashinfo->alipay_collect)){{$cashinfo->alipay_collect}}@endif" class="thumbnail" style="display: @if(!empty($cashinfo->alipay_collect)){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                                    <input type="hidden" class="thumbnail_input" name="alipay_collect" id="alipay_collect" value="@if(!empty($cashinfo->alipay_collect)){{$cashinfo->alipay_collect}}@endif">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-colla-item">
                        <h2 class="layui-colla-title">银行卡</h2>
                        <div class="layui-colla-content">
                            <div class="layui-form-item">
                                <label class="layui-form-label">开户行</label>
                                <div class="layui-input-block">
                                    <input type="text" name="bank_name" autocomplete="off" placeholder="" class="layui-input" value="{{$cashinfo->bank_name}}">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">银行账号</label>
                                <div class="layui-input-block">
                                    <input type="text" name="bank_account" autocomplete="off" placeholder="" class="layui-input" value="{{$cashinfo->bank_account}}">
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
    layui.use(['element', 'form', 'laydate', 'upload'], function() {
        var form = layui.form,
            $ = layui.jquery,
            laydate = layui.laydate,
            upload = layui.upload,
            element = layui.element
        var index = parent.layer.getFrameIndex(window.name)
            ,uploadInst = upload.render({
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
                url: '/admin/user/edit',
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