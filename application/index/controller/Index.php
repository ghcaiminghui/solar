<?php
namespace app\index\controller;

use app\common\controller\Base;
use app\common\model\ArtCate;
use think\facade\Request;
use app\common\model\Article;
use app\common\model\UserFav;

class Index extends Base
{
    // 首页
    public function index()
    {

        // 全局查询条件
        $map = []; //将所有的查询条件封装到这个数组中
        $map[] = ['status', '=', 1];
        // 获取查询内容
        $keywords = Request::param('keywords');
        if ( isset($keywords)) {
            // 查询条件
            $map[] = ['title','like','%'.$keywords.'%'];
        }

        // 标题数据
        $this->assign('title','首页');
        
        // 获取分类信息
        $cate_id=Request::param('cate_id');
        
        if (isset($cate_id)){

            $map[] = ['cate_id', '=', $cate_id];

            $cateResult = ArtCate::get($cate_id);
            
            // 文章列表分页
            $artList = Article::where($map)
                ->order('create_time','desc')
                ->paginate(5);

            // 分配分类信息
            $this->view->assign('cateName',$cateResult->name);
        } else {

            $this->view->assign('cateName', '全部文章');

            // 文章列表分页
            $artList = Article::where('status', 1)
                ->where($map)
                ->order('create_time','desc')
                ->paginate(5);
        }
        
        // 分配列表信息
        $this->view->assign('artList',$artList);
        // 加载视图
        return $this->view->fetch();
    }


    // 添加文章界面
    public function insert()
    {
        // 1.登录才允许发布文章
        $this->isLogin();

        // 2.设置页面标题
        $this->view->assign('title','发布文章');

        // 3.获取栏目的信息
        $cateList = ArtCate::all();
        if (count($cateList) >0){
            // 将查询到的栏目的信息赋值给模板
            $this->assign('cateList',$cateList);
        } else {
            $this->error('请先添加栏目','index/index');
        }

        // 4.发布界面渲染
        return $this->view->fetch('insert');
       
    }

    // 保存文章
    public function save()
    {
        if (Request::isPost()) {
            
            // 1.获取用户提交的文章信息
            $data = Request::post();
            
            // 2.验证数据
            $result = $this->validate($data,'app\common\validate\Article');

            // 验证失败
            if (true !== $result) {
                echo '<script>alert("'.$result.'");location.back()</script>';
            } else {

                // 验证成功
                $file = Request::file('title_img');
                
                // 文件信息验证，验证成功就上传到服务器上
                $info = $file->validate([
                    'size' => 1000000,
                    'ext'  => 'jpeg,jpg,png,git',
                ])->move('uploads/');

                if ($info) {
                    $data['title_img'] = $info->getSaveName();
                } else {
                    $this->error( $file->getError() );
                }

                // 将数据写到数据表中
                if (Article::create($data)) {

                    $this->success('文章发布成功','index/index');
                } else {
                    $this->error('文章保存失败');
                }
            }

        } else {
            $this->error('请求类型错误');
        }
    }

    // 详情页
    public function detail()
    {

        // 获取文章ID
        $artId = Request::param('id');

        // 文章内容
        $art = Article::get(function($query) use ($artId){

            $query->where('id', '=', $artId )
            ->setInc('pv');
        });

        if (!is_null($art)) {

            $this->assign('art',$art);
        }
       
        $this->assign('title','详情页');

        return $this->view->fetch('detail');
    }


    // ajax收藏
    public function fav()
    {
        if (!Request::isAjax()) {

            return ['code' => 0 , 'msg' => '请求类型错误'];
        }

        // 获取前端数据
        $data = Request::param();
        
        // 限制登录
        if (empty($data['session_id'])) {
            return ['code' => 2 ,'msg' => '客官,你还没登录'];
        }

        // 查询条件
        $map[] = ['user_id', '=', $data['user_id'] ];
        $map[] = ['article_id', '=', $data['article_id'] ];

        $userFav = UserFav::where($map)->find();

        if (is_null($userFav)) {

            // 用户收藏操作
            UserFav::create([
                'user_id' => $data['user_id'],
                'article_id' => $data['article_id']
            ]);
            return ['code' => 1 ,'msg' => '收藏成功'];

        } else {
            
            // 用户取消收藏
            UserFav::where($map)->delete();
            return ['code' => 3 ,'msg' => '已取消'];
        }
 
 


    }

}
