<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:74:"D:\phpTPstudy\www\pyg\public/../application/home\view\address\address.html";i:1570630926;s:55:"D:\phpTPstudy\www\pyg\application\home\view\layout.html";i:1570583529;s:60:"D:\phpTPstudy\www\pyg\application\home\view\member_left.html";i:1570761924;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />

    <link rel="stylesheet" type="text/css" href="/static/home/css/all.css" />
    <script type="text/javascript" src="/static/home/js/all.js"></script>
</head>

<body>
<!-- 头部栏位 -->
<!--页面顶部-->
<div id="nav-bottom">
    <!--顶部-->
    <div class="nav-top">
        <div class="top">
            <div class="py-container">
                <div class="shortcut">
                    <ul class="fl">
                        <li class="f-item">品优购欢迎您！</li>
                        <?php if((\think\Session::get('user_info') == null)): ?>
                        <li class="f-item">请<a href="<?php echo url('home/login/login'); ?>">登录</a>　<span><a href="<?php echo url('home/login/register'); ?>">免费注册</a></span></li>
                        <?php else: ?>
                        <li class="f-item">Hi,<a href="<?php echo url('home/info/index'); ?>"><?php echo \think\Session::get('user_info.nickname'); ?></a>　<span><a href="<?php echo url('home/login/logout'); ?>">退出</a></span></li>
                        <?php endif; ?>

                    </ul>
                    <ul class="fr">
                        <li class="f-item">我的订单</li>
                        <li class="f-item space"></li>
                        <li class="f-item"><a href="home.html" target="_blank">我的品优购</a></li>
                        <li class="f-item space"></li>
                        <li class="f-item">品优购会员</li>
                        <li class="f-item space"></li>
                        <li class="f-item">企业采购</li>
                        <li class="f-item space"></li>
                        <li class="f-item">关注品优购</li>
                        <li class="f-item space"></li>
                        <li class="f-item" id="service">
                            <span>客户服务</span>
                            <ul class="service">
                                <li><a href="cooperation.html" target="_blank">合作招商</a></li>
                                <li><a href="shoplogin.html" target="_blank">商家后台</a></li>
                            </ul>
                        </li>
                        <li class="f-item space"></li>
                        <li class="f-item">网站导航</li>
                    </ul>
                </div>
            </div>
        </div>

        <!--头部-->
        <div class="header">
            <div class="py-container">
                <div class="yui3-g Logo">
                    <div class="yui3-u Left logoArea">
                        <a class="logo-bd" title="品优购" href="JD-index.html" target="_blank"></a>
                    </div>
                    <div class="yui3-u Center searchArea">
                        <div class="search">
                            <form action="<?php echo url('home/goods/index'); ?>" method="get" class="sui-form form-inline">
                                <!--searchAutoComplete-->
                                <div class="input-append">
                                    <input type="text" id="autocomplete" class="input-error input-xxlarge" name="keywords" value="<?php echo \think\Request::instance()->param('keywords'); ?>" />
                                    <button class="sui-btn btn-xlarge btn-danger" type="submit">搜索</button>
                                </div>
                            </form>
                        </div>
                        <div class="hotwords">
                            <ul>
                                <li class="f-item">品优购首发</li>
                                <li class="f-item">亿元优惠</li>
                                <li class="f-item">9.9元团购</li>
                                <li class="f-item">每满99减30</li>
                                <li class="f-item">亿元优惠</li>
                                <li class="f-item">9.9元团购</li>
                                <li class="f-item">办公用品</li>

                            </ul>
                        </div>
                    </div>
                    <div class="yui3-u Right shopArea">
                        <div class="fr shopcar">
                            <div class="show-shopcar" id="shopcar">
                                <span class="car"></span>
                                <a class="sui-btn btn-default btn-xlarge" href="<?php echo url('home/cart/index'); ?>" target="_blank">
                                    <span>我的购物车</span>
                                    <i class="shopnum">0</i>
                                </a>
                                <div class="clearfix shopcarlist" id="shopcarlist" style="display:none">
                                    <p>"啊哦，你的购物车还没有商品哦！"</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="yui3-g NavList">
                    <div class="all-sorts-list">
                        <div class="yui3-u Left all-sort">
                            <h4>全部商品分类</h4>
                        </div>
                        <div class="sort">
                            <div class="all-sort-list2">
                                <?php foreach($category as $cate_one): ?>
                                <div class="item">
                                    <h3><a href="javascript:;"><?php echo $cate_one['cate_name']; ?></a></h3>
                                    <div class="item-list clearfix">
                                        <div class="subitem">
                                            <?php foreach($cate_one['son'] as $k=>$cate_two): ?>
                                            <dl class="fore<?php echo $k+1; ?>">
                                                <dt><a href="javascript:;"><?php echo $cate_two['cate_name']; ?></a></dt>
                                                <dd>
                                                    <?php foreach($cate_two['son'] as $cate_three): ?>
                                                    <em><a href="<?php echo url('home/goods/index', ['id' => $cate_three['id']]); ?>"><?php echo $cate_three['cate_name']; ?></a></em>
                                                    <?php endforeach; ?>
                                                </dd>
                                            </dl>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="yui3-u Center navArea">
                        <ul class="nav">
                            <li class="f-item">服装城</li>
                            <li class="f-item">美妆馆</li>
                            <li class="f-item">品优超市</li>
                            <li class="f-item">全球购</li>
                            <li class="f-item">闪购</li>
                            <li class="f-item">团购</li>
                            <li class="f-item">有趣</li>
                            <li class="f-item"><a href="seckill-index.html" target="_blank">秒杀</a></li>
                        </ul>
                    </div>
                    <div class="yui3-u Right"></div>
                </div>

            </div>
        </div>
    </div>
