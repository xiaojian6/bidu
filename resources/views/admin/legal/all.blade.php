@extends('admin._layoutNew')

@section('page-head')
    <style>
        iframe {
            border: none;
            width: 100%;
            height: 600px;
        }
    </style>
@endsection

@section('page-content')
<div class="layui-tab">
  <ul class="layui-tab-title">
    <li class="layui-this">商家发布</li>
    <li>交易记录</li>
  </ul>
  <div class="layui-tab-content">
    <div class="layui-tab-item layui-show">
        <iframe src="/admin/legal"></iframe>
    </div>
    <div class="layui-tab-item">
        <iframe src="/admin/legal_deal"></iframe>  
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    layui.use(['element', 'layer'], function () {
        var element = layui.element
            ,layer = layui.layer
            ,$ = layui.$
        var height = $(document).height();
        $('iframe').height(height - 100);
    });
</script>
@endsection
