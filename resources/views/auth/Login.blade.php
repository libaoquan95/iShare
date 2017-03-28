<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>请登录 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/login.css" >
</head>
<body>

<div id="adm_login">
	<a href='/' class='head_logo'><img src="/css/img/logo_blue.png" alt="iShare 网址分享" id='logo_img'/></a>
	<!--<form action='{{ URL('auth/login_sumbit') }}' method='post'>-->
	<form>
		<p class='error'>用户名或密码错误</p>
		<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
		<label>
			<input type="text" name="user_nicename"  id="adm_usename" placeholder="用户名" class="txt_input "/>
		</label>
		<label>
			<input type="password" name="user_pass" id="adm_ps" placeholder="密码" class="txt_input" />
		</label>
		<label>
			<button type="button" id="login_sub_but" class="sub_button">登录</button>
		</label>
	</form>
</div>

	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/login.js"></script>
</body>
</html>