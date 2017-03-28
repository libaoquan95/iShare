$(document).ready(function(){
	$('.error').hide();

	//	设置上传标志
	var sub_email_state = false;
	var sub_name_state = false;
	var sub_pass_state = false;
	var sub_code_state = true;

	var email   = $('#input_emile').val();
	var name    = $('#input_name').val();
	var pass    = $('#input_pass').val();
	var pass_rq = $('#input_pass_rq').val();
	var _token = $('#_token').val();

	//	邮箱格式
	$("#input_email").blur(function(){
		email = $('#input_email').val();
		var reg = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;

		if (email != '') {
			if (!reg.test(email)) { 
				$('#err_emile').show();
				$('#input_email').focus();
				sub_email_state = false;
			}
			else {
				$('#err_emile').hide();
				sub_email_state = true;
			}
		}

		$.post("/auth/register_sumbit_user_email",
		{
			user_email:email,
			_token:_token
		},
		function(data,status)
		{
			//	邮箱信息存在
			if(data == "1")
			{
				$('#rep_emile').show();
				$('#input_email').focus();
				sub_email_state = false;
			}
			else
			{
				$('#rep_emile').hide();
				sub_email_state = true;
			}
		});
	});

	//	密码
	$("#input_pass").blur(function(){
		pass = $('#input_pass').val();

		if(pass != pass_rq) {
			$('#err_pass').show();
			$('#input_pass_rq').focus();
			sub_pass_state = false;
		}
		else
		{
			$('#err_pass').hide();
			sub_pass_state = true;
		}
	});
	//	确认密码
	$("#input_pass_rq").blur(function(){
		pass_rq = $('#input_pass_rq').val();

		if(pass != pass_rq) {
			$('#err_pass').show();
			$('#input_pass').focus();
			sub_pass_state = false;
		}
		else
		{
			$('#err_pass').hide();
			sub_pass_state = true;
		}
	});
	
	//	用户名格式
	$("#input_name").blur(function(){
		name = $('#input_name').val();
		$.post("/auth/register_sumbit_user_name",
		{
			user_name:name,
			_token:_token
		},
		function(data,status)
		{
			//	邮箱信息存在
			if(data == "1")
			{
				$('#req_usname').show();
				$('#input_name').focus();
				sub_name_state = false;
			}
			else
			{
				$('#req_usname').hide();
				sub_name_state = true;
			}
		});
	});

	//	邀请码
	/*$("#input_code").blur(function(){
		code = $('#input_code').val();

		if(code != "iShare") {
			$('#err_invite').show();
			$('#input_code').focus();
			sub_code_state = false;
		}
		else
		{
			$('#err_invite').hide();
			sub_code_state = true;
		}
	});*/

	//	注册按钮
	$("#register_sub_but").click(function(){
		if(sub_email_state == true && sub_name_state == true && sub_pass_state == true)
		{
			user_nicename = $('#input_name').val();
			user_pass = $('#input_pass').val();
			user_email = $('#input_email').val();

			$.post("/auth/register_sumbit",
			{
				user_nicename:user_nicename,
				user_pass:user_pass,
				user_email:user_email,
				_token:_token
			},
			function(data,status)
			{
				swal({
						title: "注册成功",
						text: "点击跳转至首页",
						type: "success"
					},
				function(){
					window.location.href='/';
				});
			});
		}
	});
});