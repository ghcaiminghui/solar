<?php

namespace app\admin\controller;

use app\admin\common\controller\Base;
use app\admin\model\Site as SiteModel;
use think\facade\Request;


class Site extends Base
{
    // 站点的管理首页
    public function index()
    {
        $this->isLogin();
        
        // 获取站点信息
        $siteInfo = SiteModel::get(['status' => 1]);

        // 模板赋值
        $this->view->assign('siteInfo',$siteInfo);

        //halt($siteInfo);
        // 渲染模板
        return $this->view->fetch('index');
    }

    // 站点保存
    public function save()
    {
        $data = Request::param();

        // 2.更新
        if (SiteModel::update($data)) {

            $this->success('更新成功','index');
        }

        $this->error('更新失败','index');
    }
}