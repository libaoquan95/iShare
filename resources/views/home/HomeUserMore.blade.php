@foreach ($info as $userurl)
<!-- 展示区 -->
<div class='url_desc_block'>
	<!-- 显示用户信息 -->
	<p class='url_user'>
		<span class='index_menu' value="{{ $userurl['bu_userurls']->short_url }}">
			<img src='/css/img/index_menu.png' title='' alt=''/>
		</span>
		@foreach ($userurl['user'] as $user)
		<a href='/user/{{ $user->name }}'><img src='{{ $user->user_img }}'	title='用户：{{ $user->name }}' alt='{{ $user->name }}' class='users_img' align="middle"/></a>
		<span>{{ $user->name }}的分享</span>
		@endforeach
		
		<!-- 显示网址话题 -->
		<ul class='topic_img_list'>
			@foreach ($userurl['topics'] as $topic)
				<li><a href='/topics/{{ $topic->name }}'>
				<img src='{{ $topic->topics_img }}'	title='话题：{{ $topic->name }}' alt='{{ $topic->name }}' class='topics_img'/>
				</a></li>
			@endforeach
		</ul>

		<!-- 显示举报信息 -->
		<ul class='index_menu_list' id="index_menu_list_{{ $userurl['bu_userurls']->short_url }}">
			<li class=''>您可以向我们反映！</li>
			<li class="index_menu_item" alt="item_invalid" value="{{ $userurl['bu_userurls']->short_url }}" name="{{ $user->name }}">这是一个无效链接</li>
			<li class="index_menu_item" alt="item_harmful" value="{{ $userurl['bu_userurls']->short_url }}" name="{{ $user->name }}">这是一个不良链接</li>
			<script type="text/javascript">
				$(".index_menu_list").hide();
			</script>
		</ul>
	</p>
	<!-- 显示网址描述 -->
	<p class='url_desc'>
		{{ $userurl['bu_userurls']->url_describe }}
	</p>
	<!-- 显示网址 -->
	<p class='url_code'>
		<img src='/css/img/erweima.png' class='a_img' value="{{ getenv('LOCAL_DOMAIN_NAME') }}/to/{{ $userurl['bu_userurls']->short_url }}" name="{{ $userurl['bu_userurls']->short_url }}" title='显示连接二维码'>
		<a href='/to/{{ $userurl["bu_userurls"]->short_url }}' target='_blank' title='点击跳转'>{{ getenv('LOCAL_DOMAIN_NAME') }}/to/{{ $userurl['bu_userurls']->short_url }}</a>
		@if($userurl['white_list'] == 1)
			<img src='/css/img/safe.png' class='a_img' title='已验证短网址：{{$userurl['white_title']}}'>
		@endif
	</p>
	<p id="erweima_area_{{ $userurl['bu_userurls']->short_url }}" class="erweima_area">
	</p>
	<!-- 显示访问量 -->
	<p class='url_pv_info' title="访问量">
		访问量<br />
		@if ( $userurl['bu_userurls']->url_pv > 10000)
			<span>{{ $userurl['bu_userurls']->url_pv/10000 }}万</span>
		@else
			<span>{{ $userurl['bu_userurls']->url_pv }}</span>
		@endif
	</p>
	<!-- 显示访问时间 -->
	<p class='url_time_info'>
		<span>创建于：{{ $userurl['bu_userurls']->created_at }}</span>
		<span>上次访问：{{ $userurl['bu_userurls']->updated_at }}</span>
	</p>
</div>
@endforeach