<div class="layui-form-item">
    <div class="layui-inline">
        <label class="layui-form-label">手续费率</label>
        <div class="layui-input-inline">
            <input class="layui-input" name="jc_exchange_fee" type="text" value="{{$setting['jc_exchange_fee'] ?? ''}}" placeholder="手续费率" lay-verify="required">
        </div>
        <div class="layui-form-mid layui-word-aux">%<span></div>
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-inline">
        <label class="layui-form-label">积分价值</label>
        <div class="layui-input-inline">
            <input class="layui-input" name="jc_price" type="text" value="{{$setting['jc_price'] ?? ''}}" placeholder="积分价值"  lay-verify="required" >
        </div>
        <div class="layui-form-mid layui-word-aux">美元<span></div>
    </div>
</div>

<div class="layui-form-item">
    <div class="layui-inline">
        <label class="layui-form-label">兑换点差</label>
        <div class="layui-input-inline">
            <input class="layui-input" name="jc_price_difference" type="text" value="{{$setting['jc_price_difference'] ?? ''}}" placeholder="点差"　 lay-verify="required" >
        </div>
        <div class="layui-form-mid layui-word-aux">美元<span></div>
    </div>
</div>