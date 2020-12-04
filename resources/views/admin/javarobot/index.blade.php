@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <table id="data_table" lay-filter="data_table"></table>
    <script type="text/html" id="operate_bar">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
        <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="cancel">撤单</a>
    </script>
    <script type="text/html" id="topbar">
        <div class="layui-inline" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
        </div>
    </div>
    </script>
    <script type="text/html" id="is_start">
        <input type="checkbox" name="" value="@{{ d.id }}" lay-skin="switch" lay-text="开启|停止" lay-filter="is_start" @{{ d.is_start == 1 ? 'checked' : '' }}>
    </script>
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
                ,url: '{{url('admin/javarobot/lists')}}' //数据接口
                ,page: true //开启分页
                ,toolbar: '#topbar'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'sell_account', title: '卖家', width:120}
                    ,{field: 'buy_account', title: '买家', width:120}
                    ,{field: 'currency_name', title: '交易币', width:100}
                    ,{field:'legal_name', title:'法币', width:100}
                    ,{field:'min_range', title:'最小浮动', width:100}
                    ,{field: 'max_range', title: '最大浮动', width:100}
                    ,{field: 'min_number', title: '最小数量', width:100}
                    ,{field: 'max_number', title: '最大数量', width:100}
                    ,{field: 'min_need_second', title: '最小频率', width:100}
                    ,{field: 'max_need_second', title: '最大频率', width:100}
                    ,{field: 'is_start', title: '状态', width:100, templet: '#is_start'}
                    ,{title:'操作', minWidth:200, toolbar: '#operate_bar'}
                ]]
            });

            form.on('switch(is_start)', function(data) {
                var symbol = data.elem.checked ? 'auto_start' : 'auto_end';
                var id = data.value
                $.ajax({
                    url: '/admin/javarobot/change_start'
                    ,type: 'POST'
                    ,data: {id: id, symbol: symbol}
                    ,success: function (res) {
                        layer.msg(res.message);
                    }
                    ,error: function (res) {
                        layer.msg('网络错误');
                    }
                });
                
            })

            table.on('toolbar(data_table)', function(obj) {
                switch(obj.event) {
                    case 'add':
                        layer_show('添加机器人','{{url('admin/javarobot/add')}}', 720, 740)
                        break;
                }
            });

            table.on('tool(data_table)', function(obj) {
                var data = obj.data;
                if (obj.event === 'del') { //删除
                    layer.confirm('真的要删除吗？', function (index) {
                        //向服务端发送删除指令
                        $.ajax({
                            url: "{{url('admin/javarobot/del')}}",
                            type: 'post',
                            dataType: 'json',
                            data: {id: data.id},
                            success: function (res) {
                                if (res.type == 'ok') {
                                    obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                                    layer.close(index);
                                } else {
                                    layer.close(index);
                                    layer.alert(res.message);
                                }
                            }
                        });
                    });
                } else if(obj.event === 'edit') {
                    layer_show('添加机器人','{{url('admin/javarobot/add')}}?id='+data.id, 720, 740);
                } else if (obj.event === 'cancel') {
                    layer.confirm('真的要撤销所有交易吗？', function (index) {
                        //向服务端发送撤单指令
                        $.ajax({
                            url: "{{url('admin/javarobot/cancel')}}",
                            type: 'post',
                            dataType: 'json',
                            data: {id: data.id},
                            success: function (res) {
                                if (res.type == 'ok') {
                                    layer.close(index);
                                    layer.msg(res.message);
                                } else {
                                    layer.close(index);
                                    layer.alert(res.message);
                                }
                            }
                        });
                    });
                }
            });
        });
    </script>

@endsection