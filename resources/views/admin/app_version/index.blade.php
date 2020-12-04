@extends('admin._layoutNew')

@section('page-head')
<style>
    p.percent {
        text-align: right;
        margin-right: 10px;
    }
    p.percent::after {
        content: '%';
    }
</style>
@endsection

@section('page-content')
    <table id="demo" lay-filter="test"></table>
@endsection

@section('scripts')
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
    <script type="text/html" id="toolbar">
        <div>
            <button class="layui-btn layui-btn-sm layui-btn-primary" lay-event="add"> <i class="layui-icon layui-icon-add-1"></i> 添加</button>
        </div>
    </script>
    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="is_display" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="isDisplay" @{{ d.is_display == 1 ? 'checked' : '' }}>
    </script>

    <script>
        layui.use(['table','form'], function(){
            var table = layui.table;
            var $ = layui.jquery;
            var form = layui.form;
            //第一个实例
            table.render({
                elem: '#demo'
                ,toolbar: '#toolbar'
                ,url: '{{url('admin/app_version_list')}}' //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:60, sort: true}
                    ,{field: 'type_str', title: '系统类型', width:90}
                    ,{field: 'version_name', title: '版本名称', width:150}
                    ,{field: 'version_num', title: '版本号', width:80}
                    ,{field: 'wgt_url', title: 'wgt包地址', width:180}
                    ,{field: 'pkg_url', title: 'pkg包地址', width:180}
                    ,{field: 'update_type_str', title: '更新类型', width:100}
                    ,{field: 'created_at', title: '添加时间', width:180}
                    ,{title:'操作',width:240,toolbar: '#barDemo'}
                ]]
            });
            //监听是否显示操作
            form.on('switch(isDisplay)', function(obj){
                var id = this.value;
                $.ajax({
                    url:'{{url('admin/currency_display')}}',
                    type:'post',
                    dataType:'json',
                    data:{id:id},
                    success:function (res) {
                        if(res.error != 0){
                            layer.msg(res.message);
                        }
                    }
                });
            });

            table.on('toolbar(test)', function (obj) {
                switch (obj.event) {
                    case 'add':
                        layer.open({
                            title: '添加版本'
                            ,type: 2
                            ,content: '/admin/app_version_add'
                            ,area: ['480px', '650px']
                        });
                        break;
                    default:
                        break;
                }
            });

            table.on('tool(test)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        $.ajax({
                            url:'{{url('admin/app_version_del')}}',
                            type:'post',
                            dataType:'json',
                            data:{id:data.id},
                            success:function (res) {
                                if(res.type == 'error'){
                                    layer.msg(res.message);
                                }else{
                                    obj.del();
                                    layer.close(index);
                                }
                            }
                        });


                    });
                } else if(obj.event === 'edit'){
                    layer_show('编辑','{{url('admin/app_version_add')}}?id='+data.id);
                }
            });

            //监听提交
            form.on('submit(mobile_search)', function(data){
                var account_number = data.field.account_number;
                table.reload('mobileSearch',{
                    where:{account_number:account_number},
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>

@endsection