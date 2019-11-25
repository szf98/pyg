<?php

namespace app\adminapi\controller;

use think\Controller;

class Login extends BaseApi
{
    /**
     * 获取验证码地址的接口
     */
    public function captcha()
    {
        //生成验证码地址，验证码标识
        $uniqid = uniqid('login', true);
        $data = [
            'url' => captcha_src($uniqid),
            'uniqid' => $uniqid
        ];
        //返回数据
        $this->ok($data);
    }

    /**
     * 登录接口
     */
    public function login()
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'username|用户名' => 'require',
            'password|密码' => 'require|length:6,20',
            'uniqid|验证码标识' => 'require',
            'code|验证码' => 'require',
            //'code|验证码' => 'require|captcha:' . $params['uniqid'], //和手动验证 二选一
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //验证码校验
        if(!captcha_check($params['code'], $params['uniqid'])){
            $this->fail('验证码错误');
        }
        //数据处理（进行登录认证）
        //查询用户表
        $password = encrypt_password($params['password']);
        $info = \app\common\model\Admin::where('username', $params['username'])->where('password', $password)->find();
        if(!$info){
            //用户名或密码错误
            $this->fail('用户名或密码错误');
        }
        //登录成功，生成token
        $token = \tools\jwt\Token::getToken($info['id']);
        //返回数据
        $data = [
            'token' => $token,
            'user_id' => $info['id'],
            'username' => $info['username'],
            'nickname' => $info['nickname'],
            'email' => $info['email'],
        ];
        $this->ok($data);
    }

    /**
     * 退出接口
     */
    public function logout()
    {
        //将需要退出的token，记录到缓存的数组中
        $token = \tools\jwt\Token::getRequestToken();
        //将token记录到缓存中，放在数组中
        $delete_token = cache('delete_token') ?: [];
        $delete_token[] = $token;
        //重新存储到缓存
        cache('delete_token', $delete_token, 3600*24);
        //返回数据
        $this->ok();
    }
}
