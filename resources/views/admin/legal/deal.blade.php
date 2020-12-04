@extends('admin._layoutNew')

@section('page-head')

@endsection

<script type="text/html" id="barDemo">

    @{{#if(d.is_sure == 0) { }}
    <a class="layui-btn layui-btn-xs" lay-event="cancel">
        取消
    </a>
    @{{#} else if(d.is_sure ==1){ }}
    <span class="layui-btn layui-btn-xs layui-btn-disabled">
           已确认
        </span>
    @{{#} else if(d.is_sure == 2){ }}
    <span class="layui-btn layui-btn-xs layui-btn-disabled">
           已取消
        </span>
    @{{#} else if(d.is_sure == 3){ }}
    <span class="layui-btn layui-btn-xs " lay-event="confirm">
           确认
        </span>
    <a class="layui-btn layui-btn-xs" lay-event="cancel">
        取消
    </a>
    @{{#}}}



    {{--<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">取消</a>--}}

</script>


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
                <label >商家名称&nbsp;&nbsp;</label>
                <div class="layui-input-inline">
                    <input type="text" name="seller_name" autocomplete="off" class="layui-input">
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
                <label>交易币&nbsp;&nbsp;</label>
                <div class="layui-input-inline">
                    <select name="currency_id" id="currency_id">
                        <option value="0" class="ww">全部</option>
                        @foreach ($currency as $value)
                            <option value="{{$value->id}}" class="ww">{{$value->name}}</option>
                        @endforeach
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














    <div class="layui-card-body">
        <div class="layui-carousel layadmin-backlog" style="background-color: #fff">
            <ul class="layui-row layui-col-space10 layui-this">
                <li class="layui-col-xs3">
                    <a href="javascript:;" onclick="layer.tips('当天购买USDT', this, {tips: 3});" class="layadmin-backlog-body" style="color: #fff;background-color: #01AAED;display: block;padding: 10px;">
                        <h3>当天购买USDT：</h3>
                        <p><cite style="color:#fff" id="all">{{$todaybuy_usdt}}</cite></p>
                    </a>
                </li>
                <li class="layui-col-xs3">
                    <a href="javascript:;" onclick="layer.tips('当天出售USDT', this, {tips: 3});" class="layadmin-backlog-body" style="color: #fff;background-color: #01AAED;display: block;padding: 10px;">
                        <h3>当天出售USDT：</h3>
                        <p><cite style="color:#fff" id="toucun">{{$todaysell_usdt}}</cite></p>
                    </a>
                </li>
                <li class="layui-col-xs3">
                    <a href="javascript:;" onclick="layer.tips('USDT购买总数量', this, {tips: 3});" class="layadmin-backlog-body" style="color: #fff;background-color: #01AAED;display: block;padding: 10px;">
                        <h3>USDT购买总数量：</h3>
                        <p><cite style="color:#fff" id="shouxu">{{$buyall_usdt}}</cite></p>
                    </a>
                </li>

            </ul>
        </div>
        <div class="layui-carousel layadmin-backlog" style="background-color: #fff">
            <ul class="layui-row layui-col-space10 layui-this">
                <li class="layui-col-xs3">
                    <a href="javascript:;" onclick="layer.tips('USDT出售总数量', this, {tips: 3});" class="layadmin-backlog-body" style="color: #fff;background-color: #01AAED;display: block;padding: 10px;">
                        <h3>USDT出售总数量：</h3>
                        <p><cite style="color:#fff" id="chujin">{{$sellall_usdt}}</cite></p>
                    </a>
                </li>
                <li class="layui-col-xs3">
                    <a href="javascript:;" onclick="layer.tips('USDT总冻结数量', this, {tips: 3});" class="layadmin-backlog-body" style="color: #fff;background-color: #01AAED;display: block;padding: 10px;">
                        <h3>USDT总冻结数量：</h3>
                        <p><cite style="color:#fff" id="rujin">{{$all_lock_legal_balance}}</cite></p>
                    </a>
                </li>
                <li class="layui-col-xs3">
                    <a href="javascript:;" onclick="layer.tips('USDT总可用余额', this, {tips: 3});" class="layadmin-backlog-body" style="color: #fff;background-color: #01AAED;display: block;padding: 10px;">
                        <h3>USDT总可用余额：</h3>
                        <p><cite style="color:#fff" id="lock">{{$all_usdt_can_use}}</cite></p>
                    </a>
                </li>
                {{--<li class="layui-col-xs3">--}}
                    {{--<a href="javascript:;" onclick="layer.tips('客户法币锁定余额', this, {tips: 3});" class="layadmin-backlog-body" style="color: #fff;background-color: #01AAED;display: block;">--}}
                        {{--<h3>客户法币锁定余额</h3>--}}
                        {{--<p><cite style="color:#fff" id="locklage">0</cite></p>--}}
                    {{--</a>--}}
                {{--</li>--}}
            </ul>
        </div>
    </div>
<style>

    element.style {
        color: #fff;
        background-color: #01AAED;
        display: block;

    }
</style>

















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
                ,url: '{{url('admin/legal_deal/list')}}' //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, sort: true}
                    ,{field: 'legal_deal_send_id', title: '交易需求id', width:150}
                    ,{field: 'seller_name', title: '商家名称', width:120}
                    ,{field: 'account_number', title: '用户交易账号', width:120}
                    ,{field: 'user_realname', title: '真实姓名', width:120}
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
                    ,{fixed: 'right', title: '操作', minWidth: 150, align: 'center', toolbar: '#barDemo'}
                    // ,{title:'操作',minWidth:100,toolbar: '#barDemo'}

                ]], done: function(res){
                    $("#sum").text(res.extra_data);
                }
            });


             table.on('tool(test)', function(obj){
                 var data = obj.data;
                 if(obj.event === 'cancel')
                 {
                     layer.confirm('确定要取消吗', function(index){
                         $.ajax({
                             url:'{{url('/admin/admin_legal_pay_cancel')}}',
                             type:'post',
                             dataType:'json',
                             data:{id:data.id},
                             success:function (res) {
                                 if(res.type == 'error'){
                                     layer.msg(res.message);
                                 }else{
//                                     obj.del();
                                     layer.close(index);
                                     window.location.reload();
                                     layer.alert(res.message);
                                 }
                             }
                         });


                     });
                 }
                 else if(obj.event === 'confirm')
                 {
                     if(data.type=="buy")
                     {
                         layer.confirm('是否确认？', function(index)
                         {
                             $.ajax({
                                 url:'{{url('/admin/legal_deal_admin_user_sure')}}',
                                 type:'post',
                                 dataType:'json',
                                 data:{id:data.id},
                                 success:function (res) {
                                     if(res.type == 'error'){
                                         layer.msg(res.message);
                                     }else{
//                                     obj.del();
                                         layer.close(index);
                                         window.location.reload();
                                         layer.alert(res.message);
                                     }
                                 }
                             });


                         });
                     }
                     else if(data.type=="sell")
                     {
                         layer.confirm('是否确认？', function(index)
                         {
                             $.ajax({
                                 url:'{{url('/admin/legal_deal_admin_sure')}}',
                                 type:'post',
                                 dataType:'json',
                                 data:{id:data.id},
                                 success:function (res) {
                                     if(res.type == 'error'){
                                         layer.msg(res.message);
                                     }else{
//                                     obj.del();
                                         layer.close(index);
                                         window.location.reload();
                                         layer.alert(res.message);
                                     }
                                 }
                             });


                         });
                     }

                 }
             });

            //监听提交
            form.on('submit(mobile_search)', function(data)
            {
                var seller_name = data.field.seller_name
                    ,type = $('#type_type').val()
                    ,currency_id = $('#currency_id').val()
                    ,is_sure = $('#is_sure_type').val()
                    ,account_number = data.field.account_number
                    ,end_time=$('#end_time').val()
                    ,start_time = $('#start_time').val()


                table.reload('mobileSearch',{
                    where:{
                        account_number:account_number,
                        seller_name:seller_name,
                        is_sure:is_sure,
                        start_time:start_time,
                        end_time:end_time,
                        type:type,
                        currency_id:currency_id,

                    },
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>

@endsection