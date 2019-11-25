<?php

namespace app\common\model;

use think\Model;

class Type extends Model
{
    //设置隐藏属性
    protected $hidden = ['create_time', 'update_time', 'delete_time'];
    //定义模型到规格名称的关联  一个模型下有多个规格名称
    public function specs()
    {
        return $this->hasMany('Spec', 'type_id', 'id');
    }

    //定义模型到属性的关联
    public function attrs()
    {
        return $this->hasMany('Attribute', 'type_id', 'id');
    }
}
