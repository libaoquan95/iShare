<?php namespace App\Http\Controllers\Syshide;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_userurl;
use App\Bu_user;
use App\Bu_userurl_topic;
use App\Bu_topic;
use App\Bu_userurlvisit;
use App\Bu_report_url;
use Session;

class SyshideHomeController extends Controller {

	/**
	 * 分享网址提交
	 *
	 * @return Response
	 */
	public function url_submit()
	{		
		if(!empty($_POST["submit_url"]) && !empty($_POST["submit_url_desc"]) && !empty($_POST["submit_model"]))
		{
			/*
			 * 检测链接是否有效
			 */
			$url = $_POST['submit_url'];
			$check = @fopen($url,"r");  
			if($check)
			{
				$submit_model   = $_POST["submit_model"];
				$per_info       = $_POST["personal_sub_info"];
				$submiturl      = $_POST["submit_url"];
				$submiturl_desc = $_POST["submit_url_desc"];
				$topics_arr   = $_POST["topics"];

				//	默认压缩方式
				if($submit_model == "default_model")
				{
					//	将long_url 转成 short_url
					$url_blade = $this->Cut_Url($submiturl);

					// 首先将网址进行 4-2 压缩
					$Compressed_arr = $this->Compressed_Url($url_blade, 4, 2);
					$short_url = $Compressed_arr['short_url'];
					$long_url = $Compressed_arr['long_url'];

					//	网址正确转换
					if($short_url != "")
					{
						//	查找与用户名对应行
						$user_info = Bu_user::where('name', Session::get('user_name'))->first();
						$user_id = $user_info->id;
						$created_at = $updated_at = date('Y-m-d H:i:s');

						$userurl_id = ""; 

						//	检索网址是否已存在
						$url_info = Bu_userurl::where('short_url', $short_url)->first();

						// 存在，比较信息，再次压缩
						$i = 3;
						$state = TRUE;
						while($url_info != '' && $state == TRUE)
						{
							//	长网址是已经压缩过的，不在压缩
							if($url_info->long_url == $long_url)
							{
								$state = FALSE;
							}
							else
							{
								$Compressed_arr = $this->Compressed_Url($url_blade, 4, $i);
								$short_url = $Compressed_arr['short_url'];
								$long_url = $Compressed_arr['long_url'];
								$url_info = Bu_userurl::where('short_url', $short_url)->first();
								$i++;
								$state = TRUE;
							}
						}

						//	不存在，插入信息
						if($state == TRUE)
						{
							//	记录信息
							$userurl_id = Bu_userurl::insertGetId(
								array(  'short_url'		=> $short_url,
										'long_url'		=> $long_url,
										'user_id'		=> $user_id,
										'url_describe'	=> $submiturl_desc,
										'created_at'	=> $created_at,
										'updated_at'	=> $updated_at)
							);

							//	跟新用户提交网址数量
							$user_info = Bu_user::where('id', $user_id)->first();
							$user_url_count = $user_info->user_url_count + 1;
							Bu_user::where('id', $user_id)->update(array('user_url_count' => $user_url_count));
							
							// 将话题信息写入数据库
							foreach($topics_arr as $topic_id)
							{
								Bu_userurl_topic::insertGetId(
									array(  'bu_topics_id'	=> $topic_id,
											'bu_userurls_id'=> $userurl_id,
											'created_at'	=> $created_at,
											'updated_at'	=> $updated_at)
								);
							}
							//	更新话题网址数量
							foreach($topics_arr as $topic_id)
							{
								$topic_info = Bu_topic::where('id', $topic_id)->first();
								$urlCount = $topic_info->urlCount +1;
								Bu_topic::where('id', $topic_id)->update(array('urlCount' => $urlCount));
							}
							
							return $short_url;
						}
						//	存在，提交重复网址
						else
						{
							return "#error_02#";
						}
					}
					//	转换网址格式错误
					else
					{
						return "#error_01#";
					}
				}
				//	自定义1
				else if($submit_model == "personal_model_1")
				{
					//	将long_url 转成 short_url
					$url_blade = $this->Cut_Url($submiturl);

					// 首先将网址进行 4-2 压缩
					$Compressed_arr = $this->Compressed_Url($url_blade, 4, 2);
					$short_url = $Compressed_arr['short_url'];
					$long_url = $Compressed_arr['long_url'];
					$short_url = substr($short_url,0,4);
					$short_url = $short_url . $per_info;

					//	网址正确转换
					if($short_url != "")
					{
						//	查找与用户名对应行
						$user_info = Bu_user::where('name', Session::get('user_name'))->first();
						$user_id = $user_info->id;
						$created_at = $updated_at = date('Y-m-d H:i:s');

						$userurl_id = ""; 

						//	检索网址是否已存在
						$url_info = Bu_userurl::where('short_url', $short_url)->first();

						// 存在，比较信息
						if($url_info != '')
						{
							return "#error_04#";
						}
						//	不存在，插入信息
						else
						{
							//	记录信息
							$userurl_id = Bu_userurl::insertGetId(
								array(  'short_url'		=> $short_url,
										'long_url'		=> $long_url,
										'user_id'		=> $user_id,
										'url_describe'	=> $submiturl_desc,
										'created_at'	=> $created_at,
										'updated_at'	=> $updated_at)
							);

							//	跟新用户提交网址数量
							$user_info = Bu_user::where('id', $user_id)->first();
							$user_url_count = $user_info->user_url_count + 1;
							Bu_user::where('id', $user_id)->update(array('user_url_count' => $user_url_count));
							
							// 将话题信息写入数据库
							foreach($topics_arr as $topic_id)
							{
								Bu_userurl_topic::insertGetId(
									array(  'bu_topics_id'	=> $topic_id,
											'bu_userurls_id'=> $userurl_id,
											'created_at'	=> $created_at,
											'updated_at'	=> $updated_at)
								);
							}
							//	更新话题网址数量
							foreach($topics_arr as $topic_id)
							{
								$topic_info = Bu_topic::where('id', $topic_id)->first();
								$urlCount = $topic_info->urlCount +1;
								Bu_topic::where('id', $topic_id)->update(array('urlCount' => $urlCount));
							}
							
							return $short_url;
						}
					}
					//	转换网址格式错误
					else
					{
						return "#error_01#";
					}
				}
				//	自定义2
				else if($submit_model == "personal_model_2")
				{
					$long_url = $this->Url_Check($submiturl);
					$long_url = $long_url['bu_url'];
					$short_url = $per_info;
					
					//	网址正确
					if($long_url != "")
					{
						//	查找与用户名对应行
						$user_info = Bu_user::where('name', Session::get('user_name'))->first();
						$user_id = $user_info->id;
						$created_at = $updated_at = date('Y-m-d H:i:s');

						$userurl_id = ""; 

						//	检索网址是否已存在
						$url_info = Bu_userurl::where('short_url', $short_url)->first();

						// 存在，比较信息
						if($url_info != '')
						{
							return "#error_04#";
						}
						//	不存在，插入信息
						else
						{
							//	记录信息
							$userurl_id = Bu_userurl::insertGetId(
								array(  'short_url'		=> $short_url,
										'long_url'		=> $long_url,
										'user_id'		=> $user_id,
										'url_describe'	=> $submiturl_desc,
										'created_at'	=> $created_at,
										'updated_at'	=> $updated_at)
							);

							//	跟新用户提交网址数量
							$user_info = Bu_user::where('id', $user_id)->first();
							$user_url_count = $user_info->user_url_count + 1;
							Bu_user::where('id', $user_id)->update(array('user_url_count' => $user_url_count));
							
							// 将话题信息写入数据库
							foreach($topics_arr as $topic_id)
							{
								Bu_userurl_topic::insertGetId(
									array(  'bu_topics_id'	=> $topic_id,
											'bu_userurls_id'=> $userurl_id,
											'created_at'	=> $created_at,
											'updated_at'	=> $updated_at)
								);
							}
							//	更新话题网址数量
							foreach($topics_arr as $topic_id)
							{
								$topic_info = Bu_topic::where('id', $topic_id)->first();
								$urlCount = $topic_info->urlCount +1;
								Bu_topic::where('id', $topic_id)->update(array('urlCount' => $urlCount));
							}
							return $short_url;
						}
					}
					//	转换网址格式错误
					else
					{
						return "#error_01#";
					}
				}
			}
			//	无效的URL资源
			else
			{ 
				return "#error_03#";
			}
		}
	}

