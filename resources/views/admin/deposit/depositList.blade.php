@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <table id="userlist" lay-filter="userlist"></table>
@endsection
        <div class="layui-inline layui-row">
            <button class="layui-btn btn-search"  id="all_record" lay-submit lay-filter="all_record" style="margin-top: 15px;margin-left: 10px"> 所有记录 </button>
        </div>
        <div class="layui-inline layui-row">
            <button class="layui-btn btn-search"  id="regular_record" lay-submit lay-filter="regular_record" style="margin-top: 15px;margin-left: 10px"> 定期记录 </button>
        </div>
        <div class="layui-inline layui-row">
            <button class="layui-btn btn-search"  id="current_record" lay-submit lay-filter="current_record" style="margin-top: 15px;margin-left: 10px"> 活期记录 </button>
        </div>
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
            //活期
            form.on('submit(current_record)',function(){
                tbRend("{{url('/admin/deposit/current_deposit_list')}}");
                return false;
            });
            //定期
            form.on('submit(regular_record)',function(){
                tbRend("{{url('/admin/deposit/regular_deposit_list')}}");
                return false;
            });
            //所有
            form.on('submit(all_record)',function(){
                tbRend("{{url('/admin/deposit/deposit_list')}}");
                return false;
            });
            function tbRend(url) {
                table.render({
                    elem: '#userlist'
                    ,toolbar: true
                    ,url: url
                    ,page: true
                    ,limit: 20
                    ,height: 'full-60'
                    ,cols: [[
                        {field:'account_number', title:'存币账户', width: 150}
                        ,{field:'num', title:'存币数量', width: 120}
                        ,{field:'name', title:'存币币种', width: 90}
                        // ,{field:'ex_currency', title:'发放币种', width: 90}
                        ,{field:'desc', title:'存币周期', width: 120}
                        ,{field:'percent', title:'存币利率', width: 120}
                        ,{field:'type', title:'存币类型', width: 120}
                        ,{field:'create_time', title:'存币时间', width: 170}
                        ,{field:'limit_time', title:'结束时间', width: 170}
                        ,{field:'income', title:'总收益', width: 90}
                        ,{field:'status', title:'存币状态', width: 90}
                    ]]
                });
            }
            tbRend("{{url('/admin/deposit/deposit_list')}}");
        });
    }
</script>
@endsection
