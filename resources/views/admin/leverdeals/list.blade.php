@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div style="margin-top: 10px;width: 100%;margin-left: 10px;">
        <form class="layui-form layui-form-pane layui-inline" action="">
            <div class="layui-inline" style="margin-left: 10px;">
                <label class="layui-form-label" style="width: 80px;">交易对</label>
                <div class="layui-input-inline" style="width:80px;">
                    <select name="match_id" id="status" lay-search>
                        <option value="-1">所有</option>
                        @foreach ($matches as $match)
                        <option value="{{$match->id}}">{{$match->symbol}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-inline" style="margin-left: 10px;">
                <label class="layui-form-label" style="width: 70px;">状态</label>
                <div class="layui-input-inline" style="width:80px;">
                    <select name="status" id="status">
                        <option value="-1">所有</option>
                        <option value="0">挂单中</option>
                        <option value="1">交易中</option>
                        <option value="2">平仓中</option>
                        <option value="3">已平仓</option>
                        <option value="4">已撤单</option>
                    </select>
                </div>
            </div>
            <div class="layui-inline" style="margin-left: 10px;">
                <label class="layui-form-label" style="width: 70px;">类型</label>
                <div class="layui-input-inline" style="width:70px;">
                    <select name="type" id="type">
                        <option value="0">所有</option>
                        <option value="1">买入</option>
                        <option value="2">卖出</option>
                    </select>
                </div>
            </div>
            <div class="layui-inline" style="margin-left: 10px;">
                <label class="layui-form-label" style="width: 70px;">账号</label>
                <div class="layui-input-inline"  style="width:130px;">
                    <input type="text" name="account_number" placeholder="请输入" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label" style="width: 90px;">开始日期</label>
                <div class="layui-input-inline" style="width:150px;">
                    <input id="start_time" type="text" class="layui-input layui-date" name="start_time" value="">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label" style="width: 90px;">结束日期</label>
                <div class="layui-input-inline" style="width:150px;">
                    <input id="end_time" type="text" class="layui-input layui-date" name="end_time" value="">
                </div>
            </div>
            <div class="layui-inline">
                <button class="layui-btn btn-search" id="mobile_search" lay-submit lay-filter="mobile_search"> <i class="layui-icon">&#xe615;</i> </button>
                <!-- <button class="layui-btn layui-btn-normal" onclick="javascrtpt:window.location.href='/admin/Leverdeals/csv'">导出记录</button> -->
            </div>
            <div class="layui-inline" style="">
                <div class="layui-input-inline">
                    <input type="checkbox" name="" lay-skin="switch" lay-filter="auto_refresh" lay-text="开启刷新|关闭刷新">
                </div>
            </div>
        </form>
    </div>
        
    </div>

    <table id="leverList" lay-filter="leverList"></table>
@endsection

@section('scripts')
    <script type="text/html" id="closedTpl">
        @{{#if(d.closed_type == 1) { }}
        市价
        @{{#} else if(d.closed_type == 2) { }}
        爆仓
        @{{#} else if(d.closed_type == 3) { }}
        止盈
        @{{#} else if(d.closed_type == 4) { }}
        止损
        @{{#} else if(d.closed_type == 5) { }}
        后台
        @{{#} }}
    </script>
    <script type="text/html" id="operateBar">
        @{{# if (d.status == 1) { }}
            <a class="layui-btn layui-btn-xs layui-btn-warm" lay-event="close">平仓</a>
        @{{# } }}
    </script>
    <script>
        var interval;
        window.onload = function() {
            document.onkeydown=function(event){
                var e = event || window.event || arguments.callee.caller.arguments[0];
                if(e && e.keyCode==13) { // enter 键
                    $('#mobile_search').click();
                }
            };
            layui.use(['element', 'form', 'layer', 'table', 'laydate'], function () {
                var element = layui.element
                    ,layer = layui.layer
                    ,table = layui.table
                    ,$ = layui.$
                    ,form = layui.form
                    ,laydate = layui.laydate
                $('input.layui-date').each(function () {
                    laydate.render({
                        elem: this
                        ,type: 'datetime'
                    });
                });

                var lever_list = table.render({
                    elem: '#leverList'
                    ,url: '/admin/Leverdeals/list'
                    ,height: 'full-80'
                    ,page: true
                    ,limit: 20
                    ,toolbar: true
                    ,totalRow: true
                    ,cols: [[
                        {field: 'id', title: 'ID', width: 100}
                        ,{field: 'symbol', title: '交易对', width: 100}
                        ,{field: 'account_number', title: '用户名', width: 120}
                        ,{field: 'type_name', title: '类型', width: 60, templet: '#lockTpl'}
                        ,{field: 'status_name', title: '状态', sort: true, width: 90, templet: '#addsonTpl'}
                        ,{field: 'closed_type', title: '平仓类型', sort: true, width: 100, templet: '#closedTpl'}
                        ,{field: 'origin_price', title: '原始价', width: 140, hide: true}
                        ,{field: 'price', title: '开仓价', width: 140}
                        ,{field: 'update_price', title: '当前价', width: 140}
                        ,{field: 'share', title: '手数', sort: true, width: 90, hide: true}
                        ,{field: 'multiple', title: '倍数', sort: true, width: 90, hide: true}
                        ,{field: 'origin_caution_money', title: '初始保证金', width: 140, hide: true}
                        ,{field: 'caution_money', title: '可用保证金', width: 140}
                        ,{field: 'trade_fee', title: '手续费', width: 140, totalRow: true}
                        ,{field: 'overnight_money', title: '隔夜费', width: 140, totalRow: true}
                        ,{field: 'profits', title: '当前盈亏', sort: true, width: 160, style:"background-color: #eaeaea;", totalRow: true}
                        ,{field: 'time', title: '创建时间', width: 170}
                        ,{field: 'update_time', title: '刷新时间', sort: true, width: 170, hide: true}
                        ,{field: 'handle_time', title: '平仓时间', sort: true, width: 170}
                        ,{field: 'complete_time', title: '完成时间', width: 170}
                        ,{fixed: 'right', title: '操作', width: 100, align: 'center', toolbar: '#operateBar'}
                    ]]
                });

                table.on('tool(leverList)', function (obj) { //注：tool是工具条事件名，test是table原始容器的属性 lay-filter="对应的值"
                    var data = obj.data
                        ,layEvent = obj.event
                        ,tr = obj.tr;
                    if (layEvent === 'close') { //删除
                        layer.confirm('真的要强制平仓吗？', function (index) {
                            //向服务端发送删除指令
                            $.ajax({
                                url: "/admin/Leverdeals/close",
                                type: 'post',
                                dataType: 'json',
                                data: {id: data.id},
                                success: function (res) {
                                    layer.msg(res.message);
                                    if (res.type == 'ok') {
                                        lever_list.reload();
                                    } else {
                                        layer.close(index);
                                        
                                    }
                                }
                            });
                        });
                    }
                });

                form.on('switch(auto_refresh)', function(data){
                    
                    if (data.elem.checked) {
                        interval = setInterval(() => {
                            lever_list.reload({});
                        }, 5000);
                    } else {
                        clearInterval(interval);
                    }
                });

                form.on('submit(mobile_search)', function(data) {
                    var search_data = data.field
                    lever_list.reload({
                        where: search_data
                        ,page: {
                            curr: 1 //重新从第 1 页开始
                        }
                    });
                    return false;
                });
            });
        }
    </script>

@endsection