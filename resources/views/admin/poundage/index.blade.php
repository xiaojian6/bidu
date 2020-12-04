@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div style="margin-top: 10px;width: 100%;margin-left: 10px;">
        <div class="layui-form-item">
            <label class="layui-form-label">手续费合计</label>
            <div class="layui-input-block" style="width:90%">
                <blockquote class="layui-elem-quote layui-quote-nm" id="sum">0</blockquote>
            </div>
        </div>

        <form class="layui-form layui-form-pane layui-inline" action="">
            <div class="layui-inline">
                <label class="layui-form-label">交易类型&nbsp;&nbsp;</label>
                <div class="layui-input-inline" style="width:130px;">
                    <select name="type" id="type_type">
                        <option value="-1" class="ww">全部</option>
                        <option value="0" class="ww">C2C交易手续费</option>
                        <option value="1" class="ww">法币交易手续费</option>
                        <option value="2" class="ww">撮合交易手续费</option>
                        <option value="3" class="ww">杠杆交易手续费</option>
                    </select>
                </div>
            </div>
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
                    <input type="text" class="layui-input" id="start_time" value="">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">结束日期：</label>
                <div class="layui-input-inline" style="width:120px;">
                    <input type="text" class="layui-input" id="end_time" value="">
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit="" lay-filter="mobile_search"><i class="layui-icon">&#xe615;</i></button>
                </div>
            </div>
        </form>
    </div>
    <table id="demo" lay-filter="test"></table>
    @endsection
    @section('scripts')
    <script>
    layui.use(['table','form','laydate'], function(){
        var table = layui.table;
        var $ = layui.jquery;
        var form = layui.form;
        var laydate = layui.laydate;
        var layer = layui.layer;
        //第一个实例
        laydate.render({
            elem: '#start_time'
        });
        laydate.render({
            elem: '#end_time'
        });
        //第一个实例
        var data_table = table.render({
            elem: '#demo'
            ,url: '/admin/poundage/lists'
            ,id:'mobileSearch'
            ,page: true
            ,cols: [[
                {field: 'id', title: 'id', width: 90}
                ,{field: 'user_id', title: '用户id', width: 70}
                ,{field: 'account_number', title: '账号', width: 120}
                ,{field: 'value', title: '手续费', width: 120}
                ,{field: 'created_time', title: '创建时间', width: 180}
                ,{field: 'info', title: '备注', width: 220}
                ,{field: 'type', title: '类型', width: 70}
                ,{field: 'transaction_info', title: '交易类型', width: 120}
                ,{field: 'currency', title: '币种', width: 70}
            ]]
            , done: function(res){
                $("#sum").text(res.extra_data);
            }
        })


        //监听提交
        form.on('submit(mobile_search)', function(data){
            var end_time=$('#end_time').val()
                ,start_time = $('#start_time').val()
                ,type = $('#type_type').val()
                ,account_number = data.field.account_number
                ,currency = data.field.currency

            table.reload('mobileSearch',{
                where:{
                    account_number:account_number,
                    start_time:start_time,
                    type:type,
                    end_time:end_time,
                    currency: currency
                },
                page: {curr: 1}         //重新从第一页开始
            });
            return false;
        });

    });
    </script>
@endsection