<?php

namespace app\home\controller;

use think\Controller;

class Cart extends Base
{
    public function addcart()
    {
        if(request()->isGet()){
            $this->redirect('home/index/index');
        }
        //接收参数
        $params = input();
        // dump($params);die;
        //参数检测
        $validate = $this->validate($params, [
            'goods_id' => 'require|integer|gt:0',
            'spec_goods_id' => 'integer|egt:0',
            'number' => 'require|integer|gt:0'
        ]);
        if($validate !== true){
            $this->error($validate);
        }
        //$params['spec_goods_id'] = $params['spec_goods_id'] ?? '';
        //处理数据
        \app\home\logic\CartLogic::addCart($params['goods_id'], $params['spec_goods_id'], $params['number']);
        //查询商品以及sku的信息
        $goods = \app\home\logic\GoodsLogic::getGoodsWithSpecGoods($params['goods_id'], $params['spec_goods_id']);
        //dump($goods);die;
        //渲染页面
        return view('addcart', ['goods' => $goods, 'number'=>$params['number']]);
    }

    public function index()
    {
        //查询购物车记录
        $list = \app\home\logic\CartLogic::getAllCart();
        //dump($list);die;
        //对每条记录 查询商品和sku信息
        foreach($list as $k=>$v){
            //$v['goods_id']  $v['spec_goods_id']
            $list[$k]['goods'] = \app\home\logic\GoodsLogic::getGoodsWithSpecGoods($v['goods_id'], $v['spec_goods_id']);
        }
        //dump($list);die;
        return view('index', ['list'=>$list]);
    }

    /**
     * ajax修改购买数量
     */
    public function changenum(){
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'id' => 'require',
            'number' => 'require|integer|gt:0'
        ]);
        if($validate !== true){
            return json(['code' => 400, 'msg'=>$validate]);
        }
        //处理数据
        \app\home\logic\CartLogic::changeNum($params['id'], $params['number']);
        //返回数据
        return json(['code' => 200, 'msg'=>'success']);
    }

    /**
     * 删除购物记录
     */
    public function delcart()
    {
        //接收参数
        $params = input();
        //参数检测
        if(empty($params['id'])){
            return json(['code' => 400, 'msg' => '参数错误']);
        }
        //删除数据
        \app\home\logic\CartLogic::delCart($params['id']);
        //返回数据
        return json(['code' => 200, 'msg' => 'success']);
    }

    public function changestatus()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'id' => 'require',
            'status' => 'require|in:0,1'
        ]);
        if($validate !== true){
            return json(['code' => 400, 'msg' => $validate]);
        }
        //处理数据
        \app\home\logic\CartLogic::changeStatus($params['id'], $params['status']);
        //返回数据
        return json(['code' => 200, 'msg' => 'success']);
    }
}
