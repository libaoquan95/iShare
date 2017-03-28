<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>修改密码 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/login.css" >
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
</head>
<body>

<div id="adm_login">
	<a href='/' class='head_logo'><img src="/css/img/logo_blue.png" alt="iShare 网址分享" id='logo_img'/></a>
	<form>
		<p class='error' id="error_ps">密码错误</p>
		<input type="hidden" id="user_name" name="user_name" value="{{Session::get('user_name')}}">
		<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
		<label>
			<input type="password" name="user_pass" id="adm_ps" placeholder="原密码" class="txt_input" />
		</label>
		<p class='error' id='error_ps_confirm'>两次密码输入不一致</p>
		<label>
			<input type="password" name="user_new_pass" id="adm_new_ps" placeholder="新密码" class="txt_input" />
		</label>
		<label>
			<input type="password" name="user_confirm_pass" id="adm_confirm_ps" placeholder="确认新密码" class="txt_input" />
		</label>
		<label>
			<button type="button" id="login_sub_but" class="sub_button">确定修改</button>
		</label>
	</form>
</div>

	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="/js/alertPassword.js"></script>
</body>
</html>