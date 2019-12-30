<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

/*统计信息*/
Route::get('tonji', 'page/tonji');
/*查询所有学生信息*/
Route::get('usrlist', 'Student/usrlist');
Route::get('logout', 'index/logout');

/*页面对应路由*/
Route::get('welcome1', 'page/welcome1');
Route::get('welcome', 'page/welcome');
/*学生信息管理*/
Route::get('member-list', 'page/member_list');
Route::get('member-add', 'page/member_add');
Route::get('member-edit', 'page/member_edit');
/*学籍变更*/
Route::get('chang-list', 'page/chang_list');
Route::get('chang-add', 'page/chang_add');
Route::get('chang-edit', 'page/chang_edit');
/*奖励记录*/
Route::get('reward-list', 'page/reward_list');
Route::get('reward-add', 'page/reward_add');
Route::get('reward-edit', 'page/reward_edit');
/*处罚记录*/
Route::get('punish-list', 'page/punish_list');
Route::get('punish-add', 'page/punish_add');
Route::get('punish-edit', 'page/punish_edit');
/*成绩记录*/
Route::get('grade-list', 'page/grade_list');
Route::get('grade-add', 'page/grade_add');
Route::get('grade-edit', 'page/grade_edit');
/*管理员管理*/
Route::get('admin-list', 'page/admin_list');
Route::get('admin-add', 'page/admin_add');
Route::get('admin-edit', 'page/admin_edit');