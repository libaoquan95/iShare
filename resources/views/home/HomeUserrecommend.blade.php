@foreach ($recommend_info as $recommend)
	<p class='recommend_url'>
		<img src='/css/img/a.png' class='a_img'>
		<a href="/to/{{ $recommend->short_url }}" title="为您推荐 点击查看" target='_blank'>{{ $recommend->url_describe }}</a>
	</p>
@endforeach