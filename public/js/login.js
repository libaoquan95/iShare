$(document).ready(function(){
	$('.error').hide();

	//	登陆按钮
	$("#login_sub_but").click(function(){
		var name    = $('#adm_usename').val();
		var pass    = $('#adm_ps').val();
		var _token = $('#_token').val();

		$.post("/auth/login_sumbit",
			{
				user_nicename:name,
				user_pass:pass,
				_token:_token
			},
			function(data,status)
			{
				if(data == "1")
				{
					window.location.href='/';
				}
				else
				{
					$('.error').show();
				}
			});
	});
});