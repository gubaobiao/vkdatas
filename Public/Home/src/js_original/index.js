//banner
(function( banner, ctrlWrap ){
	if( !banner.length ) return;
	var li = banner.find(".bquery>.blist");
	var link = li.children("a");

	var firstli = li.eq(0);
	var currli = firstli;
	var currlink = currli.find("a").attr("href");
	var currurl = currli.find("img").attr("src");
	var nextli = currli.next("li");

	var filterWrap = $('<div class="bfilter">').appendTo( banner );
	var queue = $( Array(21).join('<a href="javascript:void(0);"><img src="" /></a>') ).appendTo( filterWrap );
	var reverseQueue = Array.prototype.reverse.call( filterWrap.children("a") );
	var filterImg = queue.children("img");

	var ctrl = $( $.map(li, function(){//创建控制点
		return '<a href="javascript:void(0);"></a>';
	}).join() ).appendTo( ctrlWrap );

	var currLeft = 0;
	var imgWidth = 0;
	var winWidth = $(window).width();
	function autoResize( speed ){
		var currWidth = 0;

		if ( winWidth <= 1000 ) {
			currWidth = 1000;
			currLeft = ( 1000 - imgWidth ) / 2;
		} else if( winWidth >= imgWidth ) {
			currWidth = imgWidth;
			currLeft = 0;
		} else {
			currWidth = winWidth;
			currLeft = ( winWidth - imgWidth ) / 2;
		}

		TweenLite.to( link, speed, {
			"left" : currLeft
		});

		banner.add( li ).width( currWidth );

		queue.width( Math.ceil( currWidth / queue.length ) );

		banner.find(".bfilter").children("a").each(function(i){//resize滤镜图片 位置
            $(this).children("img").css( "left", -$(this).position().left + currLeft );
        });
	}

	var timer = null;
	function winResize(){
		winWidth = $(window).width();
		if ( timer ) {
			clearTimeout( timer );
			timer = null;
		}

		timer = setTimeout(function(){
			autoResize( 0.2 );
		}, 200)
	}

	var autoTimer = null;
	var isRun = false;
	function imgLoad(){
		winWidth = $(window).width();
		imgWidth = img.width;
		autoResize(0);
		$(window).on("resize", winResize);

		ctrlWrap.children("a:eq(0)").addClass("curr");

		li.eq(0).show();
		banner.find(".bfilter").children("a").each(function(i){//初始化滤镜图片 位置 及宽度
			$(this).css( "left", ( i * 5 ) + "%" );
			$(this).children("img").css( "left", -$(this).position().left + currLeft );
		});
		queue.attr("href", currlink);
		filterImg.attr("src", currurl);//初始化

		ctrl.on("click", function(){
			if ( isRun || $(this).hasClass("curr") ) return;
			isRun = true;
			
			ctrl.removeClass("curr");
			$(this).addClass("curr");
			nextli = li.eq( $(this).index() );
			init();

			if ( autoTimer ) {
            	clearTimeout( autoTimer );
            	autoTimer = null;
            }
		});
	}

	var img = new Image();
	$(img).on("load", imgLoad);
	img.src = link.children("img")[0].src;

    function complete(){
        currli = nextli;
        currurl = nextli.find("img").attr("src");
        nextli = currli.next("li");
        nextli = nextli.length ? nextli : firstli;
        filterImg.attr("src", currurl);
        
        isRun = false;

        autoTimer = setTimeout(function(){
        	var curr = ctrl.filter(".curr").next("a");
        	curr = curr.length ? curr : ctrl.eq(0);
        	curr.trigger("click");
        },3000);
    }

    function reset(){
        filterWrap.css({
            "bottom" : 0
        });
        queue.css({
            "top" : "auto",
            "bottom" : "auto",
            "opacity" : 1
        });
    }

    function animate(target, css){
        TweenMax.staggerTo( target , 1 , css, 0.05, complete);
    }

    var css = {
        top :  {
            "top" : -570,
            "opacity" : 0
        },
        bottom : {
            "bottom" : -570,
            "opacity" : 0
        },
        lr : {
            "opacity" : 0
        }
    }

    var filter = {
        top : function(){
            animate(queue, css.top);
        },
        topReverse : function(){
            animate(reverseQueue,  css.top);
        },
        bottom : function(){
            animate(queue, css.bottom);
        },
        bottomReverse : function(){
            animate(reverseQueue, css.bottom);
        },
        left : function(){
            animate(queue, css.lr);
        },
        right : function(){
            animate(reverseQueue, css.lr);
        }
    }

    function init(){
        reset();
        filter[ ["top", "topReverse", "bottom", "bottomReverse", "left", "right"][ Math.round( Math.random() * 5 ) ] ]();
        currli.hide();
        nextli.show();
    }

    autoTimer = setTimeout(function(){
    	var curr = ctrl.filter(".curr").next("a");
    	curr = curr.length ? curr : ctrl.eq(0);
    	curr.trigger("click");
    },3000);

})( $("#bannerQuery"), $("#bannerCtrl") );

