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
            <label class="layui-form-label">币种总额</label>
            <div class="layui-input-inline">
                <input type="text" name="total" autocomplete="off" class="layui-input"
                       value="{{$air_date['total']}}">
            </div>
        </div>
        <div class="layui-form" style="margin-top: 15px">
            <label class="layui-form-label">充值币种</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <select name="cur_currency" id="cur_currency" class="layui-input">
                        <option value="">所有币种</option>
                        @foreach ($currency_type as $key=>$type)
                            <option {{$air_date['cur_currency'] == $type['id'] ? 'selected' : ''}} value="{{ $type['id'] }}" class="ww">{{ $type['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form" style="margin-top: 15px">
            <label class="layui-form-label">空投币种</label>
            <div class="layui-input-block">
                <div class="layui-input-inline">
                    <select name="air_curency" id="air_curency" class="layui-input">
                        <option value="">所有币种</option>
                        @foreach ($currency_type as $key=>$type)
                            <option {{$air_date['air_curency'] == $type['id'] ? 'selected' : ''}} value="{{ $type['id'] }}" class="ww">{{ $type['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="layui-form" style="margin-top: 15px">
            <label class="layui-form-label">开始时间</label>
            <div class="layui-input-inline">
                <input type="text" name="stime" id="start_time" placeholder="请输入开始时间" autocomplete="off" class="layui-input" value="{{$air_date['stime']}}">
            </div>
        </div>
        <div class="layui-form" style="margin-top: 15px;margin-bottom: 20px">
            <label class="layui-form-label">结束时间</label>
            <div class="layui-input-inline">
                <input type="text" name="endTime" id="end_time" placeholder="请输入开始时间" autocomplete="off" class="layui-input" value="{{$air_date['endTime']}}">
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

            laydate.render({
                elem: '#start_time',
                type:"datetime"
            });
            laydate.render({
                elem: '#end_time',
                type:"datetime"
            });
            form.on('submit(website_submit)', function (data) {
                console.log(data.field)
                var data = data.field;
                $.ajax({
                    url: '/admin/airdrop/edit',
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
