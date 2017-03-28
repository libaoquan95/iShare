<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>管理员：网站白名单 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/upload_user_img.css" >
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
			<a href='/administrator/white_list' id='local_page'>
				<li>
				<img src='/css/img/head_list_manger.png' alt='' class='head_list_img'/>管理员：网站白名单</li>
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
			<div class='url_code'>
				<button class="black_button" id="add_list" title="增加" value="" style="text-align: left">增加</button>
				<div class="operation_area">
					<input type='text' name='short_demo' id='short_demo' placeholder="填写短网址" class="txt_input "/><br />
					<input type='text' name='long_demo' id='long_demo' placeholder="填写长网址" class="txt_input "/><br />
					<input type='text' name='demo_name' id='demo_name' placeholder="填写网站简述" class="txt_input "/><br />
					<button type="button" id="sub_add_list" class="sub_button">确定增加</button><br />
				</div>
				<script>
					$(".operation_area").hide();
				</script>
				<table class='url_base_info_sheet' style="text-align: center">
				<tr>
					<td class='url_base_info_sheet_head'>短网址</td>
					<td class='url_base_info_sheet_head'>长网址</td>
					<td class='url_base_info_sheet_head'>网站简述</td>
					<td class='url_base_info_sheet_head'>操作</td>
				</tr>
				@foreach ($info['white_list'] as $list)
					<tr>
						<td class='url_base_info_sheet_cont'>{{ $list->short_demo }}</td>
						<td class='url_base_info_sheet_cont'>{{ $list->long_demo }}</td>
						<td class='url_base_info_sheet_cont'>{{ $list->demo_name }}</td>
						<td class='url_base_info_sheet_cont'>
							<button class="red_button del_list" title="删除" value="{{ $list->short_demo }}">删除</button>
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
		$(document).on("click",'#add_list',function(){
			$(".operation_area").slideToggle();
			
		});	
		
		$(document).on("click",'#sub_add_list',function(){
			var _token = $("#_token").val();
			var short_demo = $("#short_demo").val();
			var long_demo = $("#long_demo").val();
			var demo_name = $("#demo_name").val();
			$.post("/administrator/white_list/add",
			{
				_token:_token,
				short_demo:short_demo,
				long_demo:long_demo,
				demo_name:demo_name
			},
			function(data,status)
			{
				if(data=='sucess')
				{
					swal({
						title: "增加成功",
						text: "",
						type: "success"
					})
				}
				else if(data=='error')
				{
					swal({
						title: "此信息已存在",
						text: "请勿重复增加",
						type: "error"
					});
				}
			});
		});	
		
		$(document).on("click",'.del_list',function(){
			var _token = $("#_token").val();
			var short_demo = $(this).attr("value");
			
			swal({
				title: "确定删除这个信息吗？",
				text: "",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: '确定'
			},
			function(){
				$.post("/administrator/white_list/del",
				{
					_token:_token,
					short_demo:short_demo
				},
				function(data,status)
				{
					window.location.reload();
				});
			});
		});	
	</script>
</body>
</html>