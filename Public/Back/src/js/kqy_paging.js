// 保存 当前页
var PAGING=1;
function pages(a,b,c){
	var par=$('#page');
	par.empty();
	var yesum=Math.ceil(c/b);
	var html='';
	html+='<ul>';
	if(yesum<=5){
		for(var i=1;i<=yesum;i++){
			html+='<li class="num a'+i+'">'+i+'</li>';
		};
	};
	if(yesum>5){
		html+='<li class="prve"><i></i></li>';
		if(a<=3){
			for(var i=1;i<=4;i++){
				html+='<li class="num a'+i+'">'+i+'</li>';
			};
			html+='<li class="mid">...</li>';
		}else if(a>=(yesum-3)){
			html+='<li class="mid">...</li>';
			for(var i=3;i>=0;i--){
				html+='<li class="num a'+(yesum-i)+'">'+(yesum-i)+'</li>';
			};
		}else{
			html+='<li class="mid">...</li>';
			for(var i=0;i<4;i++){
				html+='<li class="num a'+(a+i)+'">'+(a+i)+'</li>';
			};
			html+='<li class="mid">...</li>';
		}
		html+='<li class="next"><i></i></li>';
	};
	html+='</ul>';
	par.append(html);
	par.find('.a'+a).addClass('active').removeClass('num');
};
$('#page').on('click','.num',function(){
	ajaxPage(parseInt($(this).html()))
});
$('#page').on('click','.next',function(){
	var n = PAGING+1;
	if(n > $('.num:last').html()) return;
	ajaxPage(n)
});
$('#page').on('click','.prve',function(){
	var n = PAGING-1;
	if(n <= 0) return;
	ajaxPage(n);
})