	/*
	 *	向不含协议的URL中添加http://协议
	 *
	 *	Protocol_Check($inputurl)
	 *
	 *	输入：$inputurl 有效URL地址
	 *	输出：含有协议的URL地址
	 */
	public function Protocol_Check($inputurl)
	{
		$pro_sta = 0;	//0为地址中不含协议，1为含有

		$finalurl = $inputurl;
		$pt = array("http://","https://","ftp://");
		$pt_position = strpos($inputurl,"://");	//查找://的位置，如果没有找到则返回空。
		$po_position = strpos($inputurl,".");

		//	:// 和 . 都存在，且 :// 在 . 之前
		if($pt_position!="" && $po_position!="")
		{
			if($pt_position < $po_position)
				$pro_sta = 1;
			else
				$pro_sta = 0;
		}
		//	:// 存在 . 不存在
		elseif($pt_position!="" && $po_position=="")
		{
			$pro_sta = 1;
		}
		//	:// 不存在 . 存在
		else
		{
			$pro_sta = 0;
		}

		//地址中不含协议，添加http://协议
		if($pro_sta == 0)
		{
			$finalurl = "http://".$finalurl;
		}

		//	返回带有协议的URL地址
		return $finalurl;
	}
	
	/*
	 *	判断URL是否是有效URL
	 *	URL规则：去掉协议后，第一个点的左右两边均有字符为正确，最后若有/, 去除，首尾有多余空格，去除
	 *
	 *	Url_Check($inputurl)
	 *
	 *	输入：$inputurl URL地址
	 *	输出：$arr(是否有效标志 => URL地址)
	 *	若有效，输出有效地址，无效，输出空
	 */
	public function Url_Check($inputurl)
	{
		$err_sta = "-1";	//	-1为地址有误，1为无误

		//	去除首尾多余空格
		$inputurl = trim($inputurl);

		//	最后一位有'/'，去除
		$last_url_ch = substr($inputurl,strlen($inputurl)-1,strlen($inputurl)); 
		if($last_url_ch == '/')
		{
			$inputurl = substr($inputurl,0,strlen($inputurl)-1); 
		}
		
		$inputurl_copy = $inputurl;
		$pt_position = strpos($inputurl_copy,"://");

		//	有协议，去掉协议
		if($pt_position != "")
		{
			//	保存协议
			$url_Protocol = substr($inputurl_copy, 0, $pt_position+3);
			//	去除协议
			str_replace($url_Protocol, '', $inputurl_copy);
		}

		//	URL规则：去掉协议后，第一个点的左右两边均有字符为正确
		$po_position = strpos($inputurl_copy,".");
		$url_copy_len = strlen($inputurl_copy);

		if($po_position != "")
		{
			if($po_position>0 && $url_copy_len>$po_position+1)
				$err_sta = "1";
		}

		//	正确的URL
		if($err_sta == "1")
		{
			$inputurl = $this->Protocol_Check($inputurl);
		}
		//	错误的URL，返回空
		else 
		{
			$inputurl = "";
		}

		$ret_arr = array("status"=>"$err_sta",
			    		"bu_url"=>"$inputurl");
		return $ret_arr;
	}

