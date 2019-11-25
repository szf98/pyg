<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Order extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //登录检测
        if(!session('?user_info')){
            //设置登录成功后的跳转的地址
            session('back_url', 'home/cart/index');
            //没有登录  跳转到登录页面
            $this->redirect('home/login/login');
        }
        //查询当前用户的收货地址
        $user_id = session('user_info.id');
        $address = \app\common\model\Address::where('user_id', $user_id)->select();
        // dump($address);die;
        // 查询当前用户（所有选中的商品）要结算的购物记录

        //获得数据层传过来的结果，结果中包函（三个数据）1、当前登录的用户；选中的商品的记录(1，表示选中的记录)2、选中商品的总数量3、总的金额
        $res = \app\home\logic\OrderLogic::getCartWithGoods();
        // dump($res);die;
        $res['address'] = $address;
        // dump($res);die;

        //渲染模板变量赋值
        return view('create', $res);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收数据
        $params = input();
        // dump($params);die;
        //参数检测
        $validate = $this->validate($parasm,[
            'address_id'=>'require|integer|gt:0'
        ]);
        return view();
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
