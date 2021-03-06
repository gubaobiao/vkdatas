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

    <!--content-->
        <div class="worker myworkers">
            <?php if(is_array($videoTypes)): $i = 0; $__LIST__ = $videoTypes;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a class="work_item work_<?php echo ($key); ?>" href="<?php echo ($domain); ?>/type/<?php echo ($vo['id']); ?>.html">
                    <img src="<?php echo (C("asset_wap")); ?>/img/work_<?php echo ($key); ?>.jpg" width="100%"/>
                    <div class="myworker">
                        <p class="work_p1">#<?php echo ($vo["type_name"]); ?>#</p>
                        <p class="work_p2"><?php echo ($vo["count"]); ?>个作品</p>
                    </div>
                </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>

    <!--bottom-->
    <!--<div class="bottom setStyle" style="position: fixed;bottom: 0rem;">-->
        <!--<div class="choose">-->
            <!--<a class="choose_item " href="<?php echo ($domain); ?>">-->
                <!--<div class="bottom_icon bottom_icon_1"></div>-->
                <!--<div class="bottom_text">首页</div>-->
            <!--</a>-->
            <!--<a class="choose_item choose_two" href="<?php echo ($domain); ?>/worker.html">-->
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
    <!--<a class="phone" href="tel:4008656770"></a>-->
    <script src="<?php echo (C("asset_wap")); ?>/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="<?php echo (C("asset_wap")); ?>/js/base.js"></script>
    <script>
    // 回到顶部
    $(".top").click(function() {
        $('.myworkers').scrollTop(0);
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