<div class="layui-form-item">
    <label class="layui-form-label">代理商最高层级</label>
    <div class="layui-input-inline">
        <input type="number" name="agent_max_level" autocomplete="off" class="layui-input" step="1"
               value="@if(isset($setting['agent_max_level'])){{$setting['agent_max_level']}}@endif">
    </div>
    <div class="layui-form-mid layui-word-aux">达到最高层级的代理商将无法发展代理商</div>
</div>