</div>


    <title>设置-个人信息</title>
     <link rel="icon" href="/assets/img/favicon.ico">

    <link rel="stylesheet" type="text/css" href="/static/home/css/all.css" />
    <link rel="stylesheet" type="text/css" href="/static/home/css/pages-seckillOrder.css" />


    <!--header-->
    <div id="account">
        <div class="py-container">
            <div class="yui3-g home">
                <!--左侧列表-->
                <!--个人中心 左侧列表-->
<div class="yui3-u-1-6 list">
    <div class="person-info">
        <div class="person-photo"><img src="/static/home/img/_/photo.png" alt=""></div>
        <div class="person-account">
            <span class="name"><?php echo \think\Session::get('user_info.nickname'); ?></span>
            <span class="safe">账户安全</span>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="list-items">
        <dl>
            <dt><i>·</i> 订单中心</dt>
            <dd><a href="<?php echo url('home/myorder/myorder'); ?>">我的订单</a></dd>
            <dd><a href="javascript:;">取消订单记录</a></dd>
        </dl>
        <dl>
            <dt><i>·</i> 关注中心</dt>
            <dd><a href="javascript:;">关注的商品 </a></dd>
            <dd><a href="javascript:;">关注的店铺 </a></dd>
        </dl>
        <dl>
            <dt><i>·</i> 直播中心</dt>
            <dd><a href="<?php echo url('home/live/index'); ?>">我的直播</a></dd>
        </dl>
        <dl>
            <dt><i>·</i> 我的中心</dt>
            <dd><a href="javascript:;">我的收藏</a></dd>
            <dd><a href="javascript:;">我的足迹</a></dd>
        </dl>
        <!-- <dl>
            <dt><i>·</i> 特色业务</dt>
            <dd><a href="javascript:;">我的营业厅 </a></dd>
            <dd><a href="javascript:;">通信 </a></dd>
            <dd><a href="javascript:;">定期送 </a></dd>
            <dd><a href="javascript:;">代下单</a></dd>
            <dd><a href="javascript:;">我的回收单</a></dd>
            <dd><a href="javascript:;">节能补贴</a></dd>
            <dd><a href="javascript:;">医药服务 </a></dd>
            <dd><a href="javascript:;">流量加油站</a></dd>
            <dd><a href="javascript:;">黄金托管</a></dd>
        </dl> -->
        <!-- <dl>
            <dt><i>·</i> 客户服务</dt>
            <dd><a href="javascript:;">返修退换货 </a></dd>
            <dd><a href="javascript:;">价格保护 </a></dd>
            <dd><a href="javascript:;">意见建议 </a></dd>
            <dd><a href="javascript:;">购买咨询 </a></dd>
            <dd><a href="javascript:;">交易纠纷 </a></dd>
            <dd><a href="javascript:;">我的发票 </a></dd>
        </dl> -->
        <dl>
            <dt><i>·</i> 设置</dt>
            <dd><a href="javascript:;">个人信息 </a></dd>
            <dd><a href="<?php echo url('home/address/address'); ?>">收货地址</a> </dd>
            <dd><a href="<?php echo url('home/safe/safe'); ?>">安全中心</a> </dd>
        </dl>
    </div>