	/*
	 *	字符串压缩编码
	 *	input:
	 *		$input_str	待压缩编码字符串
	 *		$long		压缩字符串长度
	 *	output:
	 *		$short_str	压缩字符串
	 */
	public function Compressed_Code($input_str, $long)
	{
		$base62 = array (
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", 
			"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", 
			"0", "1", "2", "3", "4", "5", "6", "7", "8", "9"
		);

		if($input_str != "")
		{
			$str_sha1 = md5($input_str);					//	sha1
			$str_hexa = str_split($str_sha1, strlen($str_sha1)/$long);		//	分割sha1字符串，存储十六进制
			$str_deci = array();							//	字符串对应十进制数组
			$str_mo62 = array();							//	十进制数组模62转换数组

			$i=0;
			$short_str = '';
			foreach($str_hexa as $str_num)
			{
				$str_deci[$i] = abs(hexdec($str_num));		//	16进制转10进制
				$str_mo62[$i] = abs($str_deci[$i]%62);		//	10进制对62取模
				$short_str .= $base62[($str_mo62[$i])];		//	得到下标对应字符
				$i++;
			}
			return substr($short_str, 0, $long);
		}
		else
		{
			return "";
		}
	}

	/*
	 *	截取网址
	 *	input:
	 *		$inputurl		输入网址字符串
	 *	output:
	 *		$url_blade_arr	网址截取片段数组
	 */
	public function Cut_Url($inputurl)
	{
		$inputurl_arr = $this->Url_Check($inputurl);	//	检验网址
		$inputurl = $inputurl_arr["bu_url"];
		$dn_position = 0;

		//	是正确格式的URL
		if($inputurl != "")
		{
			//	分割网址 得到域名'://'到之后第一个'/'之间为域名
			$pt_position = strpos($inputurl, "://");				//	得到协议位置
			$domain_name = substr($inputurl, $pt_position+3);	//	得到去除协议后位置
			$dn_position = strpos($domain_name, "/");			//	得到协议+域名字长

			if($dn_position)
			{
				$domain_name = substr($inputurl,0,$dn_position+$pt_position+3);	//	截取协议+域名
				$station_url = substr($inputurl,$dn_position+$pt_position+4);	//	截取站内URL,去除开头'/'
			}
			//	'://'后无'/'，则剩下均为域名
			else
			{
				$domain_name = $inputurl;						//	截取协议+域名
				$station_url = '';								//	截取站内URL,去除开头'/'
			}

			$url_blade_arr = array("status"	=>	"success",
							"inputurl"		=>	"$inputurl",
							"domain_name"	=>	"$domain_name",
							"station_url"	=>	"$station_url");
			return $url_blade_arr;
		}
		else
		{
			$url_blade_arr = array("status"	=>	"error",
							"inputurl"		=>	"$inputurl",
							"domain_name"	=>	"",
							"station_url"	=>	"");
			return $url_blade_arr;
		}
	}

