<?php

namespace app\adminapi\controller;

use think\Controller;
use think\Request;

class Goods extends BaseApi
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //搜索+分页  keyword  page  size
        //接收参数
        $params = input();
        $where = [];
        //根据商品名称进行搜索
        if(!empty($params['keyword'])){
            $where['goods_name'] = ['like', "%{$params['keyword']}%"];
        }
        $size = isset( $params['size'] ) ? (int)$params['size'] : 10;
        $list = \app\common\model\Goods::with('category_bind,brand_bind,type_bind')->where($where)->order('id desc')->paginate($size);

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
        $params['goods_desc'] = input('goods_desc', '', 'remove_xss');
        //参考数组结构
        //参数数组参考：(部分省略)
        /*$params = [
            'goods_name' => 'iphone X',
            'goods_price' => '8900',
            'goods_desc' => 'iphone iphonex',
            'goods_logo' => '/uploads/goods/20190101/afdngrijskfsfa.jpg',
            'goods_images' => [
                '/uploads/goods/20190101/dfsssadsadsada.jpg',
                '/uploads/goods/20190101/adsafasdadsads.jpg',
                '/uploads/goods/20190101/dsafadsadsaasd.jpg',
            ],
            'cate_id' => '72',
            'brand_id' => '3',
            'type_id' => '16',
            'item' => [
                '18_21' => [
                    'value_ids'=>'18_21',
                    'value_names'=>'颜色：黑色；内存：64G',
                    'price'=>'8900.00',
                    'cost_price'=>'5000.00',
                    'store_count'=>100
                ],
                '18_22' => [
                    'value_ids'=>'18_22',
                    'value_names'=>'颜色：黑色；内存：128G',
                    'price'=>'9000.00',
                    'cost_price'=>'5000.00',
                    'store_count'=>50
                ]
            ],
            'attr' => [
                '7' => ['id'=>'7', 'attr_name'=>'毛重', 'attr_value'=>'150g'],
                '8' => ['id'=>'8', 'attr_name'=>'产地', 'attr_value'=>'国产'],
            ]
        ]*/
        //参数检测
        $validate = $this->validate($params, [
            'goods_name|商品名称' => 'require',
            'goods_price|商品价格' => 'require|float|gt:0',
            //省略很多字段。。。
            'goods_logo' => 'require',
            'goods_images' => 'require|array',
            'item' => 'require|array',
            'attr' => 'require|array'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        if(!is_file('.' . $params['goods_logo'])){
            $this->fail('logo图片不存在');
        }
        //开启事务
        \think\Db::startTrans();
        try{
            //添加商品数据 (处理logo图片缩略图，属性值转化为json)
            \think\Image::open('.' . $params['goods_logo'])->thumb(200,240)->save('.' . $params['goods_logo']);
            $params['goods_attr'] = json_encode($params['attr'], JSON_UNESCAPED_UNICODE);
            //$params['goods_attr'] = json_encode(array_values($params['attr']), JSON_UNESCAPED_UNICODE);
            $goods = \app\common\model\Goods::create($params, true);
            //添加商品相册
            $goods_images = [];
            foreach($params['goods_images'] as $v){
                //对每一张相册图片，生成两种不同尺寸的缩略图
                if(!is_file('.' . $v)){
                    continue;
                }
                //生成缩略图 400*400 800*800
                $pics_big = dirname($v) . DS . 'thumb_800_' . basename($v);
                $pics_sma = dirname($v) . DS . 'thumb_400_' . basename($v);
                $image = \think\Image::open('.' . $v);
                $image->thumb(800, 800)->save('.' . $pics_big);
                $image->thumb(400, 400)->save('.' . $pics_sma);
                $goods_images[] = [
                    'goods_id' => $goods['id'],
                    'pics_big' => $pics_big,
                    'pics_sma' => $pics_sma,
                ];
            }
            $goods_images_model = new \app\common\model\GoodsImages();
            $goods_images_model->saveAll($goods_images);
            //添加sku
            //遍历$params['item'] 对每条数据增加goods_id字段，可以用于批量添加
            $spec_goods = [];
            foreach($params['item'] as $v){
                $v['goods_id'] = $goods['id'];
                $spec_goods[] = $v;
            }
            //批量添加数据
            $spec_goods_model = new \app\common\model\SpecGoods();
            $spec_goods_model->allowField(true)->saveAll($spec_goods);
            //提交事务
            \think\Db::commit();
            //返回数据
            //重新查询最新的数据
            $info = \app\common\model\Goods::with('category_bind,brand_bind,type_bind')->find($goods['id']);
            $this->ok($info);
        }catch(\Exception $e){
            //回滚事务
            \think\Db::rollback();
            //错误提示
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail($msg . ';file:' . $file . ';line:'. $line);
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //注：关联模型 with方法中嵌套关联，最多只能一个嵌套
        //查询商品信息 关联查询 分类、品牌、sku、相册
        $info = \app\common\model\Goods::with('category,brand,goods_images,spec_goods')->find($id);
        //查询商品所属模型Type， 关联查询 规格名称、规格值、属性
        $type = \app\common\model\Type::with('specs,attrs,specs.spec_values')->find($info['type_id']);
        //将查到的type数据，放到商品信息中
        $info['type'] = $type;
        //返回数据
        $this->ok($info);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //查询商品相关信息 分类、相册、sku
        $goods = \app\common\model\Goods::with('category,category.brands,goods_images,spec_goods')->find($id);
        //查询商品所属的type模型相关信息
        $goods['type'] = \app\common\model\Type::with('attrs,specs,specs.spec_values')->find($goods['type_id']);

        //查询所有的type信息 给下拉列表展示的
        $type = \app\common\model\Type::select();

        //查询分类信息  用于三级联动效果
        //查询所有的一级分类
        $cate_one = \app\common\model\Category::where('pid', 0)->select();
        //查询所属的一级分类下 所有的二级分类
        //从所属的三级分类中的pid_path中 找到所属的一级和二级id
        //$goods['category']['pid_path']  0_2_71   所属一级id $temp[1]   所属二级id $temp[2]
        $temp = explode('_', $goods['category']['pid_path']);
        $cate_two = \app\common\model\Category::where('pid', $temp[1])->select();
        //查询所属的二级分类下 所有的三级分类
        $cate_three = \app\common\model\Category::where('pid', $temp[2])->select();
        //返回数据
        $data = [
            'goods' => $goods,
            'type' => $type,
            'category' => [
                'cate_one' => $cate_one,
                'cate_two' => $cate_two,
                'cate_three' => $cate_three,
            ]
        ];
        $this->ok($data);

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
        //接收数据
        $params = input();
        $params['goods_desc'] = input('goods_desc', '', 'remove_xss');
        /*//参数数组参考：(部分省略)
        $params = [
            'goods_name' => 'iphone X',
            'goods_price' => '8900',
            'goods_desc' => 'iphone iphonex',
            'goods_logo' => '/uploads/goods/20190101/afdngrijskfsfa.jpg',
            'goods_images' => [
                '/uploads/goods/20190101/dfsssadsadsada.jpg',
                '/uploads/goods/20190101/adsafasdadsads.jpg',
                '/uploads/goods/20190101/dsafadsadsaasd.jpg',
            ],
            'cate_id' => '72',
            'brand_id' => '3',
            'type_id' => '16',
            'item' => [
                '18_21' => [
                    'value_ids'=>'18_21',
                    'value_names'=>'颜色：黑色；内存：64G',
                    'price'=>'8900.00',
                    'cost_price'=>'5000.00',
                    'store_count'=>100
                ],
                '18_22' => [
                    'value_ids'=>'18_22',
                    'value_names'=>'颜色：黑色；内存：128G',
                    'price'=>'9000.00',
                    'cost_price'=>'5000.00',
                    'store_count'=>50
                ]
            ],
            'attr' => [
                '7' => ['id'=>'7', 'attr_name'=>'毛重', 'attr_value'=>'150g'],
                '8' => ['id'=>'8', 'attr_name'=>'产地', 'attr_value'=>'国产'],
            ]
        ]*/
        //参数检测
        $validate = $this->validate($params, [
            'goods_name|商品名称' => 'require',
            'goods_price|商品价格' => 'require',
            //省略一些字段
            //'goods_logo|logo图片' => ''
            'goods_images|商品相册' => 'array',
            'item|规格' => 'array',
            'attr|属性' => 'array'
        ]);
        if($validate !== true){
            $this->fail($validate);
        }
        //开启事务
        \think\Db::startTrans();
        try{
            //修改数据：
            //商品logo及缩略图
            if(!empty($params['goods_logo']) && is_file('.' . $params['goods_logo'])){
                $goods_logo = dirname($params['goods_logo']) . DS . 'thumb_' . basename($params['goods_logo']);
                \think\Image::open('.' . $params['goods_logo'])->thumb(200,240)->save('.' . $goods_logo);
                $params['goods_logo'] = $goods_logo;
            }
            //处理商品属性值字段
            if(isset($params['attr'])){
                $params['goods_attr'] = json_encode($params['attr'], JSON_UNESCAPED_UNICODE);
            }
            \app\common\model\Goods::update($params, ['id'=>$id], true);
            //商品相册图片及缩略图  继续上传新图片
            if(isset($params['goods_images'])){
                $goods_images = [];
                foreach($params['goods_images'] as $v){
                    //判断图片是否存在
                    if(!is_file('.' . $v)){
                        continue;
                    }
                    //生成两种尺寸缩略图
                    $pics_big = dirname($v) . DS . 'thumb_800_' . basename($v);
                    $pics_sma = dirname($v) . DS . 'thumb_400_' . basename($v);
                    $image = \think\Image::open('.' . $v);
                    $image->thumb(800, 800)->save('.' . $pics_big);
                    $image->thumb(400, 400)->save('.' . $pics_sma);
                    //组装一条数据
                    $goods_images[] = ['goods_id' => $id, 'pics_big' => $pics_big, 'pics_sma' => $pics_sma];
                }
                //批量添加
                $goods_images_model = new \app\common\model\GoodsImages();
                $goods_images_model->saveAll($goods_images);
            }
            if(isset($params['item']) && !empty($params['item'])){
                //删除原规格商品SKU  goods_id 为条件
                \app\common\model\SpecGoods::destroy(['goods_id' => $id]);
                //添加新规格商品SKU
                $spec_goods = [];
                foreach($params['item'] as $v){
                    //$v中的字段， 对应于 pyg_spec_goods表，缺少goods_id字段
                    $v['goods_id'] = $id;
                    $spec_goods[] = $v;
                }
                //批量添加
                $spec_goods_model = new \app\common\model\SpecGoods();
                $spec_goods_model->allowField(true)->saveAll($spec_goods);
            }
            //提交事务
            \think\Db::commit();
            //返回数据
            $info = \app\common\model\Goods::with('category_bind,brand_bind,type_bind')->find($id);
            $this->ok($info);
        }catch (\Exception $e){
            //回滚事务
            \think\Db::rollback();
            //错误提示
            $msg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $this->fail('错误信息:' . $msg . ';文件：' . $file . ';行数：' . $line);
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //查询商品信息
        /*$info = \app\common\model\Goods::find($id);
        if(!$info){
            //没有查询到商品
            $this->fail('数据异常或已被删除');
        }
        if($info['is_on_sale'] == 1){
            $this->fail('商品已上架，请先下架再删除');
        }
        //删除记录
        $info->delete();
        */
        $is_on_sale = \app\common\model\Goods::where('id', $id)->value('is_on_sale');
        if($is_on_sale == 1){
            $this->fail('商品已上架，请先下架再删除');
        }
        \app\common\model\Goods::destroy($id);

        //查询相册图片
        $goods_images = \app\common\model\GoodsImages::where('goods_id', $id)->select();
        //删除相册图片 数据表记录
        \app\common\model\GoodsImages::destroy(['goods_id'=>$id]);
        //从磁盘删除图片
        $temp = [];
        foreach($goods_images as $v){
            $temp[] = $v['pics_big'];
            $temp[] = $v['pics_sma'];
        }
        //遍历删除图片
        foreach($temp as $v){
            if(is_file('.' . $v)){
                unlink('.' . $v);
            }
        }
        //返回结果
        $this->ok();
    }

    /**
     * 删除相册图片
     */
    public function delpics($id)
    {
        $info = \app\common\model\GoodsImages::find($id);
        if(!$info){
            $this->fail('数据异常');
        }
        //删除相册图片
        //$info->delete();
        \app\common\model\GoodsImages::destroy($id);
        //从磁盘删除图片
        if(is_file('.' . $info['pics_big'])){
            unlink('.' . $info['pics_big']);
        }
        if(is_file('.' . $info['pics_sma'])){
            unlink('.' . $info['pics_sma']);
        }
        //返回数据
        $this->ok();
    }
}
