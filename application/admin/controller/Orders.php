<?php
namespace app\admin\controller;
use app\admin\controller\base\Base;
use app\admin\model\Orders as OrdersModel;
use app\admin\model\Product;
use app\admin\model\Users;

class Orders extends Base
{
    public function lists(){
        $get = request()->get();
        $where = [];
        if (isset($get['type'])){
            if (isset($get['name']) && !empty($get['name'])) {
                switch ($get['type']) {
                    case 1:
                        $where['orderID'] = ['like','%'.$get['name'].'%'];
                        break;
                }
            }
        }
        $list = (new OrdersModel())->where($where)->paginate(10);
        $this->assign('list',$list);
        $get['type'] = isset($get['type']) ? $get['type'] : 1;
        $get['name'] = isset($get['name']) ? $get['name'] : '';
        $this->assign('get',$get);
        return $this->fetch();
    }

    public function show(){
        $id = input('id');
        $field = (new OrdersModel())->where(['id'=>$id])->find();

        $goods = (new Product())
            ->alias('p')
            ->field(['p.*','c.cate_name'])
            ->join('crm_cate c','p.cid = c.id')
            ->where(['p.id'=>$field['goodsID']])->find();

        $users = (new Users())->where(['id'=>$field['userID']])->find();

        $this->assign([
            'field'=>$field,
            'goods'=>$goods,
            'users'=>$users,
        ]);
        return $this->fetch();
    }

    public function send(){
      $id = input('post.id');
      $params['id'] = $id;
      $params['status'] = 2;
      if ((new OrdersModel())->update($params)) {
          return json(['msg'   => '发货成功', 'icon'  => 6]);
      }else{
          return json(['msg'   => '发货失败', 'icon'  => 5]);
      }
    }

    public function del(){
        $id = json_decode(input('post.id'),true);
        if(OrdersModel::destroy($id)){
            $return = array("msg"=>"删除成功","icon"=>6);
        }else{
            $return = array("msg"=>"删除失败","icon"=>5);
        }
        return json($return);
    }
}
