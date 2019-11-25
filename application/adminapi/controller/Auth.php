<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Auth extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数
        //$params = input();
        $type = input('type', '');
        //查询所有数据
        $list = \app\common\model\Auth::select();
        //转化为标准二维数组
        $list = (new \think\Collection($list))->toArray();
        //if(!empty($params['type']) && $params['type'] == 'tree'){
        if($type == 'tree'){
            //父子级树状结构
            $list = get_tree_list($list);
        }else{
            //转化为无限级分类结构
            $list = get_cate_list($list);
        }
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
            'auth_name|权限名称' => 'require',
            'pid|上级权限' => 'require|integer|egt:0',
            'auth_c|控制器名称' => 'length:1,30',
            'auth_a|方法名称' => 'length:1,30',
            'is_nav|是否菜单项' => 'require|in:0,1'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //添加数据（处理 level  pid_path）
        if($params['pid'] == 0){
            //顶级权限
            $params['level'] = 0;
            $params['pid_path'] = 0;
        }else{
            //二三四级权限,先查询父级
            $p_info = \app\common\model\Auth::find($params['pid']);
            if(!$p_info){
                $this->fail('数据异常');
            }
            $params['level'] = $p_info['level'] + 1;
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
        }
        $res = \app\common\model\Auth::create($params, true);
        $info = \app\common\model\Auth::find($res['id']);
        //返回数据
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
        $info = \app\common\model\Auth::find($id);
        //返回
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
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'auth_name|权限名称' => 'require',
            'pid|上级权限' => 'require|integer|egt:0',
            'auth_c|控制器名称' => 'length:1,30',
            'auth_a|方法名称' => 'length:1,30',
            'is_nav|是否菜单项' => 'require|in:0,1'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //添加数据（处理 level  pid_path）
        if($params['pid'] == 0){
            //顶级权限
            $params['level'] = 0;
            $params['pid_path'] = 0;
        }else{
            //二三四级权限,先查询父级
            $p_info = \app\common\model\Auth::find($params['pid']);
            if(!$p_info){
                $this->fail('数据异常');
            }
            $params['level'] = $p_info['level'] + 1;
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
        }
        //不能降级 查询原始级别，和修改为的级别对比   2 3
        $info = \app\common\model\Auth::find($id);
        if($info['level'] < $params['level']){
            $this->fail('不能降级');
        }
        \app\common\model\Auth::update($params, ['id' => $id], true);
        $info = \app\common\model\Auth::find($id);
        //返回数据
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
        //权限下有子权限，不能删除
        $total = \app\common\model\Auth::where('pid', $id)->count('id');
        if($total){
            $this->fail('权限下有子权限，不能删除');
        }
        //删除数据
        \app\common\model\Auth::destroy($id);
        //返回
        $this->ok();
    }

    /**
     * 菜单权限接口
     */
    public function nav()
    {
        try{
            //获取当前登录的管理员id
            $user_id = input('user_id');
            //查询管理员信息  role_id
            $user = \app\common\model\Admin::find($user_id);
            $role_id = $user['role_id'];
            //是否超级管理员
            if($role_id == 1){
                //超级管理员  直接查询权限表
                $list = \app\common\model\Auth::where('is_nav', 1)->select();
            }else{
                //其他管理员  先查询角色表 role_auth_ids
                $role = \app\common\model\Role::find($role_id);
                $role_auth_ids = $role['role_auth_ids'];
                //查询权限表 拥有的菜单权限
                $list = \app\common\model\Auth::where('is_nav', 1)->where('id', 'in', $role_auth_ids)->select();
                //$list = \app\common\model\Auth::where(['id'=>['in', $role_auth_ids], 'is_nav' => 1])->select();
            }
            //转化为标准二维数组
            $list = (new \think\Collection($list))->toArray();
            //转化为父子树状结构
            $list = get_tree_list($list);
            //返回数据
            $this->ok($list);
        }catch(\Exception $e){
            //$this->fail('数据异常，请稍后再试');
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail('错误信息：' . $msg . ';文件路径：' . $file . '行数：' . $line);
        }

    }
}