	/*
	 *	网址压缩编码
	 *	input:
	 *		$url_blade	网址截取片段数组
	 *	output:
	 *		$short_url	压缩短网址
	 */
	public function Compressed_Url($url_blade_arr, $i, $j)
	{
		if($url_blade_arr['status'] == "success")
		{
				$urlString = $this->Compressed_Code($url_blade_arr['domain_name'], $i) . $this->Compressed_Code($url_blade_arr['station_url'], $j);

			$ret_arr = array("status"	=>	"success",
							"short_url"	=>	"$urlString",
							"long_url"	=>	$url_blade_arr['inputurl']);
			return $ret_arr;
		}
		else
		{
			$ret_arr = array("status"	=>	"error",
							"short_url"	=>	"",
							"long_url"	=>	$url_blade_arr['inputurl']);
			return $ret_arr;
		}
	}

	/**
	 * url信息删除
	 *
	 * @return Response
	 */
	public function url_del()
	{
		// 处理表单:
		if (!empty($_POST['short_url']))
		{
			$short_url = $_POST['short_url'];

			//	bu_users user_url_count 减1
			$user_name = Session::get('user_name');
			$user_info = Bu_user::where('name', $user_name)->first();
			$user_url_count = $user_info->user_url_count - 1;
			Bu_user::where('name', $user_name)->update(array('user_url_count' => $user_url_count));

			//	bu_userurls 删除 url信息 索引:short_url 得到 此url的id:short_url_id
			$userurl_info = Bu_userurl::where('short_url', $short_url)->first();
			$short_url_id = $userurl_info->id;
			Bu_userurl::where('short_url', $short_url)->delete();

			//	bu_userurlvisits 删除 索引:short_url 访问信息
			Bu_userurlvisit::where('short_url', $short_url)->delete();

			//	bu_userurl_topics 删除 索引:short_url_id 话题信息 得到 话题的id:topic_id
			$url_topics = Bu_userurl_topic::where('bu_userurls_id', $short_url_id)->get();
			foreach($url_topics as $url_topic)
			{
				//	bu_topics urlCount 减1 索引:topic_id
				$bu_topics_id = $url_topic->bu_topics_id;
				$topic_info = Bu_topic::where('id', $bu_topics_id)->first();
				$topic_url_count = $topic_info->urlCount - 1;
				Bu_topic::where('id', $bu_topics_id)->update(array('urlCount' => $topic_url_count));
			}

			Bu_userurl_topic::where('bu_userurls_id', $short_url_id)->delete();
		}
	}

