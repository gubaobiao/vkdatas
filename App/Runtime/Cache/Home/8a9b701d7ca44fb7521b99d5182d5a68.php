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
        <a href="javascript:history.go(-1)" class="back"></a>
    </div>

    <div class="mywrap2">
        <div class="enws_img">
            <img src="/<?php echo ($info["ytgpath"]); ?>" width="100%" />
        </div>
        <div class="content">
            <div class="content_text">
                <p class="p1"><?php echo ($info["title"]); ?></p>
                <p class="p2"><?php echo ($info['pubday']); ?></p>
            </div>
            <div class="p3"><?php echo ($info["content"]); ?></div>
        </div>
        

        <div class="share_block">
            <div class="share"></div>
        </div>
        <div class="last">已经到底了</div>
    </div>
    <!--分享框-->
    <div class="cover">
        <div class="shareArea">
            <div class="shareblock_one">
                <p class="share_t1">分享</p>
                <p class="share_t2"><?php echo ($info["title"]); ?></p>
            </div>
            
            <div class="bdsharebuttonbox shareblock_two">
                 
                <div class="share_item">
                    <a class="qq_block bds_qzone"  data-cmd="qzone" title="分享到QQ空间"></a>
                    <span>QQ空间</span>
                </div>
                <div class="share_item">
                    <a class="wb_block bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                    <span>微博</span>
                </div>
                <div class="share_item ">
                    <!-- <a class="wx_block bds_weixin" data-cmd="weixin" title="分享到微信"></a> -->
                    <a class="wx_block"  title="分享到微信"></a>
                    <span>朋友圈</span>
                </div>
            </div>
            <div class="cancle_block">
                <div class="cancle_btn">取消</div>
            </div>
        </div>
    </div>
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
        function resizepic(){}
        $('.wx_block').click(function(){
            window.alert('可是一使用浏览器分享,按钮分享给好友哦');
        });
        $('.cancle_btn,.share').click(function(){
            $('.cover').stop(false,true).fadeToggle();
        });
        
        // 点击空白处关闭分享
        $(".cover").on('click',function(e){
            var target = $(e.target);
            if(target.closest('.shareArea').length == 0){
                $('.cover').stop(false,true).fadeToggle();
            }
            e.stopPropagation();
        });

        // 回到顶部
        $(".top").click(function() {
            $('.mywrap2').scrollTop(0);
        });
    </script>
    <script>
        window._bd_share_config={
            "common":{
                bdText : '<?php echo ($info["title"]); ?>',
                bdDesc : '<?php echo ($info["description"]); ?>',
                bdUrl : '<?php echo ($info["share_url"]); ?>',
                bdPic : '<?php echo ($info["sltpath"]); ?>'
            },
            "share":{},
            "image":{
                "viewList":["qzone","tsina","tqq","renren","weixin"],
                "viewText":"分享到：","viewSize":"16"
            },
            "selectShare":{
                "bdContainerClass":null,
                "bdSelectMiniList":["qzone","tsina","tqq","renren","weixin"]
            }
        };
        with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
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