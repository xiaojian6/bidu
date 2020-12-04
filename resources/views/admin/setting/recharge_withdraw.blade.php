
<div class="layui-form-item">
    <label class="layui-form-label">充币账户</label>
    <div class="layui-input-block">
        <div class="layui-input-inline">
            <select name="recharge_to_balance" {{$setting['recharge_to_balance'] > 0 ? 'disabled' : ''}}>
                <option value="1" {{$setting['recharge_to_balance'] == 1 ? 'selected' : ''}}>法币资产</option>
                <option value="2" {{$setting['recharge_to_balance'] == 2 ? 'selected' : ''}}>币币资产</option>
                <option value="3" {{$setting['recharge_to_balance'] == 3 ? 'selected' : ''}}>杠杆资产</option>
            </select>
        </div>
    </div>
</div>
<div class="layui-form-item">
    <label class="layui-form-label">提币账户</label>
    <div class="layui-input-block">
        <div class="layui-input-inline">
            <select name="withdraw_from_balance" {{$setting['withdraw_from_balance'] > 0 ? 'disabled' : ''}}>
                <option value="1" {{$setting['withdraw_from_balance'] == 1 ? 'selected' : ''}}>法币资产</option>
                <option value="2" {{$setting['withdraw_from_balance'] == 2 ? 'selected' : ''}}>币币资产</option>
                <option value="3" {{$setting['withdraw_from_balance'] == 3 ? 'selected' : ''}}>杠杆资产</option>
            </select>
        </div>
    </div>
</div>

