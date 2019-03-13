<?php

namespace app\common\model;

use think\Model;

class UserFav extends Model
{
	// 主键
	protected $pk = 'id';
	// 数据表
	protected $table = 'zh_user_fav';
	// 时间戳
	protected $autoWriteTimestamp = false;

}