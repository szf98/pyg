<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Test extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        /*//连表查询一个品牌以及分类名称
        //SELECT t1.*,t2.cate_name FROM `pyg_brand` t1 left join pyg_category t2 on t1.cate_id = t2.id where t1.id = 1;
        $info = \app\common\model\Brand::alias('t1')
            ->join('pyg_category t2', 't1.cate_id=t2.id', 'left')
            ->field('t1.*,t2.cate_name')
            ->where('t1.id', 1)
            ->find();
        //$this->ok($info);
        //连表查询所有品牌以及对应的分类名称
        //SELECT t1.*,t2.cate_name FROM `pyg_brand` t1 left join pyg_category t2 on t1.cate_id = t2.id;
        $data = \app\common\model\Brand::alias('t1')
            ->join('pyg_category t2', 't1.cate_id=t2.id', 'left')
            ->field('t1.*,t2.cate_name')
            ->limit(0,3)
            ->select();
        $this->ok($data);*/

        //1.关联查询 一对一的关联
        //先查询管理员
        //$info = \app\common\model\Admin::find();
        //$this->ok($info);
        //再查询档案
        //$this->ok($info->profile);
        //将档案信息放到管理员信息中
        //$info['profile_info'] = $info->profile;
        //$info['card'] = $info->profile->card;
        //$this->ok($info);
        //2.关联预载入 with方法，同时查询管理员和档案
        //默认返回的是类似二维数组格式
        //$info = \app\common\model\Admin::with('profile')->find();
        //绑定属性到父模型，返回的是类似一位数组格式
        //$info = \app\common\model\Admin::with('profile_bind')->find();
        //$this->ok($info);
        //查询多个管理员以及档案
        //$data = \app\common\model\Admin::with('profile')->limit(0,3)->select();
        //$data = \app\common\model\Admin::with('profile_bind')->limit(0,3)->select();
        //$this->ok($data);

        //二、根据档案信息查询管理员信息
        //$info = \app\common\model\Profile::with('admin')->find();
        $info = \app\common\model\Profile::with('admin_bind')->find();
        $this->ok($info);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询分类以及分类下的品牌
        //$info = \app\common\model\Category::with('brands')->find(72);
        //$info = \app\common\model\Category::with('brands')->select('71,72');
        //$this->ok($info);
        //查询品牌以及所属的分类
//        $info = \app\common\model\Brand::with('category')->find(1);
        $info = \app\common\model\Brand::with('category_bind')->find(1);
        $this->ok($info);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
