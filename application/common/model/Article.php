<?php

namespace app\common\model;

use think\Model;

// 文章的模型
class Article extends Model
{
	// 主键
	protected $pk = 'id';
	// 数据表
	protected $table = 'zh_article';
	// 时间戳
	protected $autoWriteTimestamp = true;
	// 定义时间戳字段
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';
	// 时间字段取出后的默认时间格式
	protected $dateFormat = 'Y年m月d日';
	
	// 开启自动设置
	protected $auto = []; 
	// 仅新增的有效
	protected $insert = ['create_time', 'status'=>1, 'is_top'=>0, 'is_hot'=>0];
	// 仅更新的时设置
	protected $update = ['update_time'];
}