<?php /*a:1:{s:24:"../view/member-edit.html";i:1577173225;}*/ ?>
<!DOCTYPE html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面-X-admin2.2</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
        <link rel="stylesheet" href="./css/font.css">
        <link rel="stylesheet" href="./css/xadmin.css">
        <script type="text/javascript" src="./lib/layui/layui.js" charset="utf-8"></script>
        <script type="text/javascript" src="./js/xadmin.js"></script>
        <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
        <!--[if lt IE 9]>
            <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
            <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
        <![endif]--></head>
    
    <body>
        <div class="layui-fluid">
            <div class="layui-row">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">学号</label>
                        <div class="layui-input-block">
                            <input value="<?php echo htmlentities($data['STUDENTID']); ?>" id="studentid" type="text" name="studentid" lay-verify="studentid" autocomplete="off" placeholder="请输入学号" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">姓名</label>
                        <div class="layui-input-block">
                            <input value="<?php echo htmlentities($data['NAME']); ?>" id="name" name="name" type="text"  lay-verify="name" autocomplete="off" placeholder="请输入姓名" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">生日</label>
                        <div class="layui-input-inline">
                            <input value="<?php echo htmlentities($data['BIRTHDAY']); ?>" id="birthday" name="birthday" type="text" lay-verify="birthday" class="layui-input" placeholder="MM-dd" lay-key="1">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">性别</label>
                        <div class="layui-input-block">
                            <input type="radio" name="sex" value="男" title="男" <?php if($data['SEX']=="男"): ?>checked=""<?php endif; ?>><div class="layui-unselect layui-form-radio layui-form-radioed"><i class="layui-anim layui-icon layui-anim-scaleSpring"></i><div>男</div></div>
                            <input type="radio" name="sex" value="女" title="女" <?php if($data['SEX']=="女"): ?>checked=""<?php endif; ?>><div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><div>女</div></div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">班级</label>
                        <div class="layui-input-inline">
                            <select id="class" name="class" lay-verify="class">
                                <option value="">选择班级</option>
                                <?php foreach($class as $c): ?>
                                <option value="<?php echo htmlentities($c['NAME']); ?>" <?php if($data['CLASS']==$c['NAME']): ?>selected="selected"<?php endif; ?>><?php echo htmlentities($c['NAME']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">院系</label>
                        <div class="layui-input-inline">
                            <select name="department" id="department"  lay-verify="department">
                                <option value="">选择院系</option>
                                <?php foreach($department as $c): ?>
                                <option value="<?php echo htmlentities($c['NAME']); ?>" <?php if($data['DEPARTMENT']==$c['NAME']): ?>selected="selected"<?php endif; ?>><?php echo htmlentities($c['NAME']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">籍贯</label>
                        <div class="layui-input-block">
                            <input value="<?php echo htmlentities($data['NATIVE_PLACE']); ?>" id="native"  lay-verify="native" type="text" name="native" lay-verify="title" autocomplete="off" placeholder="请输入籍贯" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <button lay-filter="edit" lay-submit="" class="layui-btn layui-btn-fluid">保存修改</button>
                    </div>
                </form>
            </div>
        </div>
        <!--layul日期选择器-->
        <script>
            layui.use('laydate', function(){
                var laydate = layui.laydate;
                //执行一个laydate实例
                laydate.render({
                    elem: '#birthday'
                    ,format: 'MM-dd' //可任意组合
                });
            });
        </script>


        <script>layui.use(['form', 'layer'],
            function() {
                $ = layui.jquery;
                var form = layui.form,
                layer = layui.layer;

                //自定义验证规则
                form.verify({
                    studentid:function (value) {
                        if(isNaN(parseInt(value))){
                            return '请输入正确的学号!';
                        }
                    },
                    name: function(value) {
                        if (value.length===0) {
                            return '请输入姓名!';
                        }
                    },
                    birthday: function(value) {
                        if (value.length===0){
                            return '请输入生日信息';
                        }
                    },
                    class:function (value) {
                        if(!value){
                            return '你还没选择班级!';
                        }
                    },
                    department:function (value) {
                        if(!value){
                            return '你还没选择院系！';
                        }

                    },
                    native:function (value) {
                        if(value.length===0){
                            return '请输入籍贯信息';
                        }
                    }
                });

                //监听提交
                form.on('submit(edit)',
                    function(data) {
                        console.log(data.field);
                        //使用ajax传递数据
                        senddata=$.ajax({
                            type:"POST",
                            url:"/tools/add",
                            data:{
                                id:data.field.studentid,
                                name:data.field.name,
                                birthday:data.field.birthday,
                                sex:data.field.sex,
                                class:data.field.class,
                                department:data.field.department,
                                native:data.field.native,
                                option:'edit'
                            },
                            success:function (msg) {
                                //发异步，把数据提交给php
                                console.log(msg);
                                if(msg==='ok'){
                                    parent.location.reload();
                                    layer.alert('修改成功！', {
                                            icon: 6
                                        },
                                        function() {
                                            // 获得frame索引
                                            var index = parent.layer.getFrameIndex(window.name);
                                            //刷新页面
                                            parent.layer.close(index);
                                        });
                                }else {
                                    layer.alert('修改失败！', {icon: 5});
                                }
                            },
                            error:function () {
                                layer.alert('修改失败！',{icon: 5})
                            }
                        });
                        //等待ajax执行完毕
                        $.when(senddata).done(function (value) {

                        });
                        return false;
                    });
            });</script>
        <script>var _hmt = _hmt || []; (function() {
                var hm = document.createElement("script");
                hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hm, s);
            })();</script>
    </body>

</html>