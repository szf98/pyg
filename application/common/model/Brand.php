<?php

namespace app\common\model;

use think\Model;

class Brand extends Model
{
    //设置隐藏属性
    protected $hidden = ['create_time', 'update_time', 'delete_time'];
    //设置可见属性
    //protected $visible = ['id','name','logo','desc','sort','is_hot','cate_id','url'];

    //定义相对的关联  品牌到分类的关联 （一个品牌属于一个分类）
    public function category()
    {
        return $this->belongsTo('Category', 'cate_id', 'id');
    }

    public function categoryBind()
    {
        //绑定属性时，属性不能和父模型已经存在的属性重名
        //return $this->belongsTo('Category', 'cate_id', 'id')->bind('cate_name');
        //定义别名  ['别名'=>'真实的字段名']
        return $this->belongsTo('Category', 'cate_id', 'id')->bind(['cate_name', 'hot'=>'is_hot']);
    }
}
