<?php

namespace app\adminapi\controller;

use think\Controller;

class BaseApi extends Controller
{
    // 验证码接口和登录接口，不需要进行登录检测
    protected $no_login = ['login/captcha', 'login/login'];
    //使用构造方法或者初始化方法
    protected function _initialize()
    {
        parent::_initialize();
        //允许跨域
        //允许的源域名
        header("Access-Control-Allow-Origin: http://localhost:8080");
        //允许的请求头信息
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
        //允许的请求类型
        header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
        //允许携带证书式访问（携带cookie）
        header('Access-Control-Allow-Credentials:true');

        //登录检测
//        $this->checkLogin();

        //权限检测
        $res = \app\adminapi\logic\AuthLogic::check();
        if(!$res){
//            $this->fail('无权访问');
        }
    }


    /**
     * 登录检测
     */
    public function checkLogin()
    {
        try{
            //特殊页面不需要检测
            $path = strtolower( request()->controller() . '/' . request()->action() );
            if(in_array($path, $this->no_login)){
                //不需要检测
                return;
            }
            //进行登录检测，解析token
            $user_id = \tools\jwt\Token::getUserId();
            if(!$user_id){
                //未登录或token无效
                $this->fail('未登录或token无效');
            }
            //将用户id保存到请求信息中
            $this->request->get(['user_id' => $user_id]);
            $this->request->post(['user_id' => $user_id]);
            //后续如果需要使用当前登录的用户id，可以使用以下方式获取
            //$user = $this->request->param('user_id');
            //$user = input('user_id');
        }catch(\Exception $e){
            //$this->fail('token解析失败');
            //开发测试过程，可以返回详细错误信息
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail($msg . ';file:' . $file . ';line:' . $line);
        }
    }

    /**
     * 通用的接口响应方法
     */
    public function response($code=200, $msg='success', $data=[]){
        $res = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ];
        //返回json格式数据 以下两种写法二选一
        //原生php写法
        echo json_encode($res, JSON_UNESCAPED_UNICODE);die;
        //框架写法
        //json($res)->send();
    }

    /**
     * 成功时的响应
     */
    public function ok($data=[], $code=200, $msg='success')
    {
        $this->response($code, $msg, $data);
    }

    /**
     * 失败时的响应
     */
    public function fail($msg='success', $code=500)
    {
        $this->response($code, $msg);
    }
}
