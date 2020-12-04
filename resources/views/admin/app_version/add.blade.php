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
                <li class="layui-this">app版本</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <div class="layui-form-item">
                        <label class="layui-form-label">版本名</label>
                        <div class="layui-input-inline">
                            <input type="text" name="version_name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->version_name}}">
                        </div>
                        <div class="layui-form-mid layui-word-aux">请确保版本名是惟一的</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">版本名</label>
                        <div class="layui-input-inline">
                            <input type="number" name="version_num" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->version_num}}">
                        </div>
                        <div class="layui-form-mid layui-word-aux">请确保版本号是惟一的</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">操作系统</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-verify="required">
                                <option value="1" @if($result->type ==1) selected @endif>Android</option>
                                <option value="2" @if($result->type ==2) selected @endif>IOS</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">wgt包地址</label>
                        <div class="layui-input-block">
                            <input type="text" name="wgt_url" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->wgt_url}}">
                        </div>

                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">pkg地址</label>
                        <div class="layui-input-block">
                            <input type="text" name="pkg_url" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->pkg_url}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">下载页面</label>
                        <div class="layui-input-block">
                            <input type="text" name="down_url" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="{{$result->down_url}}">
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
        //监听提交
        form.on('submit(demo1)', function(data){
            var data = data.field;
            $.ajax({
                url:'{{url('admin/app_version_add')}}'
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

    });
</script>

@endsection