//因为ie10一下 不支持window.URL || window.webkitURL 所以可以根据url 判断是否是ie10一下
if (window.URL || window.webkitURL) {
    document.getElementById('ie_mask').style.display="none";
} else {
	document.getElementById('ie_mask').style.display="block";

	$('#ie_mask').on('click','.close',function(e){
		e.preventDefault();
		document.getElementById('ie_mask').style.display="none";
	})
}
$('.boxs').on('click','.box_btn',function(e){
	e.preventDefault();
	$(this).toggleClass('active').parents('.boxs').siblings('.boxs').find('.box_btn').removeClass('active');
	$(this).siblings('.down_list').slideToggle().parents('.boxs').siblings('.boxs').find('.down_list').slideUp();
});
// nav 头像 下拉位置
$('.down_list,.user_step').on('click','li',function(e){
	e.stopPropagation();
	e.preventDefault();
	if(!$(this).find('a')[0]){return}
	var url=$(this).find('a').attr('href');
	var dataUrl=$(this).find('a').attr('data-url');
	var name=$(this).find('a').html();
	$('.box_real .box_btn').removeClass('active');
	$('.down_list li').removeClass('active');
	$(this).addClass('active');
	tabs(url,dataUrl,name);
});
$('.box_real').on('click','.box_btn',function(e){
	e.preventDefault();
	var url=$(this).find('a').attr('href');
	var dataUrl=$(this).find('a').attr('data-url');
	var name=$(this).find('a').html();
	$('.down_list li').removeClass('active');
	$('.box_real .box_btn').removeClass('active');
	$(this).addClass('active');
	tabs(url,dataUrl,name);
});
$('.main_tab_btn').on('click','.remove',function(e){
	e.preventDefault();
	e.stopPropagation();
	console.log()
	var dataUrl=$(this).parent().attr('href');
	remtabs(dataUrl);
});
$('.main_tab_btn').on('click','a',function(e){
	e.preventDefault();
	var url=$(this).attr('href');
	var lis=$('#main_tab .main_tab_btn').find('li');
	var iframes=$('#main_iframe').find('iframe');
	lis.removeClass('active');
	iframes.removeClass('active');
	for(var i=0;i<lis.length;i++){
		if($(lis[i]).find('a').attr('href')==url){
			$(lis[i]).addClass('active');
			$(iframes[i]).addClass('active');
			return false;
		};
	};
});
$('.user_admin').hover(function(){
	$('.user_step').show()
},function(){
	$('.user_step').hide()
})
// 添加的方法
function tabs(url,dataUrl,name){
	var btn=$('#main_tab .main_tab_btn');
	var iframe=$('#main_iframe');
	var lis=btn.find('li');
	var iframes=iframe.find('iframe');
	iframes.removeClass('active');
	lis.removeClass('active');
	console.log(dataUrl);
	for(var i=0;i<lis.length;i++){
		if($(lis[i]).find('a').attr('href')==dataUrl){
			$(lis[i]).addClass('active');
			$(iframes[i]).addClass('active');
			$(iframes[i]).attr('src',url);
			return false;
		}
	};
	btn.append('<li class="tab_btn active"><a href="'+dataUrl+'"><span>'+name+'</span><img class="remove" src="../../Public/Back/src/images/remover.png" alt="关闭"></a></li>');
	iframe.append('<iframe class="active" name='+name+' src="'+url+'" width="100%" height="100%" frameborder="0" scrolling="auto" ></iframe>');
	widthMean()
};
// 删除的方法
function remtabs(dataUrl){
	var lis=$('#main_tab .main_tab_btn').find('li');
	var iframes=$('#main_iframe').find('iframe');
	for(var i=0;i<lis.length;i++){
		if($(lis[i]).find('a').attr('href')==dataUrl){
			if($(lis[i]).attr('class').indexOf('active')!=-1){
				$(lis[i-1]).addClass('active');
				$(iframes[i-1]).addClass('active');
			}
			$(lis[i]).remove();
			$(iframes[i]).remove();
			widthMean();
			return false;
		};
	};
};
function widthMean(){
	var lis=$('#main_tab .tab_btn');
	var all_width=document.body.clientWidth-$('#main_left').outerWidth()-$('li.home').outerWidth();
	var li_width=lis.outerWidth();
	var width=all_width/lis.length>=120?120:all_width/lis.length;
	lis.css('width',width+'px');
};
function creatIframe(s,e){ // 页面内部的a标签创建iframe
	e.preventDefault();
	var self = $(s),
		s = self.attr('data-src'), // 将要跳转的地址
		u = self.attr('href'), // 后台操作地址
		t = self.attr('data-title'); // 需要生成的tab名称
		tabs(s,u,t);	
}