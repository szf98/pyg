<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//后台接口模块的域名路由
Route::domain('adminapi.pyg.com', function(){
    //默认接口
    Route::get('/', 'adminapi/index/index');
    //路由示例
    /*Route::resource('goods', 'adminapi/goods');
    Route::get('goods/:id', 'adminapi/goods/read');
    Route::post('goods', 'adminapi/goods/save');
    Route::put('goods/:id', 'adminapi/goods/update');
    Route::delete('goods/:id', 'adminapi/goods/delete');
    Route::any('login', 'adminapi/login/login');*/
    //验证码路由
    //显示验证码图片的路由
    \think\Route::get('captcha/:id', "\\think\\captcha\\CaptchaController@index");
    //获取验证码地址接口的路由
    Route::get('captcha', 'adminapi/login/captcha');
    //登录接口
    Route::post('login', 'adminapi/login/login');
    //退出接口
    Route::get('logout', 'adminapi/login/logout');
    //单图片上传接口
    Route::post('logo', 'adminapi/upload/logo');
    Route::post('logo1', 'adminapi/upload/logo1');
    //多图片上传接口
    Route::post('images', 'adminapi/upload/images');
    //商品分类
    Route::resource('categorys', 'adminapi/category', [], ['id'=>'\d+']);
    //商品品牌
    Route::resource('brands', 'adminapi/brand', [], ['id'=>'\d+']);
    //测试
    Route::resource('tests', 'adminapi/test', [], ['id'=>'\d+']);
    //商品模型
    Route::resource('types', 'adminapi/type', [], ['id'=>'\d+']);
    //商品
    Route::resource('goods', 'adminapi/goods', [], ['id'=>'\d+']);
    //商品相册图片删除
    Route::delete('delpics/:id', 'adminapi/goods/delpics', [], ['id'=>'\d+']);
    //权限
    Route::resource('auths', 'adminapi/auth', [], ['id'=>'\d+']);
    //菜单权限接口
    Route::get('nav', 'adminapi/auth/nav');
    //角色
    Route::resource('roles', 'adminapi/role', [], ['id'=>'\d+']);
    //管理员
    Route::resource('admins', 'adminapi/admin', [], ['id'=>'\d+']);

    //个人信息
    Route::get('info', 'adminapi/info/index');
});

//Route::post('home/cart/addcart', 'home/cart/addcart');
