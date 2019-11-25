<?php

namespace app\common\model;

use think\Model;

class Profile extends Model
{
    //定义相对的关联  一个档案属于一个管理员(一个外键记录 属于 主键记录)
    public function admin()
    {
        return $this->belongsTo('Admin', 'uid', 'id');
    }
    public function adminBind()
    {
        return $this->belongsTo('Admin', 'uid', 'id')->bind('username,email');
    }
}
