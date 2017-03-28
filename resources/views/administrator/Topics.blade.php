<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>管理员：话题管理 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/topic_ground_manage.css" >
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
				<img src='/css/img/head_list_manger.png' alt='' class='head_list_img'/>管理员：话题管理</li>
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
		<div id='topic_ground'>
			<p>话题广场管理</p>
			<div id="add_topic_area">

				<input type='text' name='topic_name' id='topic_name' placeholder="填写话题名称" class="txt_input "/><br />
				<button type="button" id="load_topic_page" class="sub_button">上传话题图片</button><br />

				<div id="load_topic_page_area">
					<div class="container">
						<p>上传话题图片</p>
						<div class="imageBox">
							<div class="thumbBox"></div>
							<div class="spinner" style="display: none">Loading...</div>
						</div>
						<div class="action">
							<input type="file" id="file" style="float:left; width: 250px">
							<input type="button" id="btnCrop_topic" value="上传话题图片" style="float: right">
							<input type="button" id="btnZoomIn" value="+" title="放大图像" style="float: right">
							<input type="button" id="btnZoomOut" value="-" title="缩小图像" style="float: right">
						</div>
						<div class="cropped"></div>
					</div>
				</div>
				<script type="text/javascript">
					$(".load_topic_page_area").hide();
				</script>
			</div>

			<ul class="topic_ground_list">
				<li id="add_topic">
					<img src='/css/img/Add.png' alt='Add' class='topics_img_manage'/>
					<p>添加话题</p>
				</li>
			@foreach ($info['topics'] as $topics)
				<li>
					<img src='{{  $topics->topics_img }}' alt='{{ $topics->name }}' class='topics_img' />
					<p>{{  $topics->name }}</p>
				</li>
			@endforeach
			</ul>
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
	<script type="text/javascript" src="/js/cropbox.js"></script>
	<script type="text/javascript" src="/js/upload_topic_img.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.topics_img').click(function() {
				var topic_name = $(this).attr("alt");
				var _token = $("#_token").attr("value");
				swal({
					title: "确定删除这个话题'"+topic_name+"'吗？",
					text: "点击'confirm'会删除此话题及其所有附属信息，并不可恢复！",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: '确定'
				},
				function(){
					$.post("/syshide/delete_topic",
					{
						topic_name:topic_name,
						_token:_token
					},
					function(data,status)
					{
						swal({
								title: "操作成功",
								text: "话题"+data+"已删除",
								type: "success"
							},
						function(){
							$('.container').slideToggle();
							window.location.reload();
						});
					})
				});
			});
		});
	</script>
</body>
</html>