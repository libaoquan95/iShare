@foreach ($info['message_info'] as $messages)
<li>
	<time class="cbp_tmtime" datetime="{{ $messages['created_at'] }}">
		<span>{{ $messages['created_at_date'] }}</span>
		<span>{{ $messages['created_at_time'] }}</span>
	</time>
	<div class="cbp_tmicon">
		@if($messages['messages']->state == 0)
			<span class='audit_atate'><img src="/css/img/mess_help.png" title="待审核"/></span>
		@elseif($messages['messages']->state == 1)
			<span class='audit_atate'><img src="/css/img/mess_info.png" title="待解决"/></span>
		@elseif($messages['messages']->state == 2)
			<span class='audit_atate'><img src="/css/img/mess_success.png" title="已解决"/></span>
		@elseif($messages['messages']->state == 3)
			<span class='audit_atate'><img src="/css/img/mess_error.png" title="未解决"/></span>
		@endif
	</div>
	<div class="cbp_tmlabel">
		<h2>
			<img src="{{ $messages['user_img'] }}" class="mess_user_img" />
			{{ $messages['messages']->user }}
		</h2>
		<p>{{ $messages['messages']->message }}</p>
	</div>
</li>
@endforeach
