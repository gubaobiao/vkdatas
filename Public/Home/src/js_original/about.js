//about
;(function(){
	//定位栏
	var link = $("#aboutSide>a");

	if ( link.length ) {

		var headHeight = $(".header").height();
		var posTop = $.map($("#astepWrap>.astep"), function(value,key){
			return $(value).offset().top - headHeight;
		});
		var timer = null;

		$("#aboutSide").on("click", "a", function(){
			var self = $(this);
			var index = self.index();

			if ( index >= posTop.lnegth || self.hasClass("curr") ) return;

			link.removeClass("curr");
			self.addClass("curr");

			TweenMax.to( $("html,body"), 1, {
				scrollTop : posTop[ index ]
			});
		});

		$(window).on("scroll", function(){
			if ( timer ) {
				clearTimeout( timer );
				timer = null;
			}
			timer = setTimeout(function(){
				var scrollTop = $(window).scrollTop();
				$.each(posTop, function(key, value){
					if ( scrollTop >= value && ( scrollTop < posTop[ key + 1 ] || !posTop[ key + 1 ] ) ) {
						link.removeClass("curr").eq( key ).addClass( "curr" );
					}
				});
			});
		});
	}
})();