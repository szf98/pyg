<?php

namespace app\home\controller;

use think\Controller;
use think\Request;

class Info extends Base
{
    public function index($user_id=1)
    {  
        //登录检测
        if(!session('?user_info')){
            //设置登录成功后的跳转的地址
            session('back_url', 'home/info/index');
            //没有登录  跳转到登录页面
            $this->redirect('home/login/login');
        }
        $user_id = session('user_info.id');
        $info = \app\common\model\User::where('id', $user_id)->find();
        // dump($info);
        // die;
       
        //返回页面
        return view('index', ['info'=>$info]);
    }
    public function setinfo(){
        //接收数据
        $params = input();
        $params['birthday'] = $params['year'] . '-' . $params['month'] . '-' . $params['day'];
        $params['address'] = $params['province'] . '/' . $params['city'] . '/' . $params['district'];
        // dump($params['birthday']);die;
        \app\common\model\User::update($params, ['id'=>session('user_info.id')],true);
        // dump($params);die;
        $this -> redirect('home/index/index');
    }

    //图片上传
    public function figure()
    {
        $file = request()->file('figure');
        if(!$file){
            return json(['code' => 400, 'msg' => '必须上传图片']);
        }
        $dir = ROOT_PATH.'public'.DS . 'uploads' . DS . 'user';
        if(!is_dir($dir)) mkdir($dir);
        $info = $file->validate(['size' => 10*1024*1024, 'ext' => 'jpg,png,jpeg,gif', 'type' => 'image/png,image/gif,image/jpeg'])->move($dir);
        if(!$info){
            return json(['code' => 500, 'msg' => $file->getError()]);
        }
        $logo = DS . 'uploads' . DS . 'user' . DS . $info->getSaveName();

        \think\Image::open('.' . $logo)->thumb(100,100)->save('.' . $logo);
        //将图片上传到七牛云oss
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey ="8pqELqJT-TygyfZibC6S9D5iqmuYDdeIPjrrD6ro";
        $secretKey = "WPsW5A6-T_W4yYdFZM8YUQr4s7JoypSeyaq159LS";
        $bucket = "pyg01";
        // 构建鉴权对象
        $auth = new \Qiniu\Auth($accessKey, $secretKey);
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = '.' . $logo;
        // 上传到七牛后保存的文件名
        $key = basename($logo);
        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new \Qiniu\Storage\UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传。
        //list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        $res = $uploadMgr->putFile($token, $key, $filePath);
        /*返回值 第一个值为文件信息数组，第二个为错误信息
         * $res = [
            ['hash'=>'FtUcud5AcBuJ9iEMk3W5xv5lVUp6', 'key'=>'f455a7b80d408034a77ecbcf2bf39a04.png'],
            null
        ];*/
        if($res[1]){
            //七牛云上传失败  返回本地服务器图片路径
            $path = 'http://www.pyg.com' . $logo;
        }else{
            //七牛云上传成功  返回七牛云服务器图片路径
            $path = 'http://py3xoplj8.bkt.clouddn.com/' . $res[0]['key'];
        }
        $date = \app\common\model\User::update(['figure_url'=>$path], ['id'=>session('user_info.id')]);
        // dump($date);die;
        return json(['code' => 200, 'msg' => 'success', 'data'=>$path]);
    }
    
}
