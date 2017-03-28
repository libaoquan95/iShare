<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>用户分享/{{$info['user_name']}} - iShare 网址压缩分享平台</title>

	<!--<link href="/css/app.css" rel="stylesheet">-->
	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/index_sub.css" >
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
	<div id="hold" class="normal_bk"></div>
	<!-- 网页首部 -->
	<div id='header'>
		<a href='/' class='head_logo'><img src='/css/img/logo.png' alt='iShare 网址分享' id='logo_img'/></a>
		<div class='menu_but'></div>
		
		<ul class='order_list'>
			<a href='/user/{{ $info['user_name'] }}'><li id="new_list">最新</li> <a/>
			<a href='/user/{{ $info['user_name'] }}/hot'><li id="hot_list" class="local_order">最热</li> <a/>
		</ul>

		<ul class='menu_list'>
			<li class='head_user_info'>
				Welcome {{Session::get('user_name')}}
			</li>
			<li class='head_user_img_li'>
				<img src='{{$info['user_img_2']}}' class='head_user_img'/>
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
			<a href='/user/{{ $info['user_name'] }}' id='local_page'>
				<li>
				<img src='/css/img/head_list_topic.png' alt='' class='head_list_img'/>用户分享：{{ $info['user_name'] }}</li>
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
		<div class='menu_but_click'></div>
		<script type="text/javascript" src="/js/main_menu.js"></script>
	</div>

	<!-- 网页主体 -->
	<div id='main_bk'>
		<!-- 主体 -->
		<div id='topic_ground'>
			<div class='topic_ground_bar'>
				<p class='topic_title'>用户：{{$info['user_name']}}
					<img src="{{ $info['user_img'] }}" title="用户：{{$info['user_name']}}" alt="{{$info['user_name']}} " class='topics_img_in_topicPage'/>
				</p>
			</div>

			<div id='in_url_list'>
				@foreach ($info['urls_info'] as $userurl)
				<div class='url_desc_block'>
					<p class='url_user'>
						<span class='index_menu' value="{{ $userurl['bu_userurls']->short_url }}">
							<img src='/css/img/index_menu.png' title=''/>
						</span>
						@foreach ($userurl['user'] as $user)
						<img src='{{ $user->user_img }}'	title='用户：{{ $user->name }}' alt='{{ $user->name }}' class='users_img' align="middle"/>
						<span>{{ $user->name }}的分享</span>
						@endforeach
						<ul class='topic_img_list'>
							@foreach ($userurl['topics'] as $topic)
								<li><a href='/topics/{{ $topic->name }}'>
								<img src='{{ $topic->topics_img }}'	title='话题：{{ $topic->name }}' alt='{{ $topic->name }}' class='topics_img'/>
								</a></li>
							@endforeach
						</ul>
						<!-- 显示举报信息 -->
						<ul class='index_menu_list' id="index_menu_list_{{ $userurl['bu_userurls']->short_url }}">
							<li class=''>您可以向我们反映！</li>
							<li class="index_menu_item" alt="item_invalid" value="{{ $userurl['bu_userurls']->short_url }}" name="{{ $user->name }}">这是一个无效链接</li>
							<li class="index_menu_item" alt="item_harmful" value="{{ $userurl['bu_userurls']->short_url }}" name="{{ $user->name }}">这是一个不良链接</li>
							<script type="text/javascript">
								$(".index_menu_list").hide();
							</script>
						</ul>
					</p>
					<p class='url_desc'>{{ $userurl['bu_userurls']->url_describe }}</p>
					<!-- 显示网址 -->
					<p class='url_code'>
						<img src='/css/img/erweima.png' class='a_img' value="{{ getenv('LOCAL_DOMAIN_NAME') }}/to/{{ $userurl['bu_userurls']->short_url }}" name="{{ $userurl['bu_userurls']->short_url }}" title='显示连接二维码'>
						<a href='/to/{{ $userurl["bu_userurls"]->short_url }}' target='_blank' title='点击跳转'>{{ getenv('LOCAL_DOMAIN_NAME') }}/to/{{ $userurl['bu_userurls']->short_url }}</a>
						@if($userurl['white_list'] == 1)
							<img src='/css/img/safe.png' class='a_img' title='已验证短网址：{{$userurl['white_title']}}'>
						@endif
					</p>
					<p id="erweima_area_{{ $userurl['bu_userurls']->short_url }}" class="erweima_area">
					</p>
					<p class='url_pv_info' title="访问量">
						访问量<br />
						@if ( $userurl['bu_userurls']->url_pv > 10000)
							<span>{{ $userurl['bu_userurls']->url_pv/10000 }}W</span>
						@else
							<span>{{ $userurl['bu_userurls']->url_pv }}</span>
						@endif
					</p>
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
			<input type="hidden" id="user_name" name="user_name" value='{{ $info['user_name'] }}' />
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
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="/js/load_next_hot_userurl_page.js"></script>
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
</body>
</html>