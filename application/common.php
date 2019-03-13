<?php

// 用户类
use app\common\model\User;
use app\common\model\ArtCate;


// 根据用户的主键ID,查询用户名称
function getUserName($id)
{
    $userName =  User::where('id',$id)->value('username');

    return $userName ? $userName : '获取用户异常';
}

// 过滤一下文章摘要
function getArtContent($content)
{
    return mb_substr(strip_tags($content),0,30).'...';
}

// 获取栏目名
function getCateName($cateId)
{
    return $cateName = ArtCate::where('id',$cateId)->value('name');
}   