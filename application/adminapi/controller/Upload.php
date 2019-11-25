<?php

namespace app\adminapi\controller;

use think\Controller;

class Upload extends BaseApi
{
    /**
     * 单图片上传
     */
    public function logo()
    {
        //接收参数
        $params = input();
        //获取文件对象
        $file = request()->file('logo');
        //参数检测（文件logo, type）
        if(empty($file)){
            $this->fail('必须上传图片');
        }
        if(empty($params['type'])){
            $this->fail('type参数错误');
        }
        if(!in_array($params['type'], ['goods', 'category', 'brand'])){
            $params['type'] = 'other';
        }
        //文件移动及检测
        $dir = ROOT_PATH . 'public' . DS . 'uploads' . DS . $params['type'];
        if(!is_dir($dir)) mkdir($dir);
        $info = $file->validate(['size'=>10*1024*1024, 'ext'=>'jpg,jpeg,png,gif', 'type'=>'image/jpeg,image/png,image/gif'])->move($dir);
        //判断结果返回数据
        if($info){
            $logo = DS . 'uploads' . DS . $params['type'] . DS . $info->getSaveName();
            $this->ok($logo);
        }else{
            $msg = $file->getError();
            $this->fail($msg);
        }
    }

    /**
     * 多图片上传
     */
    public function images()
    {
        //接收参数 type
        $params = input();
        //获取上传的文件数组
        $files = request()->file('images');
        //检测参数
        if(empty($files)){
            $this->error('请上传文件');
        }
        if(!is_array($files)){
            $this->error('请以数组方式上传文件');
        }
        //$type = $params['type'] ?? 'goods';
        $type = isset($params['type']) ? $params['type'] : 'goods';
        if(!in_array($type, ['goods', 'category', 'brand'])){
            $type = 'goods';
        }
        //多文件上传  foreach
        //定义结果数组
        $data = [
            'success' => [],
            'error' => []
        ];
        $dir = ROOT_PATH . 'public' . DS . 'uploads' . DS . $type;
        if(!is_dir($dir)) mkdir($dir);
        foreach($files as $file){
            $info = $file->validate(['size'=>10*1024*1024, 'ext'=>'jpg,jpeg,png,gif', 'type'=>'image/jpeg,image/png,image/gif'])->move($dir);
            if($info){
                //成功
                $data['success'][] = DS . 'uploads' . DS . $type . DS . $info->getSaveName();
            }else{
                //失败
                $data['error'][] = [
                    'name' => $file->getInfo('name'),
                    'msg' => $file->getError()
                ];
            }
        }
        //返回结果
        $this->ok($data);
    }


    public function logo1()
    {
        $this->ok(getallheaders());
        //接收参数
        $params = input();
        //获取文件对象
        $file = request()->file('logo');
        //参数检测（文件logo, type）
        if(empty($file)){
            $this->fail('必须上传图片');
        }
        if(empty($params['type'])){
            $this->fail('type参数错误');
        }
        if(!in_array($params['type'], ['goods', 'category', 'brand'])){
            $params['type'] = 'other';
        }
        //文件移动及检测
        $dir = ROOT_PATH . 'public' . DS . 'uploads' . DS . $params['type'];
        if(!is_dir($dir)) mkdir($dir);
        $info = $file->validate(['size'=>10*1024*1024, 'ext'=>'jpg,jpeg,png,gif', 'type'=>'image/jpeg,image/png,image/gif'])->move($dir);
        //判断结果返回数据
        if($info){
            $logo = DS . 'uploads' . DS . $params['type'] . DS . $info->getSaveName();
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
                $this->ok('http://www.pyg.com' . $logo);
            }else{
                //七牛云上传成功  返回七牛云服务器图片路径
                $this->ok('http://py3xoplj8.bkt.clouddn.com/' . $res[0]['key']);
                //$this->ok('http://img.tbyue.com/' . $res[0]['key']);
            }
        }else{
            $msg = $file->getError();
            $this->fail($msg);
        }
    }
}
