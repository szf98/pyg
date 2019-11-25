<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Base extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->getCategory();
    }

    protected function _initialize()
    {
        parent::_initialize();
        //$this->getCategory();
        /*//先尝试取缓存
        $category = cache('category');
        if(!$category){
            //缓存中没有，查数据表，保存到缓存
            //查询分类信息
            $category = \app\common\model\Category::select();
            //转化为标准二维数组结构
            $category = (new \think\Collection($category))->toArray();
            //转化为父子树状结构
            $category = get_tree_list($category);
            //保存到缓存
            cache('category', $category, 86400);
        }
        //模板变量赋值
        $this->assign('category', $category);*/
    }

    public function getCategory()
    {
        //先尝试取缓存
        $category = cache('category');
        if(!$category){
            //缓存中没有，查数据表，保存到缓存
            //查询分类信息
            $category = \app\common\model\Category::select();
            //转化为标准二维数组结构
            $category = (new \think\Collection($category))->toArray();
            //转化为父子树状结构
            $category = get_tree_list($category);
            //保存到缓存
            cache('category', $category, 86400);
        }
        //模板变量赋值
        $this->assign('category', $category);
    }
}
