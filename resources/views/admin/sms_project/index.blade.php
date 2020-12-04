@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')   
    <table id="data_table" lay-filter="data_table"></table>
    <script type="text/html" id="operate_bar">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script type="text/html" id="topbar">
        <div class="layui-inline" lay-event="add">
            <i class="layui-icon layui-icon-add-1"></i>
        </div>
    </script>
    <script type="text/html" id="is_default">
        <input type="checkbox" name="is_default" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="is_default" @{{ d.is_default == '1' ? 'checked' : '' }} disabled>
    </script>

    <script type="text/html" id="status">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="启用|禁用" lay-filter="status" @{{ d.status == 1 ? 'checked' : '' }} disabled>
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
                ,url: '{{url('admin/sms_project/lists')}}' //数据接口
                ,page: true //开启分页
                ,id: 'data_table'
                ,toolbar: '#topbar'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'project', title: '模板编号', width:100}
                    ,{field: 'scene_name', title: '场景', width:120}
                    ,{field: 'region_name', title: '区域', width:120}
                    ,{field:'status', title:'状态', width:100, templet: '#status', unresize: true}
                    ,{field:'is_default', title:'是否默认', width:100, templet: '#is_default', unresize: true}
                    ,{field: 'created_date', title: '添加时间', width:170, hide: true}
                    ,{field: 'updated_date', title: '修改时间', width:170, hide: true}
                    ,{title:'操作', minWidth:100, toolbar: '#operate_bar'}
                ]]
            });

            table.on('toolbar(data_table)', function(obj) {
                switch(obj.event) {
                    case 'add':
                        layer_show('添加模板','{{url('admin/sms_project/add')}}', 560, 520)
                        break;
                }
            });

            table.on('tool(data_table)', function(obj) {
                var data = obj.data;
                if (obj.event === 'del') { //删除
                    layer.confirm('真的要删除吗？', function (index) {
                        //向服务端发送删除指令
                        $.ajax({
                            url: "{{url('admin/sms_project/del')}}",
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
                } else if(obj.event === 'edit'){
                    layer_show('编辑短信模板','{{url('admin/sms_project/edit')}}?id='+data.id, 560, 520);
                }
            });
        });
    </script>

@endsection