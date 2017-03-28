@foreach ($bu_userurls as $userurl)
	<div class='url_code' id='surl_{{ $userurl->short_url }}_area'>
		<input type="hidden" id="{{ $userurl->short_url }}_token" name="{{ $userurl->short_url }}_token" value="{{ csrf_token() }}">
		<button class="del_button" title="删除此网址及其所有附属信息" value="{{ $userurl->short_url }}">删除</button>
		
		@if ($userurl->url_status == 0)
			<input type="hidden" id="{{ $userurl->short_url }}_token" name="{{ $userurl->short_url }}_token" value="{{ csrf_token() }}">
			<button class="pause_button" title="暂停分享此网址，但不删除网址信息" value="{{ $userurl->short_url }}">暂停分享</button>
		@endif

		@if ($userurl->url_status == 1)
			<input type="hidden" id="{{ $userurl->short_url }}_token" name="{{ $userurl->short_url }}_token" value="{{ csrf_token() }}">
			<button class="continue_button" title="恢复分享此网址" value="{{ $userurl->short_url }}">恢复分享</button>
		@endif

		<a href='/urlinfo/{{ $userurl->short_url }}' target='_blank'><button class="info_button" title="查看短网址统计信息" value="{{ $userurl->short_url }}">查看信息</button></a>
	<table class='url_base_info_sheet'>
		<tr>
			<td class='url_base_info_sheet_head'>网址描述</td>
			<td class='url_base_info_sheet_cont'>{{ $userurl->url_describe }}</td>
		</tr>
		<tr>
			<td class='url_base_info_sheet_head'>短网址</td>
			<td class='url_base_info_sheet_cont'>
				<a href='/urlinfo/{{ $userurl->short_url }}' title='点击查看网址信息'  target='_blank'>{{ $userurl->short_url }}</a> <span>(点击可查看网址信息)</span>
			</td>
		</tr>
		<tr>
			<td class='url_base_info_sheet_head'>原网址</td>
			<td class='url_base_info_sheet_cont'>
				<a href='{{ $userurl->long_url }}' target='_blank'>{{ $userurl->long_url }}</a>
			</td>
		</tr>
	</table>
	</div>
@endforeach
<script type="text/javascript">
$(document).ready(function(){

	//	暂停分享
	$(".pause_button").click(function() {
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
				window.location.reload();
			});
		});

		/*$(this).attr("title","恢复分享此网址");
		$(this).text("恢复分享");
		$(this).removeClass("pause_button");
		$(this).addClass("continue_button");*/
	});

	//	恢复分享
	$(".continue_button").click(function() {
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
				window.location.reload();
			});
		});

		/*$(this).attr("title","暂停分享此网址，但不删除网址信息");
		$(this).text("暂停分享");
		$(this).removeClass("continue_button");
		$(this).addClass("pause_button");*/
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
</script>