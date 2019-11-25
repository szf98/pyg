<?php

namespace app\common\model;

use think\Model;

class Category extends Model
{
    //设置隐藏属性
    protected $hidden = ['create_time', 'update_time', 'delete_time'];
    //定义分类到品牌的关联  一个分类下有多个品牌
    public function brands()
    {
        //hasMany 不能使用bind方法绑定
        return $this->hasMany('Brand', 'cate_id', 'id');
    }
}
