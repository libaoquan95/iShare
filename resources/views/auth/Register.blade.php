<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>加入我们 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
	<link rel="stylesheet" type="text/css" href="/css/login.css">
</head>
<body>

<div id="adm_login">
		<a href='/' class='head_logo'><img src="/css/img/logo_blue.png" alt="iShare 网址分享" id='logo_img'/></a>
		<!--<form action='{{ URL('auth/register_sumbit') }}' method='post'>-->
		<form>
			<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
			<label>
				<input type="text" name="user_email" placeholder="邮箱" class="txt_input" id='input_email'/>
				<p class='error' id='err_emile'>邮箱地址不正确</p>
				<p class='error' id='rep_emile'>此邮箱已被注册</p>
			</label>
			<label>
				<input type="password" name="user_pass" placeholder="密码" class="txt_input" id='input_pass'/>
			</label>
			<label>
				<input type="password" name="user_pass_rq" placeholder="确认密码" class="txt_input" id='input_pass_rq'/>
				<p class='error' id='err_pass'>两次密码不一致</p>
			</label>
			<label>
				<input type="text" name="user_nicename" placeholder="用户名" class="txt_input" id='input_name'/>
				<p class='error' id='err_usname'>用户名已存在</p>
				<p class='error' id='req_usname'>用户名已存在</p>
			</label>
			<!--<label>
				<input type="hidden" name="user_code" placeholder="邀请码" class="txt_input" id='input_code' value="iShare"/>
				<p class='error' id='err_invite'>邀请码不正确</p>
			</label>
			<label>-->
				<!--<input type="submit" value="注册" class="sub_button" id="register_sub_but" />-->
				<button type="button" id="register_sub_but" class="sub_button">注册</button>
			</label>
		</form>
	</div>

	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="/js/register.js"></script>

</body>
</html>