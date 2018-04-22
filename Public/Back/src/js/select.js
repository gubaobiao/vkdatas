
$(document).on('click','.select_box',function(e){
	e.stopPropagation()
	$(this).siblings().find('ul').slideUp(100);
	$(this).find('ul').slideToggle(100);
});
$(document).on('click','.select_box li',function(e){
	e.stopPropagation();
	var id=$(this).attr('data-id');
	var name=$(this).html();
	console.log(name);
	$(this).parent().slideUp(100);
	$(this).addClass('active').siblings().removeClass('active');
	$(this).parents('.select_box').find('.top').html(name);
	$(this).parents('.select_box').find('input').val(id).trigger("change");
});
$(document).on('click',function(){
	$('.select_box ul').slideUp(100);
});