</div>
                <!--右侧主内容-->
                <div class="yui3-u-5-6">
                    <div class="body userAddress">
                        <div class="address-title">
                            <span class="title">地址管理</span>
                            <a data-toggle="modal" data-target="#addModel" data-keyboard="false"   class="sui-btn  btn-info add-new">添加新地址</a>
                            <span class="clearfix"></span>
                        </div>
                        <div class="address-detail">
                            <table class="sui-table table-bordered">
                                <thead>
                                            <tr>
                                                <th>姓名</th>
                                                <th>地址</th>
                                                <th>联系电话</th>
                                                <th>操作</th>
                                            </tr>
                                </thead>
                                <tbody>
                                            <?php foreach($address as $v): ?>
                                            <tr address_id="<?php echo $v['id']; ?>" area="<?php echo $v['area']; ?>" address="<?php echo $v['address']; ?>">
                                                <td><?php echo $v['consignee']; ?></td>
                                                <td><?php echo $v['area']; ?> <?php echo $v['address']; ?></td>
                                                <td><?php echo $v['phone']; ?></td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#editModel" data-keyboard="false"  class="edit">编辑</a>
                                                    <a href="<?php echo url('home/address/delete',['id'=>$v['id']]); ?>" class="delete" >删除</a>
                                                    <?php if(($v['is_default'])): ?>默认地址{/else}<a href="">设为默认</a><?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                </tbody>
                            </table>                   
                        </div>
                        <!--添加新增地址弹出层-->
                         <div  tabindex="-1" role="dialog" id="addModel" data-hasfoot="false" class="sui-modal hide fade" style="width:580px;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" data-dismiss="modal" aria-hidden="true" class="sui-close">×</button>
                                        <h4 id="myModalLabel" class="modal-title">新增地址</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo url('home/address/setaddress'); ?>" id="add_form" method="post" class="sui-form form-horizontal">
                                            <div class="control-group">
                                                <label class="control-label">收货人：</label>
                                                <div class="controls">
                                                    <input type="text" class="input-medium" name="consignee">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">所在地区：</label>
                                                <div class="controls">
                                                    <div data-toggle="distpicker">
                                                        <div class="form-group area">
                                                            <select class="form-control" id="province1" name="province"></select>
                                                        </div>
                                                        <div class="form-group area">
                                                            <select class="form-control" id="city1"  name="city"></select>
                                                        </div>
                                                        <div class="form-group area">
                                                            <select class="form-control" id="district1" name="district"></select>
                                                        </div>
                                                    </div>
                                                </div>									 
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">详细地址：</label>
                                                <div class="controls">
                                                    <input type="text" name="address" class="input-large">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">联系电话：</label>
                                                <div class="controls">
                                                    <input type="text" name="phone" class="input-medium">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">邮箱：</label>
                                                <div class="controls">
                                                    <input type="text" name="email" class="input-medium">
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label">地址别名：</label>
                                                <div class="controls">
                                                    <input type="text" name="readdress" class="input-medium">
                                                </div>
                                                <div class="othername">
                                                    建议填写常用地址：<a href="#" class="sui-btn btn-default">家里</a>　<a href="#" class="sui-btn btn-default">父母家</a>　<a href="#" class="sui-btn btn-default">公司</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" data-ok="modal" class="sui-btn btn-primary btn-large" id="ok">确定</button>
                                        <button type="button" data-dismiss="modal" class="sui-btn btn-default btn-large">取消</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 修改地址弹出层 -->
                        <div  tabindex="-1" role="dialog" data-hasfoot="false" id="editModel" class="sui-modal hide fade" style="width:580px;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" data-dismiss="modal" aria-hidden="true" class="sui-close">×</button>
                                            <h4 id="myModalLabel" class="modal-title">修改地址相关信息</h4>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?php echo url('home/address/update'); ?>" method="post" id="edit_form" class="sui-form form-horizontal">
                                               <input type="hidden" name="id">
                                                <div class="control-group">
                                                    <label class="control-label">收货人：</label>
                                                    <div class="controls">
                                                        <input type="text" class="input-medium" name="consignee">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">所在地区：</label>
                                                    <div class="controls">
                                                            <div id="distpicker2">
                                                                    <div class="form-group area">
                                                                        <select class="form-control" id="province1" name="province"></select>
                                                                    </div>
                                                                    <div class="form-group area">
                                                                        <select class="form-control" id="city1"  name="city"></select>
                                                                    </div>
                                                                    <div class="form-group area">
                                                                        <select class="form-control" id="district1" name="district"></select>
                                                                    </div>
                                                                </div>
                                                    </div>									 
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">详细地址：</label>
                                                    <div class="controls">
                                                        <input type="text" id="address" name="address" class="input-large">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label">联系电话：</label>
                                                    <div class="controls">
                                                        <input type="text" name="phone" class="input-medium">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" data-ok="modal" class="sui-btn btn-primary btn-large" id="nook">确定</button>
                                            <button type="button" data-dismiss="modal" class="sui-btn btn-default btn-large">取消</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript" src="/static/home/js/all.js"></script>
