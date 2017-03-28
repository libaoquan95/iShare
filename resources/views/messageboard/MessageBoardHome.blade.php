<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>留言板 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/message_board.css" >
	<link rel="stylesheet" type="text/css" href="/css/default.css" />
	<link rel="stylesheet" type="text/css" href="/css/component.css" />
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
	<link rel="stylesheet" type="text/css" href="/css/jquery.step.css">
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
	<div id="hold" class="normal_bk"></div>
	<!-- 网页首部 -->
	<div id='header'>
		<a href='/' class='head_logo'><img src='/css/img/logo.png' alt='iShare 网址分享' id='logo_img'/></a>
		<div class='menu_but'></div>
		<div class='menu_but_click'></div>

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
			<a href='/messageboard' id='local_page'>
				<li>
				<img src='/css/img/head_list_my.png' alt='' class='head_list_img'/>留言板</li>
			</a>
			<a class='other_page_seach'><li>
				<form action="{{ URL('/syshide/normal_search') }}" target='_blank' method='post'>
					<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
					<input type='text' name='search' class='search' placeholder=""/>
					<input type='submit' id='searchbut' value='' title="点击搜索"/>
				</form>
			</li></a>
		</ul>
		<script type="text/javascript" src="/js/main_menu.js"></script>
	</div>

<div id="main_bk">
	<div class="step-body" id="myStep">
		<div class="step-header" style="width:90%">
			<ul>
				<a href="/messageboard"><li><p>全部</p></li></a>
				<a href="/messageboard/waitaudit"><li><p>待审核</p></li></a>
				<a href="/messageboard/waitsolved"><li><p>待解决</p></li></a>
				<a href="/messageboard/success"><li><p>已解决</p></li></a>
				<a href="/messageboard/error"><li><p>未解决</p></li></a>
			</ul>
		</div>
	<div>

	<div id="mess_list_bk">
	<div class="container">
		<div class="main">
			<ul class="cbp_tmtimeline">
				<li>
					<time class="cbp_tmtime" datetime="2013-04-13 05:36"><span>给我们</span><span>留言</span></time>
					<div class="cbp_tmicon cbp_tmicon-mail"></div>
					<div class="cbp_tmlabel">
						<h2>给我们留言</h2>
						<p><textarea name='submiturl_desc' height='20' width='50' class='submiturl_desc' placeholder="" id="submit_url_des"></textarea></p>
						<input type="hidden" id="sub_user" name="sub_user" value="{{Session::get('user_name')}}" />
						<p><button class="leaveword_button" id="leaveword_button" title="给我们留言">给我们留言</button></p>
					</div>
				</li>
				@foreach ($info['message_info'] as $messages)
				<li>
					<time class="cbp_tmtime" datetime="{{ $messages['created_at'] }}">
						<span>{{ $messages['created_at_date'] }}</span>
						<span>{{ $messages['created_at_time'] }}</span>
					</time>
					<div class="cbp_tmicon">
						@if($messages['messages']->state == 0)
							<span class='audit_atate'><img src="/css/img/mess_help.png" title="待审核"/></span>
						@elseif($messages['messages']->state == 1)
							<span class='audit_atate'><img src="/css/img/mess_info.png" title="待解决"/></span>
						@elseif($messages['messages']->state == 2)
							<span class='audit_atate'><img src="/css/img/mess_success.png" title="已解决"/></span>
						@elseif($messages['messages']->state == 3)
							<span class='audit_atate'><img src="/css/img/mess_error.png" title="未解决"/></span>
						@endif
					</div>
					<div class="cbp_tmlabel">
						<h2>
							<img src="{{ $messages['user_img'] }}" class="mess_user_img" />
							{{ $messages['messages']->user }}
						</h2>
						<p>{{ $messages['messages']->message }}</p>
					</div>
				</li>
				@endforeach
			</ul>
		</div>
		</div>
	</div>
	<button type="button" id="next_page_but" class="next_page_but_normal">更多</button>
	<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
	<input type="hidden" id="next_page_num" name="next_page_num" value='1' />
	<input type="hidden" id="page_line_num" name="page_line_num" value='10' />
	<input type="hidden" id="state" name="state" value='-1' />
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
	<script type="text/javascript" src="/js/load_next_message_page.js"></script>
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="/js/jquery.step.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".totop").click(function() {
				$('body,html').animate({scrollTop:0},1000);
			});	
			$(".refurbish").click(function() {
				self.location.reload();
			});	

			$("#leaveword_button").click(function() {
				var _token = $("#_token").val();
				var sub_user = $("#sub_user").val();
				var message = $("#submit_url_des").val();
				message = trimLeft(message);

				$.post("/messageboard/submit_messsage",
				{
					_token:_token,
					sub_user:sub_user,
					message:message
				},
				function(data,status)
				{
					if(data == "success")
					{
						swal({
								title: "留言已上传",
								text: "等待管理员审阅",
								type: "success"
							},
						function(){
							window.location.reload();
						});
					}
					else if(data == "error")
					{
						swal({
								title: "留言不能为空",
								text: "请重新输入",
								type: "error"
							},
						function(){
							window.location.reload();
						});
					}
				});
				
				//去掉左边的空白  
				function trimLeft(s){  
					if(s == null) {  
						return "";  
					}  
					var whitespace = new String(" \t\n\r");  
					var str = new String(s);  
					if (whitespace.indexOf(str.charAt(0)) != -1) {  
						var j=0, i = str.length;  
						while (j < i && whitespace.indexOf(str.charAt(j)) != -1){  
							j++;  
						}  
						str = str.substring(j, i);  
					}  
					return str;  
				} 
			});	

			$(function() {
				var step= $("#myStep").step({
					animate:true,
					initStep:1,
					speed:1000
				});
			});
		});	
	</script>
</body>
</html>
