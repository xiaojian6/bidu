<style>
    fieldset.layui-elem-field {
        border: 0px;
        border-top: 1px solid #eee;
    }
    fieldset legend span {
        font-size: 15px;
    }
</style>
<fieldset class="layui-elem-field">
    <legend><i class="layui-icon layui-icon-template-1"></i> <span>通用参数</span></legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <label class="layui-form-label">存储位置</label>
            <div class="layui-input-block">
                <div class="layui-input-inline" style="width: 220px">
                    <input type="radio" name="use_qiniu_storage" value="1" title="七牛云(推荐)" @if (isset($setting['use_qiniu_storage'])) {{$setting['use_qiniu_storage'] == 1 ? 'checked' : ''}} @endif >
                    <input type="radio" name="use_qiniu_storage" value="0" title="本机" @if (isset($setting['use_qiniu_storage'])) {{$setting['use_qiniu_storage'] == 0 ? 'checked' : ''}} @else checked @endif >
                </div>
                <div class="layui-form-mid layui-word-aux">推荐使用七牛云,更安全快速<span>(分布式部署必须使用七牛云)<span></div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">上传大小</label>
                <div class="layui-input-inline">
                    <input class="layui-input" lay-verify="required" placeholder="上传大小" name="upload_file_size" type="text" value="{{$setting['upload_file_size'] ?? '' }}">
                    <span style="position: absolute;right: 5px;top: 12px;">MB</span>
                </div>
            </div>
            <div class="layui-inline">
                <label class="layui-form-label">允许扩展名</label>
                <div class="layui-input-inline">
                    <input class="layui-input" type="text" lay-verify="required" name="upload_file_ext_list" value="{{$setting['upload_file_ext_list']  ?? '' }}" placeholder="用逗号间隔,如:jpg,png" >
                </div>
            </div>
        </div>
    </div>
</fieldset>

<fieldset class="layui-elem-field">
    <legend> <i class="layui-icon layui-icon-upload-drag"></i> <span>七牛云参数</span> </legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">七牛云域名</label>
                <div class="layui-input-inline" style="width: 514px">
                    <input class="layui-input" type="text" name="qiniu_url" value="{{$setting['qiniu_url']  ?? '' }}" placeholder="备案过的域名" >
                </div>
                <div class="layui-form-mid layui-word-aux">必须是备案过的域名</div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">七牛云AccessKey</label>
                <div class="layui-input-inline" style="width: 514px">
                    <input class="layui-input" type="password" name="qiniu_access_key" value="{{$setting['qiniu_access_key'] ?? '' }}" placeholder="七牛云AccessKey">
                </div>
                <div class="layui-form-mid layui-word-aux">七牛云AccessKey</div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">七牛云SecretKey</label>
                <div class="layui-input-inline" style="width: 514px">
                    <input class="layui-input" type="password" name="qiniu_secret_key" value="{{$setting['qiniu_secret_key']  ?? '' }}" placeholder="七牛云SecretKey" >
                </div>
                <div class="layui-form-mid layui-word-aux">七牛云SecretKey</div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-inline">
                <label class="layui-form-label">七牛云空间名</label>
                <div class="layui-input-inline" style="width: 514px">
                    <input class="layui-input" type="text" name="qiniu_bucket_name" value="{{$setting['qiniu_bucket_name']  ?? '' }}" placeholder="七牛云BucketName" >
                </div>
                <div class="layui-form-mid layui-word-aux">七牛云BucketName</div>
            </div>
        </div>    
    </div>
</fieldset>
            