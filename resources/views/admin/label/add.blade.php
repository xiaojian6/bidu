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

            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <div class="layui-form-item">
                        <label class="layui-form-label">标签名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->name}}">
                        </div>
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
                url:'{{url('admin/label_add')}}'
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