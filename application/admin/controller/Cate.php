<?php

namespace app\admin\controller;

use app\common\controller\Base;
use app\common\model\ArtCate;
use think\facade\Request;
use think\facade\Session;

class Cate extends Base
{
    // 分类管理的首页
    public function index()
    {
        $this->isLogin();

        return $this->redirect('cateList');
    }

    // 分类列表
    public function cateList()
    {
        $this->isLogin();

        // 查询所有的分类
        $cateList = ArtCate::all();

        // 设置模板变量
        $this->view->assign('title','分类管理');
        $this->view->assign('empty','<span style="color:red">没有分类数据</span>');
        $this->view->assign('cateList',$cateList);

        // 
        return $this->view->fetch('catelist');
    }

    // 分类编辑
    public function cateEdit()
    {
        $cateId = Request::param('id');

        $cateInfo = ArtCate::where('id',$cateId)->find();

        // 设置模板变量
        $this->view->assign('title','编辑分类');
        $this->view->assign('cateInfo',$cateInfo);

        // 4.渲染模板
        return $this->view->fetch('cateedit');
    }

    // 保存编辑
    public function doEdit()
    {
        $data = Request::param();

        $id = $data['id'];

        unset($data['id']);

        if (ArtCate::where('id', $id)->update($data)) {

            return $this->success('更新成功', 'cate/catelist');
        } 

        return $this->error('更新失败');
    }

    // 用户删除
    public function cateDelete()
    {
        $userId = Request::param('id');
        
        if (Session::get('admin_level') == 1) {

            if (ArtCate::destroy($userId)){

                $this->success('删除成功','admin/user/userlist');
            }
        } 
        $this->error('没有权限','admin/user/userlist');
    }

    // 添加分类界面
    public function cateAdd()
    {
        $this->view->assign('title','添加分类');
        return $this->fetch('cateadd');
    }

    // 执行添加
    public function save()
    {
        $data = Request::param();

        if (ArtCate::create($data)){

            return $this->success('添加成功','admin/cate/catelist');
        }

        return $this->error('添加失败','admin/cate/cateAdd');
    }
}