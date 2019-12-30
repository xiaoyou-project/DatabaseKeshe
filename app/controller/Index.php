<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\Template;
use think\facade\Session;
use think\facade\Db;

class Index extends BaseController
{


    public function index()
    {
        $template = new Template();
        if(Session::has('name'))
            return $template ->fetch('../view/index',['name'=>Session::get('name')]);
        else
            return $template ->fetch('../view/login');
    }

    public function login(){
        $data=Request::post();
        // 检测输入的验证码是否正确，$value为用户输入的验证码字符串
        //要使用验证码需要开启session
        if(captcha_check($data['captcha'])){
            // 验证失败
            $list=Db::table('admin')->where('username',$data['username'])->find();
            if($list){
                if(md5($data['password'])==$list['passwd']){
                    Session::set('name',$list['nickname']);
                    return 'ok';
                }else
                    return '密码错误！';
            }else{
                return '用户名不存在！';
            }
        }else{
            // 验证失败 输出错误信息
            return '验证码错误！';
        }
    }

    public function logout(){
        Session::delete('name');
        //重定向
        return redirect('/');
    }

    public  function test(){
        Session::set('name', 'thinkphp');
        var_dump(Session::get('captcha'));
    }
}
