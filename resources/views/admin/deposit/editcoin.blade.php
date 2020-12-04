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
    <form class="layui-form col-lg-5" id="adddir">
        <div class="layui-form" style="margin-top: 15px;margin-bottom: 20px">
            <label class="layui-form-label">定期币种</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <select name="fixed" id="fixed" class="layui-input">
                        <option value="">所有币种</option>
                        @foreach ($currency_type as $key=>$type)
                            <option {{$fixed['currency'] == $type['id'] ? 'selected' : ''}} value="{{ $type['id'] }}" class="ww">{{ $type['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form" style="margin-top: 15px;margin-bottom: 20px">
            <label class="layui-form-label">活期币种</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <select name="current" id="current" class="layui-input">
                        <option value="">所有币种</option>
                        @foreach ($currency_type as $key=>$type)
                            <option {{$current['currency'] == $type['id'] ? 'selected' : ''}} value="{{ $type['id'] }}" class="ww">{{ $type['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="website_submit">保存</button>
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
                    url: '/admin/deposit/edit',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (res) {
                        layer.msg(res.message);
                        $("#adddir")[0].reset();
                    }
                });
                return false;
            });
        });
    </script>
@stop
