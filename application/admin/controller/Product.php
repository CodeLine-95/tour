<?php
namespace app\admin\controller;
use app\admin\controller\base\Base;
use app\admin\model\Cate;
use app\admin\model\Product as Products;

class Product extends Base{

    public function lists(){
        $get = request()->get();
        $where = [];
        if (isset($get['type'])){
            if (isset($get['name']) && !empty($get['name'])) {
                switch ($get['type']) {
                    case 1:
                        $where['p.name'] = ['like','%'.$get['name'].'%'];
                        break;
                }
            }
        }
        $list = (new Products())
            ->alias('p')
            ->field(['p.*','c.cate_name'])
            ->join('crm_cate c','p.cid = c.id')
            ->where($where)->paginate(10);
        $this->assign('list',$list);
        $get['type'] = isset($get['type']) ? $get['type'] : 1;
        $get['name'] = isset($get['name']) ? $get['name'] : '';
        $this->assign('get',$get);
        return $this->fetch();
    }

    public function add(){
        if (request()->isPost()){
            $params = request()->post();
            unset($params['file']);
            $params['create_t'] = time();
            $params['update_t'] = time();
            if ((new Products())->save($params)){
                return json(['msg'   => '添加成功', 'icon'  => 6]);
            }else{
                return json(['msg'   => '添加失败', 'icon'  => 5]);
            }
        }else{
            $cate = (new Cate())->select();
            $this->assign('cate',$cate);
            return $this->fetch();
        }
    }

    public function edit(){
        if(request()->isPost()){
            $params = request()->post();
            $params['update_t'] = time();
            if ((new Products())->update($params)) {
                return json(['msg'   => '编辑成功', 'icon'  => 6]);
            }else{
                return json(['msg'   => '编辑失败', 'icon'  => 5]);
            }
        }else{
            $id = input('id');
            $field = (new Products())->where('id',$id)->find()->toArray();
            $this->assign('field',$field);
            $cate = (new Cate())->select();
            $this->assign('cate',$cate);
            return $this->fetch();
        }
    }

    public function del(){
        $id = json_decode(input('post.id'),true);
        if(Products::destroy($id)){
            $return = array("msg"=>"删除成功","icon"=>6);
        }else{
            $return = array("msg"=>"删除失败","icon"=>5);
        }
        return json($return);
    }
}
