<fieldset class="layui-elem-field">
    <legend>
        <i class="layui-icon layui-icon-cellphone"></i>
        <span>短信参数</span>
        <button class="layui-btn layui-btn-sm layui-btn-warm" type="button" id="sms_set">模版管理</button>
    </legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <div class="layui-inline" hidden>
                <label class="layui-form-label">短信宝</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required" placeholder="用户名" name="smsBao_username" type="text" value="{{$setting['smsBao_username'] ?? '' }}">
                    <span style="position: absolute;right: 5px;top: 12px;"></span>
                </div>
            </div>
            <div class="layui-inline" hidden>
                <label class="layui-form-label">密码</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="password" lay-verify="required" name="password"
                        value="{{$setting['password']  ?? '' }}" placeholder="" >
                </div>
            </div>
            <div class="layui-inline" hidden>
                <label class="layui-form-label">短信签名</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required" name="sms_signature"
                        value="{{$setting['sms_signature'] ?? '' }}" placeholder="">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">国内短信appid</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required" name="sms_internal_appid" value="{{$setting['sms_internal_appid'] ?? '' }}" placeholder="">
                </div>
            </div>

            <div class="layui-inline">
                <label class="layui-form-label">国内短信appkey</label>
                <div class="layui-input-inline" style="width: 240px">
                    <input class="layui-input" type="password" lay-verify="required" name="sms_internal_appkey" value="{{$setting['sms_internal_appkey'] ?? '' }}" placeholder="">
                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">国际短信appid</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required" name="sms_internat_appid" value="{{$setting['sms_internat_appid'] ?? '' }}" placeholder="">
                </div> 
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">国际短信appkey</label>
                <div class="layui-input-inline" style="width: 240px">
                    <input class="layui-input" type="password" lay-verify="required" name="sms_internat_appkey" value="{{$setting['sms_internat_appkey'] ?? '' }}" placeholder="">
                </div>
            </div>
        </div>
    </div>
</fieldset>
