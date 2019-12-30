<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Request;
use think\Template;
/*使用session*/
use think\facade\Session;

/*各种工具类*/
class Tools extends BaseController
{
    /*添加学生*/
    public function add()
    {
        $data=Request::post();
       // var_dump($data);
        //先进行外键的替换
        $data['class']=Db::table('CLASS')->where('NAME', $data['class'])->find()['ID'];
        $data['department']=Db::table('DEPARTMENT')->where('NAME', $data['department'])->find()['ID'];
        $alldata = ['STUDENTID' =>$data['id'], 'NAME' => $data['name'],'SEX'=>$data['sex'],'DEPARTMENT'=>$data['department'],'CLASS'=>$data['class'],'NATIVE_PLACE'=>$data['native'],'BIRTHDAY'=>$data['birthday']];
        if($data['option']=='add')
            $result=Db::table('STUDENT')->insert($alldata);
        else
            $result=Db::table('STUDENT')->save($alldata);
        if($result){
            return 'ok';
        }else{
            return 'err';
        }
    }

    /*删除*/
    public function del()
    {
        //获取请求的id
        if(Request::has('id','get')){
            $id= Request::get('id');
            if(DB::table('STUDENT')->delete($id))
                return 'ok';
            else
                return 'err';
        }
        return 'err';
    }

    /*批量删除学生*/
    public function mdel()
    {
        $data=Request::post();
        $ids=explode(",",$data['ids']);
        Db::startTrans();
        try{
            DB::table('STUDENT')->delete($ids);
            DB::commit();
        }catch (\Exception $e){
            DB:rollback();
            return 'err';
        }
        return 'ok';
        //var_dump($ids);
    }

    /*根据类型添加学生*/
    public function changAdd(){
        $data = Request::post();
        if(!Request::has('id','post'))  $data['id']=0;
//        var_dump($data);
//        //先进行外键的替换
        switch ($data['type']){
            case "change":
                $table="CHANGE";
                $alldata = ['ID' =>$data['id'], 'STUDENTID' => $data['studentid'],'CHANGE'=>$data['change'],'REC_TIME'=>$data['time'],'DESCRIPTION'=>$data['reason']];
                break;
            case "reward":
                $table="REWARD";
                $alldata = ['ID' =>$data['id'], 'STUDENTID' => $data['studentid'],'LEVELS'=>$data['change'],'REC_TIME'=>$data['time'],'DESCRIPTION'=>$data['reason']];
                break;
            case "punish":
                $table="PUNISHMENT";
                if(in_array('enable',$data)) $data['enable']='是';
                else $data['enable']='否';
                $alldata = ['ENABLE'=>$data['enable'],'ID' =>$data['id'], 'STUDENTID' => $data['studentid'],'LEVELS'=>$data['change'],'REC_TIME'=>$data['time'],'DESCRIPTION'=>$data['reason']];
                break;
            case "grade":
                $table="GRADE";
                $alldata = ['ID' =>$data['id'], 'STUDENTID' => $data['studentid'],'SUBJECT'=>$data['subject'],'GRADE'=>$data['grade'],'STATUS'=>$data['status']];
                break;
            case "admin":
                $table="admin";
                if(in_array('passwd',$data))
                    $alldata = ['passwd'=>md5($data['passwd']),'ID' =>$data['id'], 'username' => $data['username'],'nickname'=>$data['nickname'],'email'=>$data['email'],'phone'=>$data['phone']];
                else
                    $alldata = ['ID' =>$data['id'], 'username' => $data['username'],'nickname'=>$data['nickname'],'email'=>$data['email'],'phone'=>$data['phone']];
                break;
            default:
                $table="";
        }
        if($data['option']=='add') {
            unset($alldata['id']);
            $result = Db::table($table)->insert($alldata);
        }else
            $result=Db::table($table)->save($alldata);
        if($result){
            return 'ok';
        }else{
            return 'err';
        }
    }

    /*根据类型来删除学生*/
    public function changdel()
    {
        //获取请求的id
        if(Request::has('id','get')){
            $id= Request::get('id');
            $type=Request::get('type');
            switch ($type){
                case "change":
                    $table="CHANGE";
                    break;
                case "reward":
                    $table="REWARD";
                    break;
                case "punish":
                    $table="PUNISHMENT";
                    break;
                case "grade":
                    $table="GRADE";
                    break;
                case "admin":
                    $table="admin";
                    break;
            }
            if(DB::table($table)->delete($id))
                return 'ok';
            else
                return 'err';
        }
        return 'err';
    }