	/**
	 * url暂停分享
	 *
	 * @return Response
	 */
	public function url_pause_share()
	{
		//	bu_userurls url_status 赋值 1 索引:short_url

		// 处理表单:
		if (!empty($_POST['short_url']))
		{
			$short_url = $_POST['short_url'];
			Bu_userurl::where('short_url', $short_url)->update(array('url_status' => 1));	//	修改网址状态

			$userurl_info = Bu_userurl::where('short_url', $short_url)->first();
			$short_url_id = $userurl_info->id;
			$url_topics = Bu_userurl_topic::where('bu_userurls_id', $short_url_id)->get();
			foreach($url_topics as $url_topic)
			{
				$bu_topics_id = $url_topic->bu_topics_id;
				$topic_info = Bu_topic::where('id', $bu_topics_id)->first();
				$topic_url_count = $topic_info->urlCount - 1;
				Bu_topic::where('id', $bu_topics_id)->update(array('urlCount' => $topic_url_count));
			}
		}
	}

	/**
	 * url继续分享
	 *
	 * @return Response
	 */
	public function url_continue_share()
	{
		//	bu_userurls url_status 赋值 0 索引:short_url
		// 处理表单:
		if (!empty($_POST['short_url']))
		{
			$short_url = $_POST['short_url'];
			Bu_userurl::where('short_url', $short_url)->update(array('url_status' => 0));

			$userurl_info = Bu_userurl::where('short_url', $short_url)->first();
			$short_url_id = $userurl_info->id;
			$url_topics = Bu_userurl_topic::where('bu_userurls_id', $short_url_id)->get();
			foreach($url_topics as $url_topic)
			{
				$bu_topics_id = $url_topic->bu_topics_id;
				$topic_info = Bu_topic::where('id', $bu_topics_id)->first();
				$topic_url_count = $topic_info->urlCount + 1;
				Bu_topic::where('id', $bu_topics_id)->update(array('urlCount' => $topic_url_count));
			}
		}
	}

	/**
	 * 信息搜索
	 *
	 * @return Response
	 */
	public function normal_search()
	{
		// 处理表单:
		if (!empty($_POST['search']))
		{
			$page_line_num = 10;	//	每页显示的条数
			$arr_info = array();	
			$arr_urls = array();	
			$url_info = array();
			$search_arr_2 = array();

			$search = $_POST['search'];
			$search_2 = str_ireplace(" ","%", $search); 
			$urls_arr = Bu_userurl::where('url_status', '==','0')
									->where('url_describe', 'LIKE', '%'.$search_2.'%')
									->skip(0)->take($page_line_num)
									->get();
								
			$arr_info['search_info'] = $search;
			if(Session::has('auth_state'))
			{
				$user_name = Session::get('user_name');
				$user_info = Bu_user::where('name', $user_name)->first();
				$arr_info['user_img'] = $user_info->user_img;
			}
			//	遍历，找寻与某一url对应的多个话题信息
			$i = 0;
			foreach($urls_arr as $url)
			{
				if($url->url_status == 0)
				{
					$arr_urls['bu_userurls'] = $url;
					$arr_urls['topics'] = $url->belongsToManyTopic()->get();
					$arr_urls['user'] = $url->belongsToUser()->get();

					//	将url与话题数组打包成大数组
					$url_info[] = $arr_urls;
					$i++;
				}
			}

			if($i != 0)
			{
				//	向视图页面返回此大数组
				$arr_info['urls_info'] = $url_info;
				return view('search.NormalSearch')->with('info', $arr_info);
			}
			else
			{
				$arr_info['urls_info'] = '';
				return view('search.ErrorSearch')->with('info', $arr_info);
			}
		}
		else
		{
			$arr_info = array();
			$arr_info['search_info'] = '';
			$arr_info['user_img'] = '';
			if(Session::has('auth_state'))
			{
				$user_name = Session::get('user_name');
				$user_info = Bu_user::where('name', $user_name)->first();
				$arr_info['user_img'] = $user_info->user_img;
			}
			return view('search.ErrorSearch')->with('info', $arr_info);
		}
	}

