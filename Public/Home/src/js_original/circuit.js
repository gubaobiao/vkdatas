//service
;(function(){
	var dialog = $("#serDetaile");

	if ( !dialog.length ) return;

	var dialogTitle = dialog.find(".sdTitle");
	var dialogContent = dialog.find(".sdContent");
	var inner = dialog.find(".sdContentInner");
	var dialogClose = dialog.find(".sdCloseBtn");
	var mask = $("#mask");
	var target = null;
	var content = null;
	var winH = $(window).height();
	var bodyH = $("body").height();

		console.log(bodyH - winH);
	if(bodyH < winH){
		$("#serlist").css("minHeight",winH - 572 + "px");
	}

	dialogContent.mCustomScrollbar();

	$("#serlist").on("click", ".serDet", function(){
		target = $(this).closest("li");
		content = target.find(".serCon").appendTo( inner );
		dialogTitle.text( target.find(".serlTitle").text() );

		TweenMax.to( mask, 0.2, {
			"display" : "block",
			"opacity" : 0.5,
			"onComplete" : function(){
				TweenMax.to(dialog, 0.5, {
					"top" : "50%",
					"display" : "block",
					"ease" : Back.easeOut.config(2),
					"onComplete" : function(){
						dialogContent.mCustomScrollbar("update");
					}
				});
			}
		});
	}).on("mouseenter",".serli", function(){
		var self = $(this);
		TweenLite.to( self.find(".serlIco"), 0.5, {
			"backgroundColor" : "#058bbc"
		});
		TweenLite.to( self.find(".serdLink"), 0.5, {
			"opacity" : 0
		});
	}).on("mouseleave",".serli", function(){
		var self = $(this);
		TweenLite.to( self.find(".serlIco"), 0.5, {
			"backgroundColor" : "#666666"
		});
		TweenLite.to( self.find(".serdLink"), 0.5, {
			"opacity" : 1
		});
	});

	dialogClose.on("click", function(){
		TweenMax.to(dialog, 0.5, {
			"top" : -606,
			"display" : "none",
			"ease" : Back.easeIn.config(2),
			"onComplete" : function(){
				TweenMax.to( mask, 0.2, {
					"display" : "none",
					"opacity" : 0
				});
				content.appendTo( target.find(".serRight") );
				target = null;
				content = null;
			}
		});
	});


})();