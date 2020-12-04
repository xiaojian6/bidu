<fieldset class="layui-elem-field">
    <legend><i class="layui-icon layui-icon-release"></i> <span>邮件参数</span></legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">邮箱账号</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="1" placeholder="" name="phpMailer_username"
                        type="text" onkeyup=""
                        value="@if(isset($setting['phpMailer_username'])){{$setting['phpMailer_username'] ?? ''}}@endif">
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">Token密码</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required" placeholder="请输入最大值"
                        name="phpMailer_password" onkeyup="" type="text"
                        value="@if(isset($setting['phpMailer_password'])){{$setting['phpMailer_password'] ?? ''}}@endif">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">端口</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required" placeholder="请输入比例"
                        name="phpMailer_port" type="text"
                        value="@if(isset($setting['phpMailer_port'])){{$setting['phpMailer_port'] ?? ''}}@endif"><span
                            style="position: absolute;right: 5px;top: 12px;"></span>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">Host</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required" placeholder="请输入主机"
                        name="phpMailer_host" type="text"
                        value="@if(isset($setting['phpMailer_host'])){{$setting['phpMailer_host'] ?? ''}}@endif">
                </div>
            </div>
        </div>
    </div>
</fieldset>