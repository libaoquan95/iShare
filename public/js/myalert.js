$(document).ready(function(){

	//	暂停分享
	$(document).on("click",'.pause_button',function(){
	//$(".pause_button").click(function() {
		var short_url = $(this).val();
		
		var _token_name = "#" + short_url +"_token";
		var _token = $(_token_name).val();

		$.post("/syshide/url_pause_share",
		{
			_token:_token,
			short_url:short_url
		},
		function(data,status)
		{
			swal({
					title: "操作成功",
					text: short_url+"已暂停分享",
					type: "success"
				},
			function(){
				//window.location.reload();
			});
		});

		$(this).attr("title","恢复分享此网址");
		$(this).text("恢复分享");
		$(this).removeClass("pause_button");
		$(this).addClass("continue_button");
	});

	//	恢复分享
	$(document).on("click",'.continue_button',function(){
	//$(".continue_button").click(function() {
		var short_url = $(this).val();
		
		var _token_name = "#" + short_url +"_token";
		var _token = $(_token_name).val();

		$.post("/syshide/url_continue_share",
		{
			_token:_token,
			short_url:short_url
		},
		function(data,status)
		{
			swal({
					title: "操作成功",
					text: short_url+"已恢复分享",
					type: "success"
				},
			function(){
				//window.location.reload();
			});
		});

		$(this).attr("title","暂停分享此网址，但不删除网址信息");
		$(this).text("暂停分享");
		$(this).removeClass("continue_button");
		$(this).addClass("pause_button");
	});

	//	删除分享
	$(".del_button").click(function() {
		var short_url = $(this).val();
		
		var _token_name = "#" + short_url +"_token";
		var _token = $(_token_name).val();

		swal({
			title: "确定删除这个网址吗？",
			text: "点击'confirm'会删除此网址及其所有附属信息，并不可恢复！",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: '#DD6B55',
			confirmButtonText: '确定'
		},
		function(){
			$.post("/syshide/url_del",
			{
				_token:_token,
				short_url:short_url
			},
			function(data,status)
			{
				window.location.reload();
			});
		});
	});
});