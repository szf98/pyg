<?php

namespace app\common\model;

use think\Model;

class SpecGoods extends Model
{
    //设置隐藏属性
    protected $hidden = ['create_time', 'update_time', 'delete_time'];
}
