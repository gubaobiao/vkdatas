<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=meta, minimum-scale=1.0">
    <meta name="msapplication-tap-highlight" content="no">
    <meta name="msapplication-TileColor" content="#000">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <meta name="renderer" content="webkit">
    <meta name="keywords" content="<?php echo ($seoWord['keywords']); ?>" />
    <meta name="description" content="<?php echo ($seoWord['description']); ?>" />
    <link href="<?php echo (C("asset_wap")); ?>/css/swiper.min.css" rel="stylesheet">
    <link href="<?php echo (C("asset_wap")); ?>/css/index.css" rel="stylesheet">
    <title><?php echo ($seoWord['title']); ?></title>
    <script type="text/javascript">
        function fontFun() {
            var winW = document.documentElement.clientWidth;
            document.documentElement.style.fontSize = (winW / 750 * 625) + "%";
        }
        fontFun();
        window.onresize = fontFun;
    </script>
</head>
<body>
    <!--头部-->
    <div class="top">
        <div class="logo"></div>
        <div class="operate"></div>
    </div>

    <div class="mywrap">
        <!--banner轮播图-->
        <div class="banner swiper-container">
            <ul class="bannerUl swiper-wrapper">
                <?php if(is_array($res)): $k = 0; $__LIST__ = $res;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li class="swiper-slide bannerLi<?php echo ($k); ?> "></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="swiper-pagination babber_page">
                <span class="swiper-pagination-bullet"></span>
                <span class="swiper-pagination-bullet"></span>
                <span class="swiper-pagination-bullet"></span>
            </div>
        </div>

        <!--分类作品-->
        <div class="workType">
            <div class="typeTitle">分类作品案例</div>
            <div class="typeWrap">
                <div class="swiper-wrapper typeContent">
                    <?php if(is_array($videoTypes)): foreach($videoTypes as $k=>$vo): ?><a class="swiper-slide typeItem" href="<?php echo ($domain); ?>/type/<?php echo ($vo['id']); ?>.html">
                            <img src="<?php echo ($vo["img_path"]); ?>">
                            <span class="video-title"><?php echo ($vo["type_name"]); ?></span>
                        </a><?php endforeach; endif; ?>
                </div>
            </div>
        </div>

        <!--不同类型案例展示-->
        <!--品牌宣讲-->
        <?php if(is_array($anli)): foreach($anli as $k=>$vo): ?><!--品牌宣讲-->
            <?php if($k == 22 ): ?><div class="commonWrap brand_publicity">
                    <a class="titles brand_title" href="<?php echo ($domain); ?>/type/22.html"></a>
                    <div class="view">
                        <div class="swiper-wrapper viewWrap">
                            <?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><a class="swiper-slide viewItem" href="<?php echo ($domain); ?>/anli/<?php echo ($data['id']); ?>.html">
                                    <div class="viewImg">
                                        <img src="<?php echo ($data["ytpath"]); ?>" width="100%">
                                    </div>
                                    <div class="viewInfo">
                                        <p class="view_title"><?php echo ($data["title"]); ?></p>
                                        <p class="view_date"><?php echo ($data["pubday"]); ?></p>
                                    </div>
                                </a><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                </div>
                <!--APP/产品-->
                <?php elseif($k == 23): ?>
                    <div class="commonWrap app_product">
                        <a class="titles app_title" href="<?php echo ($domain); ?>/type/23.html"></a>
                        <div class="view">
                            <div class="swiper-wrapper viewWrap">
                                <?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><a class="swiper-slide viewItem" href="<?php echo ($domain); ?>/anli/<?php echo ($data['id']); ?>.html">
                                        <div class="viewImg">
                                            <img src="<?php echo ($data["ytpath"]); ?>" width="100%">
                                        </div>
                                        <div class="viewInfo">
                                            <p class="view_title"><?php echo ($data["title"]); ?></p>
                                            <p class="view_date"><?php echo ($data["pubday"]); ?></p>
                                        </div>
                                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                    </div>
                <!--科普教育-->
                <?php elseif($k == 24): ?>
                    <div class="commonWrap science_edu">
                        <a class="titles science_title" href="<?php echo ($domain); ?>/type/24.html"></a>
                        <div class="view">
                            <div class="swiper-wrapper viewWrap">
                                <?php if(is_array($vo)): $i = 0; $__LIST__ = $vo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><a class="swiper-slide viewItem" href="<?php echo ($domain); ?>/anli/<?php echo ($data['id']); ?>.html">
                                        <div class="viewImg">
                                            <img src="<?php echo ($data["ytpath"]); ?>" width="100%">
                                        </div>
                                        <div class="viewInfo">
                                            <p class="view_title"><?php echo ($data["title"]); ?></p>
                                            <p class="view_date"><?php echo ($data["pubday"]); ?></p>
                                        </div>
                                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
                            </div>
                        </div>
                    </div>
                <?php else: endif; endforeach; endif; ?>

        <!--最新动态-->
        <div class="dynamic">
            <div class="white"></div>
            <div class="dynamic_title">
                <div class="title_detail">最新动态</div>
                <a class="dynamic_more" href="<?php echo ($domain); ?>/news.html"></a>
            </div>

            <?php if(is_array($news)): $k = 0; $__LIST__ = array_slice($news,1,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="dynamic_block <?php if($k == 1): ?>margintop<?php endif; ?>">
                    <a class="dynamic_img" href="<?php echo ($domain); ?>/news/<?php echo ($vo['id']); ?>.html">
                        <img src='<?php echo ($vo["ytgpath"]); ?>' width="100%">
                    </a>
                    <div class="info_title"><?php echo ($vo["title"]); ?></div>
                    <p class="infos"><?php echo ($vo["description"]); ?></p>
                    <p class="time"><?php echo ($vo["pubday"]); ?></p>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <!--phone-->
        <!--<a class="phone" href="tel:4008656770"></a>-->
        <div class="mybottom">
            <div class="aboutUs">
                <div class="block_two">
                    <div class="service_title">服务电话：</div>
                    <a class="service_tel" href="tel:4008656770">400-8656-770</a>
                    <div class="service_bj">|</div>
                    <a class="service_phone" href="tel:<?php echo ($contact[u_tel][2]); ?>"><?php echo ($contact[u_tel][2]); ?></a>
                </div>
                <a class="block_three" href="mqqwpa://im/chat?chat_type=wpa&uin=<?php echo ($contact[u_QQ][1]); ?>&version=1&src_type=web&web_src=oicqzone.com">QQ：<?php echo ($contact[u_QQ][1]); ?></a>
            </div>
        </div>
    </div>
    <!--bottom-->
    <!--<div class="bottom">-->
        <!--<div class="choose">-->
            <!--<a class="choose_item choose_one" href="<?php echo ($domain); ?>">-->
                <!--<div class="bottom_icon bottom_icon_1"></div>-->
                <!--<div class="bottom_text">首页</div>-->
            <!--</a>-->
            <!--<a class="choose_item" href="<?php echo ($domain); ?>/worker.html">-->
                <!--<div class="bottom_icon bottom_icon_2"></div>-->
                <!--<div class="bottom_text">作品案例</div>-->
            <!--</a>-->
            <!--<a class="choose_item" href="<?php echo ($domain); ?>/wegot.html">-->
                <!--<div class="bottom_icon bottom_icon_3"></div>-->
                <!--<div class="bottom_text">来撩我们</div>-->
            <!--</a>-->
        <!--</div>-->
    <!--</div>-->
    <div class="mycover">
    <div class="dialog">
        <div class="item_wap">
            <a class="item" href="<?php echo ($domain); ?>">
                <span class="item_icon one"></span>
                <span class="item_text">首页</span>
            </a>
            <a class="item" href="<?php echo ($domain); ?>/worker.html">
                <span class="item_icon two"></span>
                <span class="item_text">作品案例</span>
            </a>
            <a class="item" href="<?php echo ($domain); ?>/wegot.html">
                <span class="item_icon three"></span>
                <span class="item_text">来撩我们</span>
            </a>
        </div>
        <div class="item_wap">
            <!-- <a class="item" href="<?php echo ($domain); ?>">
                <span class="item_icon four"></span>
                <span class="item_text">关于我们</span>
            </a>
            <a class="item" href="<?php echo ($domain); ?>">
                <span class="item_icon five"></span>
                <span class="item_text">服务流程</span>
            </a> -->
            <a class="item" href="<?php echo ($domain); ?>/news.html">
                <span class="item_icon six"></span>
                <span class="item_text">动画资讯</span>
            </a>
        </div>
    </div>
</div>
    <script type="text/javascript"src="<?php echo (C("asset_wap")); ?>/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo (C("asset_wap")); ?>/js/swiper.min.js"></script>
    <script type="text/javascript" src="<?php echo (C("asset_wap")); ?>/js/base.js"></script>
    <script>
        $(function(){
            var swiper1 = new Swiper(".swiper-container",{
                direction:"horizontal",
                autoplayDisableOnInteraction : false,
                pagination:".swiper-pagination",
                loop:true,
                autoplay:3000,
            });
            var swiper2 = new Swiper('.typeWrap', {
                slidesPerView: 4,
                autoplay:2000,
                autoplayDisableOnInteraction : false,
                spaceBetween: 20      
            });
            var swiper3 = new Swiper('.view');

            // 回到顶部
            $(".top").click(function() {
                $('.mywrap').scrollTop(0);
            });
        });
    </script>
    <!--客服代码-->
    <script>
        (function() {var _53code = document.createElement("script");
            _53code.src = "https://tb.53kf.com/code/code/10167819/2";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(_53code, s)
            ;})();
    </script>
</body>
</html>