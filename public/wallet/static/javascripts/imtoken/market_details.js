$(function () {

    var id = get_param('id');
    console.log(id)
    var address = $("#user_info").data("address")
    if (address != "") {
        layer_loading()
        $.ajax({
            type: "POST",
            url: laravel_api + "/quotation/info?id=" + id,
            id: id,
            data: {
                address: address
            },
            dataType: "json",
            success: function (data) {
                layer_close()
                console.log(data)
                if (data.type == "ok") {
                    xiangqing(data.message);
                    shuju(data.message);
                } else {
                    layer_msg(data.message)
                    return false;
                }
            }
        });
    }

    function xiangqing(arc) {
        var html = "";
        var str = (arc.quotes.USD.percent_change_24h).toString();
        html += "<div class='asset'>";
        html += '<div class="name">';
        html += '<div class="name_1">' + arc.name + '</div>';
        html += '<div class="name_2">' + arc.symbol + '</div>';
        html += '</div>';
        html += '<div class="price">';
        html += '<div class="name_1">￥' + arc.quotes.USD.price + '</div>';
        html += '<div class="name_2">$' + arc.total_supply + '</div>';
        html += '</div>';
        html += '<div class="rate">';
        if (str.substr(0, 1) == "-") {
            html += '<div>' + arc.quotes.USD.percent_change_24h + '%</div>';
        } else {
            html += '<div class="rate_green">+' + arc.quotes.USD.percent_change_24h + '%</div>';
        }
        html += '</div>';
        html += "</div>";

        $(".asset").append(html);

    }

    function shuju(abc) {
        var www = "";
        www += "<div class='no_msg'>" + abc.quotes.USD.percent_change_1h + "</div>";
        www += "<div class='hide no_msg'>" + abc.quotes.USD.percent_change_7d + "</div>";
        www += "<div class='hide no_msg'>" + abc.quotes.USD.percent_change_24h + "</div>";
        www += "<div class='hide no_msg'>" + abc.quotes.USD.volume_24h + "</div>";

        $(".charts_list").append(www);
    }
if(id==null){
    // chart
    var time0=[];
    var datas0= [];
    var time1=[];
    var datas1= [];
    var time2=[];
    var datas2= [];
    var time3=[];
    var datas3= [];
    $('.tabs li').click(function(){
        var index = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $(".chartbox>li").removeClass('on');
        $(".chartbox>li").eq(index).addClass('on');

    })
    // 获取日期
    function timestampToTime(timestamp) {
        var date = new Date(timestamp * 1000);//时间戳为10位需*1000，时间戳为13位的话不需乘1000
        Y = date.getFullYear() + '-';
        M = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
        D = date.getDate() + ' ';
        h = date.getHours() + ':';
        m = date.getMinutes() + ':';
        s = date.getSeconds();
        return Y+M+D+h+m+s;
    }
    //console.log(timestampToTime(1533484800));//2014-06-18 10:33:24
    var myChart0 = echarts.init(document.getElementById('chart0'));
    var myChart1 = echarts.init(document.getElementById('chart1'));
    var myChart2 = echarts.init(document.getElementById('chart2'));
    var myChart3 = echarts.init(document.getElementById('chart3'));
    $.ajax({
        type: "POST",
        url: laravel_api + "/historical_data",
        data: {
            address:address,
            id:id
        },
        dataType: "json",
        success: function(data){
            layer_close()
            console.log(data.message)
            var day = data.message.day;
            var week = data.message.week;
            var month =data.message.month;
            console.log(day[day.length-1].data)
            var tody=day[day.length-1].data;
            $(".open").html(tody.open);
            $(".hight").html(tody.hight);
            $(".low").html(tody.low);
            $(".volume").html(tody.volume);
            for (let i=0;i<day.length;i++){
                time1.push(timestampToTime(day[i].data.timestamp).substring(5,10));
                datas1.push(day[i].data.hight);
                // console.log(timestampToTime(day[i].data.timestamp).substring(5,10))
                // console.log(day[i].data.hight)
            }
            chart(time1,datas1);
            myChart1.setOption(option,true); 
            for (var i=0;i<week.length;i++){
                var weekDay = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];
                var myDate = new Date(Date.parse(timestampToTime(week[i].data.timestamp).substring(0,10).replace(/-/g, "/")));
                // console.log(weekDay[myDate.getDay()]);
                time2.push(weekDay[myDate.getDay()]);
                // console.log(time2)
                datas2.push(week[i].data.hight);
            }
            chart(time2,datas2);
            myChart2.setOption(option,true); 
            for (var i=0;i<month.length;i++){
                time3.push(timestampToTime(month[i].data.timestamp).substring(5,7)+"月");
                datas3.push(month[i].data.open);
            }
            chart(time3,datas3);
            myChart3.setOption(option,true); 
        }
    });
// 获取当前时间min分钟前的时间
function getNowFormatDate(min) {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    //前十分钟时间
    var minutes=parseInt(min);  
    var interTimes=minutes*60*1000;
    var interTimes=parseInt(interTimes);  
    date=new   Date(Date.parse(date)-interTimes);
    
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    var hour = date.getHours();
    var minutes = date.getMinutes();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    if (hour >= 0 && hour <= 9) {
            hour = "0" + hour;
    }
    if (minutes >= 0 && minutes <= 9) {
            minutes = "0" + minutes;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
            + " " + hour + seperator2 + minutes
            + seperator2 + date.getSeconds();
    return currentdate;
}
function getMindata(){
     $.ajax({
        type: "POST",
        url: laravel_api + "/transaction/deal",
        data: {
            address:address,
            id:id
        },
        dataType: "json",
        success: function(data){
            console.log(data,123)
            layer_close()
            var complete = data.message.complete;
            // console.log(complete)
            var result=0;
            for (let i=0;i<complete.length;i++){
                result += Number(complete[i].number);
            }
            console.log(result)
            $(".volume").html(result);
            for(let i=0;i<10;i++){
            //    console.log (getNowFormatDate(i*10).substring(10,16));
                time0.push(getNowFormatDate(i*10).substring(10,16))
                datas0.push(complete[i].price);
            }
            time0=time0.reverse();
            datas0=datas0.reverse();
            chart(time0,datas0);
            myChart0.setOption(option,true); 
        }
    });
}
   
getMindata();
// 图表

    function chart(time,datas){
        option = {
            toolbox: {
                show : true,
                feature : {
                    // mark : {show: true},
                    // dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar']},
                    restore : {show: true},
                    // saveAsImage : {show: true}
                }
            },
            calculable : true,
            tooltip : {
                trigger: 'axis',
                formatter: "时间：{b} <br>价格: {c}"
            },
            xAxis : [
                {
                    type : 'category',
                    axisLabel : {
                        formatter: '{value}'
                    },
                    axisLine:{
                        lineStyle:{
                            color:'#FFFFFF',
                        }
                    },
                    axisLabel: {
                        show: true,
                        textStyle: {
                            color: '#fff'
                        }
                    },
                    data:time
        }
            ],
            yAxis : [
                {
                    type : 'value',
                    axisLine:{
                        lineStyle:{
                            color:'#FFFFFF',
                        }
                    },
                    axisLabel : {
                        textStyle: {
                            color: '#fff'
                        }
                    },
                    boundaryGap : false,
                }
            ],
            series : [
                {
                    name:'行情趋势',
                    type:'line',
                    smooth:true,
                    itemStyle : {  
                        normal : {  
                            color:'#84c7f6',  
                            lineStyle:{  
                                color:'#84c7f6'  
                            }  
                        }  
                    }, 
                    data:datas
                }
            ]
        };
    }
}
});
