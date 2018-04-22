;(function(){
	var wrap = $("#csQuery");
	var li = $("#csQuery>li");
	var maxWidth = li.outerWidth( true );
	var maxHeight = li.outerHeight( true );
	wrap.on("mouseenter", ".csli",function(){
		var self = $(this);
		TweenMax.to( self.find("img"), 0.5, {
			"opacity" : 0.3
		});
		TweenMax.to( self.find(".cslPic span"),0.3,{
			"top" : 50+"%"
		});
		TweenMax.to( self.find(".cslLink"), 0.5, {
			"backgroundColor" : "#d21f3d"
		});
	}).on("mouseleave", ".csli", function(){
		var self = $(this);
		TweenMax.to( self.find("img"), 0.5, {
			"opacity" : 1
		});
		TweenMax.to( self.find(".cslPic span"),0.3,{
			"top" : -50+"%"
		});
		TweenMax.to( self.find(".cslLink"), 0.5, {
			"backgroundColor" : "#4d4d4d"
		});
	});
})();
