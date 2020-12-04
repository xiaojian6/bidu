@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div class="layui-inline layui-row">
        <label class="layui-form-label">用户统计</label>
    </div>
    <div id="main" style="width: 600px;height:400px;"></div>
@endsection

@section('scripts')
<script src="{{URL("js/echarts/echarts.min.js")}}"></script>
<script type="text/javascript">
console.log(1);
    $(function(){
            $.ajax({
                url:'data',
                type:'post',
                dataType:'json',
                success:function (res) {
                    console.log(res);
                    layui.use('form',function(){
                        var form=layui.form;
                        form.render();
                    })
                }
            });
    })

    var myChart = echarts.init(document.getElementById('main'));

    myChart.setOption({
        title: {
            text: '示例'
        },
        tooltip: {},
        xAxis: {
            data: ['衬衫', '羊毛衫', '雪纺衫', '裤子', '高跟鞋', '袜子']
        },
        yAxis: {},
        series: [{
            name: '销量',
            type: 'bar',
            data: [5, 20, 36, 10, 10, 20]
        }]
    });
</script>

@endsection