<script type="text/javascript" src="/static/home//static/home/pages/userInfo/distpicker.data.js"></script>
<script type="text/javascript" src="/static/home//static/home/pages/userInfo/distpicker.js"></script>
<script type="text/javascript" src="/static/home//static/home/pages/userInfo/main.js"></script>
<script>
    $('#ok').click(function(){
        $('#add_form').submit();
    });
    $('#nook').click(function(){
        $('#edit_form').submit();
    });

    $(function(){
        $('.edit').click(function(){
            var id = $(this).closest('tr').attr('address_id');
            $('#editModel input[name=id]').val(id);
            
            var consignee = $(this).closest('tr').find('td:first').html();
            var phone = $(this).closest('tr').find('td:eq(2)').html();
            var address = $(this).closest('tr').attr('address');

            $('#editModel input[name=consignee]').val(consignee);
            $('#editModel input[name=phone]').val(phone);
            $('#editModel input[name=address]').val(address);

            //获取到省市区，用/分隔  销毁地址组件效果并重置设置
            var area = $(this).closest('tr').attr('area').split(' ');
            $('#distpicker2').distpicker('destroy').distpicker({
                province: area[0],
                city: area[1],
                district: area[2]
            });
        });
    });

</script>
</body>

</html>

<!-- 底部栏位 -->
<!--页面底部-->
<div class="clearfix footer">
    <div class="py-container">
        <div class="footlink">
            <div class="Mod-service">
                <ul class="Mod-Service-list">
                    <li class="grid-service-item intro  intro1">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro2">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro  intro3">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item  intro intro4">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                    <li class="grid-service-item intro intro5">

                        <i class="serivce-item fl"></i>
                        <div class="service-text">
                            <h4>正品保障</h4>
                            <p>正品保障，提供发票</p>
                        </div>

                    </li>
                </ul>
            </div>
            <div class="clearfix Mod-list">
                <div class="yui3-g">
                    <div class="yui3-u-1-6">
                        <h4>购物指南</h4>
                        <ul class="unstyled">
                            <li>购物流程</li>
                            <li>会员介绍</li>
                            <li>生活旅行/团购</li>
                            <li>常见问题</li>
                            <li>购物指南</li>
                        </ul>

                    </div>
                    <div class="yui3-u-1-6">
                        <h4>配送方式</h4>
                        <ul class="unstyled">
                            <li>上门自提</li>
                            <li>211限时达</li>
                            <li>配送服务查询</li>
                            <li>配送费收取标准</li>
                            <li>海外配送</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>支付方式</h4>
                        <ul class="unstyled">
                            <li>货到付款</li>
                            <li>在线支付</li>
                            <li>分期付款</li>
                            <li>邮局汇款</li>
                            <li>公司转账</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>售后服务</h4>
                        <ul class="unstyled">
                            <li>售后政策</li>
                            <li>价格保护</li>
                            <li>退款说明</li>
                            <li>返修/退换货</li>
                            <li>取消订单</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>特色服务</h4>
                        <ul class="unstyled">
                            <li>夺宝岛</li>
                            <li>DIY装机</li>
                            <li>延保服务</li>
                            <li>品优购E卡</li>
                            <li>品优购通信</li>
                        </ul>
                    </div>
                    <div class="yui3-u-1-6">
                        <h4>帮助中心</h4>
                        <img src="/static/home/img/wx_cz.jpg">
                    </div>
                </div>
            </div>
            <div class="Mod-copyright">
                <ul class="helpLink">
                    <li>关于我们<span class="space"></span></li>
                    <li>联系我们<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>商家入驻<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们<span class="space"></span></li>
                    <li>营销中心<span class="space"></span></li>
                    <li>友情链接<span class="space"></span></li>
                    <li>关于我们</li>
                </ul>
                <p>地址：北京市昌平区建材城西路金燕龙办公楼一层 邮编：100096 电话：400-618-4000 传真：010-82935100</p>
                <p>京ICP备08001421号京公网安备110108007702</p>
            </div>
        </div>
    </div>
