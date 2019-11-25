<?php
namespace app\adminapi\controller;

class Index extends BaseApi
{
    public function index()
    {
        //echo 'adminapi index index';die;
        //测试数据库连接的配置
        /*$res = \think\Db::table('pyg_goods')->find();
        dump($res);die;*/

        //返回数据
        /*$res = ['code' => 200, 'msg' => 'success', 'data'=>['id'=>10, 'name'=>'tp']];
        echo json_encode($res, JSON_UNESCAPED_UNICODE);die;*/

        //$this->response(200, 'success', ['id'=>10, 'name'=>'tp']);
        //$this->response(400, '请求参数错误');

        //成功的响应
        //$this->ok();
        //$this->ok(['id'=>10, 'name'=>'tp']);

        //失败的响应
        //$this->fail('请求参数错误');
        //$this->fail('请求参数错误', 501);

        //头部信息.负载
        /*$header = '{"alg":"HS256","typ":"JWT"}';
        $payload = '{"iss":"zhangsan","sub":"login","aud":"lisi","exp"=>1534567943600,"nbf"=>1534567939999,"iat":1534567940000,"jti":"100","user_id":1}';
        $secret = 'pinyougou';
        $sign = md5($header.$payload.$secret);
        $token = $header.'.'.$payload.'.'.$sign;*/


        /*$header2 = '{"alg":"HS256","typ":"JWT"}';
        $payload2 = '{"iss":"zhangsan","sub":"login","aud":"lisi","exp"=>1534567943600,"nbf"=>1534567939999,"iat":1534567940000,"jti":"100","user_id":2}';
        $secret = 'pinyougou';
        $sign2 = md5($header2.$payload2.$secret);

        if($sign != $sign2){
            //说明token被篡改
        }*/

        //测试token的使用
        /*$user_id = 100;
        $token = \tools\jwt\Token::getToken($user_id);
        //dump($token);die;

        $id = \tools\jwt\Token::getUserId($token);
        dump($id);*/

        //初始化密码
        //echo encrypt_password('123456');die;

        $user_id = input('user_id');
        echo $user_id;die;

    }
}
