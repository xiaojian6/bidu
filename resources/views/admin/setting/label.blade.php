
<fieldset class="layui-elem-field">
    <legend><i class="layui-icon layui-icon-component"></i> <span>标签显示设置</span></legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <label class="layui-form-label">标签显示</label>
            <div class="layui-input-block">
                @foreach (unserialize($setting['paypassword_scenes']) as $key => $scene)
                    <input type="checkbox" name="{{$key}}_need_password" title="{{$scene}}" {{!isset($setting["{$key}_need_password"]) || $setting["{$key}_need_password"] ? 'checked' : ''}} value="1">
                @endforeach
            </div>
            <div class="layui-form-mid layui-word-aux">选择是否显示该标签</div>
        </div>
    </div>
</fieldset>