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
    <div class="mywrap2" style="bottom: 0.85rem;">
        <div class="news_top">
            <div class="news_title"></div>
        </div>
        <div class="content_s">
            <?php if(is_array($news)): $i = 0; $__LIST__ = array_slice($news,0,5,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="dynamic_block">
                    <a class="dynamic_img" href="<?php echo ($domain); ?>/news/<?php echo ($vo['id']); ?>.html">
                        <img src="<?php echo ($vo["ytgpath"]); ?>" width="100%">
                    </a>
                    <div class="info_title"><?php echo ($vo["title"]); ?></div>
                    <p class="infos">"<?php echo ($vo["content"]); ?>"</p>
                    <p class="time"><?php echo ($vo["pubday"]); ?></p>
                </div><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <?php if($total > 1): ?><div class="last" <?php echo ($total); ?>>上拉加载更多</div>
        <?php else: ?>
            <div class="last">已经到底了</div><?php endif; ?>
    </div>
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
    <!--<a class="phone" href="tel:4008656770"></a>-->
    <script type="text/javascript"src="<?php echo (C("asset_wap")); ?>/js/zepto.min.js"></script>
    <script>
        $('.operate').click(function(){
            $('.mycover').toggle();
            return false;
        });
    
        $('.mycover').click(function(e){
            var target = $(e.target);
            if(target.closest('.dialog').length == 0){
                $('.mycover').toggle();
            }
        });

        // 回到顶部
        $(".top").click(function() {
            $('.mywrap2').scrollTop(0);
        });
    </script>
    <script>
        /*下拉加载*/
        var page_num = 1;
        var PullDown=function(area,fun){
            var startY, endY;
            var detailList=$(area);
            detailList.on('touchstart',function (e) {
                startY = e.touches[0].pageY;
            });
            detailList.on('touchend',function (e) {
                endY = e.changedTouches[0].pageY;
                var y = endY - startY;
                    /*滑动大于200，发送请求*/
                if(y<-100){ 
                    /*滑动的区域，滑动完执行的函数 (回调函数)*/
                    fun();
                }
            });
        };

        var AjaxPost = function(url, query,fun,para){
            $.ajax({
                type:"GET",
                url:url,
                data:query,
                dataType:'json',
                async:true,
                success:function(data){
                    fun(data,para);
                }
            });
        };

        PullDown('.mywrap2',function(){
            $('.last').html('加载中...');
            page_num++;
            var query="page_num="+page_num+"&type=<?php echo ($typeData["id"]); ?>";
            AjaxPost('/index/newslazy',query,function(data){
                if(parseInt(data.status) == 0){
                    var html ='';
                    for(var i = 0;i<data.data.length;i++){
                        var lists = data.data[i];
                        var day =lists.pubday;
                        html +=  '<div class="dynamic_block">'+
                                        '<a class="dynamic_img" href="<?php echo ($domain); ?>/news/'+lists.id+'.html" >'+
                                            '<img  src="'+lists.ytgpath+'" width="100%"/>'+
                                        '</a>'+
                                        '<div class="info_title">'+lists.title+'</div>'+
                                        '<p class="infos">'+lists.content+'</p>'+
                                        '<p class="time">'+day+'</p>'+
                                    '</div>';
                    }
                    $('.content_s').append(html);
                    if(page_num <= data.total){
                        $('.last').html('上拉加载更多');
                    }
                }else{
                    $('.last').html('已经到底了');
                }
            })
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