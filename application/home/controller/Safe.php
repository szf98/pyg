<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Safe extends Base
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function safe($user_id=1)
    {
        //登录检测
        if(!session('?user_info')){
            //设置登录成功后的跳转的地址
            session('back_url', 'home/safe/safe');
            //没有登录  跳转到登录页面
            $this->redirect('home/login/login');
        }
        return view();
    }

    public function setsafe(){
        //接收数据
        $params = input();
        // dump($params);die;
        //参数检测
        $validate = $this->validate($params,[
            'nickname|用户名' => 'require',
            'password|密码' => 'require|length:6,20',
            'password|确认密码' => 'require|length:6,20'
        ]);
        if($params['password']==$params['confirm']){
            $params['password'] = encrypt_password($params['password']);
            $params['confirm'] = $params['password'];
            $params['nickname']=$params['nickname'];
            // dump($params['birthday']);die;
            \app\common\model\User::update($params, ['id'=>session('user_info.id')],true);
    
            // dump($data);die;
        }else{
            dump('用户名或密码错误，请从新输入');die;
        }
       
        $this->redirect('home/index/index');
    }
}
