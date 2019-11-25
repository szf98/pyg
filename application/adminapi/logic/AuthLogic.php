<?php
namespace app\adminapi\logic;

class AuthLogic
{
    public static function check()
    {
        //判断特殊页面(首页、退出)
        $pages = ['index/index', 'login/logout'];
        $controller = request()->controller();
        $action = request()->action();
        $path = strtolower( $controller . '/' . $action );
        if(in_array($path, $pages)){
            //特殊页面不需要检测
            return true;
        }
        //判断超级管理员
        $user_id = input('user_id');
        $info = \app\common\model\Admin::find($user_id);
        if($info['role_id'] == 1){
            //超级管理员 不需要检测
            return true;
        }
        //其他情况
        //查询拥有的权限 查询角色表
        $role = \app\common\model\Role::find($info['role_id']);
        $role_auth_ids = explode(',', $role['role_auth_ids']);
        //查询当前访问的权限id
        $auth = \app\common\model\Auth::where('auth_c', $controller)->where('auth_a', $action)->find();
        if(in_array($auth['id'], $role_auth_ids)){
            return true;
        }
        //无权访问
        return false;
    }
}