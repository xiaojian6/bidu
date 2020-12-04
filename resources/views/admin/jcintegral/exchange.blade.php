@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')   
    <table id="data_table" lay-filter="data_table"></table>
@endsection

@section('scripts')
    <script>
        layui.use(['table', 'form', 'layer'], function(){
            var table = layui.table
                ,layer = layui.layer
                ,$ = layui.$
                ,form = layui.form
            //第一个实例
            table.render({
                elem: '#data_table'
                ,url: '{{url('admin/jcintegral/exchange_list')}}' //数据接口
                ,page: true //开启分页
                ,id: 'data_table'
                ,toolbar: '#topbar'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:90, sort: true}
                    ,{field: 'account_number', title: '用户账号', width:120}
                    ,{field: 'integral', title: '使用积分', width:120}
                    ,{field: 'integral_price', title: '积分价值', width: 120}
                    ,{field: 'fee_ratio', title: '手续费率', width:120}
                    ,{field:'fee', title:'手续费', width:120}
                    ,{field:'market_price', title:'行情价格', width:140}
                    ,{field:'fact_market_price', title:'点差价格', width:140}
                    ,{field:'exchange_num', title:'兑换数量', width:140}
                    ,{field: 'created_at', title: '兑换时间', width:170, hide: true}
                    ,{field: 'updated_at', title: '修改时间', width:170, hide: true}
                ]]
            });
        });
    </script>

@endsection