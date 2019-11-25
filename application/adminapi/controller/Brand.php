<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Brand extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //接收参数  keyword  page  cate_id
        $params = input();
        if(!empty($params['cate_id'])){
            //查询分类下的品牌
            $list = \app\common\model\Brand::field('id,name')->where('cate_id', $params['cate_id'])->select();
        }else{
            //分页+搜索
            $where = [];
            if(!empty($params['keyword'])){
                $where['t1.name'] = ['like', "%{$params['keyword']}%"];
            }
            //$list = \app\common\model\Brand::where($where)->paginate(10);
            //连表查询SELECT t1.id,t1.name,t1.logo,t1.desc,t1.sort,t1.is_hot, t2.cate_name FROM `pyg_brand` t1 left join pyg_category t2 on t1.cate_id = t2.id where t1.name like '%亚%' limit 0,10;
            $list = \app\common\model\Brand::alias('t1')
                ->join('pyg_category t2', 't1.cate_id=t2.id')
                ->field('t1.id,t1.name,t1.logo,t1.desc,t1.sort,t1.is_hot, t2.cate_name')
                ->where($where)
                ->paginate(10);
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
            'name|品牌名称' => 'require',
            'cate_id|所属分类' => 'require|integer|gt:0',
            'is_hot|是否热门' => 'require|in:0,1',
            'sort|排序' => 'require|integer|between:0,9999',
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //logo图片生成缩略图
        if(isset($params['logo']) && is_file('.' . $params['logo'])){
            \think\Image::open('.' . $params['logo'])->thumb(100, 50)->save('.' . $params['logo']);
        }
        //添加数据
        $res = \app\common\model\Brand::create($params, true);
        $info = \app\common\model\Brand::find($res['id']);
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
        $info = \app\common\model\Brand::find($id);
        //$info = \app\common\model\Brand::field('id,name,logo,desc,sort,is_hot,cate_id,url')->find($id);
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
            'name|品牌名称' => 'require',
            'cate_id|所属分类' => 'require|integer|gt:0',
            'is_hot|是否热门' => 'require|in:0,1',
            'sort|排序' => 'require|integer|between:0,9999',
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //logo图片生成缩略图
        if(isset($params['logo']) && is_file('.' . $params['logo'])){
            \think\Image::open('.' . $params['logo'])->thumb(100, 50)->save('.' . $params['logo']);
        }else{
            //防止图片不存在，导致页面无法显示图片
            unset($params['logo']);
        }
        //修改数据
        \app\common\model\Brand::update($params, ['id'=>$id], true);
        $info = \app\common\model\Brand::find($id);
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
        //品牌下有商品，不能删除
        $total = \app\common\model\Goods::where('brand_id', $id)->count('id');
        if($total){
            $this->fail('品牌下有商品，不能删除');
        }
        //删除一条数据
        \app\common\model\Brand::destroy($id);
        //返回数据
        $this->ok();
    }
}