	/**
	 * 信息搜索加载下一页
	 *
	 * @return Response
	 */
	public function load_next_normal_search_page()
	{
		// 处理表单:
		if (!empty($_POST['search']))
		{
			$limlt_begin = $_POST['limlt_begin'];		//	每页显示的条数
			$page_line_num = $_POST['page_line_num'];	//	开始条

			$arr_info = array();	
			$arr_urls = array();	
			$url_info = array();
			$search_arr_2 = array();

			$search = $_POST['search'];
			$search_2 = str_ireplace(" ","%", $search); 
			$urls_arr = Bu_userurl::where('url_status', '==','0')
									->where('url_describe', 'LIKE', '%'.$search_2.'%')
									->skip($limlt_begin)->take($page_line_num)
									->get();

			//	遍历，找寻与某一url对应的多个话题信息
			$i = 0;
			foreach($urls_arr as $url)
			{
				if($url->url_status == 0)
				{
					$arr_urls['bu_userurls'] = $url;
					$arr_urls['topics'] = $url->belongsToManyTopic()->get();
					$arr_urls['user'] = $url->belongsToUser()->get();

					//	将url与话题数组打包成大数组
					$arr_info[] = $arr_urls;
					$i++;
				}
			}

			//	向视图页面返回此大数组
			return view('home.HomeUserMore')->with('info', $arr_info);
		}
	}

	/**
	 * 信息高级搜索
	 *
	 * @return Response
	 */
	public function more_search()
	{
		// 处理表单:
		if (!empty($_POST['search_desc']) OR !empty($_POST['search_lurl']) OR !empty($_POST['search_surl']) OR !empty($_POST['search_user']) )
		{
			$search_desc = $_POST['search_desc'];
			$search_lurl = $_POST['search_lurl'];
			$search_surl = $_POST['search_surl'];
			$search_user = $_POST['search_user'];
			$page_line_num = 10;

			$arr_info = array();	
			$arr_urls = array();	
			$url_info = array();
			$users_id = array();
			
			if(Session::has('auth_state'))
			{
				$user_name = Session::get('user_name');
				$user_info = Bu_user::where('name', $user_name)->first();
				$arr_info['user_img'] = $user_info->user_img;
			}

			$search_desc_2 = str_ireplace(" ", "%", $search_desc);
			$search_lurl_2 = str_ireplace(" ", "%", $search_lurl);
			$search_surl_2 = str_ireplace(" ", "%", $search_surl);
			$search_user_2 = str_ireplace(" ", "%", $search_user);
			
			$users_arr = Bu_user::Where('name', 'LIKE', '%' . $search_user_2 . '%')->get();
			foreach($users_arr as $user)
			{
				$users_id[] = $user->id;
			}

			$urls_arr = Bu_userurl::where('url_status', '==','0')
				                  ->Where('url_describe', 'LIKE', '%' . $search_desc_2 . '%')
								  ->Where('long_url',     'LIKE', '%' . $search_lurl_2 . '%')
								  ->Where('short_url',    'LIKE', '%' . $search_surl_2 . '%')
								  ->whereIn('user_id', $users_id)
								  ->skip(0)->take($page_line_num)
								  ->get();

			$arr_info['search_info'] = array('search_desc' => $search_desc, 
											 'search_lurl' => $search_lurl, 
											 'search_surl' => $search_surl,
											 'search_user' => $search_user);

			//	遍历，找寻与某一url对应的多个话题信息
			$i = 0;
			foreach($urls_arr as $url)
			{
				if($url->url_status == 0)
				{
					$arr_urls['bu_userurls'] = $url;
					$arr_urls['topics'] = $url->belongsToManyTopic()->get();
					$arr_urls['user'] = $url->belongsToUser()->get();

					//	将url与话题数组打包成大数组
					$url_info[] = $arr_urls;
					$i++;
				}
			}

			if($i != 0)
			{
				$arr_info['urls_info'] = $url_info;
				return view('search.MoreSearch')->with('info', $arr_info);
			}
			else
			{
				$arr_info['urls_info'] = '';
				return view('search.ErrorMoreSearch')->with('info', $arr_info);
			}
		}
		else
		{
			$arr_info = array();
			$arr_info['search_info'] = '';
			if(Session::has('auth_state'))
			{
				$user_name = Session::get('user_name');
				$user_info = Bu_user::where('name', $user_name)->first();
				$arr_info['user_img'] = $user_info->user_img;
			}
			return view('search.ErrorSearch')->with('info', $arr_info);
		}
	}	

