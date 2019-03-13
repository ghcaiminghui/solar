<?php

namespace app\common\controller;

use think\Controller;
use think\facade\Session;
use app\common\model\ArtCate;
use think\facade\Request;
use app\admin\model\Site;

// 公用控制器
class Base extends Controller
{

	// 初始化方法(创建常量,公共方法)在所有方法之前调用
	protected function initialize()
	{
		// 加载分类信息
		$this->showNav();

		$this->is_open();
	}

	// 检查是否已登录
	protected function logined()
	{
		if (Session::has('user_id')) {

			$this->error('客官,你已经登录啦~~','index/index');
		}
	}
	
	// 检查是否未登录
	public function isLogin()
	{
		
		if (!Session::has('user_id')){
			
			$this->error('客官,您是不是忘记登录啦~~','index/user/login');
		}
	}

	// 分类导航
	protected function showNav()
	{
		$cateList = ArtCate::all(function($query){

			$query->where('status', 1)->order('sort','asc')->field('id,name,status');
		});
		
		// 分配数据
		$this->view->assign('cateList', $cateList);
	}

	// 检测站点是否关闭
	public function is_open()
	{
		// 1.获取当前站点状态
		$isOpen = Site::where('status', 1)->value('is_open');

		// 2.如果你的站点已经关闭
		if ($isOpen==0 && Request::module()=='index') {

			exit('网站维护中');
		}
	}

	// 用户注册是否开启
	public function is_reg()
	{
		$isReg = Site::where('status', 1)->value('is_reg');

		if ($isReg == 0) {

			$this->error('注册已关闭，请联系管理员');
		}
	}
}