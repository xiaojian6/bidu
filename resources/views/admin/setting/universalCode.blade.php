<div class="layui-form-item">
    <label class="layui-form-label">注册场景</label>
    <div class="layui-input-inline">
        <input type="text" name="register_universalCode" autocomplete="off" class="layui-input"
            value="@if(isset($setting['register_universalCode'])){{$setting['register_universalCode'] ?? ''}}@endif">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">登录场景</label>
    <div class="layui-input-inline">
        <input type="text" name="login_universalCode" autocomplete="off" class="layui-input"
            value="@if(isset($setting['login_universalCode'])){{$setting['login_universalCode'] ?? ''}}@endif">
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">找回密码场景</label>
    <div class="layui-input-inline">
        <input type="text" name="reset_password_universalCode" autocomplete="off" class="layui-input"
            value="@if(isset($setting['reset_password_universalCode'])){{$setting['reset_password_universalCode'] ?? ''}}@endif">
    </div>
    <div class="layui-form-mid layui-word-aux">请勿告知他人,以避免会员密码被重置</div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">修改密码场景</label>
    <div class="layui-input-inline">
        <input type="text" name="change_password_universalCode" autocomplete="off" class="layui-input"
            value="@if(isset($setting['change_password_universalCode'])){{$setting['change_password_universalCode'] ?? ''}}@endif">
    </div>
    <div class="layui-form-mid layui-word-aux"></div>
</div>