	/**
	 * 信息高级搜索加载下一页
	 *
	 * @return Response
	 */
	public function load_next_more_search_page()
	{
		// 处理表单:
		if (!empty($_POST['search_desc']) OR !empty($_POST['search_lurl']) OR !empty($_POST['search_surl'])OR !empty($_POST['search_user']) )
		{
			$search_desc = $_POST['search_desc'];
			$search_lurl = $_POST['search_lurl'];
			$search_surl = $_POST['search_surl'];
			$search_user = $_POST['search_user'];

			$limlt_begin = $_POST['limlt_begin'];		//	每页显示的条数
			$page_line_num = $_POST['page_line_num'];	//	开始条
			$arr_info = array();	
			$arr_urls = array();	

			$search_desc_2 = str_ireplace(" ", "%", $search_desc);
			$search_lurl_2 = str_ireplace(" ", "%", $search_lurl);
			$search_surl_2 = str_ireplace(" ", "%", $search_surl);
			$search_user_2 = str_ireplace(" ", "%", $search_user);
			
			$users_arr = Bu_user::Where('name', 'LIKE', '%' . $search_user_2 . '%')->get();
			foreach($users_arr as $user)
			{
				$users_id[] = $user->id;
			}

			$urls_arr = Bu_userurl::where('url_status', '==','0')
				                  ->Where('url_describe', 'LIKE', '%' . $search_desc_2 . '%')
								  ->Where('long_url',     'LIKE', '%' . $search_lurl_2 . '%')
								  ->Where('short_url',    'LIKE', '%' . $search_surl_2 . '%')
								  ->whereIn('user_id', $users_id)
								  ->skip($limlt_begin)->take($page_line_num)
				                  ->get();

			//	遍历，找寻与某一url对应的多个话题信息
			$i = 0;
			foreach($urls_arr as $url)
			{
				if($url->url_status == 0)
				{
					$arr_urls['bu_userurls'] = $url;
					$arr_urls['topics'] = $url->belongsToManyTopic()->get();
					$arr_urls['user'] = $url->belongsToUser()->get();

					//	将url与话题数组打包成大数组
					$arr_info[] = $arr_urls;
					$i++;
				}
			}
			return view('home.HomeUserMore')->with('info', $arr_info);
		}
	}

	/**
	 * 分享网址信息搜索
	 *
	 * @return Response
	 */
	public function myurl_search()
	{
		// 处理表单:
		if (!empty($_POST['search']) && !empty($_POST['user_name']))
		{
			$search = $_POST['search'];
			$search_2 = str_ireplace(" ","%", $search); 
			$arr_info =array();

			$page_line_num = 10;	//	每页显示的条数

			//	检索数据库
			$User = Bu_user::where('name', $_POST['user_name'])->first();
			$User_id = $User->id;

			//	找到某一用户所有的urls
			$Urls = Bu_user::find($User->id)->hasManyUrls()
				                  ->where('url_describe', 'LIKE', '%'.$search_2.'%')
				                  ->get();
			$arr_info['url_info'] = $Urls;

			$arr_info['search_info'] = $search;
			if(Session::has('auth_state'))
			{
				$user_name = Session::get('user_name');
				$user_info = Bu_user::where('name', $user_name)->first();
				$arr_info['user_img'] = $user_info->user_img;
			}
			return view('search.MyurlSearch')->with('info',$arr_info);
		}
		else
		{
			$arr_info = array();
			$arr_info['search_info'] = '';
			return view('search.ErrorSearch')->with('info', $arr_info);
		}
	}

