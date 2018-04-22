//判断导航吸顶0826
$(window).scroll(function(){
    var top = $(window).scrollTop();
    console.log(top);
    if(top>0){
    	$('.header_bar1').css('display','none');
    }else{
    	$('.header_bar1').css('display','block');
    }
})


! function(t) {
	t.length && t.find(".mlink").each(function() {
		var t = $(this),
			e = t.find(".micoi"),
			i = t.children(".mname"),
			n = parseInt(e.css("top"));
		t.hasClass("active") || t.hover(function() {
			TweenLite.to(t, .3, {
				backgroundColor: "#4d4d4d"
			}), TweenLite.to(e, .5, {
				top: n - 25
			}), TweenLite.to(i, .3, {
				color: "#FFCC00"
			})
		}, function() {
			TweenLite.to(t, .3, {
				backgroundColor: "#FFCC00"
			}), TweenLite.to(e, .5, {
				top: n
			}), TweenLite.to(i, .3, {
				color: "#373737"
			})
		})
	})
}($("#menu")),
function(t) {
	function e() {
		var n = t.find(".plList:lt(6)");
		TweenMax.staggerTo(n, .5, {
			opacity: 0,
			onComplete: function() {
				var e = $(this.target),
					i = t.find(".plList:eq(6)").css({
						opacity: 0,
						display: "block"
					});
				e.after(i), e.appendTo(t).hide(), TweenLite.to(i, 2, {
					opacity: 1
				})
			}
		}, .25), i || (timer = setTimeout(e, 5e3))
	}
	if(t.length) {
		var i = !1;
		t.find(".plList").hover(function() {
			TweenLite.to($(this).find(".plGrey"), 1, {
				opacity: 0
			}), i = !0, clearTimeout(timer)
		}, function() {
			TweenLite.to($(this).find(".plGrey"), 1, {
				opacity: 1
			}), i = !1, timer = setTimeout(e, 5e3)
		}), t.find(".plList:gt(5)").hide(), timer = setTimeout(e, 5e3)
	}
}($("#plQuery")),
function() {
	var t = $(".icaserPic,.cslPic,.cslName");
	t.click(function() {
		var t = $(this),
			e = " 发布于：" + t.attr("data-time") + "</span>";
		$("#video-url").attr("src", t.attr("data-url")), $("#gpc-info").html(e), $("#gpc-detail").attr("href", t.attr("data-path")), $(".gpc-t-box").layerModel({
			blurClose: !0,
			bgColor: "#000000",
			title: t.attr("data-title")
		})
	})
}();