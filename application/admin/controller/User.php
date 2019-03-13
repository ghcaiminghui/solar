<?php

namespace app\admin\controller;

use app\admin\common\controller\Base;
use app\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;

// 后台用户类
class User extends Base 
{
    // 登录界面
    public function login()
    {
        $this->view->assign('title','管理员登录');

        return $this->view->fetch('login');
    }

    // 登录检查
    public function checkLogin()
    {
        $data = Request::param();
        
        $map[] = ['email', '=', $data['email']];
        $map[] = ['password', '=', sha1($data['password'])];

       $result =  UserModel::where($map)->find();

       if ($result) {

            Session::set('admin_id',$result['id']);
            Session::set('admin_name', $result['username']);
            Session::set('admin_level', $result['is_admin']);

            $this->success('登录成功','admin/index/userList');
       }

       $this->error('登录失败');
    }

    // 退出登录
    public function logout()
    {
        // 1.清除当前的Session
        Session::clear();
        
        // 2.退出登录并跳转到登录页面
        $this->success('退出成功','admin/user/login');
    }

    // 用户列表
    public function userList()
    {
        // 1.获取当前用户的ID和级别
        $data['admin_id'] = Session::get('admin_id');
        $data['admin_level'] = Session::get('admin_level');

        // 2.获取当前用户信息
        $userList = UserModel::where('id',$data['admin_id'])->select();

        // 3.权限管理
        if ($data['admin_level'] == 1) {

            $userList = UserModel::select();
        }

        // 4.模板赋值
        $this->view->assign('title','用户管理');
        $this->view->assign('empty','<span style="color:red">没有任何数据</span>');
        $this->view->assign('userList',$userList);

        // 5.渲染模板
        return $this->view->fetch('userlist');
    }

    // 用户编辑
    public function userEdit()
    {
        // 1.获取用户ID
        $userId = Request::param('id');

        // 2.根据主键进行查询
        $userInfo = userModel::where('id',$userId)->find();

        // 3.设置编辑界面的模板变量
        $this->view->assign('title','编辑用户');
        $this->view->assign('userInfo',$userInfo);

        // 4.渲染模板
        return $this->view->fetch('useredit');    
    }

    // 执行用户信息的保存
    public function doEdit()
    {
        // 1.获取提交的信息
        $data = Request::param();

        // 2.取出主键
        $id = $data['id'];

        // 3.用户密码加密
        $data['password'] = sha1($data['password']);

        // 4.删除主键
        unset($data['id']);

        // 5.执行用户更新
        if (UserModel::where('id',$id)->data($data)->update()) {

            return $this->success('更新成功','userList');
        }

        // 6.更新失败
        $this->error('没有更新或更新失败');
    }

    // 用户删除
    public function userDelete()
    {
        $userId = Request::param('id');
        
        if (Session::get('admin_level') == 1) {

            if (UserModel::destroy($userId)){

                $this->success('删除成功','admin/user/userlist');
            }
        } 

        $this->error('没有权限','admin/user/userlist');
        
    }
}