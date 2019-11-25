<?php
namespace app\home\logic;

class CartLogic{
    /**
     * 加入购物车
     */
    public static function addCart($goods_id, $spec_goods_id, $number, $is_selected=1)
    {
        //判断登录状态：未登录，添加到cookie；已登录，添加到数据表
        if(session('?user_info')){
            //已登录，添加到数据表
            $user_id = session('user_info.id');
            //判断是否存在相同购物记录（同一个用户，同一个商品，同一个sku）
            $where = [
                'user_id' => $user_id,
                'goods_id' => $goods_id,
                'spec_goods_id' => $spec_goods_id
            ];
            $info = \app\common\model\Cart::where($where)->find();
            if($info){
                //存在相同记录，累加购买数量
                $info->number += $number;
                $info->is_selected = $is_selected;
                $info->save();
            }else{
                //不存在，添加新记录
                $where['number'] = $number;
                $where['is_selected'] = $is_selected;
                \app\common\model\Cart::create($where, true);
            }
        }else{
            //未登录，添加到cookie
            //先从cookie中取出所有购物车数据
            $data = cookie('cart') ?: [];
            //拼接当前记录的下标
            $key = $goods_id . '_' . $spec_goods_id;
            //判断是否存在相同购物记录
            if(isset($data[$key])){
                //累加数量
                $data[$key]['number'] += $number;
                $data[$key]['is_selected'] = $is_selected;
            }else{
                //添加新记录
                $data[$key] = [
                    'id' => $key,
                    'goods_id' => $goods_id,
                    'spec_goods_id' => $spec_goods_id,
                    'number' => $number,
                    'is_selected' => $is_selected,
                ];
            }
            //重新保存到cookie
            cookie('cart', $data, 86400*7);
        }
    }

    /**
     * 获取所有购物车数据
     */
    public static function getAllCart()
    {
        //判断登录状态：未登录：取cookie; 已登录：取数据表
        if(session('?user_info')){
            //已登录：取数据表
            $user_id = session('user_info.id');
            $data = \app\common\model\Cart::where('user_id', $user_id)->select();
            //转化为标准二维数组
            $data = (new \think\Collection($data))->toArray();
        }else{
            //未登录：取cookie;
            $data = cookie('cart') ?: [];
            $data = array_values($data);
        }
        return $data;
    }

    /**
     * 登录迁移cookie购物车到数据表
     */
    public static function cookieTodb()
    {
        //从cookie中取出所有数据
        $data = cookie('cart') ?: [];
        foreach($data as $v){
            //$v :  ['id'=>'69_834', 'goods_id'=>69, 'spec_goods_id'=>834, 'number' => 10, 'is_selected'=>1]
            //每一条数据，对数据表来说，都是一个加入购物车功能
            self::addCart($v['goods_id'], $v['spec_goods_id'], $v['number']);
        }
        //清空cookie购物车数据
        cookie('cart', null);
    }

    /**
     * 修改购买数量
     */
    public static function changeNum($id, $number){
        //判断登录状态
        if(session('?user_info')){
            //登录，修改数据表
            $user_id = session('user_info.id');
            //修改数据
            \app\common\model\Cart::update(['number' => $number], ['id'=>$id, 'user_id' => $user_id], true);
        }else{
            //未登录，修改cookie
            //取出所有的数据
            $data = cookie('cart') ?: [];
            //修改数据
            $data[$id]['number'] = $number;
            //重新保存到cookie
            cookie('cart', $data, 86400*7);
        }
    }
    /**
     * 删除购物记录
     */
    public static function delCart($id)
    {
        //判断登录状态
        if(session('?user_info')){
            //登录，删除数据表
            $user_id = session('user_info.id');
            \app\common\model\Cart::destroy(['id'=>$id, 'user_id'=>$user_id]);
        }else{
            //未登录，删除cookie
            //取出所有cookie数据
            $data = cookie('cart') ?: [];
            //删除
            unset($data[$id]);
            //重新保存到cookie
            cookie('cart', $data, 86400*7);
        }
    }

    /**
     * 修改选中状态
     * @param $id  'all' 修改所有，否则修改单个
     */
    public static function changeStatus($id, $is_selected)
    {
        //判断登录状态
        if(session('?user_info')){
            //修改数据表
            $user_id = session('user_info.id');
            $where['user_id'] = $user_id;
            if($id != 'all'){
                //修改指定的一条
                $where['id'] = $id;
            }
            \app\common\model\Cart::update(['is_selected' => $is_selected], $where, true);
        }else{
            //修改cookie
            //取出所有数据
            $data = cookie('cart') ?: [];
            if($id != 'all'){
                //修改指定的一条
                $data[$id]['is_selected'] = $is_selected;
            }else{
                //修改所有
                foreach($data as $k=>$v){
                    $data[$k]['is_selected'] = $is_selected;
                }
            }
            //重新保存到cookie
            cookie('cart', $data, 86400*7);
        }
    }
}