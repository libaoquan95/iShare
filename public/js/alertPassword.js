$(document).ready(function(){
	$('.error').hide();

	//	按钮
	$("#login_sub_but").click(function(){
		var pass           = $('#adm_ps').val();
		var adm_new_ps     = $('#adm_new_ps').val();
		var adm_confirm_ps = $('#adm_confirm_ps').val();
		var user_name      = $('#user_name').val();
		var _token         = $('#_token').val();

		if(pass!='' && adm_new_ps!='' && adm_confirm_ps!='')
		{
			if(adm_new_ps == adm_confirm_ps)
			{
				$.post("/auth/alert_password_sumbit",
				{
					user_pass:pass,
					user_new_pass:adm_new_ps,
					user_name:user_name,
					_token:_token
				},
				function(data,status)
				{
					if(data == "1")
					{
						swal({
							title: "密码修改成功！",
							text: "点击跳转，请重新登陆！",
							type: "success"
						},
						function(){
							window.location.href='/auth/login';
						});
					}
					else
					{
						$('#error_ps').show();
					}
				});
			}
			else
			{
				$('#error_ps_confirm').show();
			}
		}
	});
});