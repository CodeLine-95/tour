<?php
namespace app\admin\controller;
use app\admin\controller\base\Base;
use app\admin\model\Comment as CommentModel;

class Comment extends Base{
  /*
  * 商品评论列表
  */
  public function  goods_lists(){
    $get = request()->get();
    $where = [];
    if (isset($get['type'])){
        if (isset($get['name']) && !empty($get['name'])) {
            switch ($get['type']) {
                case 1:
                    $where['user_name'] = ['like','%'.$get['name'].'%'];
                    break;
                case 2:
                    $where['product_name'] = ['like','%'.$get['name'].'%'];
                    break;
            }
        }
    }
    $list = (new CommentModel())->where($where)->paginate(10);
    $this->assign('list',$list);
    $get['type'] = isset($get['type']) ? $get['type'] : 1;
    $get['name'] = isset($get['name']) ? $get['name'] : '';
    $this->assign('get',$get);
    return $this->fetch();
  }

  public function del(){
      $id = json_decode(input('post.id'),true);
      if(CommentModel::destroy($id)){
          $return = array("msg"=>"删除成功","icon"=>6);
      }else{
          $return = array("msg"=>"删除失败","icon"=>5);
      }
      return json($return);
  }
}
