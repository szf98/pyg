<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Test extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //cookie中存储购物车数据 数据结构原理分析
        //最外层下标：方便修改或删除数组中的某一条数据，避免循环+判断
        //里层id：和数据表统一格式，页面中修改或删除 直接取id作为条件
        $data = [
            '69_834' => ['id' => '69_834', 'goods_id' => 69, 'spec_goods_id' => 834, 'number' => 10, 'is_selected' => 1],
            '69_835' => ['id' => '69_835','goods_id' => 69, 'spec_goods_id' => 835, 'number' => 20, 'is_selected' => 1],
        ];
        cookie('cart', $data, 7*86400);

        //列表查询所有数据
        /*$data = cookie('cart') ?: [];
        $data = array_values($data); //和数据表的数据统一格式，去掉下标*/

        //加入购物车  goods_id 69 ; spec_goods_id  836 ; 15
        /*$goods_id = 69;
        $spec_goods_id = 837;
        $number = 30;
        $data = cookie('cart') ?: [];
        $key = $goods_id . '_' . $spec_goods_id;
        //存在相同的购物记录：商品id一样、选中的规格值组合一样
        if(isset($data[$key])){
            //累加数量
            $data[$key]['number'] += $number;
        }else{
            $data[$key] = ['id' => $key, 'goods_id' => $goods_id, 'spec_goods_id' => $spec_goods_id, 'number' => $number, 'is_selected' => 1];
        }
        cookie('cart', $data, 7*86400);
        dump(cookie('cart'));die;*/
        //修改购买数量
        /*$goods_id = 69;
        $spec_goods_id = 837;
        $number = 40;
        $data = cookie('cart') ?: [];
        $key = $goods_id . '_' . $spec_goods_id;
        $data[$key]['number'] = $number;
        cookie('cart', $data, 7*86400);
        dump(cookie('cart'));die;*/

        //修改选中状态
        /*$goods_id = 69;
        $spec_goods_id = 837;
        $data = cookie('cart') ?: [];
        $key = $goods_id . '_' . $spec_goods_id;
        $data[$key]['is_selected'] = 0;
        cookie('cart', $data, 7*86400);
        dump(cookie('cart'));die;*/

        //删除购物记录
        $goods_id = 69;
        $spec_goods_id = 837;
        $data = cookie('cart') ?: [];
        $key = $goods_id . '_' . $spec_goods_id;
        unset($data[$key]);
        cookie('cart', $data, 7*86400);
        // dump(cookie('cart'));die;

        //参考 最外层没有下标，需要使用循环+判断 写法
        /*$data = [
            ['id' => '69_834', 'goods_id' => 69, 'spec_goods_id' => 834, 'number' => 10, 'is_selected' => 1],
            ['id' => '69_835','goods_id' => 69, 'spec_goods_id' => 835, 'number' => 20, 'is_selected' => 1],
        ];
        cookie('cart', $data, 7*86400);*/
        //删除购物记录
        /*$goods_id = 69;
        $spec_goods_id = 837;
        $data = cookie('cart') ?: [];
        foreach($data as $k=>$v){
            if($v['goods_id'] == $goods_id && $v['spec_goods_id'] == $spec_goods_id){
                unset($data[$k]);
            }
        }
        cookie('cart', $data, 7*86400);
        dump(cookie('cart'));die;*/
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
        //
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