//service
;(function( service ){
	if ( !service.length ) return;
	var timer = null;
	var speed = 5000;
	var serLi = service.children(".serLi");
	var isAuto = serLi.length > 3 ? true : false;

	var config = {
		topHover : {
			"top" : -12
		},
		topLink : {
			"top" : 0
		},
		bgHover : {
			"backgroundColor" : "#058bbc"
		},
		bgLink : {
			"backgroundColor" : "#666666"
		},
		bgGrey : {
			"backgroundColor" : "#cbcbcb"
		},
		opaHover : {
			"opacity" : 0
		},
		opaLink : {
			"opacity" : 1
		},
		removeStyle : {
			"width" : 223.4,
			"marginRight" : 14,
			"marginLeft" : 14
		}
	}

	function ctrl( status ){//控制
		if ( !isAuto ) return;
		if ( status) {
			timer = setTimeout(autoSwitch, 3000);
		} else {
			clearTimeout( timer );
		}
	}

	function autoSwitch(){//自动切换
		TweenLite.to( serLi.eq(0), 1, {
			opacity : 0,
			"onComplete" : function(){
				TweenLite.to( serLi.eq(0), 0.5, {
					"width" : 0,
					"marginLeft": 0,
					"marginRight": 0,
					"onComplete" : function(){
						serLi.eq(0).appendTo( service ).css( config.removeStyle );
						serLi = service.children(".serLi");
						TweenLite.fromTo( serLi.eq(3).show() , 1, {
							opacity : 0
						}, {
							opacity : 1
						});
						clearTimeout( timer );
						timer = setTimeout(autoSwitch, speed);
					}
				});
			}
		});
	}

	serLi.filter(":gt(3)").hide();//隐藏多余

	ctrl( true );//start

	serLi.each(function(){//hover
		var self = $(this);
		var wrap = self.children(".serWrap")
		var ico = self.find(".serIco");
		var button = self.find(".serBtnLink");

		self.hover(function(){
			var i=self.find(".serIco img"),
				i_src=i.attr("src");
				i.attr(i_src+"")
			TweenLite.to( wrap, 0.3, config.topHover);
			TweenLite.to( ico, 0.5, config.bgHover);
			TweenLite.to( button, 0.5, config.opaHover);
			ctrl( false);
		}, function(){
			TweenLite.to( wrap, 0.3, config.topLink);
			TweenLite.to( ico, 0.5, config.bgLink);
			TweenLite.to( button, 0.5, config.opaLink);
			ctrl( true );
		});
	});

	var detBtn = $(".icaseDetLink");
	detBtn.hover(function(){
		TweenLite.to( detBtn, 0.5, config.bgHover);
	}, function(){
		TweenLite.to( detBtn, 0.5, config.bgGrey);
	});
})( $("#service") );

//case query
;(function( inQuery ){
	if ( !inQuery.length ) return;
	var query = inQuery.children(".icaserQuery");
	query.each(function(){
		var self = $(this);
		var mask = self.find(".icaserMask");
		var link = self.find(".icaserLink");

		self.hover(function(){
			TweenLite.to( self, 0.3, {
				"backgroundColor" : "#058bbc"
			});
			TweenLite.to( mask, 0.3, {
				"display" : "block",
				"opacity" : 0.3
			});
			TweenLite.to( link, 0.3, {
				"top" : 58
			});
		}, function(){
			TweenLite.to( self, 0.3, {
				"backgroundColor" : "#ffffff"
			});
			TweenLite.to( mask, 0.3, {
				"display" : "none",
				"opacity" : 0
			});
			TweenLite.to( link, 0.3, {
				"top" : -45
			});
		})

	});
})( $("#vlist") );

//News
;(function( news ){
	if ( !news.length ) return;
	var prev = news.find(".inPrev");
	var next = news.find(".inNext");
	var query = news.find(".inQuery");
	var list = query.find(".inList");
	var isAnimate = false;
	var config = {
		left : {
			"left" : 0
		},
		right : {
			"left" : -598
		},
		bgLink : {
			"backgroundColor" : "#ffffff"
		},
		bgHover : {
			"backgroundColor" : "#545454"
		}
	}

	if( list.length <= 2 ) return;

	list.filter(":gt(1)").hide();

	prev.on("click", function(){
		var self = $(this);
		var a = list.filter(":visible:eq(0)");
		var b = list.filter(":visible:eq(1)");
		var index = a.index();
		if ( index === 0 ) return;
		var top = list.eq( index - 1 );
		var bottom = list.eq( index - 2 );

		isAnimate = true;

		top.show();
		bottom.show();

		a.addClass("inListTop").show();
		b.addClass("inListBottom").show();

		query.css( config.right );

		TweenLite.to( query, 0.6, {
			"css" : config.left,
			"onComplete" : function(){
				isAnimate = false;
				list.hide();
				top.removeClass("inListTop").show();
				bottom.removeClass("inListBottom").show();
				query.css( config.left );

			}
		});
	});

	next.on("click", function(){
		var index = list.filter(":visible").last().index();
		var top = list.eq( index + 1 );
		var bottom = list.eq( index + 2 );

		if ( !top.length || isAnimate ) return;
		isAnimate = true;
		top.addClass("inListTop").show();

		if( bottom.length ) bottom.addClass("inListBottom").show();

		TweenLite.to( query, 0.6, {
			css : config.right,
			"onComplete" : function(){
				isAnimate = false;
				list.hide();
				top.removeClass("inListTop").show();
				bottom.removeClass("inListBottom").show();
				query.css( config.left );

			}
		});
	});

	list.hover(function(){
		TweenLite.to($(this).find(".inLableDate"), 0.3, {
			"backgroundColor" : "#058bbc"
		});
	}, function(){
		TweenLite.to($(this).find(".inLableDate"), 0.3, {
			"backgroundColor" : "#9b9b9b"
		});
	});

	news.find(".inMore,.inNext,.inPrev").hover(function(){
		TweenLite.to( $(this), 0.5, config.bgHover);
	}, function(){
		TweenLite.to( $(this), 0.5, config.bgLink)
	})
})( $("#iNews") );


