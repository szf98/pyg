<?php

namespace app\common\model;

use think\Model;

class Cart extends Model
{
    //定义购物车到商品的关联  购物车记录属于商品记录
    public function goods(){
        return $this->belongsTo('Goods', 'goods_id', 'id');
    }

    //定义购物车到商品SKU关联  购物车记录属于商品SKU记录
    public function specGoods(){
        return $this->belongsTo('SpecGoods', 'spec_goods_id', 'id');
    }
}
