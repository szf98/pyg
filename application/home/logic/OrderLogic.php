<?php

namespace app\home\Logic;

class OrderLogic {
    //查询选中的购物记录以及购物信息
    public static function getCartWithGoods()
    {
        //关联模型查询 购物车、商品、SKU
        //获取登录用户的id
        $user_id = session('user_info.id');
        //查询的是两个条件，1、当前登录的用户；2、选中的商品的记录(1，表示选中的记录)
        //查询到的cart_data是多个数据，类似于二维数组
        $cart_data = \app\common\model\Cart::with('goods,spec_goods')->where('user_id', $user_id)->where('is_selected', 1)->select();
        $cart_data = (new \think\Collection($cart_data))->toArray();
        //使用SKU的价格、库存、覆盖商品价格、库存（目的是为了同步）   &加一个引用，该所有的数据
        //总的数量
        $total_number = 0;
        //总的价格
        $total_price = 0;
        foreach($cart_data as $k=>$v){
            //$v中有购物车的记录和商品的记录   $v['goods']（商品）  $v['spec_goods'](SKU信息)
            
           // $cart_data = (new \think\Collection($cart_data))->toArray();
            //价格
            if($v['spec_goods']['price']>0){
                $v['goods']['goods_price'] = $v['spec_goods']['price'];
                // $cart_data[$k]['goods']['goods_price'] = $v['spec_goods']['price'];
            
            }
            //成本价
            if($v['spec_goods']['cost_price']>0){
                $v['goods']['cost_price'] = $v['spec_goods']['cost_price'];
            }
            //库存
            if($v['spec_goods']['store_count']>0){
                $v['goods']['goods_number'] = $v['spec_goods']['store_count'];
            }
            //冻结的库存数据
            if($v['spec_goods']['store_frozen']>0){
                $v['goods']['frozen_number'] = $v['spec_goods']['store_frozen'];
            }

            //累加计算总数量和总的金额
            $total_number += $v['number'];
            $total_price += $v['number'] * $v['goods']['goods_price'];
        }
               return[
                    'cart_data' => $cart_data,
                     'total_number' => $total_number,
                     'total_price' => $total_price
               ];
            //框架自带的一个函数，主要用来组装一个数组，直接传三个参数（变量的名称）返回的结果与常规写法返回的结果相同
            // return compact('cart_data','total_number','total_price');
    }
}