<?php

namespace app\admin\model;

use think\Model;

// 
class Site extends Model
{
    protected $pk = 'id';
    protected $table = 'zh_site';

    protected $autoWriteTimestamp = true;
	// 定义时间戳字段
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';
}