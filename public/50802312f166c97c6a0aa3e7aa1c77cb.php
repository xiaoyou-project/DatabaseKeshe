<?php /*a:1:{s:23:"../view/admin-edit.html";i:1577545123;}*/ ?>
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
                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input value="<?php echo htmlentities($data['username']); ?>" id="username" type="text" name="username" lay-verify="username" autocomplete="off" placeholder="请输入用户名" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-item">
                    <label class="layui-form-label">昵称</label>
                    <div class="layui-input-block">
                        <input value="<?php echo htmlentities($data['nickname']); ?>" id="nickname" type="text" name="nickname" lay-verify="nickname" autocomplete="off" placeholder="请输入昵称" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-item">
                    <label class="layui-form-label">邮件</label>
                    <div class="layui-input-block">
                        <input value="<?php echo htmlentities($data['email']); ?>" id="email" type="text" name="email" lay-verify="email" autocomplete="off" placeholder="请输入邮件地址" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-form-item">
                    <label class="layui-form-label">手机</label>
                    <div class="layui-input-block">
                        <input value="<?php echo htmlentities($data['phone']); ?>" id="phone" type="text" name="phone" lay-verify="phone" autocomplete="off" placeholder="请输入手机号" class="layui-input">
                    </div>
                </div>
            </div>
            <div class="layui-form-item">
                <button lay-filter="edit" lay-submit="" class="layui-btn layui-btn-fluid">保存修改</button>
            </div>
        </form>
    </div>
</div>

<script>layui.use(['form', 'layer'],
    function() {
        $ = layui.jquery;
        var form = layui.form,
            layer = layui.layer;

        //自定义验证规则
        form.verify({
            username:function (value) {
                if (value.length===0){
                    return '请输入用户名！';
                }
            },
            nickname: function(value) {
                if (value.length===0){
                    return '请输入昵称！';
                }
            },
            email:function (value) {
                if(!value){
                    return '请输入邮件地址！';
                }

            },
            phone:function (value) {
                if(value.length===0){
                    return '请输入手机号！';
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
                    url:"/tools/changAdd",
                    data:{
                        id:<?php echo htmlentities($id); ?>,
                        username:data.field.username,
                        nickname:data.field.nickname,
                        email:data.field.email,
                        phone:data.field.phone,
                        option:'edit',
                        type:'admin'
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
</body>
</html>