@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div style="margin-top: 10px;width: 100%;margin-left: 10px;">
        <div class="layui-form-item">
            <label class="layui-form-label">撮合交易（挂买）合计</label>
            <div class="layui-input-block" style="width:90%">
                <blockquote class="layui-elem-quote layui-quote-nm" id="sum">0</blockquote>
            </div>
        </div>
        <form class="layui-form layui-form-pane layui-inline" action="">
            <div class="layui-inline" style="margin-left: -10px;">
                <label class="layui-form-label">用户名</label>
                <div class="layui-input-inline">
                    <input type="text" name="account_number" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">法币</label>
                <div class="layui-input-inline" style="width:130px;">
                    <select name="legal" id="type_type">
                        <option value="-1" class="ww">全部</option>
                        @foreach ($legal_currencies as $currency)
                        <option value="{{$currency->id}}" class="ww">{{$currency->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">交易币</label>
                <div class="layui-input-inline" style="width:130px;">
                    <select name="currency" id="type_type">
                        <option value="-1" class="ww">全部</option>
                        @foreach ($currencies as $currency)
                        <option value="{{$currency->id}}" class="ww">{{$currency->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">开始日期：</label>
                <div class="layui-input-inline" style="width:120px;">
                    <input type="text" class="layui-input" id="start_time" value="" name="start_time">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">结束日期：</label>
                <div class="layui-input-inline" style="width:120px;">
                    <input type="text" class="layui-input" id="end_time" value="" name="end_time">
                </div>
            </div>
            <div class="layui-inline">
                <div class="layui-input-inline">
                    <button class="layui-btn" lay-submit="" lay-filter="mobile_search"><i class="layui-icon">&#xe615;</i></button>
                </div>
            </div>
        </form>
    </div>

    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="is_recommend" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="sexDemo" @{{ d.is_recommend == 1 ? 'checked' : '' }}>
    </script>

    <table id="demo" lay-filter="test"></table>
    <script type="text/html" id="barDemo">
        {{--<a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">调节账户</a>--}}
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>

@endsection

@section('scripts')
    <script>

        layui.use(['table','form','laydate'], function(){
            var table = layui.table;
            var $ = layui.jquery;
            var form = layui.form;
            var laydate = layui.laydate;
            laydate.render({
                elem: '#start_time'
            });
            laydate.render({
                elem: '#end_time'
            });
            //第一个实例
            table.render({
                elem: '#demo'
                ,url: '{{url('admin/in_list')}}' //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width: 90, sort: true}
                    ,{field: 'account_number', title: '用户名', width: 120}
                    ,{field: 'currency_name', title: '交易币', width: 90}
                    ,{field: 'legal_name', title: '法币', width: 90}
                    ,{field: 'price', title: '价格', width: 120}
                    ,{field: 'number', title: '数量', width: 100}
                    ,{field: 'create_time', title: '创建时间', width: 180}
                ]], done: function(res){
                    $("#sum").text(res.extra_data);
                }
            });

            table.on('tool(test)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        $.ajax({
                            url:'{{url('')}}',
                            type:'post',
                            dataType:'json',
                            data:{id:data.id},
                            success:function (res) {
                                if(res.type == 'error'){
                                    layer.msg(res.message);
                                } else {
                                    obj.del();
                                    layer.close(index);
                                }
                            }
                        });


                    });
                } else if(obj.event === 'edit'){
                    layer_show('编辑承兑商','{{url('')}}?id='+data.id);
                } else if(obj.event === 'detail'){
                    layer_show('调节账户','{{url('')}}?id='+data.id,800,600);
                }
            });

            //监听提交
            form.on('submit(mobile_search)', function(data) {
                var account_number = data.field.account_number;
                table.reload('mobileSearch', {
                    where: data.field,
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>

@endsection