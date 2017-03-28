$(document).ready(function(){
	//	初始化
	$('body,html').animate({scrollTop:0},10);
	$("#topic_choice").show();
	$("#share_form").hide();
	$("#personal_submit_form_area_1").hide();
	$("#personal_submit_form_area_2").hide();
	$("#per_info").hide();
	$(".error").hide();

	//	下一步按钮不可用
	$("#next_step_but_id").attr("disabled","disabled");
	$("#next_step_but_id").addClass("disabled_but");
	$("#next_step_but_id").removeClass("next_step_but");

	//	选择话题后使下一步按钮可用
	$('.label_share').click(function() {
		var val = $(this).attr('value');
		var img_id = '#topics_img_share_id_' + val;
		$(img_id).toggleClass('topics_img_share_click');

		var choice_count = 1;
		$('input:checkbox[name="topics[]"]:checked').each(function(){
			choice_count++;
		});

		if(choice_count >=5 )
		{
			swal({
				title: "最多可以选择5个话题信息",
				text: "多余的不会被记录",
				type: "warning",
				confirmButtonColor: '#DD6B55',
				confirmButtonText: '知道了'
			},
			function(){
				$('.topics_box_share').each(function() {
					$(this).attr('disabled','disabled');
				});
			});
		}

		//	下一步按钮可用
		$("#next_step_but_id").removeAttr("disabled");
		$("#next_step_but_id").removeClass("disabled_but");
		$("#next_step_but_id").addClass("next_step_but");
	});

	//	下一步按钮被点击
	$("#next_step_but_id").click(function() {
		$("#topic_choice").slideUp();
		$("#share_form").slideDown();
		
		//	修改step
		$("#share_step_1").attr("style","color: #fff;border-bottom: 3px solid #00A2CA;");
		$("#share_step_2").attr("style","color: #fff;border-bottom: 3px solid #E7C951;");

		//	显示选择话题
		var topics = '已选话题：';
		$('input:checkbox[name="topics[]"]:checked').each(function(){
			var str = $(this).attr("alt") + " ";
			topics += str;
		});
		$('#topics_choiced').html(topics);

		$('body,html').animate({scrollTop:0},10);
	});

	//	压缩模式切换
	$("#defaule_model_but").click(function(){
		$("#personal_submit_form_area_1").slideUp();
		$("#personal_submit_form_area_2").slideUp();
		$("#default_submit_form_area").slideDown();
		$("#per_info").hide();

		$(this).addClass("red_button");
		$(this).removeClass("green_button");
		$("#personal_model_1_but").addClass("green_button");
		$("#personal_model_1_but").removeClass("red_button");
		$("#personal_model_2_but").addClass("green_button");
		$("#personal_model_2_but").removeClass("red_button");

		$("#submit_but").attr("value","default_model");
	});
	$("#personal_model_1_but").click(function(){
		$("#default_submit_form_area").slideUp();
		$("#personal_submit_form_area_2").slideUp();
		$("#personal_submit_form_area_1").slideDown();
		$("#per_info").show();
		$("#per_info").attr("placeholder","请输入您的短网址后缀");

		$(this).addClass("red_button");
		$(this).removeClass("green_button");
		$("#defaule_model_but").addClass("green_button");
		$("#defaule_model_but").removeClass("red_button");
		$("#personal_model_2_but").addClass("green_button");
		$("#personal_model_2_but").removeClass("red_button");

		$("#submit_but").attr("value","personal_model_1");
	});
	$("#personal_model_2_but").click(function(){
		$("#default_submit_form_area").slideUp();
		$("#personal_submit_form_area_1").slideUp();
		$("#personal_submit_form_area_2").slideDown();
		$("#per_info").show();
		$("#per_info").attr("placeholder","请输入您自定义的短网址");

		$(this).addClass("red_button");
		$(this).removeClass("green_button");
		$("#defaule_model_but").addClass("green_button");
		$("#defaule_model_but").removeClass("red_button");
		$("#personal_model_1_but").addClass("green_button");
		$("#personal_model_1_but").removeClass("red_button");

		$("#submit_but").attr("value","personal_model_2");
	});

	//	提交按钮被点击
	$("#submit_but").click(function() {
		//	得到压缩模式
		var submit_model = $('#submit_but').attr("value");
		//	得到提交的网址
		var submit_url = $('#submit_url').val();
		//	得到提交的网址描述
		var submit_url_desc = $('#submit_url_desc').val()
		//	得到用户自定义以信息;
		var personal_sub_info = $('#per_info').val();
		var _token = $('#_token').val();
		
		//	检查
		if(submit_url != '' && submit_url_desc != '' )
		{
			$("#url_error").hide();
			$("#des_error").hide();
			
			var sub_state = false;
			//var str_count = personal_sub_info.length;
			var str_count = len(personal_sub_info);

			switch(submit_model)
			{
			//	默认
			case "default_model":
				sub_state = true;
				break;
			//	自定义1
			case "personal_model_1":
				if(str_count >= 4)
					sub_state = true;
				else
					sub_state = false;
				break;
			//	自定义2
			case "personal_model_2":
				if(str_count >= 10)
					sub_state = true;
				else
					sub_state = false;
				break;
			}

			if(sub_state == true)
			{
				//	得到话题选中序列
				var topics = {};
				var i = 0;
				$('input:checkbox[name="topics[]"]:checked').each(function(){
					topics[i] = $(this).val();
					i++;
				});
				$("#wait_area").show();
				//	提交
				$.post("/syshide/url_submit",
				{
					submit_model:submit_model,
					submit_url:submit_url,
					submit_url_desc:submit_url_desc,
					personal_sub_info:personal_sub_info,
					topics:topics,
					_token:_token
				},
				function(data,status)
				{
					$("#wait_area").hide();
					//alert(data);
					//	网址格式错误
					if(data == "#error_01#")
					{
						swal({
							title: "错误网址格式！无法转换！",
							text: "请输入正确的网址！",
							type: "error"
						});
					}
					//	提交重复网址
					else if(data == "#error_02#")
					{
						swal({
							title: "此网址已有人分享了！",
							text: "您可以在此网站搜索此网址",
							type: "error"
						});
					}
					//	无效网络资源
					else if(data == "#error_03#")
					{
						swal({
							title: "此网页经系统检测为无效网页",
							text: "请确认输入网址是否出错，你也可以在留言板给我们留下建议",
							type: "error"
						});
					}
					//	重复短地址
					else if(data == "#error_04#")
					{
						swal({
							title: "您的输入短地址已被占用",
							text: "请重新输入",
							type: "error"
						});
					}
					//	网址转换成功
					else
					{
						swal({
							title: "网址转换成功，快去分享吧！",
							text: data,
							type: "success"
						},
						function(){
							window.location.reload();
						});
					}
				});
			}
			else
			{
				swal({
					title: "您输入的自定义信息小于规定的长度",
					text: "请重新输入",
					type: "warning"
				});
			}
		}
		if(submit_url == '' && submit_url_desc != '' )
		{
			$("#url_error").show();
			$("#des_error").hide();
		}
		if(submit_url != ''  && submit_url_desc == '' )
		{
			$("#url_error").hide();
			$("#des_error").show();
		}
		if(submit_url == ''  && submit_url_desc == '' )
		{
			$("#url_error").show();
			$("#des_error").show();
		}
	});

	//	判断字符串位数
	function len(s) { 
		var l = 0; 
		var a = s.split(""); 
		for (var i=0;i<a.length;i++) 
		{ 
			if (a[i].charCodeAt(0)<299) 
			{ 
				l++; 
			} 
			else 
			{ 
				l+=2; 
			} 
		} 
		return l; 
	}
});