    /*批量删除学生*/
    public function changmdel()
    {
        $data=Request::post();
        $ids=explode(",",$data['ids']);
        switch ($data['type']){
            case "change":
                $table="CHANGE";
                break;
            case "reward":
                $table="REWARD";
                break;
            case "punish":
                $table="PUNISHMENT";
                break;
            case "grade":
                $table="GRADE";
                break;
            case "admin":
                $table="admin";
                break;
        }

        Db::startTrans();
        try{
            DB::table($table)->delete($ids);
            DB::commit();
        }catch (\Exception $e){
            DB:rollback();
            return 'err';
        }
        return 'ok';
        //var_dump($ids);
    }

    /*获取地域信息的接口*/
    public function addrdata(){
        $addr=['台湾','河北','山西','内蒙古','辽宁','吉林','黑龙江','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆','北京','天津','上海','重庆','香港','澳门'];
        $list=[];
        $i=0;
        foreach ($addr as $data){
            $list[$i]['name']=$data;
            $list[$i]['value'] =Db::table('STUDENT')->where('NATIVE_PLACE',$data)->count();
            $i++;
        }
        return json_encode($list,JSON_UNESCAPED_UNICODE);
    }

    /*班级分布接口*/
    public function classData(){
        //查询所有的数据
        $list=Db::query("select ID,NAME from CLASS");
        $data=[];
        for($i=0;$i<count($list);$i++){
            $man=Db::table('STUDENT')->where('CLASS',$list[$i]['ID'])->where('SEX','男')->count();
            $woman=Db::table('STUDENT')->where('CLASS',$list[$i]['ID'])->where('SEX','女')->count();
            $data[$i]=$list[$i]['NAME'].",".$woman.",".$man;
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /*院系分布接口*/
    public function departmentData(){
        //查询所有的数据
        $list=Db::query("select ID,NAME from DEPARTMENT");
        $data=[];
        for($i=0;$i<count($list);$i++){
            $num=Db::table('STUDENT')->where('DEPARTMENT',$list[$i]['ID'])->count();
            $data[$i]['name']=$list[$i]['NAME'];
            $data[$i]['value']=$num;
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /*找出数组里面的最大值最小值*/
    public function getmax($list){
        $min=1000;
        $max=0;
        for($i=0;$i<count($list);$i++){
            if($min>$list[$i]) $min=$list[$i];
            if($max<$list[$i]) $max=$list[$i];
        }
        $data[0]=$min;
        $data[1]=$max;
        return $data;
    }

    /*成绩分布接口*/
    public function gradedata(){
       $subject=['思想道德修养','高等数学','大学英语','计算机'];
       $data=[];
       $j=0;
       $list=Db::query("select NAME from CLASS");
       for($i=0;$i<count($list);$i++){
           $data['class'][$i]=$list[$i]['NAME'];
       }
       foreach ($subject as $key){
           $data['data'][$j]['name']=$key;
           $data['data'][$j]['type']="bar";
           for($i=0;$i<16;$i++) {
               $data['data'][$j]['data'][$i]=Db::table('GRADE')->where('class',$i)->where('SUBJECT', $key)->avg('GRADE');
           }
           $data['data'][$j]['markPoint']['data']=[['type'=>'max','name'=>'最大值'],['type'=>'min','name'=>'最小值']];
           $data['data'][$j]['markLine']['data']=[['type'=>'average','name'=>'平均值']];
           $j++;
       }
       return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /*奖励处罚情况分布*/
    public function rewarddata(){
        $data=[];
        $reward=Db::query(" SELECT DESCRIPTION from REWARD_LEVELS");
        $punish=Db::query(" SELECT DESCRIPTION from PUNISH_LEVELS");
        $data['option']=[];
        for($i=0;$i<count($reward);$i++){
            array_push($data['option'],$reward[$i]['DESCRIPTION']);
            $data['reward'][$i]['value']=Db::table('REWARD')->where('LEVELS',$i)->count();
            $data['reward'][$i]['name']=$reward[$i]['DESCRIPTION'];
        }
        for($i=0;$i<count($punish);$i++){
            array_push($data['option'],$punish[$i]['DESCRIPTION']);
            $data['punish'][$i]['value']=Db::table('PUNISHMENT')->where('LEVELS',$i)->count();
            $data['punish'][$i]['name']=$punish[$i]['DESCRIPTION'];
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }


    /*学籍变更分布*/
    public function changedata(){
        $data=[];
        $change=Db::query(" SELECT DESCRIPTION from CHANGE_CODE");
        for($i=0;$i<count($change);$i++){
            $data['change'][$i]=$change[$i]['DESCRIPTION'];
            $data['data'][$i]['value']=Db::table('CHANGE')->where('CHANGE',$i)->count();
            $data['data'][$i]['name']=$change[$i]['DESCRIPTION'];
        }
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

}