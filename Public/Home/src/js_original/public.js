//menu
;(function( menu ){
	if ( !menu.length ) return;
	menu.find(".mlink").each(function(){
		var self = $(this),
			mico = self.find(".micoi"),
			mname = self.children(".mname"),
			top = parseInt( mico.css("top") );

		if ( self.hasClass("active") ) return;

		self.hover(function(){
			TweenLite.to( self, 0.3, {
				"backgroundColor" : "#4d4d4d"
			});

			TweenLite.to( mico, 0.5, {
				"top" : top - 25
			});

			TweenLite.to( mname, 0.3, {
				"color" : "#ffffff"
			});
		}, function(){
			TweenLite.to( self, 0.3, {
				"backgroundColor" : "#ffffff"
			});

			TweenLite.to( mico, 0.5, {
				"top" : top
			});

			TweenLite.to( mname, 0.3, {
				"color" : "#373737"
			});
		});
	});
})( $("#menu") );

//image link
;(function( link ){
	if ( !link.length ) return;
	var tier = null;
	var speed = 5000;
	var isHover = false;
	link.find(".plList").hover(function(){
		TweenLite.to( $(this).find(".plGrey"), 1, {
			"opacity" : 0
		});
		isHover = true;
		clearTimeout( timer );
	}, function(){
		TweenLite.to( $(this).find(".plGrey"), 1, {
			"opacity" : 1
		});
		isHover = false;
		timer = setTimeout( autoSwitch , 5000 );
	});

	link.find(".plList:gt(5)").hide();

	function autoSwitch(){
		var curr = link.find(".plList:lt(6)");
		TweenMax.staggerTo(curr, 0.5, {
			"opacity" : 0,
			"onComplete" : function(){
				var target = $( this.target );
				var after = link.find(".plList:eq(6)").css({
					opacity : 0,
					display : "block"
				});
				target.after( after );
				target.appendTo( link ).hide();
				TweenLite.to(after, 2, {
					opacity : 1
				});
			}
		}, 0.25)
		if( !isHover ) timer = setTimeout( autoSwitch , 5000 );
	}

	timer = setTimeout( autoSwitch , 5000 );
})( $("#plQuery") );

//点击视频弹出弹窗
;(function(){
	var video=$(".icaserPic,.cslPic,.cslName");
	video.click(function(){
		var self=$(this);
		var html=" 发布于："+self.attr('data-time')+"&nbsp;&nbsp;&nbsp;时长："+self.attr('data-duration')+" &nbsp;&nbsp;</span>"
		$('#video-url').attr('src',self.attr('data-url'));
		$('#gpc-info').html(html);
		$('#gpc-detail').attr('href',self.attr('data-path'));
		$('.gpc-t-box').layerModel({
	  		blurClose : true,
	  		bgColor:"#000000",
	  		title:self.attr("data-title")
	  	});
	});
})();