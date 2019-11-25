<?php

namespace app\common\model;

use think\Model;

class Admin extends Model
{
    //设置隐藏属性
    protected $hidden = ['password', 'create_time', 'update_time', 'delete_time'];

    //定义管理员到档案的关联  一个管理员有一个档案（主键记录有一条关联外键记录）
    public function profile()
    {
        //参数1：关联的模型类名；参数2：关联外键名；参数3：主键名
        return $this->hasOne('Profile', 'uid', 'id');
    }
    public function profileBind()
    {
        //参数1：关联的模型类名；参数2：关联外键名；参数3：主键名
        //bind方法 用于将关联模型的属性，绑定到父模型
        return $this->hasOne('Profile', 'uid', 'id')->bind('card,idnum');
    }

    //定义 管理员-角色的关联
    public function roleBind()
    {
        return $this->belongsTo('Role', 'role_id', 'id')->bind('role_name');
    }

    //设置修改器  密码字段自动加密
    public function setPasswordAttr($value)
    {
        return encrypt_password($value);
    }
}
