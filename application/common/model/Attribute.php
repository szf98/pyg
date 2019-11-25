<?php

namespace app\common\model;

use think\Model;

class Attribute extends Model
{
    //设置隐藏属性
    protected $hidden = ['create_time', 'update_time', 'delete_time'];
    //设置获取器方法，对attr_values字段进行自动转化
    public function getAttrValuesAttr($value)
    {
        return $value ? explode(',', $value) : [];
    }
}
