<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>管理员：用户管理 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/user_manger.css" >
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
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
			<a href='/manage/administrator_allurl' class='other_page'>
				<li>
				<img src='/css/img/head_list_manger_blue.png' alt='' class='head_list_img'/>我的分享</li>
			</a>
			<a href='/administrator' class='other_page'>
				<li>
				<img src='/css/img/head_list_manger_blue.png' alt='' class='head_list_img'/>管理员主页</li>
			</a>
			<a href='/administrator/topics' id='local_page'>
				<li>
				<img src='/css/img/head_list_manger.png' alt='' class='head_list_img'/>管理员：用户管理</li>
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
		<div id='main'>
			<div class='url_code' style="text-align: center">
				<a href="/administrator/users" style="text-decoration:none">
					<button class="black_button" title="查看全部用户信息" value="">查看全部</button>
				</a>
				<a href="/administrator/users/admin" style="text-decoration:none">
					<button class="black_button" title="查看全部管理员用户信息" value="">查看管理员</button>
				</a>
				<a href="/administrator/users/normal" style="text-decoration:none">
					<button class="black_button" title="查看全部普通用户信息" value="">查看普通用户</button>
				</a>
				<a href="/administrator/users/forbid" style="text-decoration:none">
					<button class="black_button" title="查看全部封禁用户信息" value="">查看封禁用户</button>
				</a>
				<a href="/administrator/users/sortforinvalid" style="text-decoration:none">
					<button class="black_button" title="按无效网址数降序排序" value="">无效网址数排序</button>
				</a>
				<a href="/administrator/users/sortforharmful" style="text-decoration:none">
					<button class="black_button" title="按不良网址数降序排序" value="">不良网址数排序</button>
				</a>
			</div>
			<div class='url_code' style="text-align: center">
				<table class='url_base_info_sheet'>
				<tr>
					<td class='url_base_info_sheet_head' style="min-width:100px;">用户名</td>
					<td class='url_base_info_sheet_head' style="min-width:200px;">email</td>
					<td class='url_base_info_sheet_head'>用户组</td>
					<td class='url_base_info_sheet_head'>用户状态</td>
					<td class='url_base_info_sheet_head'>分享网址数量</td>
					<td class='url_base_info_sheet_head'>被报告无效网址数量</td>
					<td class='url_base_info_sheet_head'>被报告不良网址数量</td>
					<td class='url_base_info_sheet_head' style="min-width:110px;">操作</td>
				</tr>
				@foreach ($info['users'] as $user)
					<tr>
						<td class='url_base_info_sheet_cont'>{{ $user['base_info']->name }}</td>
						<td class='url_base_info_sheet_cont'>{{ $user['base_info']->email }}</td>
						@if ($user['base_info']->user_group == 1)
							<td class='url_base_info_sheet_cont'>管理员</td>
						@else
							<td class='url_base_info_sheet_cont'>普通用户</td>
						@endif

						@if ($user['base_info']->user_state == 1)
							<td class='url_base_info_sheet_cont'>正常</td>
						@else
							<td class='url_base_info_sheet_cont'>停封</td>
						@endif
						<td class='url_base_info_sheet_cont'>{{ $user['base_info']->user_url_count }}</td>
						<td class='url_base_info_sheet_cont'>{{ $user['invalid_count'] }}</td>
						<td class='url_base_info_sheet_cont'>{{ $user['harmful_count'] }}</td>
						<td class='url_base_info_sheet_cont'>
						@if ($user['base_info']->user_state == 1)
							<button class="red_button forbid_user" title="封禁此用户" value="{{ $user['base_info']->name }}">封禁</button>
						@else
							<button class="green_button unforbid_user" title="解封此用户" value="{{ $user['base_info']->name }}">解封</button>
						@endif
						@if ($user['base_info']->user_group == 1)
							<button class="red_button set_normal_user" title="设为普通用户" value="{{ $user['base_info']->name }}">普通</button>
						@else
							<button class="green_button set_admin_user" title="设为管理员" value="{{ $user['base_info']->name }}">管理员</button>
						@endif
						</td>
					</tr>
				@endforeach
				</table>
			</div>
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
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript">
		$(document).on("click",'.forbid_user',function(){
			var user_name = $(this).attr("value");
			var _token = $("#_token").val();

			$(this).removeClass("forbid_user");
			$(this).removeClass("red_button");
			$(this).addClass("unforbid_user");
			$(this).addClass("green_button");
			$(this).text("解封");
			$(this).attr("title","解封此用户");

			swal({
				title: "要继续进行操作吗？",
				text: "封禁用户"+user_name,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: '确定'
			},
			function(){
				$.post("/administrator/set_user_state",
				{
					_token:_token,
					user_name:user_name,
					fob_token:"yes"
				});
			});	
		});	
		
		$(document).on("click",'.unforbid_user',function(){
			var user_name = $(this).attr("value");
			var _token = $("#_token").val();

			$(this).removeClass("green_button");
			$(this).removeClass("unforbid_user");
			$(this).addClass("forbid_user");
			$(this).addClass("red_button");
			$(this).text("封禁");
			$(this).attr("title","封禁此用户");

			swal({
				title: "要继续进行操作吗？",
				text: "解封用户"+user_name,
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: '确定'
			},
			function(){
				$.post("/administrator/set_user_state",
				{
					_token:_token,
					user_name:user_name,
					fob_token:"no"
				});
			});
		});	
		
		$(document).on("click",'.set_normal_user',function(){
			var user_name = $(this).attr("value");
			var _token = $("#_token").val();

			$(this).removeClass("set_normal_user");
			$(this).removeClass("red_button");
			$(this).addClass("set_admin_user");
			$(this).addClass("green_button");
			$(this).text("管理员");
			$(this).attr("title","设为管理员");

			swal({
				title: "要继续进行操作吗？",
				text: "将用户"+user_name+"设为普通用户",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: '确定'
			},
			function(){
				$.post("/administrator/set_user_group",
				{
					_token:_token,
					user_name:user_name,
					fob_token:"normal"
				});
			});	
		});	

		$(document).on("click",'.set_admin_user',function(){
			var user_name = $(this).attr("value");
			var _token = $("#_token").val();

			$(this).removeClass("set_admin_user");
			$(this).removeClass("green_button");
			$(this).addClass("set_normal_user");
			$(this).addClass("red_button");
			$(this).attr("title","设为普通用户");
			$(this).text("普通");

			swal({
				title: "要继续进行操作吗？",
				text: "将用户"+user_name+"设为管理员",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: '确定'
			},
			function(){
				$.post("/administrator/set_user_group",
				{
					_token:_token,
					user_name:user_name,
					fob_token:"admin"
				});
			});	
		});	
	</script>
</body>
</html>