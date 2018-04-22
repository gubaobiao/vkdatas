;(function(){
var li = $("#nqtWrap>li");

if ( !li.length ) return;

var url = window.location.href;
li.removeClass("curr").find("a").each(function(){
	if ( url.indexOf( $(this).attr("href") ) !== -1 ) {
		$(this).closest("li").addClass("curr");
		return false;
	}
});

var curr = li.filter(".curr").find("a");
curr = curr.length > 0 ? curr : li.eq(0).children("a");
var underline = $('<div class="nqUnderline">').appendTo("#nqType");

TweenMax.to(underline, 0.2, {
	left : curr.position().left + 23,
	ease : Back.easeOut
});

TweenMax.to(underline, 0.5, {
	width : curr.width()
});

li.children("a").hover(function(){
	TweenMax.to(underline, 0.2, {
		left : $(this).position().left + 23,
		ease : Back.easeOut
	});
	TweenMax.to(underline, 0.5, {
		width : $(this).width()
	});
}, function(){
	TweenMax.to(underline, 0.2, {
		left : curr.position().left + 23,
		ease : Back.easeOut
	});
	TweenMax.to(underline, 0.5, {
		width : curr.width()
	});
});

$("#nqlWrap").on("mouseenter", ".nqli", function(){
	var self = $(this);
	TweenMax.to( self.find(".nqlDate"), 0.5, {
		"backgroundColor" : "#ec173a"
	});
}).on("mouseleave", ".nqli", function(){
	var self = $(this);
	TweenMax.to( self.find(".nqlDate"), 0.5, {
		"backgroundColor" : "#787878"
	});
});
})();