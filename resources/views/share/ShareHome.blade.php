<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>网页分享 - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/sweet-alert.css">
	<link rel="stylesheet" type="text/css" href="/css/share_page.css" >
	<script type="text/javascript" src="/js/jquery.min.js"></script>
</head>
<body>
	<div id="hold" class="normal_bk"></div>
	<div id="wait_area">
	<div id="wait_info" align="center">
			<img src='/css/img/wait.gif' alt='请稍等' id='wait_img'/>
			<p>正在进行处理，请稍等</p>
		</div>
	</div>
	<script type="text/javascript">
		$("#wait_area").hide();
	</script>
	<!-- 网页首部 -->
	<div id='header'>
		<a href='/' class='head_logo'><img src='/css/img/logo.png' alt='iShare 网址分享' id='logo_img'/></a>
		<div class='menu_but'></div>	
		
		<!-- 分享进度条 -->
		<ul id='share_step_bar'>
			<li id='share_step_1' style="color: #fff;border-bottom: 3px solid #E7C951;">1、请选择话题，可多选</li>
			<li id='share_step_2' style="color: #fff;bborder-bottom: 3px solid #00A2CA;">2、请填写网址与网址描述</li>
		</ul>

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
			<a href='/share' id='local_page'>
				<li>
				<img src='/css/img/head_list_share.png' alt='' class='head_list_img'/>我要分享</li>
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
			<a class='other_page_seach'><li>
				<form action="{{ URL('/syshide/normal_search') }}" target='_blank' method='post'>
					<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
					<input type='text' name='search' class='search' placeholder=""/>
					<input type='submit' id='searchbut' value='' title="点击搜索"/>
				</form>
			</li></a>
		</ul>
		<div class='menu_but_click'></div>
		<script type="text/javascript" src="/js/main_menu.js"></script>
	</div>

	<!-- 网页主体 -->
	<div id="main_bk">
		
		<!-- 话题选择 -->
		<div id='topic_choice'>
			<ul class="topic_ground_list">
				@foreach ($info['topics_info'] as $topics)
					<li>
						<input type='checkbox' name='topics[]' value='{{ $topics->id }}' id='topic{{ $topics->id }}' alt='{{ $topics->name }}' class='topics_box_share'/>
						<label for='topic{{ $topics->id }}' class='label_share' title='点击选择话题"{{ $topics->name }}"'  value='{{ $topics->id }}'>
							<img src='{{ $topics->topics_img }}' alt='{{ $topics->name }}' class='topics_img_share' id="topics_img_share_id_{{ $topics->id }}"/>
							<p>{{ $topics->name }}</p>
						</label>
					</li>
				@endforeach
			</ul>
			<input type='hidden' id='choice_count' value='0'>
			<button type="button" id="next_step_but_id" class="next_step_but">下一步</button>
		</div>

		<!-- 提交表单 -->
		<div id='share_form'>
			<!-- 选择按钮 -->
			<div style="text-align:center">
				<button class="red_button" id="defaule_model_but" title="" value="">系统默认方式压缩</button>
				<button class="green_button" id="personal_model_1_but" title="" value="">自定义压缩1</button>
				<button class="green_button" id="personal_model_2_but" title="" value="">自定义压缩2</button>
			</div>

			<!-- 选择话题显示 -->
			<label><p id='topics_choiced'>话题</p></label>
			<div class="submit_form_area">
				<p class='error' id='url_error'>这里是空的</p>
				<input type="text" name="submit_url" placeholder="请输入网址" class="submiturl" id="submit_url"/>

				<p class='error' id='per_error'>这里是空的</p>
				<input type="text" name="per_info" placeholder="" class="submiturl" id="per_info"/>

				<p class='error' id='des_error'>这里是空的</p>
				<p id='des_count'></p>
				<textarea name="submit_url_desc" height='20' width='50' class="submiturl_desc" placeholder="您可以给网址添加描述（400字以内）" id="submit_url_desc"></textarea>

				<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
				<button type="button" id="submit_but" value="default_model">提交</button>
			</div>

			<!-- 默认模式表单 -->
			<div class="submit_info_area" id="default_submit_form_area">
				<h1>系统默认方式压缩说明</h1>
				<p>* 您提交的网址会被系统自动压缩成短网址</p>
				<p>* 短网址长度至少是6位</p>
				<p>* 短网址由大写英文字母，小写英文字母和数字组成</p>
				<p>* 短网址前4位包含网址所属网站信息，<span style="font-weight:bold">同一域名下的短网址前4位相同</span></p>
			</div>

			<!-- 自定义1模式表单 -->
			<div class="submit_info_area" id="personal_submit_form_area_1">
				<h1>自定义1模式说明</h1>
				<p>* 您提交的网址会被系统自动压缩成短网址</p>
				<p>* 系统生成前4位，用户自定义第5位及之后位的方式压缩网址</p>
				<p>* 短网址前4位包含网址所属网站信息，同一域名下的短网址前4位相同</p>
				<p>* 短网址前4位由大写英文字母，小写英文字母和数字组成</p>
				<p>* 您可以自己定义短网址后缀，但<span style="color:red">输入后缀最小长度为4位</span>，一个汉字占2位</p>
				<p>* 您可以输入a~z，A~Z，0~9，以及<span style="color:red">汉字</span>作为您的短网址后缀</p>
				<p>* <span style="font-weight:bold">请不要使用其他字符，因为这可能使您的短网址不可用</span></p>
			</div>
			
			<!-- 自定义2模式表单 -->
			<div class="submit_info_area" id="personal_submit_form_area_2">
				<h1>自定义2模式说明</h1>
				<p>* 您可以自己定义您的短网址，包括短地址的前4位</p>
				<p>* 要求自定义的短地址<span style="color:red">最小长度为10位</span>，一个汉字占2位</p>
				<p>* 您可以输入a~z，A~Z，0~9，以及<span style="color:red">汉字</span>作为您的短网址后缀</p>
				<p>* <span style="font-weight:bold">请不要使用其他字符，因为这可能使您的短网址不可用</span></p>
			</div>
		</div>

		<!-- 按钮区域 
		<div id='step_but_area'>
			<button type="button" id="submit_but" value="default_model">提交</button>
			<input type="hidden" id="now_step_num" value='1' />
			<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
		</div>-->
	</div>
	<div class='totop'>
	</div>
	<div class='refurbish'>
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
	<script type="text/javascript" src="/js/sweet-alert.min.js"></script>
	<script type="text/javascript" src="/js/share_step.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$(".totop").click(function() {
				$('body,html').animate({scrollTop:0},1000);
			});	
			$(".refurbish").click(function() {
				self.location.reload();
			});	
		});	
	</script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit_url_des").keydown(function(){
				var input_str = $('#submit_url_des').val();
				var inpot_count = input_str.length;
				$("#des_count").html(inpot_count + "/400");

				if(inpot_count >= 400)
				{
					$('#submit_url_des').val(input_str.slice(0,399));
				}
			});
		});
	</script>

</body>
</html>