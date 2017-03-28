$(document).ready(function(){

	//	得到总页码
	var page_num = $('#page_num').val();
	if(page_num == 0)
	{
		$("#next_page_but").text("没有了");
		$("#next_page_but").attr("disabled","disabled");
		$("#next_page_but").addClass("next_page_but_disabled");
		$("#next_page_but").removeClass("next_page_but_normal");
	}
	
	//	加载按钮被点击
	$("#next_page_but").click(function() {
		
		$("#next_page_but").html("<img src='/css/img/loader.gif'  align='top'/>  正在加载");
		$("#next_page_but").attr("disabled","disabled");
		$("#next_page_but").addClass("next_page_but_disabled");
		$("#next_page_but").removeClass("next_page_but_normal");

		//	得到总页码
		var page_num = $('#page_num').val();
		page_num = page_num;

		//	得到当前页码，从0开始
		var next_page_num = $('#next_page_num').val();

		//	得到每页数据数量
		var page_line_num = $('#page_line_num').val();

		//	得到当前话题id
		var topics_id = $('#topics_id').val();

		//	limit语句起始位置
		var limlt_begin = next_page_num*page_line_num;

		var _token = $('#_token').val();
		var topic_name = $('#topic_name').val();

		$.get("/topics/load_topic_next_hot_page",
		{
			limlt_begin:limlt_begin,
			page_line_num:page_line_num,
			topic_name:topic_name,
			_token:_token
		},
		function(data,status)
		{
			if(data != "")
			{
				//	添加内容
				$("#in_url_list").append(data);
				$("#next_page_but").text("更多");
				$("#next_page_but").removeAttr("disabled");
				$("#next_page_but").removeClass("next_page_but_disabled");
				$("#next_page_but").addClass("next_page_but_normal");
				//	修改页码
				next_page_num++;
				$('#next_page_num').val(next_page_num);
			}
			else
			{
				$("#next_page_but").text("没有了");
				$("#next_page_but").attr("disabled","disabled");
				$("#next_page_but").addClass("next_page_but_disabled");
				$("#next_page_but").removeClass("next_page_but_normal");
			}
		});
	});	
	
	//	页面滚动到底部
	window.onscroll = function () { 
		if (getScrollTop() + getClientHeight() == getScrollHeight()) { 
		
			$("#next_page_but").html("<img src='/css/img/loader.gif'  align='top'/>  正在加载");
			$("#next_page_but").attr("disabled","disabled");
			$("#next_page_but").addClass("next_page_but_disabled");
			$("#next_page_but").removeClass("next_page_but_normal");

			//	得到总页码
			var page_num = $('#page_num').val();
			page_num = page_num;

			//	得到当前页码，从0开始
			var next_page_num = $('#next_page_num').val();

			//	得到每页数据数量
			var page_line_num = $('#page_line_num').val();

			//	得到当前话题id
			var topics_id = $('#topics_id').val();

			//	limit语句起始位置
			var limlt_begin = next_page_num*page_line_num;

			var _token = $('#_token').val();
			var topic_name = $('#topic_name').val();

			$.get("/topics/load_topic_next_hot_page",
			{
				limlt_begin:limlt_begin,
				page_line_num:page_line_num,
				topic_name:topic_name,
				_token:_token
			},
			function(data,status)
			{
				if(data != "")
				{
					//	添加内容
					$("#in_url_list").append(data);
					$("#next_page_but").text("更多");
					$("#next_page_but").removeAttr("disabled");
					$("#next_page_but").removeClass("next_page_but_disabled");
					$("#next_page_but").addClass("next_page_but_normal");
					//	修改页码
					next_page_num++;
					$('#next_page_num').val(next_page_num);
				}
				else
				{
					$("#next_page_but").text("没有了");
					$("#next_page_but").attr("disabled","disabled");
					$("#next_page_but").addClass("next_page_but_disabled");
					$("#next_page_but").removeClass("next_page_but_normal");
				}
			});
		} 
	} 

	//获取滚动条当前的位置 
	function getScrollTop() { 
		var scrollTop = 0; 
		if (document.documentElement && document.documentElement.scrollTop) { 
			scrollTop = document.documentElement.scrollTop; 
		} 
		else if (document.body) { 
			scrollTop = document.body.scrollTop; 
		} 
		return scrollTop; 
	} 

	//获取当前可是范围的高度 
	function getClientHeight() { 
		var clientHeight = 0; 
		if (document.body.clientHeight && document.documentElement.clientHeight) { 
			clientHeight = Math.min(document.body.clientHeight, document.documentElement.clientHeight); 
		} 
		else { 
			clientHeight = Math.max(document.body.clientHeight, document.documentElement.clientHeight); 
		} 
		return clientHeight; 
	} 

	//获取文档完整的高度 
	function getScrollHeight() { 
		return Math.max(document.body.scrollHeight, document.documentElement.scrollHeight); 
	} 
});