<?php

namespace app\index\controller;
use app\common\controller\Base;
use app\common\model\User as UserModel;
use think\facade\Request;
use think\facade\Session;


class User extends Base
{

	// 注册页面
	public function register()
	{

		// 检测注册
		$this->is_reg();

		$this->assign('title','用户注册');

		return $this->fetch();
	}


	// 处理用户提交的信息
	public function insert()
	{

		if (Request::isAjax()) {
			// 验证数据
			$data = Request::post();
			// 验证规则
			$rule = 'app\common\validate\UserCheck';

			$result = $this->validate($data,$rule);

			if ( true !== $result) {

				return ['status'=>-1,'message'=>$result];
			} else {
				// 验证成功
				if(UserModel::create($data)){

					return ['status'=>1,'message'=>'注册成功'];
				} else {
					return ['status'=>0,'message'=>'注册失败'];
				}
			}

		} else {
			return $this->error("请求类型错误",'register');
		}
	}

	// 用户登录
	public function login()
	{
		// 检查用户是否登录
		$this->logined();

		return $this->view->fetch('login',['title'=>'用户登录']);
	}


	// 用户登录验证与查询
	public function loginCheck()
	{
		// 验证数据
		$data = Request::post();

		// 验证规则
		$rule = [
			'email|邮箱' =>	"require|email",
			'password|密码' => 'require|alphaNum',
		];

		// 开始验证
		$result = $this->validate($data,$rule);

		if (true !== $result) {
			return ['status'=> -1 ,'message' => $result];
		} else {

			$result = UserModel::where('email',$data['email'])->where('password',sha1($data['password']))->find();

			if (null == $result) {

				return ['status'=>0,'message' =>'邮箱或密码不正确,请检查'];
			} else {
				
				// 将用户数据写入Session
				Session::set('user_id',$result->id);
				Session::set('user_name',$result->username);
				return ['status'=>1, 'message' => '登录成功'];
			}
		}
	}


	// 用户退出
	public function logout()
	{
		Session::delete('user_id');
		Session::delete('user_name');
		$this->success('退出登录成功','index/index');
	}
}

