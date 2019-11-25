<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Category extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数 pid  type
        $params = input();
        $validate = $this->validate($params, [
            'pid' => 'integer|egt:0',
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        $where = [];
        if(isset($params['pid'])){
            $where['pid'] = $params['pid'];
        }
        //查询数据
        $list = \app\common\model\Category::field('id,cate_name,pid,pid_path_name,level,image_url,is_hot')->where($where)->select();

        //转化为标准的二维数组
        $list = (new \think\Collection($list))->toArray();
        //if(!isset($params['type']) || $params['type'] != 'list'){
        if(!(isset($params['type']) && $params['type'] == 'list')){
            //转化为无限级分类列表
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
            'cate_name|分类名称' => 'require',
            'pid|上级分类' => 'require|integer|egt:0',
            'is_show|是否显示' => 'require|in:0,1',
            'is_hot|是否热门' => 'require|in:0,1',
            //'logo|logo图片' => '',
            'sort|排序' => 'require|integer',
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //添加数据
        if($params['pid'] == 0){
            //添加的是一级分类
            $params['pid_path'] = 0;
            $params['pid_path_name'] = '';
            $params['level'] = 0;
        }else{
            //先查询上级分类信息 $params['pid']
            $p_info = \app\common\model\Category::find($params['pid']);
            if(!$p_info){
                $this->fail('数据异常');
            }
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
            $params['pid_path_name'] = $p_info['pid_path_name'] ? $p_info['pid_path_name'] . '_' . $p_info['cate_name'] : $p_info['cate_name'];
            $params['level'] = $p_info['level'] + 1;
        }
        //logo图片的处理 生成缩略图
        if(isset($params['logo']) && is_file('.' . $params['logo'])){
            \think\Image::open('.' . $params['logo'])->thumb(30, 30)->save('.' . $params['logo']);
            $params['image_url'] = $params['logo'];
        }
        $res = \app\common\model\Category::create($params, true);
        //返回数据
        $info = \app\common\model\Category::field('id,cate_name,pid,pid_path_name,level,is_show,is_hot,image_url')->find($res['id']);
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
        $info = \app\common\model\Category::field('id,cate_name,pid,pid_path_name,level,image_url,is_hot,is_show')->find($id);
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
        //接收参数
        $params = input();
        //参数检测
        $validate = $this->validate($params, [
            'cate_name|分类名称' => 'require',
            'pid|上级分类' => 'require|integer|egt:0',
            'is_show|是否显示' => 'require|in:0,1',
            'is_hot|是否热门' => 'require|in:0,1',
            //'logo|logo图片' => '',
            'sort|排序' => 'require|integer',
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //修改数据
        if($params['pid'] == 0){
            //修改的是一级分类
            $params['pid_path'] = 0;
            $params['pid_path_name'] = '';
            $params['level'] = 0;
        }else{
            //先查询上级分类信息 $params['pid']
            $p_info = \app\common\model\Category::find($params['pid']);
            if(!$p_info){
                $this->fail('数据异常');
            }
            $params['pid_path'] = $p_info['pid_path'] . '_' . $p_info['id'];
            $params['pid_path_name'] = $p_info['pid_path_name'] ? $p_info['pid_path_name'] . '_' . $p_info['cate_name'] : $p_info['cate_name'];
            $params['level'] = $p_info['level'] + 1;
        }
        //不能降级
        $info = \app\common\model\Category::find($id);
        if($info['level'] < $params['level']){
            //不能降级  1 <  2
            $this->fail('不能降级');
        }
        //logo图片的处理 生成缩略图
        if(isset($params['logo']) && is_file('.' . $params['logo'])){
            \think\Image::open('.' . $params['logo'])->thumb(30, 30)->save('.' . $params['logo']);
            $params['image_url'] = $params['logo'];
        }
        \app\common\model\Category::update($params, ['id' => $id], true);
        //返回数据
        $info = \app\common\model\Category::field('id,cate_name,pid,pid_path_name,level,is_show,is_hot,image_url')->find($id);
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
        //分类下有子分类，不能删除
        $total = \app\common\model\Category::where('pid', $id)->count('id');
        if($total > 0){
            $this->fail('分类下有子分类，不能删除');
        }
        /*$son = \app\common\model\Category::where('pid', $id)->find();
        if($son){
            $this->fail('分类下有子分类，不能删除');
        }*/
        //分类下有品牌，不能删除
        $total = \app\common\model\Brand::where('cate_id', $id)->count('id');
        if($total){
            $this->fail('分类下有品牌，不能删除');
        }
        //分类下有商品，不能删除
        $total = \app\common\model\Goods::where('cate_id', $id)->count('id');
        if($total){
            $this->fail('分类下有商品，不能删除');
        }

        //删除一条数据
        \app\common\model\Category::destroy($id);
        //返回数据
        $this->ok();
    }
}
