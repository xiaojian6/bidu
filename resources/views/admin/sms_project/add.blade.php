@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <form class="layui-form" action="" method="post">
        <input type="hidden" name="id" value="{{$result->id ?? 0}}">
        <div class="layui-form-item">
            <label class="layui-form-label">模版ID</label>
            <div class="layui-input-block">
                <input type="text" name="project" autocomplete="off" placeholder="请输入短信模版ID" class="layui-input layui-input-inline" value="{{$result->project ?? ''}}" >
                <div class="layui-form-mid layui-word-aux">短信模版ID</div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">场景</label>
            <div class="layui-input-block">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="scene" lay-verify="required">
                            <option value=""></option>
                            <option value="register" {{ (isset($result) && $result->scene == 'register') ? 'selected' : ''}}>用户注册</option>
                            <option value="login" {{ (isset($result) && $result->scene == 'login') ? 'selected' : ''}}>用户登录</option>
                            <option value="change_password" {{ (isset($result) && $result->scene == 'change_password') ? 'selected' : ''}}>修改密码</option>
                            <option value="reset_password" {{ (isset($result) && $result->scene == 'reset_password') ? 'selected' : ''}}>找回密码</option>
                        </select>
                    </div>
                    <div class="layui-form-mid layui-word-aux">短信的发送场景</div>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">区域</label>
            <div class="layui-input-block">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <select name="country_code" lay-verify="required" lay-search>
                            <option value=""></option>
                            @foreach ($region_list as $key => $region)
                                <option value="{{$key}}" {{ (isset($result->country_code) && $result->country_code == $key) ? 'selected' : ''}}>{{$region}}</option>
                            @endforeach
                            {{--
                            <!--                             
                            <optgroup label="中国">
                                <option value="86" {{ (isset($result) && $result->country_code == 86) ? 'selected' : ''}} >大陆</option>
                                <option value="852" {{ (isset($result) && $result->country_code == 852) ? 'selected' : ''}} >香港</option>
                                <option value="853" {{ (isset($result) && $result->country_code == 853) ? 'selected' : ''}} >澳门</option>
                                <option value="886" {{ (isset($result) && $result->country_code == 886) ? 'selected' : ''}} >台湾</option>
                            </optgroup>
                            <optgroup label="其他">
                            <option value="81" {{ (isset($result) && $result->country_code == 81) ? 'selected' : ''}} >日本</option>
                            <option value="1" {{ (isset($result) && $result->country_code == 1) ? 'selected' : ''}} >美国</option>
                            <option value="44" {{ (isset($result) && $result->country_code == 44) ? 'selected' : ''}} >英国</option>
                            -->
                            --}}
                        </select>
                    </div>
                    <div class="layui-form-mid layui-word-aux">国际电话区号</div>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">是否默认</label>
            <div class="layui-input-block">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="radio" name="is_default" value="1" title="是" {{(isset($result) && $result->is_default == 1) ? 'checked' : ''}}>
                        <input type="radio" name="is_default" value="0" title="否" {{(isset($result) && $result->is_default == 1) ? '' : 'checked'}}>
                    </div>
                    <div class="layui-form-mid layui-word-aux">当国家代码找不到时,取默认模版</div>
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <input type="radio" name="status" value="1" title="开启" {{(isset($result) && $result->status == 0) ? '' : 'checked'}}>
                        <input type="radio" name="status" value="0" title="关闭" {{(isset($result) && $result->status == 0) ? 'checked' : ''}}>
                    </div>
                    <div class="layui-form-mid layui-word-aux">开启后才能发送成功</div>
                </div>
            </div>
        </div>

        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">短信内容</label>
            <div class="layui-input-block">
                <textarea name="contents" placeholder="请输入内容" class="layui-textarea">{{$result->contents ?? ''}}</textarea>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="form_submit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>

    </form>

@endsection

@section('scripts')
    <script>
        layui.use(['element', 'form', 'layer'], function () {
            var element = layui.element
                ,form = layui.form
                ,layer = layui.layer
                ,$ = layui.$
            //表单提交
            form.on('submit(form_submit)', function (data) {
                $.ajax({
                    url: '/admin/sms_project/add'
                    ,type: 'POST'
                    ,data: data.field
                    ,success: function (res) {
                        layer.msg(res.message, {
                            time: 2000
                            ,end: function () {
                                var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
                                parent.layer.close(index); //再执行关闭 
                                parent.layui.table.reload('data_table');
                            }
                        })
                    }
                    ,error: function (res) {
                        layer.msg(res.message);
                    }
                });
                return false;
            });
        });
    </script>
@endsection