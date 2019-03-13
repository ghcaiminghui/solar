<?php

namespace app\common\validate;

// 引入系统类
use think\Validate;

// 用户验证器
class UserCheck extends Validate
{

	// 设置验证规则
	protected $rule = [

		'username|用户名'	=>		'require|length:4,18|chsAlphaNum',
		'password|密码'		=>		'require|length:4,18|confirm',
		'email|邮箱'			=>		'require|email|unique:zh_user',
		'mobile|手机号'		=>		'require|mobile|unique:zh_user'
	];
}
