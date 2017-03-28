$(".menu_list").hide();
$(".menu_list").animate({right:'-310px'},"fast");
$(".menu_but_click").hide();

//	显示菜单
$(".menu_but").click(function() {
	$(".menu_list").show();
	$(".menu_list").animate({right:'0px'},"normal");
	$(".menu_but").hide();
	$(".menu_but_click").show();
	$("#hold").addClass("click_bk");
	$("#hold").removeClass("narmal_bk");
});	

//	隐藏菜单
$(".menu_but_click").click(function() {
	$(".menu_list").animate({right:'-360px'},"normal");
	$(".menu_but_click").hide();
	$(".menu_but").show();
	$("#hold").addClass("narmal_bk");
	$("#hold").removeClass("click_bk");
});	
$(".menu_list").mouseleave(function() {
	$(".menu_list").animate({right:'-360px'},"normal");
	$(".menu_but_click").hide();
	$(".menu_but").show();
	$("#hold").addClass("narmal_bk");
	$("#hold").removeClass("click_bk");
});	

//	二维码
$(document).on("click",'.a_img',function(){
	thisURL = $(this).attr("value");
	shortURL = $(this).attr("name");
	$("#erweima_area_"+shortURL).html("<img src='https://chart.googleapis.com/chart?cht=qr&chs=150x150&choe=UTF-8&chld=L|4&chl="+thisURL+"' width='150' height='150' alt='扫我也可以浏览' title='扫我也可以浏览' class='erweiam_img'/>");
	$(this).removeClass("a_img");
	$(this).addClass('a_img_click');
	$(this).attr('title','隐藏连接二维码');
});

$(document).on("click",'.a_img_click',function(){
	thisURL = $(this).attr("value");
	shortURL = $(this).attr("name");
	$("#erweima_area_"+shortURL).html("");
	$(this).removeClass('a_img_click');
	$(this).addClass('a_img');
	$(this).attr('title','显示连接二维码');
});

//	小菜单
$(document).on("click",'.index_menu',function(){
	var short_url = $(this).attr("value");
	$("#index_menu_list_"+short_url).fadeToggle();
});
	
$(document).on("click",'.index_menu_item',function(){
	var short_url = $(this).attr("value");
	var url_user = $(this).attr("name");
	var post_item = $(this).attr("alt");
	var _token = $("#_token").val();

	swal({
		title: "要继续进行操作吗？",
		text: "",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: '#DD6B55',
		confirmButtonText: '确定'
	},
	function(){
		$.post("/syshide/report_url",
		{
			_token:_token,
			short_url:short_url,
			url_user:url_user,
			post_item:post_item
		},
		function(data,status)
		{
			//window.location.reload();
		});
	});
});	