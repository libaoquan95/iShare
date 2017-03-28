<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>我的信息/{{Session::get('user_name')}} - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/url_info.css" >
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
	<link rel="stylesheet" type="text/css" href="/css/upload_user_img.css" >
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
			<a href='/manage/{{Session::get('user_name')}}' id='local_page'>
				<li>
				<img src='/css/img/head_list_my.png' alt='' class='head_list_img'/>我的信息</li>
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
		<div id='main'>
			<div class='user_info_area'>
				<img src="{{ $info['user_img'] }}"	title="用户：{{ $info['user_name'] }} 点击更换头像" alt="点击更换头像" class="users_img" align="middle"/>
				<p>已分享网址数量：
					@if ( $info['share_url_count'] > 10000)
						<span class="special_number">{{ $info['share_url_count']/10000 }}万</span>
					@else
						<span class="special_number">{{ $info['share_url_count'] }}</span>
					@endif
				条</p>
				<p>已浏览网址次数：
					@if ( $info['share_url_count'] > 10000)
						<span class="special_number">{{ $info['visit_url_count']/10000 }}万</span>
					@else
						<span class="special_number">{{ $info['visit_url_count'] }}</span>
					@endif
				次</p>
				<p calss='a_but_area'><a href='/manage/{{$info['user_name']}}/allurls' class='usuall_but'>我的分享</a></p>
				<p class='a_but_area'><a href='/auth/alert_password' class='usuall_but'>修改密码</a></p>
				@if($info['user_group'] == 1)
					<p class='a_but_area'><a href='/administrator' class='usuall_but'>进入管理员界面</a></p>
				@endif
			</div>

			<div class="container">
				<div class="imageBox">
					<div class="thumbBox"></div>
					<div class="spinner" style="display: none">Loading...</div>
				</div>
				<script type="text/javascript">
					$('.container').hide();
				</script>
				<div class="action">
					<input type="file" id="file" style="float:left; width: 250px">
					<input type="button" id="btnCrop" value="上传头像" style="float: right">
					<input type="button" id="btnZoomIn" value="+" title="放大图像" style="float: right">
					<input type="button" id="btnZoomOut" value="-" title="缩小图像" style="float: right">
					<input type="hidden" id="user_name" name="user_name" value="{{ Session::get('user_name') }}">
				</div>

				<div class="cropped"></div>
			</div>

			<div class='charts_area'>
				<div class='hiden_aera'>
					<input type="hidden" value="{{ $i=0 }}">
					@foreach ($info['share_topic_count'] as $share_topic_name => $share_topic_count)
						<input type="hidden" name="{{ $share_topic_name }}" id="" value="{{ $share_topic_count }}" class="share_topic_info">
						<input type="hidden" value="{{ $i++ }}">
					@endforeach
				</div>
				<div id="share_topic_count_charts" style="height:{{$i*30}}px; min-height:300px"></div>
				<div id="share_topic_count_pie" class="pie_charts"></div>
			</div>
			<div class='charts_area'>
				<div class='hiden_aera'>
					<input type="hidden" value="{{ $j=0 }}">
					@foreach ($info['visit_topic_count'] as $visit_topic_name => $visit_topic_count)
						<input type="hidden" name="{{ $visit_topic_name }}" id="" value="{{ $visit_topic_count }}" class="visit_topic_info">
						<input type="hidden" value="{{ $j++ }}">
					@endforeach
				</div>
				<div id="visit_topic_count_charts" style="height:{{$j*30}}px; min-height:300px"></div>
				<div id="visit_topic_count_pie" class="pie_charts"></div>
			</div>
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
	<div class='totop'>
	</div>
	<div class='refurbish'>
	</div>
	<script type="text/javascript" src="/js/highcharts.js"></script>
	<script type="text/javascript" src="/js/mycharts.js"></script>
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="/js/cropbox.js"></script>
	<script type="text/javascript" src="/js/upload_user_img.js"></script>
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