$(function () {
	$(document).ready(function () {
		$('#add_topic_area').hide();
		$('#load_topic_page_area').hide();

		$('#add_topic').click(function() {
			$('#add_topic_area').slideToggle();
		});
		$('#load_topic_page').click(function() {
			$('#load_topic_page_area').slideToggle();
		});
		$('#load_topic_hover_page').click(function() {
			$('#load_topic_page_area').slideUp();
		});
		
		var options =
		{
			thumbBox: '.thumbBox',
			spinner: '.spinner',
			imgSrc: '/css/img/default_user.jpg'
		}
		var cropper = $('.imageBox').cropbox(options);
		$('#file').on('change', function(){
			var reader = new FileReader();
			reader.onload = function(e) {
				options.imgSrc = e.target.result;
				cropper = $('.imageBox').cropbox(options);
			}
			reader.readAsDataURL(this.files[0]);
			this.files = [];
		});
		$('#btnZoomIn').on('click', function(){
			cropper.zoomIn();
			var img = cropper.getDataURL();
			$('.cropped').empty();
			$('.cropped').append('<img src="'+img+'">');
		});
		$('#btnZoomOut').on('click', function(){
			cropper.zoomOut();
			var img = cropper.getDataURL();
			$('.cropped').empty();
			$('.cropped').append('<img src="'+img+'">');
		});
		$('.thumbBox').on('click', function(){
			var img = cropper.getDataURL();
			$('.cropped').empty();
			$('.cropped').append('<img src="'+img+'">');
		});

		//	上传话题图片
		$('#btnCrop_topic').click(function() {
			var _token = $('#_token').val();
			var topic_name = $('#topic_name').val();
			var img = cropper.getDataURL();

			$.post("/syshide/upload_topic_img",
			{
				file:cropper.getDataURL(),
				topic_name:topic_name,
				_token:_token
			},
			function(data,status)
			{
				swal({
						title: "操作成功",
						text: "话题"+data+"图片已上传",
						type: "success"
					},
				function(){
					$('#load_topic_page_area').slideUp();
					window.location.reload();
				});
			});
		});
	});
});