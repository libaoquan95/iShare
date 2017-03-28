$(document).ready(function(){

	//	鼠标移动到话题图标
	$(".topics_img").mouseover(function() {
		//	得到当前图标alt
		var alt = $(this).attr('alt');
		//alert('./img_2/'+alt+'.png');
		$(this).attr('src','/css/topics_img/img_2/'+alt+'.png'); 
	});
	
	$(".topics_img").mouseout(function() {
		//	得到当前图标alt
		var alt = $(this).attr('alt');
		$(this).attr('src','/css/topics_img/img/'+alt+'.png'); 
	});
});