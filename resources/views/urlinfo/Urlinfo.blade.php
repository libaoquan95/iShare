<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>短网址信息/{{ $info['userurl']->short_url }} - iShare 网址压缩分享平台</title>

	<link rel="stylesheet" type="text/css" href="/css/header_footer.css" >
	<link rel="stylesheet" type="text/css" href="/css/url_info.css" >
	<script type="text/javascript" src="/js/jquery.min.js"></script>
	<script type="text/javascript" src="/js/highcharts.js"></script>
	<script type="text/javascript" src="/js/highmaps_map.js"></script>
	<script type="text/javascript" src="/js/mycharts.js"></script>
	<script type="text/javascript" src="/js/maps_modules_data.js"></script>
	<script type="text/javascript" src="/js/map_cn-all-sar-taiwan.js"></script>
</head>
<body>
	<div id="hold" class="normal_bk"></div>
	<!-- 网页首部 -->
	<div id='header'>
		<a href='/' class='head_logo'><img src='/css/img/logo.png' alt='iShare 网址分享' id='logo_img'/></a>
		<div class='menu_but'></div>

		@if (Session::has('auth_state'))
		<ul class='menu_list'>
			<li class='head_user_info'>
				Welcome {{Session::get('user_name')}}
			</li>
			<li class='head_user_img_li'>
				<img src='{{$info['user_img_2']}}' class='head_user_img'/>
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
			<a class='other_page_seach'><li>
				<form action="{{ URL('/syshide/normal_search') }}" target='_blank' method='post'>
					<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
					<input type='text' name='search' class='search' placeholder=""/>
					<input type='submit' id='searchbut' value='' title="点击搜索"/>
				</form>
			</li></a>
		</ul>
		@else
		<ul class='menu_list'>
			<li class='head_user_info'>
				Welcome 游客
			</li>
			<a href='/' class='other_page'>
				<li>
				<img src='/css/img/head_list_home_blue.png' alt='' class='head_list_img'/>首页</li>
			</a>
			<a href='/auth/login' class='other_page'>
				<li>
				<img src='/css/img/head_list_login_blue.png' alt='' class='head_list_img'/>我要登录</li>
			</a>
			<a href='auth/register' class='other_page'>
				<li>
				<img src='/css/img/head_list_register_blue.png' alt='' class='head_list_img'/>加入我们</li>
			</a>
			<a class='other_page_seach'><li>
				<form action="{{ URL('/syshide/normal_search') }}" target='_blank' method='post'>
					<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
					<input type='text' name='search' class='search' placeholder=""/>
					<input type='submit' id='searchbut' value='' title="点击搜索"/>
				</form>
			</li></a>
		</ul>
		@endif
		<div class='menu_but_click'></div>
		<script type="text/javascript" src="/js/main_menu.js"></script>
	</div>

	<!-- 网页主体 -->
	<div id='main_bk'>
		<!-- 主体 -->
		<div id='main'>
			<div class='url_code'>
				<!-- 网址基本信息 -->
				<table class='url_base_info_sheet'>
					<tr>
						<td class='url_base_info_sheet_head'>网址描述</td>
						<td class='url_base_info_sheet_cont'>{{ $info['userurl']->url_describe }}</td>
					</tr>
					<tr>
						<td class='url_base_info_sheet_head'>原地址</td>
						<td class='url_base_info_sheet_cont'>{{ $info['userurl']->long_url }}</td>
					</tr>
					<tr>
						<td class='url_base_info_sheet_head'>短地址</td>
						<td class='url_base_info_sheet_cont'>{{ $info['userurl']->short_url }}</td>
					</tr>
					<tr>
						<td class='url_base_info_sheet_head'>分享用户</td>
						<td class='url_base_info_sheet_cont'>
							<img src="{{ $info['user_img'] }}" title="用户：{{ $info['user_name'] }}" alt="{{ $info['user_img'] }}" class="users_img_samll" align="middle"/>
						</td>
					</tr>
					<tr>
						<td class='url_base_info_sheet_head'>网址状态</td>
						@if ($info['userurl']->url_status == 0)
							<td class='url_base_info_sheet_cont'>正常</td>
						@elseif ($info['userurl']->url_status == 1)
							<td class='url_base_info_sheet_cont'>暂停分享</td>
						@endif
					</tr>
					<tr>
						<td class='url_base_info_sheet_head'>创建时间</td>
						<td class='url_base_info_sheet_cont'>{{ $info['userurl']->created_at }}</td>
					</tr>
					<tr>
						<td class='url_base_info_sheet_head'>最近一次访问时间</td>
						<td class='url_base_info_sheet_cont'>{{ $info['userurl']->updated_at }}</td>
					</tr>
					<tr>
						<td class='url_base_info_sheet_head'>所属话题</td>
						<td class='url_base_info_sheet_cont'>
							<ul class='topic_img_list'>
								@foreach ($info['topics'] as $topic)
									<li>
										<a href='/topics/{{ $topic->name }}'>
										<img src='{{ $topic->topics_img }}'	title='话题：{{ $topic->name }}' alt='{{ $topic->name }}' class='topics_img'/></a>
									</li>
								@endforeach
							</ul>
						</td>
					</tr>
				</table>
			</div>

			<!-- 总访问量 -->
			<div class='hiden_aera'>
				<input type="hidden" name="注册用户" id="" value="{{ $info['userurl']->user_url_pv }}" class="visit_user_statu">
				<input type="hidden" name="游客" id="" value="{{ $info['userurl']->url_pv - $info['userurl']->user_url_pv }}" class="visit_user_statu">
				<input type="hidden" name="总访问量" id="" value="{{ $info['userurl']->url_pv }}" class="visit_user_statu">
			</div>

			<div class='charts_area'>
				<div id="visit_user_statu_count_charts_col"></div>
				<div id="visit_user_statu_count_charts_pie" class="pie_charts"></div>
			</div>

			<!-- 单日 单月访问量 -->
			<div class='hiden_aera'>
				@foreach($info['days_pv'] as $days_pv)
					<input type="hidden" name="{{ $days_pv->visit_year }}-{{ $days_pv->visit_month }}-{{ $days_pv->visit_day }}" id="" value="{{ $days_pv->url_pv }}" src="{{ $days_pv->visit_month }}" class="days_pv">
				@endforeach
				<input type="hidden" name="{{ $info['today_month'] }}" id="today_month" value="{{ $info['today_month'] }}">

				@foreach($info['months_pv'] as $month =>  $months_pv)
					<input type="hidden" name="{{ $month }}月" id="" value="{{ $months_pv }}" class="months_pv">
				@endforeach
			</div>
			<div class='charts_area'>
				<select id="select_id">
				@for($i=1;$i<$info['today_month']; $i++)
					<option value ="{{ $i }}" class="mouth_select">{{ $i }}月</option>
				@endfor
					<option value ="{{ $i }}" selected="selected" class="mouth_select">{{ $i }}月</option>
				</select>
				<div id="visit_days_pv_count_charts_line"></div>
				<div id="visit_months_pv_count_charts_line"></div>
			</div>

			<!-- 访问操作系统 -->
			<div class='hiden_aera'>
				<input type="hidden" name="windows" id="" value="{{ $info['userurl']->OS_windows }}" class="OS_info">
				<input type="hidden" name="mac" id="" value="{{ $info['userurl']->OS_mac }}" class="OS_info">
				<input type="hidden" name="ipod" id="" value="{{ $info['userurl']->OS_ipod }}" class="OS_info">
				<input type="hidden" name="ipad" id="" value="{{ $info['userurl']->OS_ipad }}" class="OS_info">
				<input type="hidden" name="iphone" id="" value="{{ $info['userurl']->OS_iphone }}" class="OS_info">
				<input type="hidden" name="android" id="" value="{{ $info['userurl']->OS_android }}" class="OS_info">
				<input type="hidden" name="unix" id="" value="{{ $info['userurl']->OS_unix }}" class="OS_info">
				<input type="hidden" name="linux" id="" value="{{ $info['userurl']->OS_linux }}" class="OS_info">
				<input type="hidden" name="windows_phone" id="" value="{{ $info['userurl']->OS_windows_phone }}" class="OS_info">
				<input type="hidden" name="other" id="" value="{{ $info['userurl']->OS_other }}" class="OS_info">
			</div>

			<div class='charts_area'>
				<div id="OS_count_charts_col"></div>
				<div id="OS_count_charts_pie" class="pie_charts"></div>
			</div>

			<!-- 访问地址 -->
			<div class='hiden_aera'>
				<div id="china_map">
					<input type="hidden" name="北京" id="cn-bj" value="{{ $info['userurl']->province_beijing }}" class="province_name">
					<input type="hidden" name="上海" id="cn-sh" value="{{ $info['userurl']->province_shanghai }}" class="province_name">
					<input type="hidden" name="天津" id="cn-tj" value="{{ $info['userurl']->province_tianjin }}" class="province_name">
					<input type="hidden" name="重庆" id="cn-cq" value="{{ $info['userurl']->province_chongqing }}" class="province_name">
					<input type="hidden" name="河北" id="cn-hb" value="{{ $info['userurl']->province_hebei }}" class="province_name">
					<input type="hidden" name="山西" id="cn-sx" value="{{ $info['userurl']->province_shanxi }}" class="province_name">
					<input type="hidden" name="内蒙古" id="cn-nm" value="{{ $info['userurl']->province_neimenggu }}" class="province_name">
					<input type="hidden" name="辽宁" id="cn-ln" value="{{ $info['userurl']->province_liaoning }}" class="province_name">
					<input type="hidden" name="吉林" id="cn-jl" value="{{ $info['userurl']->province_jilin }}" class="province_name">
					<input type="hidden" name="黑龙江" id="cn-hl" value="{{ $info['userurl']->province_heilongjiang }}" class="province_name">
					<input type="hidden" name="江苏" id="cn-js" value="{{ $info['userurl']->province_jiangsu }}" class="province_name">
					<input type="hidden" name="浙江" id="cn-zj" value="{{ $info['userurl']->province_zhejiang }}" class="province_name">
					<input type="hidden" name="安徽" id="cn-ah" value="{{ $info['userurl']->province_anhui }}" class="province_name">
					<input type="hidden" name="福建" id="cn-fj" value="{{ $info['userurl']->province_fujian }}" class="province_name">
					<input type="hidden" name="江西" id="cn-jx" value="{{ $info['userurl']->province_jiangxi }}" class="province_name">
					<input type="hidden" name="山东" id="cn-sd" value="{{ $info['userurl']->province_shandong }}" class="province_name">
					<input type="hidden" name="河南" id="cn-he" value="{{ $info['userurl']->province_henan }}" class="province_name">
					<input type="hidden" name="湖北" id="cn-hu" value="{{ $info['userurl']->province_hubei }}" class="province_name">
					<input type="hidden" name="湖南" id="cn-hn" value="{{ $info['userurl']->province_hunan }}" class="province_name">
					<input type="hidden" name="广东" id="cn-gd" value="{{ $info['userurl']->province_guangdong }}" class="province_name">
					<input type="hidden" name="广西" id="cn-gx" value="{{ $info['userurl']->province_guangxi }}" class="province_name">
					<input type="hidden" name="海南" id="cn-ha" value="{{ $info['userurl']->province_hainan }}" class="province_name">
					<input type="hidden" name="四川" id="cn-sc" value="{{ $info['userurl']->province_sichuan }}" class="province_name">
					<input type="hidden" name="贵州" id="cn-gz" value="{{ $info['userurl']->province_guizhou }}" class="province_name">
					<input type="hidden" name="云南" id="cn-yn" value="{{ $info['userurl']->province_yunnan }}" class="province_name">
					<input type="hidden" name="陕西" id="cn-sa" value="{{ $info['userurl']->province_shaanxi }}" class="province_name">
					<input type="hidden" name="甘肃" id="cn-gs" value="{{ $info['userurl']->province_gansu }}" class="province_name">
					<input type="hidden" name="青海" id="cn-qh" value="{{ $info['userurl']->province_qinghai }}" class="province_name">
					<input type="hidden" name="宁夏" id="cn-nx" value="{{ $info['userurl']->province_ningxia }}" class="province_name">
					<input type="hidden" name="新疆" id="cn-xj" value="{{ $info['userurl']->province_xinjiang }}" class="province_name">
					<input type="hidden" name="西藏" id="cn-xz" value="{{ $info['userurl']->province_xizang }}" class="province_name">
					<input type="hidden" name="香港" id="cn-hk" value="{{ $info['userurl']->province_xianggang }}" class="province_name">
					<input type="hidden" name="澳门" id="cn-macau" value="{{ $info['userurl']->province_aomen }}" class="province_name">
					<input type="hidden" name="台湾" id="tw-tw" value="{{ $info['userurl']->province_taiwan }}" class="province_name">
					<input type="hidden" name="其它地区" id="other" value="{{ $info['userurl']->province_other }}" class="province_name">
				</div>
			</div>
			<div class='charts_area'>
				<div id="visit_user_province_count_charts_col"></div>
				<div id="visit_user_province_count_charts_pie" class="pie_charts"></div>
				<div id="demo-wrapper">
					<div id="mapBox">
						<div id="up"></div>
						<div id="visit_user_province_count_charts_map" style="height: 600px"></div> 
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id='footer'>
		@if (Session::has('auth_state'))
		<ul>
			<li><a href='/' '>首页</a></li>
			<li><a href='/messageboard/'>留言板</a></li>
			<li><a href='/download/iShare.apk'>下载Android客户端</a></li>
			<li><a href='/download/iShare.ipa'>下载IOS客户端</a></li>
			<li><a href='/aboutus'>关于我们</a></li>
		</ul>
		@else
		<ul>
			<li><a href='/' '>首页</a></li>
			<li><a href='/auth/login'>我要登录</a></li>
			<li><a href='/auth/register'>我要注册</a></li>
		</ul>
		@endif
		<p class='copyright_info'>Copyright &copy; iShare网址分享平台 2015-2016</p>
	</div>
	<div class='totop'>
	</div>
	<div class='refurbish'>
	</div>
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
</body>
</html>