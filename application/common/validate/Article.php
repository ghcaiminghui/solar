<?php

namespace app\common\validate;

use think\Validate;

// 文章的验证规则
class Article extends Validate
{

	protected $rule = [
		'title|标题' => 'require|length:3,22|chsAlphaNum',
		//'title_img|标题图片' => 'require',
		'user_id|作者' => 'require',
		'cate_id|栏目名称'	=> 'require'
	];
}