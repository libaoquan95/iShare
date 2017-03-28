<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>搜索/{{ $info['search_info'] }} - iShare 网址压缩分享平台</title>

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
			<a href='' id='local_page'>
				<li>
				<img src='/css/img/search.png' alt='' class='head_list_img'/>搜索页面</li>
			</a>
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
			<a href='' id='local_page'>
				<li>
				<img src='/css/img/search.png' alt='' class='head_list_img'/>搜索页面</li>
			</a>
		</ul>
		@endif
		<div class='menu_but_click'></div>
		<script type="text/javascript" src="/js/main_menu.js"></script>
	</div>
	
	<!-- 网页主体 -->
	<div id='main_bk'>
		<!-- 主体 -->
		<div id='main'>
			<!-- 列表 -->
			<div id='in_url_list'>
				@foreach ($info['urls_info'] as $userurl)
				<div class='url_desc_block'>
					<p class='url_user'>
						@foreach ($userurl['user'] as $user)
						<a href='/user/{{ $user->name }}'><img src='{{ $user->user_img }}'	title='用户：{{ $user->name }}' alt='{{ $user->name }}' class='users_img' align="middle"/></a>
						<span>{{ $user->name }}的分享</span>
						@endforeach
						<ul class='topic_img_list'>
							@foreach ($userurl['topics'] as $topic)
								<li><a href='/topics/{{ $topic->name }}'>
								<img src='{{ $topic->topics_img }}'	title='话题：{{ $topic->name }}' alt='{{ $topic->name }}' class='topics_img'/>
								</a></li>
							@endforeach
						</ul>
					</p>
					<p class='url_desc'>{{ $userurl['bu_userurls']->url_describe }}</p>
					<p class='url_code'>
						<img src='/css/img/erweima.png' class='a_img' value="{{ getenv('LOCAL_DOMAIN_NAME') }}/to/{{ $userurl['bu_userurls']->short_url }}" name="{{ $userurl['bu_userurls']->short_url }}" title='显示连接二维码'>
						<a href='/to/{{ $userurl["bu_userurls"]->short_url }}' target='_blank' title='点击跳转'>{{ getenv('LOCAL_DOMAIN_NAME') }}/to/{{ $userurl['bu_userurls']->short_url }}</a>
					</p>
					<p id="erweima_area_{{ $userurl['bu_userurls']->short_url }}" class="erweima_area">
					</p>
					<p class='url_pv_info' title="访问量">访问量<br /><span>{{ $userurl['bu_userurls']->url_pv }}</span></p>
					<p class='url_time_info'>
						<span>创建于：{{ $userurl['bu_userurls']->created_at }}</span>
						<span>上次访问：{{ $userurl['bu_userurls']->updated_at }}</span>
					</p>
				</div>
				@endforeach
			</div>
			<button type="button" id="next_page_but" class="next_page_but_normal">更多</button>
			<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="next_page_num" name="next_page_num" value='1' />
			<input type="hidden" id="page_line_num" name="page_line_num" value='10' />
		</div>
		
		<!-- 高级搜索模式 -->
		<div id='more_search'>
			<p>当前搜索：<span id="search_info">{{ $info['search_info'] }}</span></p>
			<input type="hidden" name="now_search_desc" id="now_search_desc" value="{{ $info['search_info'] }}">
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
	<div class='totop'>
	</div>
	<div class='refurbish'>
	</div>
	<script type="text/javascript" src="/js/load_next_normal_search_page.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".totop").click(function() {
				$('body,html').animate({scrollTop:0},1000);
			});	
			$(".refurbish").click(function() {
				self.location.reload();
			});	
		});	
	</script>
	<script>
		// 遍历
		var now_search_desc = $('#now_search_desc').val();
		var now_search_desc_arr = now_search_desc.split(" ");
		$(".url_desc").each(function(){
			var url_desc = $(this).html();
			var url_desc = url_desc.replace(now_search_desc , "<span style='color: red'>" + now_search_desc + "</span>");
			$(this).html(url_desc);
		});
	</script>
</body>
</html>
