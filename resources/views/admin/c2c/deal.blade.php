@extends('admin._layoutNew')

@section('page-head')

@endsection



@section('page-content')
    <div style="margin-top: 10px;width: 100%;margin-left: 10px;">
        <div class="layui-form-item">
            <label class="layui-form-label">法币交易合计</label>
            <div class="layui-input-block" style="width:90%">
                <blockquote class="layui-elem-quote layui-quote-nm" id="sum">0</blockquote>
            </div>
        </div>

        <form class="layui-form layui-form-pane layui-inline" action="">

            <div class="layui-inline" style="margin-left: 50px;">
                <label >用户交易账号&nbsp;&nbsp;</label>
                <div class="layui-input-inline">
                    <input type="text" name="account_number" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline" style="margin-left: 50px;">
                <label>交易状态&nbsp;&nbsp;</label>
                <div class="layui-input-inline">
                    <select name="is_sure" id="is_sure_type">
                        <option value="" class="ww">全部</option>
                        <option value="0" class="ww">未确认</option>
                        <option value="1" class="ww">已确认</option>
                        <option value="2" class="ww">已取消</option>
                        <option value="3" class="ww">已付款</option>
                    </select>
                </div>
            </div>
            <div class="layui-inline" style="margin-left: 50px;">
                <label >商家交易账号&nbsp;&nbsp;</label>
                <div class="layui-input-inline">
                    <input type="text" name="seller_number" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline" style="margin-left: 50px;">
                <label>买入/卖出&nbsp;&nbsp;</label>
                <div class="layui-input-inline">
                    <select name="type" id="type_type">
                        <option value="" class="ww">全部</option>
                        <option value="sell" class="ww">买入</option>
                        <option value="buy" class="ww">卖出</option>

                    </select>
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
    </div>

    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="is_recommend" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="sexDemo" @{{ d.is_recommend == 1 ? 'checked' : '' }}>
    </script>

    <table id="demo" lay-filter="test"></table>
    <!-- <script type="text/html" id="barDemo">
       
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script> -->

    <script type="text/html" id="type">
        @{{d.type=="buy" ? '<span class="layui-badge layui-bg-green">'+'卖出'+'</span>' : '' }}
        @{{d.type=="sell" ? '<span class="layui-badge layui-bg-red">'+'买入'+'</span>' : '' }}

    </script>
    <script type="text/html" id="is_sure">
        @{{d.is_sure==0 ? '<span class="layui-badge layui-bg-red">'+'未确认'+'</span>' : '' }}
        @{{d.is_sure==1 ? '<span class="layui-badge layui-bg-blue "  >'+'已确认'+'</span>' : '' }}
        @{{d.is_sure==2 ? '<span class="layui-badge layui-bg-orange">'+'已取消'+'</span>' : '' }}
        @{{d.is_sure==3 ? '<span class="layui-badge layui-bg-green">'+'已付款'+'</span>' : '' }}

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
                ,url: '{{url('admin/c2c_deal/list')}}' //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'legal_deal_send_id', title: '交易需求id', width:150}
                    
                    ,{field: 'account_number', title: '用户交易账号', width:120}
                    ,{field: 'user_realname', title: '真实姓名', width:120}
                    ,{field: 'seller_name', title: '商家名称', width:120}
                    ,{field: 'type', title: '买入/卖出', width:100, templet: '#type'}
                    ,{field: 'way_name', title: '支付方式', width:100}

                    ,{field: 'price', title: '单价', width:100 }
                    ,{field: 'number', title: '交易数量', width:100}
                    // ,{field: 'surplus_number', title: '剩余数量', width:100}
                    ,{field: 'currency_name', title: '交易币', width:100}
                    ,{field: 'deal_money', title: '交易总金额', width:100}

                    // ,{field: 'limitation', title: '限额', width:100, templet: '#limitation'}
                    ,{field: 'is_sure', title: '交易状态', width:100, templet: '#is_sure'}

                    ,{field: 'format_create_time', title: '交易时间', width:180}
                    ,{field: 'format_update_time', title: '确认时间', width:180}


                    // ,{title:'操作',minWidth:100,toolbar: '#barDemo'}

                ]],done: function(res){
                    $("#sum").text(res.extra_data);
                }
            });



            //监听提交
            form.on('submit(mobile_search)', function(data){
                var seller_number = data.field.seller_number
                    ,type = $('#type_type').val()
                    ,is_sure = $('#is_sure_type').val()
                    // ,currency_id = $('#currency_id').val()
                    ,account_number = data.field.account_number
                    ,end_time=$('#end_time').val()
                    ,start_time = $('#start_time').val()


                table.reload('mobileSearch',{
                    where:{
                        account_number:account_number,
                        seller_number:seller_number,
                        start_time:start_time,
                        type:type,
                        end_time:end_time,
                        is_sure:is_sure,
                        // currency_id:currency_id,

                    },
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>

@endsection