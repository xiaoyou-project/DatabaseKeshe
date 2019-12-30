<?php /*a:1:{s:24:"../view/reward-list.html";i:1577263606;}*/ ?>
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
    <script src="./lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="./js/xadmin.js"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="mybg">
<div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">演示</a>
            <a>
              <cite>导航元素</cite></a>
          </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form">
                        <div class="layui-input-inline">
                            <select lay-verify="choose" name="optioin">
                                <option value="">选择要查找的内容</option>
                                <option value="student">学号</option>
                                <option value="name">姓名</option>
                                <option value="change">奖励类型</option>
                                <option value="time">时间</option>
                            </select>
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <input lay-verify="message" type="text" name="message"  placeholder="输入信息" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-inline layui-show-xs-block">
                            <button class="layui-btn"  lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
                        </div>
                    </form>
                </div>
                <div class="layui-card-header">
                    <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                    <button class="layui-btn" onclick="xadmin.open('添加记录','reward-add',430,480)"><i class="layui-icon"></i>添加记录</button>
                    <button class="layui-btn layui-btn-normal" onclick="window.location.href='reward-list'">显示所有</button>
                </div>
                <div class="layui-card-body layui-table-body layui-table-main">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" lay-filter="checkall" name="" lay-skin="primary">
                            </th>
                            <th>ID</th>
                            <th>学号</th>
                            <th>姓名</th>
                            <th>奖励情况</th>
                            <th>记录时间</th>
                            <th>备注</th>
                            <th>操作</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach($list as $key): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="id" value="<?php echo htmlentities($key['ID']); ?>"   lay-skin="primary">
                            </td>
                            <td><?php echo htmlentities($key['ID']); ?></td>
                            <td><?php echo htmlentities($key['STUDENTID']); ?></td>
                            <td><?php echo htmlentities($key['NAME']); ?></td>
                            <td><?php echo htmlentities($key['DESCRIPTION']); ?></td>
                            <td><?php echo htmlentities($key['REC_TIME']); ?></td>
                            <td><?php echo htmlentities($key['REASON']); ?></td>
                            <td class="td-manage">
                                <a title="编辑"  onclick="xadmin.open('编辑','reward-edit?id=<?php echo htmlentities($key['ID']); ?>',430,550);" href="javascript:;">
                                    <i class="layui-icon">&#xe642;</i>
                                </a>
                                <a title="删除" onclick="member_del(this,'<?php echo htmlentities($key['ID']); ?>')" href="javascript:;">
                                    <i class="layui-icon">&#xe640;</i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="layui-card-body ">
                    <div class="page">
                        <div>
                            <form class="layui-form">
                                <p class="layui-laypage-count"> 当前第<?php echo htmlentities($id+1); ?>页/共<?php echo htmlentities($page); ?>页(共 <?php echo htmlentities($num); ?>条记录)</p>
                                <?php if($id>0): ?>
                                <a class="prev" href="reward-list?id=<?php echo htmlentities($id-1); ?>">上一页</a>
                                <?php endif; 
                                if($page>7){
                                for($i=($id-3);$i<=$id;$i++){
                                if($i>=1){
                                if($i==($id+1))
                                echo '<span class="current">'.$i.'</span>';
                                else
                                echo '<a class="num" href="reward-list?id='.($i-1).'">'.$i.'</a>';
                                }
                                }
                                for($i=($id+1);$i<=($id+5);$i++){
                                if($i<=$page){
                                if($i==($id+1))
                                echo '<span class="current">'.$i.'</span>';
                                else
                                echo '<a class="num" href="reward-list?id='.($i-1).'">'.$i.'</a>';
                                }
                                }
                                }else{
                                for($i=1;$i<=$page;$i++){
                                if($i==($id+1))
                                echo '<span class="current">'.$i.'</span>';
                                else
                                echo '<a class="num" href="reward-list?id='.($i-1).'">'.$i.'</a>';
                                }
                                }
                                 if(($id+1)!=$page): ?>
                                <a class="next" href="reward-list?id=<?php echo htmlentities($id+1); ?>">下一页</a>
                                <?php endif; ?>
                                <div class="layui-inline layui-show-xs-block">
                                    <input style="width:90px;height:37px" lay-verify="message" type="text" name="message" placeholder="输入页数" autocomplete="off" class="layui-input">
                                </div>
                                <div class="layui-inline layui-show-xs-block">
                                    <button style="width:41px;height:37px" class="layui-btn" lay-submit lay-filter="goto"><i class="layui-icon">&#xe609;</i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    layui.use(['form', 'layer'],
        function() {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer;
            //自定义验证规则
            form.verify({
                choose:function (value) {
                    if(!value)
                        return '请选择搜索项！'
                },
                message:function (value) {
                    if(!value)
                        return '请输入要搜索的内容';
                }

            });
            //监听提交
            form.on('submit(sreach)',
                function(data) {
                    //layer.msg('搜索!', {icon: 1, time: 1000});
                    window.location.href="reward-list?choose="+data.field.optioin+"&message="+data.field.message;
                    return false;
                });
            //跳转页面
            form.on('submit(goto)',
                function(data) {
                    window.location.href="reward-list?id="+(data.field.message-1);
                    return false;
                });
        });
    layui.use(['laydate','form'], function(){
        var  form = layui.form;
        // 监听全选
        form.on('checkbox(checkall)', function(data){

            if(data.elem.checked){
                $('tbody input').prop('checked',true);
            }else{
                $('tbody input').prop('checked',false);
            }
            form.render('checkbox');
        });
    });

    /*用户-删除*/
    function member_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            //发异步删除数据
            console.log(id);
            var result='err';
            del=$.ajax({
                type:'get',
                url:'/tools/changdel?',
                data:{
                    id:id,
                    type:'reward'
                },
                success:function (msg) {
                    result=msg;
                },
                error:function () {
                    layer.msg('删除失败!', {icon: 2, time: 1000});
                }
            });
            $.when(del).done(function (data) {
                if(result==='ok') {
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!', {icon: 1, time: 1000});
                }else{
                    layer.msg('删除失败!', {icon: 2, time: 1000});
                }
            });
        });
    }

    /*批量删除*/
    function delAll (argument) {
        var ids = [];
        // 获取选中的id
        $('tbody input').each(function(index, el) {
            if($(this).prop('checked')){
                ids.push($(this).val())
            }
        });
        layer.confirm('确认要删除吗？',function(index){
            //捉到所有被选中的，发异步进行删除
            //这里获取选中的id
            del=$.ajax({
                type: 'post',
                url:'/tools/changmdel',
                data:{ids:ids.toString(),type:'reward'},
                success:function (msg) {
                    console.log("获取到的数据"+msg)
                },
                error:function () {
                    layer.msg('删除失败！', {icon: 2});
                }
            });
            $.when(del).done(function (data) {
                console.log("最终数据"+data)
                if(data==='ok'){
                    layer.msg('删除成功！', {icon: 1});
                    $(".layui-form-checked").not('.header').parents('tr').remove();
                }else{
                    layer.msg('删除失败！', {icon: 2});
                }
            });
        });
    }
</script>
</html>