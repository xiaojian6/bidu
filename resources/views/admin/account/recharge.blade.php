@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div style="margin-top: 10px;width: 100%;margin-left: 0px;">
        <div class="layui-form-item">
            <label class="layui-form-label">充币合计</label>
            <div class="layui-input-block" style="width:90%">
                <blockquote class="layui-elem-quote layui-quote-nm" id="sum">0</blockquote>
            </div>
        </div>
        <form class="layui-form layui-form-pane layui-inline" action="">
            <div class="layui-inline">
                <label class="layui-form-label">币种&nbsp;&nbsp;</label>
                <div class="layui-input-inline" style="width:130px;">
                    <select name="currency" id="type_type">
                        <option value="-1" class="ww">全部</option>
                        @foreach ($currencies as $currency)
                        <option value="{{$currency->id}}" class="ww">{{$currency->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">用户账号&nbsp;&nbsp;</label>
                <div class="layui-input-inline" style="width:130px;">
                    <input type="text" name="account_number" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">开始日期：</label>
                <div class="layui-input-inline" style="width:120px;">
                    <input type="text" class="layui-input" id="start_time" value="" name="start_time">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">结束日期：</label>
                <div class="layui-input-inline" style="width:120px;">
                    <input type="text" class="layui-input" id="end_time" value="" name="end_time">
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit="" lay-filter="search"><i class="layui-icon">&#xe615;</i></button>
                </div>
            </div>
        </form>
    </div>
    <table id="data_table" lay-filter="data_table"></table>
    @endsection
    @section('scripts')
    <script>
    layui.use(['table','form','laydate'], function(){
        var table = layui.table
            ,$ = layui.jquery
            ,form = layui.form
            ,laydate = layui.laydate
            ,layer = layui.layer
        //第一个实例
        laydate.render({
            elem: '#start_time'
        });
        laydate.render({
            elem: '#end_time'
        });
        //第一个实例
        var data_table = table.render({
            elem: '#data_table'
            ,url: '/admin/account/recharge/lists'
            ,page: true
            ,cols: [[
                {field: 'id', title: 'id', width: 90}
                ,{field: 'user_id', title: '用户id', width: 70}
                ,{field: 'account_number', title: '账号', width: 120}
                ,{field: 'currency_name', title: '币种', width: 120}
                ,{field: 'before',title:'充值前', width:100}
                ,{field: 'value', title: '充值金额', width: 120}
                ,{field: 'after',title:'充值后', width:100}
                ,{field: 'created_time', title: '充值时间', width: 180}
            ]]  , done: function(res){
                $("#sum").text(res.extra_data);
            }
        })

        //监听提交
        form.on('submit(search)', function(data) {
            table.reload('data_table', {
                where: data.field,
                page: {curr: 1}//重新从第一页开始
            });
            return false;
        });

    });
    </script>
@endsection