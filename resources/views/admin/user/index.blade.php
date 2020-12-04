@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div class="layui-inline layui-row">
        <label class="layui-form-label">用户帐号</label>
        <div class="layui-input-inline">
            <input type="text" name="account" placeholder="用户手机号或邮箱" autocomplete="off" class="layui-input" value="">
        </div>
        <button class="layui-btn btn-search" id="mobile_search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
    </div>
    <button class="layui-btn layui-btn-normal" onclick="javascrtpt:window.location.href='{{url('/admin/user/csv')}}'">
        <i class="layui-icon layui-icon-export"></i>
        <span>导出用户</span>
    </button>
    <button class="layui-btn layui-btn-primary" id="user_relation">
        <i class="layui-icon layui-icon-user"></i>
        <span>推荐关系</span>
    </button>
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
        document.onkeydown=function(event) {
            var e = event || window.event || arguments.callee.caller.arguments[0];
            if(e && e.keyCode==13) { // enter 键
                $('#mobile_search').click();
            }
        };
        layui.use(['element', 'form', 'layer', 'table'], function () {
            var element = layui.element
                ,layer = layui.layer
                ,table = layui.table
                ,$ = layui.$
                ,form = layui.form
            //会员关系查看
            $('#user_relation').click(function () {
                parent.layer.open({
                    type: 2
                    ,title: '会员关系查看'
                    ,content: '/admin/invite/childs'
                    ,area: ['600px', '800px']
                    ,maxmin: true
                    ,shade: 0.4
                    ,zIndex: parent.layer.zIndex
                });
            });
            /*$('#add_user').click(function(){layer_show('添加会员', '/admin/user/add');});*/

            form.on('submit(mobile_search)',function(obj){
                var account =  $("input[name='account']").val();

                tbRend("{{url('/admin/user/list')}}?account="+account);
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
                        {field: 'id', title: 'ID', width: 100}
                        ,{field:'account_number', title:'交易账号', width: 200}
                        ,{field:'country_code', title:'国际区号', width: 90}
                        ,{field:'phone', title:'手机号', width: 120}
                        ,{field:'email', title:'邮箱', width: 150, hide: true}
                        ,{field:'nationality', title:'国籍', width: 130, hide: true}
                        ,{field:'card_id', title: '身份证号',width:  180, hide: true}
                        // ,{field: 'candy_number', title: '通证数量', width:  130}
                        // ,{field:'top_upnumber', title:'团队充值业绩', width: 130}
                        // ,{field:'zhitui_real_number', title:'直推实名人数', width: 130}
                        // ,{field:'real_teamnumber', title:'团队实名人数', width: 130}
                        // ,{field:'status', title:'工作室', width: 90, templet: '#isAtelier'}
                        // ,{field:'type', title: '积分兑换', width:  100, templet: '#allowExchange'}
                        ,{field:'status', title:'是否锁定', width: 90, templet: '#switchTpl'}
                        
                        ,{field:'time', title:'注册时间', width: 170} 
                        ,{fixed: 'right', title: '操作', width: 220, align: 'center', toolbar: '#barDemo'}
                    ]]
                });
            }
            tbRend("{{url('/admin/user/list')}}");

            //监听锁定操作
            form.on('switch(status)', function(obj){
                var id = this.value;
                
                $.ajax({
                    url:'{{url('admin/user/lock')}}',
                    type:'post',
                    dataType:'json',
                    data:{id:id},
                    success:function (res) {
                        layer.msg(res.message);
                        
                    }
                });
            });

            form.on('switch(allowExchange)', function (obj) {
                console.log(obj);
                var id = this.value;
                $.ajax({
                    url: '/admin/user/allow_exchange',
                    type:'post',
                    dataType:'json',
                    data:{id: id},
                    success:function (res) {
                        layer.msg(res.message);
                    }
                });
            });

            //监听工具条
            table.on('tool(userlist)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
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
                } else if (layEvent === 'edit'){ //编辑
                    layer_show('编辑会员','{{url('admin/user/edit')}}?id='+data.id);
                } else if (layEvent === 'users_wallet') {
                    var index = layer.open({
                        title: '钱包管理'
                        , type: 2
                        , content: '{{url('/admin/user/users_wallet')}}?id=' + data.id
                        , maxmin: true
                    });
                    layer.full(index);
                } else if (layEvent == 'candy_change') {
                    var index = layer.open({
                        title: '通证调节'
                        , type: 2
                        , content: '/admin/user/candy_conf/' + data.id
                        , maxmin: true
                    });
                    layer.full(index);
                }
            });
        });
    }
</script>
@endsection