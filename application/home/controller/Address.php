<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Address extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function address()
    {
        //登录检测
        if(!session('?user_info')){
            //设置登录成功后的跳转的地址
            session('back_url', 'home/info/index');
            //没有登录  跳转到登录页面
            $this->redirect('home/login/login');
        }
        $info = \app\common\model\Address::where('user_id',session('user_info.id'))->select();
        
        return view('address', ['address'=>$info]);
    }
    public function setaddress(Request $request){
        //接收数据
        $params = input();
        // dump($params);die;
        $params['area'] = $params['province'] . ' ' . $params['city'] . ' ' . $params['district'];
        $params['address'] = $params['address'];
        $params['email'] = $params['email'];
        $params['readdress'] =  $params['readdress'];
        $params['consignee'] =  $params['consignee'];
        $params['user_id'] =  session('user_info.id');

        $date = \app\common\model\Address::create($params,true);
        // dump($date);die;
        //跳转页面
        $this->redirect('home/address/address');

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
    public function update()
    {
        //
        $params = input();
       // dump($params);die;
        $validate = $this->validate($params,[
            'consignee'=>'require',
            'phone'=>'require|regex:1[3-9]\d{9}',

        ]);
        if($validate !== true){
            $this->error($validate);
        }
       // dump($params);die;

        $params['area'] = $params['province'] . ' ' . $params['city'] . ' ' . $params['district'];
       

        $data = \app\common\model\Address::update($params,[],true);
        //dump($date);die;
        if($data){
            $this->redirect('home/address/address');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //删除地址
        $params = input();
        // dump($params);
        $data = \app\common\model\Address::destroy($id);
        // dump($data);
        //跳转页面
        $this->redirect('home/address/address');
    }
}
