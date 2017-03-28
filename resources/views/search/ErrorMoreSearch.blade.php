<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>搜索内容不存在 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/index_sub.css" >
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
	<div id="hold" class="normal_bk"></div>
	<!-- 网页首部 -->
	<div id='header'>
		<a href='/' class='head_logo'><img src='/css/img/logo.png' alt='iShare 网址分享' id='logo_img'/></a>
		<div class='menu_but'></div>
		@if (Session::has('auth_state'))
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
			<a href='/manage/{{Session::get('user_name')}}/allurls' class='other_page'>
				<li>
				<img src='/css/img/head_list_manger_blue.png' alt='' class='head_list_img'/>我的分享</li>
			</a>
			<a href='/auth/logout' class='other_page'>
				<li>
				<img src='/css/img/head_list_exit_blue.png' alt='' class='head_list_img'/>注销登录</li>
			</a>
			<a class='other_page_seach'><li>
				<form action="{{ URL('/syshide/normal_search') }}" target='_blank' method='post'>
					<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
					<input type='text' name='search' class='search' placeholder=""/>
					<input type='submit' id='searchbut' value='' title="点击搜索"/>
				</form>
			</li></a>
		</ul>
		@else
		<ul class='menu_list'>
			<li class='head_user_info'>
				Welcome 游客
			</li>
			<a href='/' class='other_page'>
				<li>
				<img src='/css/img/head_list_home_blue.png' alt='' class='head_list_img'/>首页</li>
			</a>
			<a href='/share' class='other_page'>
				<li>
				<img src='/css/img/head_list_login_blue.png' alt='' class='head_list_img'/>我要登录</li>
			</a>
			<a href='/topics' class='other_page'>
				<li>
				<img src='/css/img/head_list_register_blue.png' alt='' class='head_list_img'/>我要注册</li>
			</a>
			<a class='other_page_seach'><li>
				<form action="{{ URL('/syshide/normal_search') }}" target='_blank' method='post'>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type='text' name='search' class='search' placeholder=""/>
					<input type='submit' id='searchbut' value='' title="点击搜索"/>
				</form>
			</li></a>
		</ul>
		@endif
		<div class='menu_but_click'></div>
		<script type="text/javascript" src="/js/main_menu.js"></script>
	</div>
	
	<!-- 网页主体 -->
	<div id='main_bk'>
		<!-- 主体 -->
		<div id='main'>
			<p class="error_search_info">您搜索的内容不存在！</p>
		</div>
		
		<!-- 高级搜索模式 -->
		<div id='more_search'>
			<p>当前搜索</p>
			<p>网址描述信息：{{ $info['search_info']['search_desc'] }}</p>
			<p>原网址：{{ $info['search_info']['search_lurl'] }}</p>
			<p>短网址：{{ $info['search_info']['search_surl'] }}</p>
			<p>分享用户：<span id="search_user">{{ $info['search_info']['search_user'] }}</span></p>
			<p>高级搜索模式</p>
			<form action="{{ URL('/syshide/more_search') }}" method='post'>
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type='text' name='search_desc' class='txt_input' placeholder="搜索网址描述信息"/>
				<input type='text' name='search_lurl' class='txt_input' placeholder="搜索原网址"/>
				<input type='text' name='search_surl' class='txt_input' placeholder="搜索短网址"/>
				<input type='text' name='search_user' class='txt_input' placeholder="搜索分享用户"/>
				<input type='submit' class="sub_button" value='高级搜索' title="点击搜索"/>
			</form>
		</div>
	</div>
	<div id='footer'>
		@if (Session::has('auth_state'))
		<ul>
			<li><a href='/' '>首页</a></li>
			<li><a href='/messageboard/'>留言板</a></li>
			<li><a href='/download/iShare.apk'>下载Android客户端</a></li>
			<li><a href='/download/iShare.ipa'>下载IOS客户端</a></li>
			<li><a href='/aboutus'>关于我们</a></li>
		</ul>
		@else
		<ul>
			<li><a href='/' '>首页</a></li>
			<li><a href='/auth/login'>我要登录</a></li>
			<li><a href='/auth/register'>我要注册</a></li>
		</ul>
		@endif
		<p class='copyright_info'>Copyright &copy; iShare网址分享平台 2015-2016</p>
	</div>
</body>
</html>
