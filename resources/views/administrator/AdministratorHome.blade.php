<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>管理员主页 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/url_info.css" >
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
	<link rel="stylesheet" type="text/css" href="/css/upload_user_img.css" >
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body style="background-color:#fnff">
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
			<a href='/manage/administrator_allurl' class='other_page'>
				<li>
				<img src='/css/img/head_list_manger_blue.png' alt='' class='head_list_img'/>我的分享</li>
			</a>
			<a href='/administrator' id='local_page'>
				<li>
				<img src='/css/img/head_list_manger.png' alt='' class='head_list_img'/>管理员主页</li>
			</a>
			<a href='/auth/logout' class='other_page'>
				<li>
				<img src='/css/img/head_list_exit_blue.png' alt='' class='head_list_img'/>注销登录</li>
			</a>
			<a class='other_page_seach'><li>
				<form action="{{ URL('/syshide/normal_search') }}" target='_blank' method='post'>
					<input type="hidden" name="_token" id='_token' value="{{ csrf_token() }}">
					<input type='text' name='search' class='search' placeholder=""/>
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
		<div class='administrator_area'>
			@if($info['user_group'] == 1)
				<!--<p><a href='/test'>生成自由格式短地址</a></p>
				<p><a href='/test/4_2'>生成4-2格式短地址</a></p>
				<p><a href='/test/4_3'>生成4-3格式短地址</a></p>-->
				<ul class='administrator_area_ul'>
					<li>
						<a href='/administrator/allurl/1' class='administrator_but'>
							<img src='/css/img/gear.png' class='administrator_but_img' align="center"/>
							管理分享网址
						</a>
					</li>
					<li>
						<a href='/administrator/messageboard' class='administrator_but'>
							<img src='/css/img/gear.png' class='administrator_but_img' align="center"/>
							查看留言板
						</a>
					</li>
					<li>
						<a href='/administrator/topics' class='administrator_but'>
						<img src='/css/img/gear.png' class='administrator_but_img' align="center"/>
						管理话题信息</a>
					</li>
					<li>
						<a href='/administrator/users' class='administrator_but'>
						<img src='/css/img/gear.png' class='administrator_but_img' align="center"/>
						管理注册用户</a>
					</li>
					<li>
						<a href='' class='administrator_but'>
						<img src='/css/img/gear.png' class='administrator_but_img' align="center"/>
						网址黑名单设置</a>
					</li>
					<li>
						<a href='/administrator/white_list' class='administrator_but'>
						<img src='/css/img/gear.png' class='administrator_but_img' align="center"/>
						网址白名单设置</a>
					</li>
				</ul>
			@endif
		</div>	
	</div>
	<div id='footer'>
		<ul>
			<li><a href='/'>首页</a></li>
			<li><a href='/messageboard/'>留言板</a></li>
			<li><a href='/download/iShare.apk'>下载Android客户端</a></li>
			<li><a href='/download/iShare.ipa'>下载IOS客户端</a></li>
			<li><a href='/aboutus'>关于我们</a></li>
		</ul>
		<p class='copyright_info'>Copyright &copy; iShare网址分享平台 2015-2016</p>
	</div>
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="/js/cropbox.js"></script>
	<script type="text/javascript" src="/js/upload_topic_img.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			
		});
	</script>
</body>
</html>