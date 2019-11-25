<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Admin extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //搜索+分页
        //接收参数  keyword  page
        $params = input();
        $where = [];
        if(!empty($params['keyword'])){
            $keyword = $params['keyword'];
            $where['username'] = ['like', "%{$keyword}%"];
        }
        //查询数据
        //$size = $params['size'] ?? 10;
        $size = isset($params['size']) ? (int)$params['size'] : 10;

        $list = \app\common\model\Admin::with('role_bind')->where($where)->paginate($size);
        //返回数据
        $this->ok($list);
    }


    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'username|用户名' => 'require|unique:admin,username',
            'email|邮箱' => 'require|email',
            'password|初始密码' => 'length:6,20',
            'role_id|角色' => 'require|integer|gt:0'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //添加数据（密码加密）  手动加密或者模型修改器自动加密，二选一
        $params['password'] = $params['password'] ?? '123456';
        //$params['password'] = isset($params['password']) ? $params['password'] : '123456';
        //$params['password'] = encrypt_password($params['password']);
        $res = \app\common\model\Admin::create($params, true);
        //返回数据
        $info = \app\common\model\Admin::find($res['id']);
        $this->ok($info);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //查询一条数据
        $info = \app\common\model\Admin::find($id);
        //返回数据
        $this->ok($info);
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
        //超级管理员admin不能修改
        if($id == 1){
            $this->fail('无权修改此管理员');
        }
        //重置密码、修改其他信息
        //接收参数
        $params = input();
        //参数检测
        if(isset($params['type']) && $params['type'] == 'reset_pwd'){
            //重置密码 123456
            //密码通过模型的修改器自动加密
            \app\common\model\Admin::update(['password'=>'123456'], ['id' => $id], true);
        }else{
            //修改其他信息
            $validate = $this->validate($params, [
                'email|邮箱' => 'email',
                'nickname|昵称' => 'length:1,100',
                'role_id|角色' => 'integer|gt:0'
            ]);
            if($validate !== true){
                $this->fail($validate);
            }
            //修改数据
            unset($params['password']);
            \app\common\model\Admin::update($params, ['id' => $id], true);
        }
        //返回数据
        $info = \app\common\model\Admin::find($id);
        $this->ok($info);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //不能删除超级管理员，不能删除自己
        //不能删除admin管理员
        /*if($id == 1){
            $this->fail('不能删除超级管理员');
        }*/
        //如果是其他超级管理员，不能删除
        $info = \app\common\model\Admin::find($id);
        if($info['role_id'] == 1){
            $this->fail('不能删除超级管理员');
        }
        //不能删除自己
        $user_id = input('user_id');
        if($user_id == $id){
            $this->fail('你在开玩笑?');
        }
        //删除一条数据
        \app\common\model\Admin::destroy($id);
        //返回
        $this->ok();
    }
}
