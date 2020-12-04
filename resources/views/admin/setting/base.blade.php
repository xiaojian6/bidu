@extends('admin._layoutNew')
@section('page-head')
    <style>
        [hidden] {
            display: none;
        }
        .layui-form-label {
            width: 150px;
        }
    </style>
@stop
@section('page-content')
    <div class="larry-personal-body clearfix">
        <form class="layui-form col-lg-5">
            <div class="layui-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">通知设置</li>
                    <li>基础参数</li>
                    <li>交易手续费</li>
                    <li>杠杆设置</li>
                    <li>代理商设置</li>
                    <li>安全中心</li>

                </ul>
                <div class="layui-tab-content">
                    <!--通知设置开始-->
                    <div class="layui-tab-item layui-show">
                        <div id="email">
                            @include('admin.setting.email')
                        </div>
{{--                        <div id="sms">--}}
{{--                            @include('admin.setting.sms')--}}
{{--                        </div>--}}
                    </div>
                    <!--基础设置开始-->
                    <div class="layui-tab-item">
                        @include('admin.setting.common')
                    </div>

                    <!--交易设置开始-->
                    <div class="layui-tab-item">
                        @include('admin.setting.trade')
                    </div>
                    <!--杠杆设置开始-->
                    <div class="layui-tab-item">
                        @include('admin.setting.lever')
                    </div>
                    <!--代理商设置开始-->
                    <div class="layui-tab-item">
                        @include('admin.setting.agent')
                    </div>
                    <!--安全中心设置开始-->
                    <div class="layui-tab-item">
                        @include('admin.setting.safe')
                    </div>

                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="website_submit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        layui.use(['element', 'form', 'upload', 'layer'], function () {
            var element = layui.element;
            var layer = layui.layer;
            var form = layui.form;
            var $ = layui.$;
            $('#handle_multi_set').click(function () {
                layer.open({
                    type: 2
                    ,title: '杠杆交易手数和倍数设置'
                    ,content: '/admin/levermultiple/index'
                    ,area: ['600px', '430px']
                    ,id: 99
                });
            });
            form.on('submit(website_submit)', function (data) {
                var data = data.field;
                $.ajax({
                    url: '/admin/setting/postadd',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (res) {
                        layer.msg(res.message);
                    }
                });
                return false;
            });
            var template = `
                <tr>
                    <td>
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 90px;">
                                <input class="layui-input" name="generation[]" value="" required lay-verify="required">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 90px;">
                                <input class="layui-input" name="reward_ratio[]" value="" required lay-verify="required">
                            </div>
                            <div class="layui-form-mid">
                                <span>%</span></div>
                            </div>
                        </td>
                        <td>
                            <div class="layui-input-inline" style="width: 90px;">
                                <input class="layui-input" name="need_has_trades[]" value="" required lay-verify="required">
                            </div>
                        </td>
                        <td>
                            <div class="layui-input-inline">
                            <button class="layui-btn layui-btn-sm layui-btn-danger" type="button" lay-event="del">删除</button>
                            </div>
                    </td>
                </tr>`;
            $('#addLeverTradeOption').click(function () {
                $('#leverTradeFeeOption').append(template);
            });
            $('#leverTradeFeeOption').on('click', 'button[lay-event]', function () {
                var that = this
                    ,event = $(this).attr('lay-event')
                if (event == 'del') {
                    layer.confirm('真的确定要删除吗?' , {
                        title: '删除确认'
                        ,icon: 3
                    }, function (index) {
                        $(that).parent().parent().parent().remove();
                        layer.close(index);
                    });
                }
            });

            $('#sms_set').click(function () {
                layer.open({
                    type: 2
                    ,title: '短信模版管理'
                    ,content: '/admin/sms_project/index'
                    ,area: ['1200px', '600px']
                    ,id: 99
                });
            });

            $('#currency_set').click(function () {
                layer.open({
                    type: 2
                    ,title: '币种管理'
                    ,content: '/admin/currency'
                    ,area: ['1200px', '800px']
                    ,id: 99
                });
            });
        });
    </script>
@stop
