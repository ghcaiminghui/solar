<?php

namespace app\admin\common\controller;

use think\Controller;
use think\facade\Session;

// 后台基类控制器
class Base extends Controller
{
    // 初始化
    protected function initialize()
    {
        //$this->isLogin();
    }

    // 检查用户是否登录
    protected function isLogin()
    {
        if (!Session::has('admin_id')) {

            $this->error('请先登录','admin/user/login');
        }
    }
}