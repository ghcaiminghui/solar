<?php

namespace app\common\model;

use think\Model;

class User extends Model
{
	// 主键
	protected $pk = 'id';
	// 数据表
	protected $table = 'zh_user';
	// 时间戳
	protected $autoWriteTimestamp = true;
	// 定义时间戳字段
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';

	// 状态获取器
	public function getStatusAttr($value)
	{
		$status = ['1'=>'启用','0'=>'禁用'];

		return $status[$value];
	}

	// 管理员获取器
	public function getis_AdminAttr($value)
	{
		$status = ['1'=>'管理员','0'=>'注册会员'];

		return $status[$value];
	}

	// 修改器
	public function setPasswordAttr($value)
	{
		return sha1($value);
	}

}