	/**
	 * 用户头像上传
	 *
	 * @return Response
	 */
	public function upload_user_img()
	{
		// 处理表单:
		if (!empty($_POST['file']) AND !empty($_POST['user_name']))
		{
			$user_name = $_POST['user_name'];
			$file = $_POST['file'];

			Bu_user::where('name', $user_name)->update(array('user_img' => $file));
		}
	}

	/**
	 * 话题图片上传
	 *
	 * @return Response
	 */
	public function upload_topic_img()
	{
		// 处理表单:
		if (!empty($_POST['file']) AND !empty($_POST['topic_name']))
		{
			$topic_name = $_POST['topic_name'];
			$file = $_POST['file'];

			//	检索话题信息是否存在
			$topic = '';
			$topic = Bu_topic::where('name', $topic_name)->first();

			//	不存在，新建话题信息
			if($topic == '')
			{
				$created_at = $updated_at = date('Y-m-d H:i:s');
				$topic_id = Bu_topic::insertGetId(
					array(  'name'		=> $topic_name,
							'urlCount'	=> 0,
							'status'	=> 1,
							'created_at'=> $created_at,
							'updated_at'=> $updated_at)
				);
				Bu_topic::where('name', $topic_name)->update(array('topics_img' => $file));
			}
			//	存在，修改话题信息
			else
			{
				Bu_topic::where('name', $topic_name)->update(array('topics_img' => $file));
			}
			return $topic_name;
		}
	}

	/**
	 * 话题删除
	 *
	 * @return Response
	 */
	public function delete_topic()
	{
		// 处理表单:
		if (!empty($_POST['topic_name']))
		{
			$topic_name = $_POST['topic_name'];
			$Topic = Bu_topic::where('name', $topic_name)->first();
			$topic_id = $Topic->id;
			Bu_topic::where('name', $topic_name)->delete();
			Bu_userurl_topic::where('bu_topics_id', $topic_id)->delete();

			return $topic_name;
		}
	}

	/**
	 * 报告问题URL
	 *
	 * @return Response
	 */
	public function report_url()
	{
		if (!empty($_POST['short_url']) && !empty($_POST['post_item']) && !empty($_POST['url_user']))
		{
			$user = Session::get('user_name');
			//	无效链接
			if($_POST['post_item'] == "item_invalid")
			{
				$url = Bu_report_url::where('short_url', $_POST['short_url'])->first();
				if($url == "")
				{
					$created_at = $updated_at = date('Y-m-d H:i:s');
					$url_id = Bu_report_url::insertGetId(
							array(  'short_url'		=> $_POST['short_url'],
									'invalid'		=> 1,
									'harmful'		=> 0,
									'report_man'	=> $user,
									'url_user'	    => $_POST['url_user'],
									'created_at'	=> $created_at,
									'updated_at'	=> $updated_at
							)
					);
				}
				else
				{
					$url_invalid_count = $url->invalid + 1;
					Bu_report_url::where('short_url', $_POST['short_url'])->update(
						array(
							'invalid'		=> $url_invalid_count,
							'report_man'	=> $user,
						)
					);
				}
			}
			//	不良链接
			else if($_POST['post_item'] == "item_harmful")
			{
				$url = Bu_report_url::where('short_url', $_POST['short_url'])->first();
				if($url == "")
				{
					$created_at = $updated_at = date('Y-m-d H:i:s');
					$url_id = Bu_report_url::insertGetId(
							array(  'short_url'		=> $_POST['short_url'],
									'invalid'		=> 0,
									'harmful'		=> 1,
									'report_man'	=> $user,
									'url_user'	    => $_POST['url_user'],
									'created_at'	=> $created_at,
									'updated_at'	=> $updated_at
							)
					);
				}
				else
				{
					$url_harmful_count = $url->harmful + 1;
					Bu_report_url::where('short_url', $_POST['short_url'])->update(
						array(
							'harmful'		=> $url_harmful_count,
							'report_man'	=> $user,
						)
					);
				}
			}
		}
		return $_POST['short_url'];
	}
}
