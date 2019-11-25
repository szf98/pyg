<?php

namespace app\common\model;

use think\Model;

class Goods extends Model
{
    //定义商品-分类的关联
    public function categoryBind()
    {
        return $this->belongsTo('Category', 'cate_id', 'id')->bind('cate_name');
    }
    public function category()
    {
        return $this->belongsTo('Category', 'cate_id', 'id')->field('id,cate_name,pid_path_name,pid_path');
    }
    //定义商品-品牌的关联
    public function brandBind()
    {
        return $this->belongsTo('Brand', 'brand_id', 'id')->bind(['brand_name' =>'name']);
    }
    public function brand()
    {
        return $this->belongsTo('Brand', 'brand_id', 'id');
    }
    //定义商品-模型的关联
    public function typeBind()
    {
        return $this->belongsTo('Type', 'type_id', 'id')->bind('type_name');
    }
    public function type()
    {
        return $this->belongsTo('Type', 'type_id', 'id');
    }

    //定义商品-相册的关联
    public function goodsImages()
    {
        return $this->hasMany('GoodsImages', 'goods_id', 'id');
    }
    //定义商品SPU-规格商品SKU的关联
    public function specGoods()
    {
        return $this->hasMany('SpecGoods', 'goods_id', 'id');
    }

    //设置获取器，对goods_attr字段进行转化
    public function getGoodsAttrAttr($value)
    {
        return json_decode($value, true);
    }
}
