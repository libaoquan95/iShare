$(function () {
	$(document).ready(function () {
		
		//	隐藏作图信息
		$('.hiden_aera').hide();
		var mydate = new Date();
		var today_month = $('#today_month').attr("value");

		//	分享话题信息统计——条形图
		$('#share_topic_count_charts').highcharts({
			chart: {
				type: 'bar'
			},
			title: {
				text: '个人分享话题统计',
				style: {
					fontSize: '18px',
					fontFamily: '微软雅黑'
				}
			},
			subtitle: {
				text: 'Source: 个人分享数据'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: 0,
					style: {
						fontSize: '13px',
						fontFamily: '微软雅黑'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '分享数量 (次)',
					style: {
						fontSize: '14px',
						fontFamily: '微软雅黑'
					}
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				pointFormat: '分享数量: <b>{point.y:.0f} 次</b>'
			},
			series: [{
				name: '数量',
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".share_topic_info").each(function(){
						var sel = $(this).attr("name");
						var count = parseInt($(this).attr("value"));
						data.push({
							name: sel,
							y: count
						});
					});
					return data; 
				})(),
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#000000',
					align: 'center',
					format: '{point.y:.0f}', // one decimal
					x: 10, // 10 pixels down from the top
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});
			
		//	分享话题信息统计——饼图
		$('#share_topic_count_pie').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie',
				options3d: {
					enabled: false,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: '',
					style: {
						fontSize: '18px',
						fontFamily: '微软雅黑'
					}
			},
			subtitle: {
				text: ''
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					depth: 45,
					dataLabels: {
						enabled: true,
						color: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					},
					showInLegend: true
				}
			},
			series: [{
				name: '比例',
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".share_topic_info").each(function(){
						var sel = $(this).attr("name");
						var count = parseInt($(this).attr("value"));
						data.push({
							name: sel,
							y: count
						});
					});
					return data; 
				})()
			}]
		});

		//	浏览话题信息统计——条形图
		$('#visit_topic_count_charts').highcharts({
			chart: {
				type: 'bar'
			},
			title: {
				text: '个人浏览话题统计',
				style: {
					fontSize: '18px',
					fontFamily: '微软雅黑'
				}
			},
			subtitle: {
				text: 'Source: 个人浏览数据'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: 0,
					style: {
						fontSize: '13px',
						fontFamily: '微软雅黑'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '浏览数量 (次)',
					style: {
						fontSize: '14px',
						fontFamily: '微软雅黑'
					}
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				pointFormat: '浏览数量: <b>{point.y:.0f} 次</b>'
			},
			series: [{
				name: '数量',
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".visit_topic_info").each(function(){
						var sel = $(this).attr("name");
						var count = parseInt($(this).attr("value"));
						data.push({
							name: sel,
							y: count
						});
					});
					return data; 
				})(),
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#000000',
					align: 'center',
					format: '{point.y:.0f}', // one decimal
					x: 10, // 10 pixels down from the top
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});
			
		//	浏览话题信息统计——饼图
		$('#visit_topic_count_pie').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie',
				options3d: {
					enabled: false,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: '',
					style: {
						fontSize: '18px',
						fontFamily: '微软雅黑'
					}
			},
			subtitle: {
				text: ''
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					depth: 35,
					dataLabels: {
						enabled: true,
						color: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					},
					showInLegend: true
				}
			},
			series: [{
				name: '比例',
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".visit_topic_info").each(function(){
						var sel = $(this).attr("name");
						var count = parseInt($(this).attr("value"));
						data.push({
							name: sel,
							y: count
						});
					});
					return data; 
				})()
			}]
		});

		//	访问者操作系统统计——饼图
		$('#OS_count_charts_pie').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie',
				options3d: {
					enabled: false,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: '访问者操作系统分布比例统计',
					style: {
						fontSize: '18px',
						fontFamily: '微软雅黑'
					}
			},
			subtitle: {
				text: 'Source: 网址浏览数据'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					depth: 35,
					dataLabels: {
						enabled: true,
						color: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					},
					showInLegend: true
				}
			},
			series: [{
				name: "比例：",
				colorByPoint: true,
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".OS_info").each(function(){
						if(parseInt($(this).attr("value")) > 0)
						{
							var sel = $(this).attr("name");
							var count = parseInt($(this).attr("value"));
							data.push({
								name: sel,
								y: count
							});
						}
					});
					return data; 
				})()
			}]
		});

		//	访问者操作系统统计——条形图
		$('#OS_count_charts_col').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: '访问者操作系统分布数量统计',
				style: {
					fontSize: '18px',
					fontFamily: '微软雅黑'
				}
			},
			subtitle: {
				text: 'Source: 网址浏览数据'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: '微软雅黑'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '浏览数量 (次)',
					style: {
						fontSize: '14px',
						fontFamily: '微软雅黑'
					}
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				pointFormat: '浏览数量: <b>{point.y:.0f} 次</b>'
			},
			series: [{
				name: "数量：",
				colorByPoint: true,
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".OS_info").each(function(){
						if(parseInt($(this).attr("value")) > 0)
						{
							var sel = $(this).attr("name");
							var count = parseInt($(this).attr("value"));
							data.push({
								name: sel,
								y: count
							});
						}
					});
					return data; 
				})(),
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#000000',
					align: 'center',
					format: '{point.y:.0f}', // one decimal
					y: 0, // 10 pixels down from the top
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});

		//	访问用户类型统计——饼图
		$('#visit_user_statu_count_charts_pie').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie',
				options3d: {
					enabled: false,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: '访问用户类型分布比例统计',
					style: {
						fontSize: '18px',
						fontFamily: '微软雅黑'
					}
			},
			subtitle: {
				text: 'Source: 网址浏览数据'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					depth: 35,
					dataLabels: {
						enabled: true,
						color: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					},
					showInLegend: true
				}
			},
			series: [{
				name: "比例：",
				colorByPoint: true,
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".visit_user_statu").each(function(){
						if($(this).attr("name") != "总访问量")
						{
							var sel = $(this).attr("name");
							var count = parseInt($(this).attr("value"));
							data.push({
								name: sel,
								y: count
							});
						}
					});
					return data; 
				})()
			}]
		});
	
		//	访问用户类型统计——条形图
		$('#visit_user_statu_count_charts_col').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: '访问用户类型分布数量统计',
				style: {
					fontSize: '18px',
					fontFamily: '微软雅黑'
				}
			},
			subtitle: {
				text: 'Source: 网址浏览数据'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: '微软雅黑'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '浏览数量 (次)',
					style: {
						fontSize: '14px',
						fontFamily: '微软雅黑'
					}
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				pointFormat: '浏览数量: <b>{point.y:.0f} 次</b>'
			},
			series: [{
				name: "数量：",
				colorByPoint: true,
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".visit_user_statu").each(function(){
						var sel = $(this).attr("name");
						var count = parseInt($(this).attr("value"));
						data.push({
							name: sel,
							y: count
						});
					});
					return data; 
				})(),
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#000000',
					align: 'center',
					format: '{point.y:.0f}', // one decimal
					y: 0, // 10 pixels down from the top
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});
	
		//	浏览省份信息统计——条形图
		$('#visit_user_province_count_charts_col').highcharts({
			chart: {
				type: 'column'
			},
			title: {
				text: '访问地址信息数量统计',
				style: {
					fontSize: '18px',
					fontFamily: '微软雅黑'
				}
			},
			subtitle: {
				text: 'Source: 网址浏览数据'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: '微软雅黑'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '浏览数量 (次)',
					style: {
						fontSize: '14px',
						fontFamily: '微软雅黑'
					}
				}
			},
			legend: {
				enabled: false
			},
			tooltip: {
				pointFormat: '浏览数量: <b>{point.y:.0f} 次</b>'
			},
			series: [{
				name: '数量',
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".province_name").each(function(){
						if(parseInt($(this).attr("value")) > 0)
						{
							var sel = $(this).attr("name");
							var count = parseInt($(this).attr("value"));
							var num = count;
							data.push({
								name: sel,
								y: num
							});
						}
					});
					return data; 
				})(),
				dataLabels: {
					enabled: true,
					rotation: 0,
					color: '#000000',
					align: 'center',
					format: '{point.y:.0f}', // one decimal
					y: 0, // 10 pixels down from the top
					style: {
						fontSize: '13px',
						fontFamily: 'Verdana, sans-serif'
					}
				}
			}]
		});
			
		//	浏览省份信息统计——饼图
		$('#visit_user_province_count_charts_pie').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie',
				options3d: {
					enabled: false,
					alpha: 45,
					beta: 0
				}
			},
			title: {
				text: '访问地址信息比例统计',
					style: {
						fontSize: '18px',
						fontFamily: '微软雅黑'
					}
			},
			subtitle: {
				text: 'Source: 网址浏览数据'
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					depth: 35,
					dataLabels: {
						enabled: true,
						color: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					},
					showInLegend: true
				}
			},
			series: [{
				name: "比例：",
				colorByPoint: true,
				data: (function() {
					// 定义一个数组
					var data = [];
					$(".province_name").each(function(){
						if(parseInt($(this).attr("value")) > 0)
						{
							var sel = $(this).attr("name");
							var count = parseInt($(this).attr("value"));
							var num = count;
							data.push({
								name: sel,
								y: num
							});
						}
					});
					return data; 
				})()
			}]
		});

		//	单日访问量统计——条形图
		$('#visit_days_pv_count_charts_line').highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: mydate.getFullYear()+'年'+parseInt(mydate.getMonth()+1)+'月日访问量统计',
					style: {
						fontSize: '18px',
						fontFamily: '微软雅黑'
					}
			},
			subtitle: {
				text: 'Source: 网址浏览数据'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: '微软雅黑'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '单日浏览量 (次)',
					style: {
						fontSize: '14px',
						fontFamily: '微软雅黑'
					}
				}
			},
			tooltip: {
				enabled: true,
				formatter: function() {
					return '<b>' + this.y + '</b>'+'次';
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: true
				},
				area: {
					fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, Highcharts.getOptions().colors[0]],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},
					lineWidth: 1,
					marker: {
						enabled: false
					},
					shadow: false,
					states: {
						hover: {
							lineWidth: 1
						}
					},
					threshold: null
				}
			},
			series: [{
				name: '访问量',
				//type: 'area',
				data: (function() {
					var data = [];
					$(".days_pv").each(function(){
						var sel = $(this).attr("name");
						var mouth = $(this).attr("src");
						if(parseInt(mouth) == parseInt(mydate.getMonth()+1))
						{
							var count = parseInt($(this).attr("value"));
							var num = count;
							data.push({
								name: sel,
								y: num
							});
						}
					});
					return data; 
				})()
			}]
		});

		//	单月访问量统计——条形图
		$('#visit_months_pv_count_charts_line').highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: mydate.getFullYear()+'年月访问量统计',
					style: {
						fontSize: '18px',
						fontFamily: '微软雅黑'
					}
			},
			subtitle: {
				text: 'Source: 网址浏览数据'
			},
			xAxis: {
				type: 'category',
				labels: {
					rotation: -45,
					style: {
						fontSize: '13px',
						fontFamily: '微软雅黑'
					}
				}
			},
			yAxis: {
				min: 0,
				title: {
					text: '单日浏览量 (次)',
					style: {
						fontSize: '14px',
						fontFamily: '微软雅黑'
					}
				}
			},
			tooltip: {
				enabled: true,
				formatter: function() {
					return '<b>' + this.y + '</b>'+'次';
				}
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: true
				},
				area: {
					fillColor: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
						stops: [
							[0, Highcharts.getOptions().colors[0]],
							[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
						]
					},
					lineWidth: 1,
					marker: {
						enabled: false
					},
					shadow: false,
					states: {
						hover: {
							lineWidth: 1
						}
					},
					threshold: null
				}
			},
			series: [{
				name: '访问量',
				//type: 'area',
				data: (function() {
					var data = [];
					$(".months_pv").each(function(){
						var sel = $(this).attr("name");
						var count = parseInt($(this).attr("value"));
						var num = count;
						data.push({
							name: sel,
							y: num
						});
					});
					return data; 
				})()
			}]
		});

		//	浏览省份信息统计——地图
		$(function () {
			var data = Highcharts.geojson(Highcharts.maps['countries/cn/custom/cn-all-sar-taiwan']),small = $('#container').width() < 400;
			// 给城市设置数据
			$.each(data, function (i) {
				this.drilldown = this.properties['hc-key'];
				this.value = $("#"+this.properties['hc-key']).attr("value"); 
				//alert(this.properties['hc-key']);
			});
			$('#visit_user_province_count_charts_map').highcharts('Map', {
				chart : {
					events: {
						drilldown: function (e) {
							if (!e.seriesOptions) {
								var chart = this,
									mapKey = 'countries/us/' + e.point.drilldown + '-all',
									fail = setTimeout(function () {
										if (!Highcharts.maps[mapKey]) {
											chart.showLoading('<i class="icon-frown"></i> 加载失败 ' + e.point.name);
											fail = setTimeout(function () {
												chart.hideLoading();
											}, 1000);
										}
									}, 3000);
								chart.showLoading('<i class="icon-spinner icon-spin icon-3x"></i>'); 
								// 加载城市数据
								/*$.getJSON('/uploads/rs/228/zroo4bdf/bei_jing.geo.json', function (json) {
									data = Highcharts.geojson(json);
									$.each(data, function (i) {
										this.value = i;
									});
									chart.hideLoading();
									clearTimeout(fail);
									chart.addSeriesAsDrilldown(e.point, {
										name: e.point.name,
										data: data,
										dataLabels: {
											enabled: true,
											format: '{point.name}'
										}
									});
								});*/
							}
							this.setTitle(null, { text: e.point.name });
						},
						drillup: function () {
							this.setTitle(null, { text: '中国' });
						}
					}
				},
				title : {
					text : '访问地址信息'
				},
				subtitle: {
					text: '',
					floating: true,
					align: 'right',
					y: 50,
					style: {
						fontSize: '13px'
					}
				},
				legend: small ? {} : {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'middle'
				},
				colorAxis: {
					min: 0,
					minColor: '#E6E7E8',
					maxColor: '#005645'
				},
				mapNavigation: {
					enabled: true,
					buttonOptions: {
						verticalAlign: 'bottom'
					}
				},
				plotOptions: {
					map: {
						states: {
							hover: {
								color: '#EEDD66'
							}
						}
					}
				},
				series : [{
					data : data,
					name: "访问量",
					dataLabels: {
						enabled: true,
						format: '{point.properties.cn-name}'
					}
				}],
				drilldown: {
					activeDataLabelStyle: {
						color: '#FFFFFF',
						textDecoration: 'none',
						textShadow: '0 0 3px #000000'
					},
					drillUpButton: {
						relativeTo: 'spacingBox',
						position: {
							x: 0,
							y: 60
						}
					}
				}
			});
		});

		$("#select_id").change(function() {
			var today_month = $("#select_id").val();
			$("#today_month").attr("value",today_month);
			//	单日访问量统计——条形图
			$('#visit_days_pv_count_charts_line').highcharts({
				chart: {
					type: 'line'
				},
				title: {
					text: mydate.getFullYear()+'年'+today_month+'月日访问量统计',
						style: {
							fontSize: '18px',
							fontFamily: '微软雅黑'
						}
				},
				subtitle: {
					text: 'Source: 网址浏览数据'
				},
				xAxis: {
					type: 'category',
					labels: {
						rotation: -45,
						style: {
							fontSize: '13px',
							fontFamily: '微软雅黑'
						}
					}
				},
				yAxis: {
					min: 0,
					title: {
						text: '单日浏览量 (次)',
						style: {
							fontSize: '14px',
							fontFamily: '微软雅黑'
						}
					}
				},
				tooltip: {
					enabled: true,
					formatter: function() {
						return '<b>' + this.y + '</b>'+'次';
					}
				},
				plotOptions: {
					line: {
						dataLabels: {
							enabled: true
						},
						enableMouseTracking: true
					},
					area: {
						fillColor: {
							linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
							stops: [
								[0, Highcharts.getOptions().colors[0]],
								[1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
							]
						},
						lineWidth: 1,
						marker: {
							enabled: false
						},
						shadow: false,
						states: {
							hover: {
								lineWidth: 1
							}
						},
						threshold: null
					}
				},
				series: [{
					name: '访问量',
					//type: 'area',
					data: (function() {
						var data = [];
						$(".days_pv").each(function(){
							var sel = $(this).attr("name");
							var mouth = $(this).attr("src");
							if(mouth == today_month)
							{
								var count = parseInt($(this).attr("value"));
								var num = count;
								data.push({
									name: sel,
									y: num
								});
							}
						});
						return data; 
					})()
				}]
			});
		});
    });
});