<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>首页 - iShare 网址压缩分享平台</title>

	<!--<link href="/css/app.css" rel="stylesheet">-->
	<link href="/css/header_footer.css" rel="stylesheet">
	<link href="/css/index_sub.css" rel="stylesheet">
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
	<div id="hold" class="normal_bk"></div>
	<!-- 网页首部 -->
	<div id='header'>
		<a href='/' class='head_logo'><img src='/css/img/logo.png' alt='iShare 网址分享' id='logo_img'/></a>
		<div class='menu_but'></div>

		
		<ul class='order_list'>
			<a href='/'><li id="new_list">最新</li> <a/>
			<a href='/hot'><li id="hot_list" class="local_order">最热</li> <a/>
		</ul>
		
		<ul class='menu_list'>
			<li class='head_user_info'>
				Welcome 游客
				<input type="hidden" id="this_user_name" value="0">
				<input type="hidden" id="this_user_id" value="-1">
			</li>
			<a href='/'class='head_a_first' id='local_page'>
				<li>
				<img src='/css/img/head_list_home.png' alt='' class='head_list_img'/>首页</li>
			</a>
			<a href='/auth/login' class='other_page'>
				<li>
				<img src='/css/img/head_list_login_blue.png' alt='' class='head_list_img'/>我要登录</li>
			</a>
			<a href='auth/register' class='other_page'>
				<li>
				<img src='/css/img/head_list_register_blue.png' alt='' class='head_list_img'/>加入我们</li>
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
		<div id='main'>
			<!-- 列表 -->
			<div id='in_url_list'>
				@foreach ($info["url_info"] as $userurl)
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
					<p class='url_desc'>
						{{ $userurl['bu_userurls']->url_describe }}
					</p>
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
							<span>{{ $userurl['bu_userurls']->url_pv/10000 }}万</span>
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
		</div>

		<!-- 推荐 -->
		<div id='recommend'>
			<img src='/css/img/recommend.jpg' alt='今日推荐' id='recommend_img'/>
			<div id='load_recommend'>
				<p class='load_recommend_p'>正在生成个性推荐...<p>
				<img src='/css/img/wait_recommend.gif' alt='正在生成个性推荐' id='load_recommend_img'/>
			</div>
		</div>
	</div>
	<div id='footer'>
		<ul>
			<li><a href='/' '>首页</a></li>
			<li><a href='/auth/login'>我要登录</a></li>
			<li><a href='/auth/register'>我要注册</a></li>
		</ul>
		<p class='copyright_info'>Copyright &copy; iShare网址分享平台 2015-2016</p>
	</div>
	<div class='totop'>
	</div>
	<div class='refurbish'>
	</div>
	<script type="text/javascript" src="/js/load_next_hot_page.js"></script>
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
		$(document).ready(function(){
			var user_name = $("#this_user_name").attr("value");
			var user_id = $("#this_user_id").attr("value");
			var _token = $("#_token").val();

			$.post("/getRecommendUrl",
			{
				_token:_token,
				user_name:user_name,
				user_id:user_id
			},
			function(data,status)
			{
				$("#load_recommend").slideUp();
				$("#recommend").append(data);
			});
		});
	</script>
</body>
</html>
