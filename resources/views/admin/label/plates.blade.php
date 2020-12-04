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
            <button class="layui-btn layui-btn-sm layui-btn-primary" lay-event="add"> <i class="layui-icon layui-icon-add-1"></i> 添加板块</button>
            
        </div>
    </script>
    
    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="isDisplay" @{{ d.status== 1 ? 'checked' : '' }}>
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
                ,url: '{{url('admin/currency_plates/list')}}' //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:60, sort: true}
                    ,{field: 'name', title: '名称', width: 120}
                    ,{field: 'sorts', title: '顺序', width: 90}
                    ,{field: 'status', title:'是否显示', width: 120, templet: '#switchTpl'}
                    ,{field: 'created_at', title: '添加时间', width:160}
                    ,{field: 'updated_at', title: '修改时间', width:160}
                    ,{title:'操作',width:240,toolbar: '#barDemo'}
                ]]
            });
            //监听是否显示操作
            form.on('switch(isDisplay)', function(obj){
                var id = this.value;
                $.ajax({
                    url:'{{url('admin/currency_plates/is_show')}}',
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
                            title: '添加板块'
                            ,type: 2
                            ,content: '/admin/currency_plates/add'
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
                            url:'{{url('admin/currency_plates/del')}}',
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
                    layer_show('编辑板块','{{url('admin/currency_plates/add')}}?id='+data.id);
                }
            });

        });
    </script>

@endsection