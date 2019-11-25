<?php

namespace app\common\model;

use think\Model;

class Spec extends Model
{
    //设置隐藏属性
    protected $hidden = ['create_time', 'update_time', 'delete_time'];
    //定义 规格名称到规格值的关联 一个规格名称有多个规格值
    public function specValues()
    {
        return $this->hasMany('SpecValue', 'spec_id', 'id');
    }
}