</div>
<!--页面底部END-->
<!--侧栏面板开始-->
<div class="J-global-toolbar">
    <div class="toolbar-wrap J-wrap">
        <div class="toolbar">
            <div class="toolbar-panels J-panel">

                <!-- 购物车 -->
                <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-cart toolbar-animate-out">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="" class="title"><i></i><em class="title">购物车</em></a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('cart');" ></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div id="J-cart-tips" class="tbar-tipbox hide">
                                <div class="tip-inner">
                                    <span class="tip-text">还没有登录，登录后商品将被保存</span>
                                    <a href="#none" class="tip-btn J-login">登录</a>
                                </div>
                            </div>
                            <div id="J-cart-render">
                                <!-- 列表 -->
                                <div id="cart-list" class="tbar-cart-list">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 小计 -->
                    <div id="cart-footer" class="tbar-panel-footer J-panel-footer">
                        <div class="tbar-checkout">
                            <div class="jtc-number"> <strong class="J-count" id="cart-number">0</strong>件商品 </div>
                            <div class="jtc-sum"> 共计：<strong class="J-total" id="cart-sum">¥0</strong> </div>
                            <a class="jtc-btn J-btn" href="#none" target="_blank">去购物车结算</a>
                        </div>
                    </div>
                </div>

                <!-- 我的关注 -->
                <div style="visibility: hidden;" data-name="follow" class="J-content toolbar-panel tbar-panel-follow">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="#" target="_blank" class="title"> <i></i> <em class="title">我的关注</em> </a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('follow');"></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div class="tbar-tipbox2">
                                <div class="tip-inner"> <i class="i-loading"></i> </div>
                            </div>
                        </div>
                    </div>
                    <div class="tbar-panel-footer J-panel-footer"></div>
                </div>

                <!-- 我的足迹 -->
                <div style="visibility: hidden;" class="J-content toolbar-panel tbar-panel-history toolbar-animate-in">
                    <h3 class="tbar-panel-header J-panel-header">
                        <a href="#" target="_blank" class="title"> <i></i> <em class="title">我的足迹</em> </a>
                        <span class="close-panel J-close" onclick="cartPanelView.tbar_panel_close('history');"></span>
                    </h3>
                    <div class="tbar-panel-main">
                        <div class="tbar-panel-content J-panel-content">
                            <div class="jt-history-wrap">
                                <ul>
                                    <!--<li class="jth-item">
                                        <a href="#" class="img-wrap"> <img src="/static/home//static/home/./static/home/portal/img/like_03.png" height="100" width="100" /> </a>
                                        <a class="add-cart-button" href="#" target="_blank">加入购物车</a>
                                        <a href="#" target="_blank" class="price">￥498.00</a>
                                    </li>
                                    <li class="jth-item">
                                        <a href="#" class="img-wrap"> <img src="/static/home//static/home//static/home/portal/img/like_02.png" height="100" width="100" /></a>
                                        <a class="add-cart-button" href="#" target="_blank">加入购物车</a>
                                        <a href="#" target="_blank" class="price">￥498.00</a>
                                    </li>-->
                                </ul>
                                <a href="#" class="history-bottom-more" target="_blank">查看更多足迹商品 &gt;&gt;</a>
                            </div>
                        </div>
                    </div>
                    <div class="tbar-panel-footer J-panel-footer"></div>
                </div>

            </div>

            <div class="toolbar-header"></div>

            <!-- 侧栏按钮 -->
            <div class="toolbar-tabs J-tab">
                <div onclick="cartPanelView.tabItemClick('cart')" class="toolbar-tab tbar-tab-cart" data="购物车" tag="cart" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count " id="tab-sub-cart-count">0</span>
                </div>
                <div onclick="cartPanelView.tabItemClick('follow')" class="toolbar-tab tbar-tab-follow" data="我的关注" tag="follow" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count hide">0</span>
                </div>
                <div onclick="cartPanelView.tabItemClick('history')" class="toolbar-tab tbar-tab-history" data="我的足迹" tag="history" >
                    <i class="tab-ico"></i>
                    <em class="tab-text"></em>
                    <span class="tab-sub J-count hide">0</span>
                </div>
            </div>

            <div class="toolbar-footer">
                <div class="toolbar-tab tbar-tab-top" > <a href="#"> <i class="tab-ico  "></i> <em class="footer-tab-text">顶部</em> </a> </div>
                <div class="toolbar-tab tbar-tab-feedback" > <a href="#" target="_blank"> <i class="tab-ico"></i> <em class="footer-tab-text ">反馈</em> </a> </div>
            </div>

            <div class="toolbar-mini"></div>

        </div>

        <div id="J-toolbar-load-hook"></div>

    </div>
</div>
<!--购物车单元格 模板-->
<script type="text/template" id="tbar-cart-item-template">
    <div class="tbar-cart-item" >
        <div class="jtc-item-promo">
            <em class="promo-tag promo-mz">满赠<i class="arrow"></i></em>
            <div class="promo-text">已购满600元，您可领赠品</div>
        </div>
        <div class="jtc-item-goods">
            <span class="p-img"><a href="#" target="_blank"><img src="{2}" alt="{1}" height="50" width="50" /></a></span>
            <div class="p-name">
                <a href="#">{1}</a>
            </div>
            <div class="p-price"><strong>¥{3}</strong>×{4} </div>
            <a href="#none" class="p-del J-del">删除</a>
        </div>
    </div>
</script>
<!--侧栏面板结束-->

</body>

</html>