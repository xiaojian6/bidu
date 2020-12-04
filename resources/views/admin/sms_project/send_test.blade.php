@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" autocomplete="off" placeholder="请输入测试的手机号" class="layui-input" value="" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">验证码</label>
            <div class="layui-input-block">
                <input type="text" name="code" autocomplete="off" placeholder="请输入测试的验证码" class="layui-input layui-input-inline" value="" >
            </div>
        </div>
        <input type="hidden" name="id" value="{{$res->id}}">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="send">发送</button>
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
            form.on('submit(send)', function(data){
                var data = data.field;
                $.ajax({
                    url:'{{url('admin/sms_project/send_sms')}}'
                    ,type:'post'
                    ,dataType:'json'
                    ,data : data
                    ,success:function(res){
                        if(res.type=='error'){
                            layer.msg(res.message);
                        }else{
                            layer.msg(res.message)
                        }
                    }
                });
                return false;
            });
        });
    </script>

@endsection