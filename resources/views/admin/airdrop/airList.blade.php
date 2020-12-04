@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <table id="userlist" lay-filter="userlist"></table>
@endsection

@section('scripts')
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="users_wallet">钱包管理</a>
    <!-- <a class="layui-btn layui-btn-xs layui-btn-normal" lay-event="candy_change">通证调节</a> -->
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
</script>
<script type="text/html" id="switchTpl">
    <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="status" @{{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="isAtelier">
    <input type="checkbox" name="is_atelier" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="is_atelier" @{{ d.is_atelier == 1 ? 'checked' : '' }} disabled>
</script>
<script type="text/html" id="allowExchange">
    <input type="checkbox" name="type" value="@{{d.id}}" lay-skin="switch" lay-text="开启|关闭" lay-filter="allowExchange" @{{ d.type == 1 ? 'checked' : '' }} >
</script>
<script>
    window.onload = function() {
        layui.use(['element', 'form', 'layer', 'table'], function () {
            var element = layui.element
                ,layer = layui.layer
                ,table = layui.table
                ,$ = layui.$
                ,form = layui.form

            function tbRend(url) {
                table.render({
                    elem: '#userlist'
                    ,toolbar: true
                    ,url: url
                    ,page: true
                    ,limit: 20
                    ,height: 'full-60'
                    ,cols: [[
                        {field: 'id', title: 'ID', width: 100}
                        ,{field:'account_number', title:'账号', width: 200}
                        ,{field:'total', title:'充值总量', width: 90}
                        ,{field:'createtime', title:'时间', width: 120}
                        ,{field:'sort', title:'排名', width: 120}
                    ]]
                });
            }
            tbRend("{{url('/admin/airdrop/airdrop_list')}}");
        });
    }
</script>
@endsection
