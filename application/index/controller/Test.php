<?php


namespace app\index\controller;

use app\common\validate\UserCheck;

class Test 
{

	public function test()
	{

		$data = [

			'username' 	=> 	'ghcaiminghui1',
			'password'	=> 	'root',
			'email'		=>	'ghcaiminghui@163.com',
			'mobile'	=>	'13432265131'
		];


		$validate = new UserCheck;


		if(  true !== $validate->check($data) ){

			return $validate->getError();
		}

		return '验证通过';
	}
}