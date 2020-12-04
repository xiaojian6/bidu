<fieldset class="layui-elem-field">
    <legend>
        <i class="layui-icon layui-icon-password"></i>
        <span>万能验证码</span>
        <span class=" layui-word-aux">该功能仅用于测试</span>
    </legend>
    <div class="layui-field-box">
        @include('admin.setting.universalCode')
    </div>
</fieldset>

<fieldset class="layui-elem-field">
    <legend><i class="layui-icon layui-icon-component"></i> <span>支付密码场景</span></legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <label class="layui-form-label">场景</label>
            <div class="layui-input-block">
                @foreach (unserialize($setting['paypassword_scenes']) as $key => $scene)
                    <input type="checkbox" name="{{$key}}_need_password" title="{{$scene}}" {{!isset($setting["{$key}_need_password"]) || $setting["{$key}_need_password"] ? 'checked' : ''}} value="1">
                @endforeach
            </div>
            <div class="layui-form-mid layui-word-aux">选中的场景将必须输入支付密码</div>
        </div>
    </div>
</fieldset>