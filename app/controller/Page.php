<?php
namespace app\controller;


use think\facade\Session;
use app\BaseController;
use think\facade\Db;
use think\Template;
use think\facade\View;
use think\facade\Request;

class Page extends BaseController
{

    /*欢迎页面*/
    public function welcome()
    {
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
        $time=date("Y-m-d H:i");
        //获取学生数据
        $data['student']=Db::table('STUDENT')->count();
        $data['class']=Db::table('CLASS')->count();
        $data['department']=Db::table('DEPARTMENT')->count();
        $data['change']=Db::table('CHANGE')->count();
        $data['punishment']=Db::table('PUNISHMENT')->count();
        $data['reward']=Db::table('REWARD')->count();
        return $template ->fetch('../view/welcome',['name'=>!Session::get('name'),'time'=>$time,'data'=>$data]);
    }

    /*欢迎页面*/
    public function tonji()
    {
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
            return $template ->fetch('../view/tonji');
    }


    /*会员列表*/
    public function member_list()
    {
        if(!Session::has('name'))
            return redirect('/');
        $message="";
        $choose=Request::has('choose','get') ? Request::get('choose') : "";
        if($choose){
            $message=Request::get('message');
            Session::set('message',$message);
            Session::set('choose',$choose);
        }else{
            /*利用session来避免查找无法分页问题*/
            if(!Request::has('id','get')){
                if(Session::has('message')){Session::delete('message');Session::delete('choose');}
            }else{
                if(Session::has('message')){$message=Session::get('message');$choose=Session::get('choose');}
            }
        }
        //var_dump($choose);
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
        //这里根据要求来查询数据
        switch ($choose){
            case "student":
                $num=Db::table('STUDENT')->where('STUDENTID',$message)->count();
                $list = Db::query("select * from STUDENT WHERE STUDENTID=".$message." ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "birthday":
                $num=Db::table('STUDENT')->where('BIRTHDAY',$message)->count();
                $list = Db::query("select * from STUDENT WHERE BIRTHDAY='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "department":
                $ids=Db::table('DEPARTMENT')->where('NAME',$message)->find()['ID'];
                if($ids==='') $ids=0;
                $num=Db::table('STUDENT')->where('DEPARTMENT',$ids)->count();
                $list = Db::query("select * from STUDENT WHERE 	DEPARTMENT=".$ids." ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "name":
                $num=Db::table('STUDENT')->where('NAME',$message)->count();
                $list = Db::query("select * from STUDENT WHERE NAME='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "class":
                $ids=Db::table('CLASS')->where('NAME',$message)->find()['ID'];
                if($ids==='') $ids=0;
                $num=Db::table('STUDENT')->where('CLASS',$ids)->count();
                $list = Db::query("select * from STUDENT WHERE CLASS=".$ids." ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            default:
                $num=Db::table('STUDENT')->count();
                $list = Db::query("select * from STUDENT ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
        }
        //分页数
        $page=ceil($num/10);
        //替换外键
        foreach ($list as $key=>$vlaue){
            //替换班级和学院
            $list[$key]['CLASS']=Db::table('CLASS')->where('ID',$list[$key]['CLASS'])->find()['NAME'];
            $list[$key]['DEPARTMENT']=Db::table('DEPARTMENT')->where('ID',$list[$key]['DEPARTMENT'])->find()['NAME'];
            //判断是否有获奖情况
            if(Db::table('REWARD')->where('STUDENTID',$list[$key]['STUDENTID'])->find())
                $list[$key]['REWARD']='查看';
            else
                $list[$key]['REWARD']='无';
            //判断是否有惩罚情况
            if(Db::table('PUNISHMENT')->where('STUDENTID',$list[$key]['STUDENTID'])->find())
                $list[$key]['PUNISHMENT']='查看';
            else
                $list[$key]['PUNISHMENT']='无';
            //判断是否有学籍变更的情况
            if(Db::table('CHANGE')->where('STUDENTID',$list[$key]['STUDENTID'])->find())
                $list[$key]['CHANGE']='查看';
            else
                $list[$key]['CHANGE']='无';
        }
        //显示信息
        return $template ->fetch('../view/member-list',['list'=>$list,'page'=>$page,'num'=>$num,'id'=>$id]);
    }

    /*添加学生*/
    public function member_add()
    {
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
        //查询所有的班级
        $class = Db::query("select * from CLASS;");
        //查询所有部门
        $department = Db::query("select * from DEPARTMENT;");
        return $template ->fetch('../view/member-add',['class'=>$class,'department'=>$department]);
    }

    /*编辑学生*/
    public function member_edit()
    {
        if(!Session::has('name'))
            return redirect('/');
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
        $result=Db::table('STUDENT')->where('STUDENTID',$id)->find();
        //替换外键
        $result['CLASS']=Db::table('CLASS')->where('ID',$result['CLASS'])->find()['NAME'];
        $result['DEPARTMENT']=Db::table('DEPARTMENT')->where('ID',$result['DEPARTMENT'])->find()['NAME'];
       // var_dump($result);
        //查询所有的班级
        $class = Db::query("select * from CLASS;");
        //查询所有部门
        $department = Db::query("select * from DEPARTMENT;");
        return $template ->fetch('../view/member-edit',['class'=>$class,'department'=>$department,'data'=>$result]);
    }

    /*所有的学籍变更记录*/
    public function chang_list(){
        if(!Session::has('name'))
            return redirect('/');
        $message="";
        $choose=Request::has('choose','get') ? Request::get('choose') : "";
        if($choose){
            $message=Request::get('message');
            Session::set('message',$message);
            Session::set('choose',$choose);
        }else{
            /*利用session来避免查找无法分页问题*/
            if(!Request::has('id','get')){
                if(Session::has('message')){Session::delete('message');Session::delete('choose');}
            }else{
                if(Session::has('message')){$message=Session::get('message');$choose=Session::get('choose');}
            }
        }
        //  var_dump($choose);
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
//        //这里根据要求来查询数据
        switch ($choose){
            case "student":
                $num=Db::table('CHANGE')->where('STUDENTID',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE AND A.STUDENTID=".$message." ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "name":
                $num = Db::query("SELECT count(*) FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE AND C.NAME='".$message."'")[0]['count(*)'];
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE AND C.NAME='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "change":
                $num = Db::query("SELECT count(*) FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE AND B.DESCRIPTION='".$message."'")[0]['count(*)'];
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE AND B.DESCRIPTION='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "time":
                $num=Db::table('CHANGE')->where('REC_TIME',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE AND A.REC_TIME='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            default:
                //获取记录条数
                $num=Db::table('CHANGE')->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
        }
        $page=ceil($num/10);
        //$list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
        return $template ->fetch('../view/chang-list',['list'=>$list,'page'=>$page,'num'=>$num,'id'=>$id]);
    }
    public function chang_add(){
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
        //查询所有的代码
        $chang = Db::query("select * from CHANGE_CODE;");
        return $template ->fetch('../view/chang-add',['chang'=>$chang]);
    }
    public function chang_edit(){
        if(!Session::has('name'))
            return redirect('/');
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
        $result=Db::query('SELECT A.STUDENTID,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B WHERE A.CHANGE=B.CODE AND A.ID='.$id);
        //var_dump($result);
        //查询所有的代码
        $chang = Db::query("select * from CHANGE_CODE;");
        return $template ->fetch('../view/chang-edit',['chang'=>$chang,'data'=>$result[0],'id'=>$id]);
    }
    /*奖励管理*/
    public function reward_list(){
        if(!Session::has('name'))
            return redirect('/');
        $message="";
        $choose=Request::has('choose','get') ? Request::get('choose') : "";
        if($choose){
            $message=Request::get('message');
            Session::set('message',$message);
            Session::set('choose',$choose);
        }else{
            /*利用session来避免查找无法分页问题*/
            if(!Request::has('id','get')){
                if(Session::has('message')){Session::delete('message');Session::delete('choose');}
            }else{
                if(Session::has('message')){$message=Session::get('message');$choose=Session::get('choose');}
            }
        }
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
//        //这里根据要求来查询数据
        switch ($choose){
            case "student":
                $num=Db::table('REWARD')->where('STUDENTID',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM REWARD A,REWARD_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND A.STUDENTID=".$message." ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "name":
                $num = Db::query("SELECT count(*) FROM REWARD A,REWARD_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND C.NAME='".$message."'")[0]['count(*)'];
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM REWARD A,REWARD_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND C.NAME='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "change":
                $num = Db::query("SELECT count(*) FROM REWARD A,REWARD_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND B.DESCRIPTION='".$message."'")[0]['count(*)'];
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM REWARD A,REWARD_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND B.DESCRIPTION='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "time":
                $num=Db::table('REWARD')->where('REC_TIME',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM REWARD A,REWARD_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND A.REC_TIME='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            default:
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM REWARD A,REWARD_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                //获取记录条数
                $num=Db::table('REWARD')->count();
                break;
        }
        //分页数
        $page=ceil($num/10);
        //$list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
        return $template ->fetch('../view/reward-list',['list'=>$list,'page'=>$page,'num'=>$num,'id'=>$id]);
    }
    public function reward_add(){
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
        //查询所有的代码
        $chang = Db::query("select * from REWARD_LEVELS;");
        return $template ->fetch('../view/reward-add',['chang'=>$chang]);
    }
    public function reward_edit(){
        if(!Session::has('name'))
            return redirect('/');
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
        $result=Db::query('SELECT A.STUDENTID,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM REWARD A,REWARD_LEVELS B WHERE A.LEVELS=B.CODE AND A.ID='.$id);
        //var_dump($result);
        //查询所有的代码
        $chang = Db::query("select * from REWARD_LEVELS;");
        return $template ->fetch('../view/reward-edit',['chang'=>$chang,'data'=>$result[0],'id'=>$id]);
    }
    /*处罚管理*/
    public function punish_list(){
        if(!Session::has('name'))
            return redirect('/');
        $message="";
        $choose=Request::has('choose','get') ? Request::get('choose') : "";
        if($choose){
            $message=Request::get('message');
            Session::set('message',$message);
            Session::set('choose',$choose);
        }else{
            /*利用session来避免查找无法分页问题*/
            if(!Request::has('id','get')){
                if(Session::has('message')){Session::delete('message');Session::delete('choose');}
            }else{
                if(Session::has('message')){$message=Session::get('message');$choose=Session::get('choose');}
            }
        }
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
//        //这里根据要求来查询数据
        switch ($choose){
            case "student":
                $num=Db::table('PUNISHMENT')->where('STUDENTID',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME,A.ENABLE FROM PUNISHMENT A,PUNISH_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND A.STUDENTID=".$message." ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "name":
                $num = Db::query("SELECT count(*) FROM PUNISHMENT A,PUNISH_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND C.NAME='".$message."'")[0]['count(*)'];
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME,A.ENABLE FROM PUNISHMENT A,PUNISH_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND C.NAME='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "change":
                $num = Db::query("SELECT count(*) FROM PUNISHMENT A,PUNISH_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND B.DESCRIPTION='".$message."'")[0]['count(*)'];
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME,A.ENABLE FROM PUNISHMENT A,PUNISH_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND B.DESCRIPTION='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "time":
                $num=Db::table('PUNISHMENT')->where('REC_TIME',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME,A.ENABLE FROM PUNISHMENT A,PUNISH_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND A.REC_TIME='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "enable":
                $num=Db::table('PUNISHMENT')->where('ENABLE',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME,A.ENABLE FROM PUNISHMENT A,PUNISH_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE AND A.ENABLE='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            default:
                //获取记录条数
                $num=Db::table('PUNISHMENT')->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME,A.ENABLE FROM PUNISHMENT A,PUNISH_LEVELS B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.LEVELS=B.CODE ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
        }
        //分页数
        $page=ceil($num/10);
        //$list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME FROM `CHANGE` A,CHANGE_CODE B,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.CHANGE=B.CODE ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
        return $template ->fetch('../view/punish-list',['list'=>$list,'page'=>$page,'num'=>$num,'id'=>$id]);
    }
    public function punish_add(){
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
        //查询所有的代码
        $chang = Db::query("select * from PUNISH_LEVELS;");
        return $template ->fetch('../view/punish-add',['chang'=>$chang]);
    }
    public function punish_edit(){
        if(!Session::has('name'))
            return redirect('/');
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
        $result=Db::query('SELECT A.STUDENTID,B.DESCRIPTION,A.DESCRIPTION AS REASON,REC_TIME,A.ENABLE FROM PUNISHMENT A,PUNISH_LEVELS B WHERE A.LEVELS=B.CODE AND A.ID='.$id);
        //var_dump($result);
        //查询所有的代码
        $chang = Db::query("select * from PUNISH_LEVELS;");
        return $template ->fetch('../view/punish-edit',['chang'=>$chang,'data'=>$result[0],'id'=>$id]);
    }

    /*成绩显示*/
    public function grade_list(){
        /*302重定向*/
        if(!Session::has('name'))
            return redirect('/');
        $message="";
        $choose=Request::has('choose','get') ? Request::get('choose') : "";
        if($choose){
            $message=Request::get('message');
            Session::set('message',$message);
            Session::set('choose',$choose);
        }else{
            /*利用session来避免查找无法分页问题*/
            if(!Request::has('id','get')){
              if(Session::has('message')){Session::delete('message');Session::delete('choose');}
            }else{
                if(Session::has('message')){$message=Session::get('message');$choose=Session::get('choose');}
            }
        }
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
//        //这里根据要求来查询数据
        switch ($choose){
            case "student":
                $num=Db::table('GRADE')->where('STUDENTID',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,A.SUBJECT,GRADE,STATUS FROM GRADE A,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND A.STUDENTID=".$message." ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "name":
                $num = Db::query("SELECT count(*) FROM GRADE A,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND C.NAME='".$message."'")[0]['count(*)'];
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,A.SUBJECT,GRADE,STATUS FROM GRADE A,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND C.NAME='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "subject":
                $num=Db::table('GRADE')->where('SUBJECT',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,A.SUBJECT,GRADE,STATUS FROM GRADE A,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND SUBJECT='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            case "status":
                $num=Db::table('GRADE')->where('STATUS',$message)->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,A.SUBJECT,GRADE,STATUS FROM GRADE A,STUDENT C WHERE C.STUDENTID=A.STUDENTID AND STATUS='".$message."' ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
            default:
                //获取记录条数
                $num=Db::table('GRADE')->count();
                $list = Db::query("SELECT A.ID,C.STUDENTID,C.NAME,A.SUBJECT,GRADE,STATUS FROM GRADE A,STUDENT C WHERE C.STUDENTID=A.STUDENTID ORDER BY STUDENTID ASC LIMIT ".($id*10).",10;");
                break;
        }
        //分页数
        $page=ceil($num/10);
        return $template ->fetch('../view/grade-list',['list'=>$list,'page'=>$page,'num'=>$num,'id'=>$id]);

    }
    public function grade_edit(){
        if(!Session::has('name'))
            return redirect('/');
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
        $result=Db::query('SELECT STUDENTID,SUBJECT,GRADE,STATUS FROM GRADE WHERE ID='.$id);
        return $template ->fetch('../view/grade-edit',['data'=>$result[0],'id'=>$id]);
    }
    public function grade_add(){
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
        //查询所有的代码
        return $template ->fetch('../view/grade-add');

    }

    /*成绩显示*/
    public function admin_list(){
        /*302重定向*/
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
        $list = Db::query("SELECT * FROM admin");
        return $template ->fetch('../view/admin-list',['data'=>$list]);
    }

    /*成绩编辑*/
    public function admin_edit(){
        if(!Session::has('name'))
            return redirect('/');
        //获取请求的id
        $id=Request::has('id','get') ? Request::get('id') : 0;
        $template = new Template();
        $result=Db::query('SELECT username,nickname,email,phone FROM admin WHERE ID='.$id);
        return $template ->fetch('../view/admin-edit',['data'=>$result[0],'id'=>$id]);
    }

    /*添加用户*/
    public function admin_add(){
        if(!Session::has('name'))
            return redirect('/');
        $template = new Template();
        //查询所有的代码
        return $template ->fetch('../view/admin-add');
    }
}
