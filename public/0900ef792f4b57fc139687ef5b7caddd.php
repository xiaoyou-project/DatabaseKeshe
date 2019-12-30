<?php /*a:1:{s:18:"../view/tonji.html";i:1577686982;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <script src="https://cdn.bootcss.com/jquery/3.4.0/jquery.js"></script>
        <script src="/js/echarts/echarts.min.js"></script>
        <script src="/js/echarts/map/china.js"></script>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <link rel="stylesheet" href="./css/font.css">
        <link rel="stylesheet" href="./css/xadmin.css">
        <script src="./lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="./js/xadmin.js"></script>

        <!--[if lt IE 9]>
          <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
          <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-sm12 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">学生地域分布</div>
                        <div class="layui-card-body" style="min-height:500px;">
                            <div id="main1" class="layui-col-sm12" style="height:500px;"></div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm12 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">学院人数分布</div>
                        <div class="layui-card-body" style="min-height:500px;">
                            <div id="main3" class="layui-col-sm12" style="height:500px;"></div>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm12 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">奖励处罚情况分布</div>
                        <div class="layui-card-body" style="min-height:500px;">
                            <div id="main5" class="layui-col-sm12" style="height:500px;"></div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm12 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">学籍变更分布</div>
                        <div class="layui-card-body" style="min-height:500px;">
                            <div id="main6" class="layui-col-sm12" style="height:500px;"></div>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm12 layui-col-md6" style="width:100%">
                    <div class="layui-card">
                        <div class="layui-card-header">班级分布</div>
                        <div class="layui-card-body" style="min-height:280px;">
                            <div id="main2" class="layui-col-sm12" style="height:300px;width:100%"></div>
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm12 layui-col-md6" style="width:100%">
                    <div class="layui-card">
                        <div class="layui-card-header">成绩分布</div>
                        <div class="layui-card-body" style="min-height: 500px;">
                            <div id="main4" class="layui-col-sm12" style="height: 500px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <script type="text/javascript">


        // 学生地域分布
        var myChart = echarts.init(document.getElementById('main1'));
        myChart.hideLoading();
        $.get("/tools/addrdata",function (result){
            myChart.setOption(option = {
                tooltip: {
                    trigger: 'item',
                    formatter: '{b}<br/>{c} (人)'
                },
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    left: 'right',
                    top: 'center',
                    feature: {
                        dataView: {readOnly: false},
                        restore: {},
                        saveAsImage: {}
                    }
                },
                visualMap: {
                    min: 0,
                    max: 2500,
                    text:['High','Low'],
                    realtime: false,
                    calculable: true,
                    inRange: {
                        color: ['lightskyblue','yellow', 'orangered']
                    }
                },
                series: [
                    {
                        name: '学生地域分布',
                        type: 'map',
                        mapType: 'china', // 自定义扩展图表类型
                        itemStyle:{
                            normal:{label:{show:true}},
                            emphasis:{label:{show:true}}
                        },
                        data:JSON.parse(result),
                    }
                ]
            });
        });

        //学生班级分布
        var myChart2 = echarts.init(document.getElementById('main2'));
        $.get("/tools/classData",function (result) {
            // 指定图表的配置项和数据
            var data = JSON.parse(result);
            var arr = [['性别', '女', '男']];
            //遍历json数据
            for (key in data) {
                arr.push(data[key].split(","));
            }
            var option = {
                legend: {},
                tooltip: {},
                // data: ['计算机科学与技术', '网络工程', '信息安全', '音乐与舞蹈学', '美术学', '设计学', '汉语国际教育','日语','翻译','电气工程及其自动化','电子信息工程','通信工程','应用化学','化学工程与工艺','环境工程'],
                dataset: {
                    source:arr
                },
                xAxis: {type: 'category',
                    axisLabel:{
                        fontSize:'7',
                        align:'center'
                    }
                },
                yAxis: {},
                // Declare several bar series, each will be mapped
                // to a column of dataset.source by default.
                series: [
                    {type: 'bar'},
                    {type: 'bar'}
                ]
            };
            myChart2.setOption(option);
        });

        // // 基于准备好的dom，初始化echarts实例
        var myChart3 = echarts.init(document.getElementById('main3'));
        // 指定图表的配置项和数据
        $.get("/tools/departmentdata",function (result) {
            var data = JSON.parse(result);
            var department=[];
            for(key in data){
               department.push(data[key].name);
            }
            var option = {
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    left: 'left',
                    data:department
                },
                series: [
                    {
                        name: '学院名称',
                        type: 'pie',
                        radius: '55%',
                        center: ['50%', '60%'],
                        data:data,
                        itemStyle: {
                            emphasis: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: 'rgba(0, 0, 0, 0.5)'
                            }
                        }
                    }
                ]
            };
            // 使用刚指定的配置项和数据显示图表。
            myChart3.setOption(option);
        });


        // 所有班级成绩分布
        var myChart4= echarts.init(document.getElementById('main4'));
        $.get("/tools/gradedata",function (result) {
            var data=JSON.parse(result);
            // 指定图表的配置项和数据
            var option = {
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['思想道德修养', '高等数学', '大学英语','计算机']
                },
                toolbox: {
                    show: true,
                    feature: {
                        dataView: {show: true, readOnly: false},
                        magicType: {show: true, type: ['line', 'bar']},
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        data: data['class'],
                        axisLabel:{
                            fontSize:'7',
                            align:'center'
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series:data['data']
            };
            // 使用刚指定的配置项和数据显示图表。
            myChart4.setOption(option);
        });


        //奖励处罚情况分布
        var myChart5= echarts.init(document.getElementById('main5'));
        $.get("/tools/rewarddata",function (result) {
            var data=JSON.parse(result);
            // 指定图表的配置项和数据
            var option = {
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: data['option']
                },
                series: [
                    {
                        name: '处罚情况',
                        type: 'pie',
                        selectedMode: 'single',
                        radius: [0, '30%'],

                        label: {
                            normal: {
                                position: 'inner'
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data: data['punish']
                    },
                    {
                        name: '奖励情况',
                        type: 'pie',
                        radius: ['40%', '55%'],
                        data: data['reward']
                    }
                ]
            };
            // 使用刚指定的配置项和数据显示图表。
            myChart5.setOption(option);
        });


        //学籍变更分布
        var myChart6= echarts.init(document.getElementById('main6'));
        $.get("/tools/changedata",function (result) {
            var data=JSON.parse(result);
            // 指定图表的配置项和数据
            var option = {
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    x : 'center',
                    y : 'bottom',
                    data:data['change']
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {
                            show: true,
                            type: ['pie', 'funnel']
                        },
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                series : [
                    {
                        name:'学籍变更分布',
                        type:'pie',
                        radius : [30, 110],
                        roseType : 'area',
                        data:data['data']
                    }
                ]
             };
            // 使用刚指定的配置项和数据显示图表。
            myChart6.setOption(option);
        });
    </script>
    </body>
</html>