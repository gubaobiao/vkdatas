! function(t, e) {
	function i(e) {
		var i = 0;
		C <= 1e3 ? (i = 1e3, T = (1e3 - k) / 2) : C >= k ? (i = k, T = 0) : (i = C, T = (C - k) / 2), TweenLite.to(f, e, {
			left: T
		}), t.add(l).width(i), b.width(Math.ceil(i / b.length)), t.find(".bfilter").children("a").each(function(t) {
			$(this).children("img").css("left", -$(this).position().left + T)
		})
	}

	function o() {
		C = $(window).width(), y && (clearTimeout(y), y = null), y = setTimeout(function() {
			i(.2)
		}, 200)
	}

	function n() {
		C = $(window).width(), k = H.width, i(0), $(window).on("resize", o), e.children("a:eq(0)").addClass("curr"), l.eq(0).show(), t.find(".bfilter").children("a").each(function(t) {
			$(this).css("left", 5 * t + "%"), $(this).children("img").css("left", -$(this).position().left + T)
		}), b.attr("href", u), m.attr("src", g), L.on("click", function() {
			x || $(this).hasClass("curr") || (x = !0, L.removeClass("curr"), $(this).addClass("curr"), p = l.eq($(this).index()), a(), q && (clearTimeout(q), q = null))
		})
	}

	function r() {
		d = p, g = p.find("img").attr("src"), p = d.next("li"), p = p.length ? p : h, m.attr("src", g), x = !1, q = setTimeout(function() {
			var t = L.filter(".curr").next("a");
			t = t.length ? t : L.eq(0), t.trigger("click")
		}, 3e3)
	}

	function c() {
		w.css({
			bottom: 0
		}), b.css({
			top: "auto",
			bottom: "auto",
			opacity: 1
		})
	}

	function s(t, e) {
		TweenMax.staggerTo(t, 1, e, .05, r)
	}

	function a() {
		c(), R[["top", "topReverse", "bottom", "bottomReverse", "left", "right"][Math.round(5 * Math.random())]](), d.hide(), p.show()
	}
	if(t.length) {
		var l = t.find(".bquery>.blist"),
			f = l.children("a"),
			h = l.eq(0),
			d = h,
			u = d.find("a").attr("href"),
			g = d.find("img").attr("src"),
			p = d.next("li"),
			w = $('<div class="bfilter">').appendTo(t),
			b = $(Array(21).join('<a href="javascript:void(0);"><img src="" /></a>')).appendTo(w),
			v = Array.prototype.reverse.call(w.children("a")),
			m = b.children("img"),
			L = $($.map(l, function() {
				return '<a href="javascript:void(0);"></a>'
			}).join()).appendTo(e),
			T = 0,
			k = 0,
			C = $(window).width(),
			y = null,
			q = null,
			x = !1,
			H = new Image;
		$(H).on("load", n), H.src = f.children("img")[0].src;
		var M = {
				top: {
					top: -570,
					opacity: 0
				},
				bottom: {
					bottom: -570,
					opacity: 0
				},
				lr: {
					opacity: 0
				}
			},
			R = {
				top: function() {
					s(b, M.top)
				},
				topReverse: function() {
					s(v, M.top)
				},
				bottom: function() {
					s(b, M.bottom)
				},
				bottomReverse: function() {
					s(v, M.bottom)
				},
				left: function() {
					s(b, M.lr)
				},
				right: function() {
					s(v, M.lr)
				}
			};
		q = setTimeout(function() {
			var t = L.filter(".curr").next("a");
			t = t.length ? t : L.eq(0), t.trigger("click")
		}, 3e3)
	}
}($("#bannerQuery"), $("#bannerCtrl")),
function(t) {
	function e(t) {
		c && (t ? o = setTimeout(i, 3e3) : clearTimeout(o))
	}

	function i() {
		TweenLite.to(r.eq(0), 1, {
			opacity: 0,
			onComplete: function() {
				TweenLite.to(r.eq(0), .5, {
					width: 0,
					marginLeft: 0,
					marginRight: 0,
					onComplete: function() {
						r.eq(0).appendTo(t).css(s.removeStyle), r = t.children(".serLi"), TweenLite.fromTo(r.eq(4).show(), 1, {
							opacity: 0
						}, {
							opacity: 1
						}), clearTimeout(o), o = setTimeout(i, n)
					}
				})
			}
		})
	}
	if(t.length) {
		var o = null,
			n = 5e3,
			r = t.children(".serLi"),
			c = r.length > 4,
			s = {
				topHover: {
					top: -12
				},
				topLink: {
					top: 0
				},
				bgHover: {
					backgroundColor: "#fdb11a"
				},
				bgLink: {
					backgroundColor: "#666666"
				},
				bgGrey: {
					backgroundColor: "#cbcbcb"
				},
				opaHover: {
					opacity: 0
				},
				opaLink: {
					opacity: 1
				},
				removeStyle: {
					width: 223.4,
					marginRight: 14,
					marginLeft: 14
				}
			};
		r.filter(":gt(4)").hide(), e(!0), r.each(function() {
			var t = $(this),
				i = t.children(".serWrap"),
				o = t.find(".serIco"),
				n = t.find(".serBtnLink");
			t.hover(function() {
				var r = t.find(".serIco img"),
					c = r.attr("src");
				r.attr(c + ""), TweenLite.to(i, .3, s.topHover), TweenLite.to(o, .5, s.bgHover), TweenLite.to(n, .5, s.opaHover), e(!1)
			}, function() {
				TweenLite.to(i, .3, s.topLink), TweenLite.to(o, .5, s.bgLink), TweenLite.to(n, .5, s.opaLink), e(!0)
			})
		});
		var a = $(".icaseDetLink");
		a.hover(function() {
			TweenLite.to(a, .5, s.bgHover)
		}, function() {
			TweenLite.to(a, .5, s.bgGrey)
		})
	}
}($("#service")),
function(t) {
	if(t.length) {
		var e = t.children(".icaserQuery");
		e.each(function() {
			var t = $(this),
				e = t.find(".icaserMask"),
				i = t.find(".icaserLink");
			t.hover(function() {
				TweenLite.to(t, .3, {
					backgroundColor: "#058bbc"
				}), TweenLite.to(e, .3, {
					display: "block",
					opacity: .3
				}), TweenLite.to(i, .3, {
					top: 58
				})
			}, function() {
				TweenLite.to(t, .3, {
					backgroundColor: "#ffffff"
				}), TweenLite.to(e, .3, {
					display: "none",
					opacity: 0
				}), TweenLite.to(i, .3, {
					top: -45
				})
			})
		})
	}
}($("#vlist"));
/*
function(t) {
	if(t.length) {
		var e = t.find(".inPrev"),
			i = t.find(".inNext"),
			o = t.find(".inQuery"),
			n = o.find(".inList"),
			r = !1,
			c = {
				left: {
					left: 0
				},
				right: {
					left: -598
				},
				bgLink: {
					backgroundColor: "#ffffff"
				},
				bgHover: {
					backgroundColor: "#545454"
				}
			};
		n.length <= 2 || (n.filter(":gt(1)").hide(), e.on("click", function() {
			var t = ($(this), n.filter(":visible:eq(0)")),
				e = n.filter(":visible:eq(1)"),
				i = t.index();
			if(0 !== i) {
				var s = n.eq(i - 1),
					a = n.eq(i - 2);
				r = !0, s.show(), a.show(), t.addClass("inListTop").show(), e.addClass("inListBottom").show(), o.css(c.right), TweenLite.to(o, .6, {
					css: c.left,
					onComplete: function() {
						r = !1, n.hide(), s.removeClass("inListTop").show(), a.removeClass("inListBottom").show(), o.css(c.left)
					}
				})
			}
		}), i.on("click", function() {
			var t = n.filter(":visible").last().index(),
				e = n.eq(t + 1),
				i = n.eq(t + 2);
			e.length && !r && (r = !0, e.addClass("inListTop").show(), i.length && i.addClass("inListBottom").show(), TweenLite.to(o, .6, {
				css: c.right,
				onComplete: function() {
					r = !1, n.hide(), e.removeClass("inListTop").show(), i.removeClass("inListBottom").show(), o.css(c.left)
				}
			}))
		}), n.hover(function() {
			TweenLite.to($(this).find(".inLableDate"), .3, {
				backgroundColor: "#058bbc"
			})
		}, function() {
			TweenLite.to($(this).find(".inLableDate"), .3, {
				backgroundColor: "#9b9b9b"
			})
		}), t.find(".inMore,.inNext,.inPrev").hover(function() {
			TweenLite.to($(this), .5, c.bgHover)
		}, function() {
			TweenLite.to($(this), .5, c.bgLink)
		}))
	}
}($("#iNews"));
*/