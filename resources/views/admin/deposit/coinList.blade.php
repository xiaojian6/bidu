@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <table id="userlist" lay-filter="userlist"></table>
@endsection
        {{--<div class="layui-inline layui-row">--}}
            {{--<button class="layui-btn btn-search"  id="all_record" lay-submit lay-filter="all_record" style="margin-top: 15px;margin-left: 10px"> 所有记录 </button>--}}
        {{--</div>--}}
        {{--<div class="layui-inline layui-row">--}}
            {{--<button class="layui-btn btn-search"  id="regular_record" lay-submit lay-filter="regular_record" style="margin-top: 15px;margin-left: 10px"> 定期记录 </button>--}}
        {{--</div>--}}
        {{--<div class="layui-inline layui-row">--}}
            {{--<button class="layui-btn btn-search"  id="current_record" lay-submit lay-filter="current_record" style="margin-top: 15px;margin-left: 10px"> 活期记录 </button>--}}
        {{--</div>--}}
@section('scripts')
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="edit">编辑</a>
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
            {{--//活期--}}
            {{--form.on('submit(current_record)',function(){--}}
                {{--tbRend("{{url('/admin/deposit/current_deposit_list')}}");--}}
                {{--return false;--}}
            {{--});--}}
            {{--//定期--}}
            {{--form.on('submit(regular_record)',function(){--}}
                {{--tbRend("{{url('/admin/deposit/regular_deposit_list')}}");--}}
                {{--return false;--}}
            {{--});--}}
            {{--//所有--}}
            {{--form.on('submit(all_record)',function(){--}}
                {{--tbRend("{{url('/admin/deposit/deposit_list')}}");--}}
                {{--return false;--}}
            {{--});--}}
            function tbRend(url) {
                table.render({
                    elem: '#userlist'
                    ,toolbar: true
                    ,url: url
                    ,page: true
                    ,limit: 20
                    ,height: 'full-60'
                    ,cols: [[
                        {field:'currency', title:'币种', width: 150}
                        ,{field:'config', title:'简介', width: 120}
                        ,{field:'type', title:'类型', width: 120}
                        ,{fixed: 'right', title: '操作', width: 120, align: 'center', toolbar: '#barDemo'}
                    ]]
                });
            }
            tbRend("{{url('/admin/deposit/coin_list')}}");

            //监听工具条
            table.on('tool(#userlist)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                var data = obj.data;
                var layEvent = obj.event;
                var tr = obj.tr;
                if (layEvent === 'delete') { //删除
                    layer.confirm('真的要删除吗？', function (index) {
                        //向服务端发送删除指令
                        $.ajax({
                            url: "{{url('admin/user/del')}}",
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
                } else  (layEvent === 'edit')
                { //编辑
                    layer_show('编辑会员','{{url('admin/user/edit')}}?id='+data.id);
                }
            });
        });
    }
</script>
@endsection
