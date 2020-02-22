<?php
namespace app\admin\controller;
use app\admin\controller\base\Base;
use app\admin\model\Admin;
use app\admin\model\News;
use app\admin\model\Comment;
use app\admin\model\Users;
use app\admin\model\Product;
class Index extends Base
{
	// 首页
    public function index()
    {
      $user = (new Admin)->field(true)->where('id',$this->user['uid'])->find();
      $this->assign('user',$user);
      return $this->fetch();
    }
    // 控制台数据统计
    public function console(){
      $newsCount = (new News())->count();
      $CommentCount = (new Comment())->count();
      $UsersCount = (new Users())->count();
      $ProductCount = (new Product())->count();
      $this->assign([
        'newsCount'  =>  $newsCount,
        'CommentCount' => $CommentCount,
        'UsersCount' => $UsersCount,
        'ProductCount'=>$ProductCount
      ]);
    	return $this->fetch();
    }
}
