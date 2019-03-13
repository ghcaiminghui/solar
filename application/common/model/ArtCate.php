<?php

namespace app\common\model;

use think\Model;

class ArtCate extends Model
{
    protected $pk = 'id';

    protected $table = 'zh_article_category';
    // 时间戳
	protected $autoWriteTimestamp = true;
	// 定义时间戳字段
	protected $createTime = 'create_time';
	protected $updateTime = 'update_time';
	protected $dateFormat = 'Y年m月d日';
	// 开启自动设置
	protected $auto = [];	
	// 仅新增的有效
	protected $insert = ['create_time', 'status'=>1];
	// 仅更新的时设置
	protected $update = ['update_time'];
}