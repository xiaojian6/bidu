@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div style="margin-top: 10px;width: 100%;margin-left: 10px;">
        <div class="layui-form-item">
            <label class="layui-form-label">提币合计</label>
            <div class="layui-input-block" style="width:90%">
                <blockquote class="layui-elem-quote layui-quote-nm" id="sum">0</blockquote>
            </div>
        </div>
        <form class="layui-form layui-form-pane layui-inline" action="">

            <div class="layui-inline" style="margin-left: 50px;">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-inline">
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
        <button class="layui-btn layui-btn-normal" onclick="javascrtpt:window.location.href='{{url('/admin/cashb/csv')}}'">导出提币记录</button>
    </div>

    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="is_recommend" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="sexDemo" @{{ d.is_recommend == 1 ? 'checked' : '' }}>
    </script>

    <table id="demo" lay-filter="test"></table>
    <script type="text/html" id="barDemo">
    
    <a class="layui-btn layui-btn-xs" lay-event="show">查看</a>
    
    </script>
    <script type="text/html" id="statustml">
        @{{d.status==1 ? '<span class="layui-badge layui-bg-green">'+'申请提币'+'</span>' : '' }}
        @{{d.status==2 ? '<span class="layui-badge layui-bg-red">'+'提币完成'+'</span>' : '' }}
        @{{d.status==3 ? '<span class="layui-badge layui-bg-black">'+'申请失败'+'</span>' : '' }}

    </script>
@endsection

@section('scripts')
    <script>

        layui.use(['table','form','laydate'], function(){
            var table = layui.table
                ,$ = layui.jquery
                ,form = layui.form
                ,laydate = layui.laydate
                ,layer = layui.layer
            //第一个实例
            laydate.render({
                elem: '#start_time'
            });
            laydate.render({
                elem: '#end_time'
            });
            //第一个实例
            table.render({
                elem: '#demo'
                ,url: "{{url('admin/cashb_list')}}" //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'account_number', title: '用户名', width:140}
                    ,{field: 'nationality', title: '国籍', width:100}
                    ,{field: 'currency_name', title: '虚拟币', width:80}
                    ,{field: 'number', title: '提币数量', width: 140}
                    ,{field: 'rate', title: '手续费', width: 120}
                    ,{field: 'real_number', title: '实际提币', width:110}
                    // ,{field: 'address', title: '提币地址', width:100}
                    ,{field: 'status', title: '状态', width: 120, templet: '#statustml'}
                    ,{field: 'create_time', title: '提币时间', width:180}
                    ,{title:'操作', width:120, toolbar: '#barDemo'}

                ]] , done: function(res){
                    $("#sum").text(res.extra_data);
                }
            });

            table.on('tool(test)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        $.ajax({
                            url:'{{url('admin/cashb_show')}}',
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
                } else if(obj.event === 'show'){
                    layer_show('确认提币','{{url('admin/cashb_show')}}?id='+data.id, 900, 640);
                } else if(obj.event === 'back'){
                    layer_show('退回申请','{{url('admin/adjust_account')}}?id='+data.id, 900, 640);
                }
            });

            //监听提交
            form.on('submit(mobile_search)', function(data){
                var account_number = data.field.account_number;
                var end_time=$('#end_time').val()
                    ,start_time = $('#start_time').val()
                table.reload('mobileSearch',{
                    where:{
                        account_number:account_number,
                        start_time:start_time,
                        end_time:end_time,},
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>

@endsection