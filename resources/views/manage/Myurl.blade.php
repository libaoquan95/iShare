<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>分享网址管理/{{Session::get('user_name')}} - iShare 网址压缩分享平台</title>

	<!--<link href="/css/app.css" rel="stylesheet">-->
	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
	<link rel="stylesheet" type="text/css" href="/css/url_manger.css" >
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
	<div id="hold" class="normal_bk"></div>
	<!-- 网页首部 -->
	<div id='header'>
		<a href='/' class='head_logo'><img src='/css/img/logo.png' alt='iShare 网址分享' id='logo_img'/></a>
		<div class='menu_but'></div>

		<ul class='menu_list'>
			<li class='head_user_info'>
				Welcome {{Session::get('user_name')}}
			</li>
			<li class='head_user_img_li'>
				<img src='{{$info['user_img']}}' class='head_user_img'/>
			</li>
			<a href='/' class='other_page'>
				<li>
				<img src='/css/img/head_list_home_blue.png' alt='' class='head_list_img'/>首页</li>
			</a>
			<a href='/share' class='other_page'>
				<li>
				<img src='/css/img/head_list_share_blue.png' alt='' class='head_list_img'/>我要分享</li>
			</a>
			<a href='/topics' class='other_page'>
				<li>
				<img src='/css/img/head_list_topic_blue.png' alt='' class='head_list_img'/>话题广场</li>
			</a>
			<a href='/manage/{{Session::get('user_name')}}' class='other_page'>
				<li>
				<img src='/css/img/head_list_my_blue.png' alt='' class='head_list_img'/>我的信息</li>
			</a>
			<a href='/manage/{{Session::get('user_name')}}/allurls' id='local_page'>
				<li>
				<img src='/css/img/head_list_manger.png' alt='' class='head_list_img'/>我的分享</li>
			</a>
			<a href='/auth/logout' class='other_page'>
				<li>
				<img src='/css/img/head_list_exit_blue.png' alt='' class='head_list_img'/>注销登录</li>
			</a>
			<a class='other_page_seach'><li>
				<form action="{{ URL('/syshide/myurl_search') }}" target='_blank' method='post'>
					<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="user_name" id="_token" value="{{Session::get('user_name')}}">
					<input type='text' name='search' class='search' placeholder="搜索我的分享"/>
					<input type='submit' id='searchbut' value='' title="点击搜索"/>
				</form>
			</li></a>
		</ul>
		<div class='menu_but_click'></div>
		<script type="text/javascript" src="/js/main_menu.js"></script>
	</div>

	<!-- 网页主体 -->
	<div id='main_bk'>
		<!-- 主体 -->
		<div id='main'>
			<div id='in_url_list'>
				@foreach ($info['urls_info'] as $userurl)
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
			</div>

			<a href="/manage/{{Session::get('user_name')}}/allurls/{{$info['page_last']}}"><button type="button" id="last_page_but" class="last_page_but_normal" value="{{$info['page_last']}}">上一页</button></a>
			<a href="/manage/{{Session::get('user_name')}}/allurls/{{$info['page_next']}}"><button type="button" id="next_page_but" class="next_page_but_normal" value="{{$info['page_next']}}">下一页</button></a>
		</div>
	</div>
	<div id='footer'>
		<ul>
			<li><a href='/' '>首页</a></li>
			<li><a href='/messageboard/'>留言板</a></li>
			<li><a href='/download/iShare.apk'>下载Android客户端</a></li>
			<li><a href='/download/iShare.ipa'>下载IOS客户端</a></li>
			<li><a href='/aboutus'>关于我们</a></li>
		</ul>
		<p class='copyright_info'>Copyright &copy; iShare网址分享平台 2015-2016</p>
	</div>
	<div class='totop'>
	</div>
	<div class='refurbish'>
	</div>
	<!--<script type="text/javascript" src="/js/load_next_myurl_page.js"></script>-->
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="/js/myalert.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".totop").click(function() {
				$('body,html').animate({scrollTop:0},1000);
			});	
			$(".refurbish").click(function() {
				self.location.reload();
			});

			var last_page = $('#last_page_but').attr('value');
			if(last_page == 0)
			{
				$("#last_page_but").text("已到首页");
				$("#last_page_but").attr("disabled","disabled");
				$("#last_page_but").addClass("last_page_but_disabled");
				$("#last_page_but").removeClass("last_page_but_normal");
			}
			var next_page = $('#next_page_but').attr('value');
			if(next_page == 0)
			{
				$("#next_page_but").text("已到尾页");
				$("#next_page_but").attr("disabled","disabled");
				$("#next_page_but").addClass("next_page_but_disabled");
				$("#next_page_but").removeClass("next_page_but_normal");
			}
		});	
	</script>
</body>
</html>