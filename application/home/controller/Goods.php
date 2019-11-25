<?php

namespace app\home\controller;

use think\Controller;

class Goods extends Base
{
    public function index($id=0)
    {
        //接收参数
        $keywords = input('keywords');
        if(empty($keywords)){
            //获取指定分类下商品列表
            if(!preg_match('/^\d+$/', $id)){
                $this->error('参数错误');
            }
            //查询分类下的商品
            $list = \app\common\model\Goods::where('cate_id', $id)->order('id desc')->paginate(10);
            //查询分类名称
            $category_info = \app\common\model\Category::find($id);
            $cate_name = $category_info['cate_name'];
        }else{
            try{
                //从ES中搜索
                $list = \app\home\logic\GoodsLogic::search();
                $cate_name = $keywords;
            }catch (\Exception $e){
                $this->error('服务器异常');
            }
        }
        return view('index', ['list' => $list, 'cate_name' => $cate_name]);
    }

    public function detail($id)
    {
        //查询商品基本信息
        $goods = \app\common\model\Goods::with('goods_images,spec_goods')->find($id);
        if(!$goods){
            $this->error('商品不存在');
        }
        $goods = $goods->toArray();
        //如果有规格商品SKU，默认使用第一个规格商品的价格
        if(!empty($goods['spec_goods'])){
            $goods['goods_price'] = $goods['spec_goods'][0]['price'];
        }
        //dump($goods->toArray());die;
        //spec_goods中二维数组结构
        /*$goods['spec_goods'] = [
            ['id'=> 836, 'goods_id'=>71, 'value_ids'=>'28_32'],
            ['id'=> 837, 'goods_id'=>71, 'value_ids'=>'28_33'],
            ['id'=> 838, 'goods_id'=>71, 'value_ids'=>'29_32'],
            ['id'=> 839, 'goods_id'=>71, 'value_ids'=>'29_33'],
        ];*/
        //预期得到  [28,29,32,33], 用于查询规格值表pyg_spec_value表
        $value_ids = array_column($goods['spec_goods'], 'value_ids'); //['28_32', '28_33', '29_32', '29_33']
        $value_ids = implode('_', $value_ids); //'28_32_28_33_29_32_29_33'
        $value_ids = explode('_', $value_ids); // [28,32,28,33,29,32,29,33]
        $value_ids = array_unique($value_ids); // [28,32,33,29]
        //查询规格值表 以及 规格名称
        $spec_values = \app\common\model\SpecValue::with('spec_bind')->where('id', 'in', $value_ids)->select();
        $spec_values = (new \think\Collection($spec_values))->toArray();
        /*参考结构$spec_values = [
            ['id'=> 28, 'spec_id'=>23, 'spec_value'=>'黑色', 'spec_name' => '颜色'],
            ['id'=> 29, 'spec_id'=>23, 'spec_value'=>'金色', 'spec_name' => '颜色'],
            ['id'=> 32, 'spec_id'=>24, 'spec_value'=>'128G', 'spec_name' => '内存'],
            ['id'=> 33, 'spec_id'=>24, 'spec_value'=>'金色', 'spec_name' => '内存'],
        ];*/
        //预期转化的目标结构
        /*$specs = [
            '23' => ['id'=>23, 'spec_name' => '颜色', 'spec_values' => [
                ['id'=> 29, 'spec_id'=>23, 'spec_value'=>'金色', 'spec_name' => '颜色'],
            ]
            ],
            '24' => ['id'=>24, 'spec_name' => '内存', 'spec_values' => [
                ['id'=> 32, 'spec_id'=>24, 'spec_value'=>'128G', 'spec_name' => '内存'],
                ['id'=> 33, 'spec_id'=>24, 'spec_value'=>'金色', 'spec_name' => '内存'],
            ]
            ],
        ];*/
        $specs = [];
        //一个foreach同时处理规格名称和规格值
        /*foreach($spec_values as $v){
            if(!isset($specs[$v['spec_id']])){
                $specs[$v['spec_id']] = [
                    'id' => $v['spec_id'],
                    'spec_name' => $v['spec_name'],
                    'spec_values' => [$v],
                ];
            }else{
                $specs[$v['spec_id']]['spec_values'][] = $v;
            }

        }*/
        //找规格名称
        foreach($spec_values as $v){
            $specs[$v['spec_id']] = [
                'id' => $v['spec_id'],
                'spec_name' => $v['spec_name'],
                'spec_values' => [],
            ];
        }
        //得到结构
        /*$specs = [
           '23' => ['id'=>23, 'spec_name' => '颜色', 'spec_values' => [] ],
           '24' => ['id'=>24, 'spec_name' => '内存', 'spec_values' => [] ],
       ];*/
        //找规格值
        foreach($spec_values as $v){
            $specs[$v['spec_id']]['spec_values'][] = $v;
        }
        //dump($spec_values);die;
        //dump($specs);die;
        //规格值选中切换价格
        /*$value_ids_map = [
            '35_37' => ['id' => '843','price' => '10000'],
            '36_37' => ['id' => '844','price' => '12000'],
            '35_38' => ['id' => '845','price' => '11000'],
            '36_38' => ['id' => '846','price' => '13000'],
        ];*/
        $value_ids_map = [];
        foreach($goods['spec_goods'] as $v){
            $value_ids_map[$v['value_ids']] = [
                'id' => $v['id'],
                'price' => $v['price']
            ];
            //$value_ids_map[$v['value_ids']]['id'] = $v['id'];
            //$value_ids_map[$v['value_ids']]['price'] = $v['price'];
        }
        //dump($value_ids_map);die;
        //转化为json格式字符串，在页面js中使用
        $value_ids_map = json_encode($value_ids_map, JSON_UNESCAPED_UNICODE);
        return view('detail', ['goods' => $goods, 'specs' => $specs, 'value_ids_map' => $value_ids_map]);
    }
}
