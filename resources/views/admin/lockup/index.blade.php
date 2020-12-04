@extends('admin._layoutNew')
@section('page-head')
    <style>
        [hidden] {
            display: none;
        }
        .layui-form-label {
            width: 150px;
        }
    </style>
@stop
@section('page-content')
    <form class="layui-form col-lg-5">
        <div class="layui-form" style="margin-top: 15px;display: none">
            <label class="layui-form-label">当前ID（隐藏中）</label>
            <div class="layui-input-inline">
                <input type="text" name="id" autocomplete="off" class="layui-input"
                       value="{{$air_date['id']}}">
            </div>
        </div>
        <div class="layui-form" style="margin-top: 15px">
            <label class="layui-form-label">交易额度设置</label>
            <div class="layui-input-inline">
                <input type="text" name="t_precent" autocomplete="off" class="layui-input"
                       value="{{$air_date['t_precent']}}">
            </div>
        </div>
        <div class="layui-form" style="margin-top: 15px">
            <label class="layui-form-label">余额年化率</label>
            <div class="layui-input-inline">
                <input type="text" name="t_rate" autocomplete="off" class="layui-input"
                       value="{{$air_date['t_rate']}}">
            </div>
        </div>
        <div class="layui-form-item" style="margin-top: 30px">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="website_submit">修改</button>
            </div>
        </div>
    </form>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        layui.use(['element', 'form', 'layer', 'table','laydate'], function () {
            var element = layui.element;
            var layer = layui.layer;
            var table = layui.table;
            var $ = layui.$;
            var form = layui.form;
            var laydate = layui.laydate;

            form.on('submit(website_submit)', function (data) {
                console.log(data.field)
                var data = data.field;
                $.ajax({
                    url: '/admin/lockup/edit',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (res) {
                        layer.msg(res.message);
                    }
                });
                return false;
            });
        });
    </script>
@stop
