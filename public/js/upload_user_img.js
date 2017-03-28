$(function () {
	$(document).ready(function () {
		$('.container').hide();

		//	点击用户头像
		$('.users_img').click(function() {
			$('.container').slideToggle();
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
		/*$('#btnCrop').on('click', function(){
			//var img = cropper.getDataURL();
			//$('.cropped').empty();
			//$('.cropped').append('<img src="'+img+'">');
			//$('.cropped').append('<br />');
			//$('.cropped').append("<input type='button' id='upload_img' value='上传头像'>");
		});*/
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

		//	上传头像
		$('#btnCrop').click(function() {
			var _token = $('#_token').val();
			var user_name = $('#user_name').val();
			
			var img = cropper.getDataURL();
			$.post("/syshide/upload_user_img",
			{
				file:cropper.getDataURL(),
				user_name:user_name,
				_token:_token
			},
			function(data,status)
			{
				swal({
						title: "操作成功",
						text: "新头像已上传",
						type: "success"
					},
				function(){
					$('.container').slideToggle();
					window.location.reload();
				});
			});
		});
	});
});