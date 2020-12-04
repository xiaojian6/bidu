<fieldset class="layui-elem-field">
    <legend>
        <i class="layui-icon layui-icon-fire"></i>
        <span>撮合交易</span>
    </legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <label class="layui-form-label">卖出手续费</label>
            <div class="layui-input-inline">
                <input type="text" name="match_sell_fee" autocomplete="off" class="layui-input"
                    value="@if(isset($setting['match_sell_fee'])){{$setting['match_sell_fee']}}@endif">
            </div>
            <div class="layui-form-mid layui-word-aux">%</div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">买入手续费</label>
            <div class="layui-input-inline">
                <input type="text" name="match_buy_fee" autocomplete="off" class="layui-input"
                    value="@if(isset($setting['match_buy_fee'])){{$setting['match_buy_fee']}}@endif">
            </div>
            <div class="layui-form-mid layui-word-aux">%</div>
        </div>
    </div>
</fieldset>

<fieldset class="layui-elem-field">
    <legend>
        <i class="layui-icon layui-icon-dollar"></i>
        <span>C2C交易</span>
    </legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <label class="layui-form-label">卖出手续费</label>
            <div class="layui-input-inline">
                <input type="text" name="c2c_sell_fee" autocomplete="off" class="layui-input"
                    value="@if(isset($setting['c2c_sell_fee'])){{$setting['c2c_sell_fee']}}@endif">
            </div>
            <div class="layui-form-mid layui-word-aux">%</div>
        </div>
    </div>
</fieldset>

<fieldset class="layui-elem-field">
    <legend>
        <i class="layui-icon layui-icon-rmb"></i>
        <span>法币交易</span>
    </legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <label class="layui-form-label">卖出手续费</label>
            <div class="layui-input-inline">
                <input type="text" name="legal_sell_fee" autocomplete="off" class="layui-input"
                    value="@if(isset($setting['legal_sell_fee'])){{$setting['legal_sell_fee']}}@endif">
            </div>
            <div class="layui-form-mid layui-word-aux">%</div>
        </div>
    </div>
</fieldset>

<fieldset class="layui-elem-field">
    <legend>
        <i class="layui-icon layui-icon-release"></i>
        <span>合约交易</span>
        <button class="layui-btn layui-btn-sm layui-btn-warm" type="button" id="currency_set">币种管理</button>
    </legend>
    <div class="layui-field-box">
        <div class="layui-form-item">
            <label class="layui-form-label"></label>
            <div class="layui-inline">
                <h5 style="color: #aba8a8;">请在币种管理,找到对应法币在交易对中进行设置</h5>
            <div class="layui-form-mid layui-word-aux"></div>
        </div>
    </div>
</fieldset>