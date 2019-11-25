<?php

namespace app\common\model;

use think\Model;

class SpecValue extends Model
{
    //设置隐藏属性
    protected $hidden = ['create_time', 'update_time', 'delete_time'];

    public function spec()
    {
        return $this->belongsTo('Spec', 'spec_id', 'id');
    }
    public function specBind()
    {
        return $this->belongsTo('Spec', 'spec_id', 'id')->bind('spec_name');
    }
}
