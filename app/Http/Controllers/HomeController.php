<?php namespace App\Http\Controllers;

use DB;
use App\Bu_userurl;
use App\Bu_user;
use App\Bu_userurlvisit;
use App\Bu_urlvisit;
use App\Bu_topic;
use App\Bu_userurl_topic;
use App\Bu_white_list;
use Session;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 
	public function __construct()
	{
		$this->middleware('auth');
	}*/

	/**
	 * 首页——时间排序
	 *
	 * @return Response
	 */
	public function index()
	{
		$page_line_num = 10;	//	每页显示的条数
		$arr_info = array();	
		$arr_urls = array();	
		$info = array();

		if(Session::has('auth_state'))
		{
			$user_name = Session::get('user_name');
			$user_info = Bu_user::where('name', $user_name)->first();
			$User_id = $user_info->id;
			$user_group = $user_info->user_group;
			$info['user_name'] = Session::get('user_name');
			$info['user_id'] = $user_info->id;
			$info['user_img'] = $user_info->user_img;

			//	select Bu_userurl对象实例
			$urls_arr = Bu_userurl::where('url_status', '==','0')->orderBy('created_at', 'desc')->skip(0)->take($page_line_num)->get();
	
			//	遍历，找寻与某一url对应的多个话题信息
			foreach($urls_arr as $url)
			{
				if($url->url_status == 0)
				{
					$arr_urls['bu_userurls'] = $url;
					$arr_urls['topics'] = $url->belongsToManyTopic()->get();
					$arr_urls['user'] = $url->belongsToUser()->get();

					if(strlen($url->short_url) < 10)
					{
						$short_demo = substr($url->short_url,0,4);
						$white_list = Bu_white_list::where('short_demo',$short_demo)->first();
						if($white_list != "")
						{
							$arr_urls['white_list'] = 1;
							$arr_urls['white_title'] = $white_list->demo_name;
						}
						else
						{
							$arr_urls['white_list'] = 0;
							$arr_urls['white_title'] = "";
						}
					}
					else
					{
						$arr_urls['white_list'] = 0;
						$arr_urls['white_title'] = "";
					}
					//	将url与话题数组打包成大数组
					$arr_info[] = $arr_urls;
				}
			}

			$info['url_info'] = $arr_info;

			//	向视图页面返回此大数组
			return view('home.HomeUser')->with('info',$info);
		}
		else
		{
			$urls_arr = Bu_userurl::orderBy('created_at', 'desc')->skip(0)->take($page_line_num)->get();
			
			foreach($urls_arr as $url)
			{
				if($url->url_status == 0)
				{
					$arr_urls['bu_userurls'] = $url;
					$arr_urls['topics'] = $url->belongsToManyTopic()->get();
					$arr_urls['user'] = $url->belongsToUser()->get();

					if(strlen($url->short_url) < 10)
					{
						$short_demo = substr($url->short_url,0,4);
						$white_list = Bu_white_list::where('short_demo',$short_demo)->first();
						if($white_list != "")
						{
							$arr_urls['white_list'] = 1;
							$arr_urls['white_title'] = $white_list->demo_name;
						}
						else
						{
							$arr_urls['white_list'] = 0;
							$arr_urls['white_title'] = "";
						}
					}
					else
					{
						$arr_urls['white_list'] = 0;
						$arr_urls['white_title'] = "";
					}
					$arr_info[] = $arr_urls;
				}
			}
			$info['url_info'] = $arr_info;
			//	向视图页面返回此大数组
			return view('home.HomeTourist')->with('info',$info);
		}
	}

	
	/**
	 * 信息推荐
	 *
	 * @return Response
	 */
	public function recommendUrl() 
	{
		$User_id = $_POST['user_id'];
		$user_name = $_POST['user_name'];

		//	注册信息推荐
		if($User_id != "-1")
		{
			$un_arr_recommend_url = array();    //	不推荐的url（本人注册的及已经浏览过的）
			$un_arr_recommend_url_id = array(); //	不推荐的url_id
			$arr_recommend_topics = array();    //	推荐的话题名
			$arr_recommend_topics_id = array(); //	推荐的话题id
			$arr_recommend = array();			//	最终推荐信息

			/*
			 * 统计用户分享话题信息           
			 */
			$arr_share_topic = array();	//	分享话题数组
			$arr_share_surls = array();	//	分享shorturl数组
			$arr_s_cot_topic = array();	//	分享话题数量数组
			$share_url_count = 0;		//	分享话题数量

			//	找到某一用户分享的所有的urls
			$Urls = Bu_user::find($User_id)->hasManyUrls()->get();

			foreach($Urls as $url)
			{
				//	某用户所有的short_url
				$arr_share_surls[] = $url->short_url;

				//	找到某一URL的话题信息
				$arr_topic = $url->belongsToManyTopic()->get();
				foreach($arr_topic as $topic)
				{
					$arr_share_topic[] = $topic->name;
				}
				$share_url_count++;
			}
			//	统计每一分享话题数量
			$arr_s_cot_topic = array_count_values($arr_share_topic);
			$arr_share_surls = array_unique($arr_share_surls);

			/*
			 * 统计用户浏览话题信息           
			 */
			$arr_visit_topic = array();	//	浏览话题数组
			$arr_visit_surls = array();	//	浏览shorturl数组
			$arr_v_cot_topic = array();	//	浏览话题数量数组
			$visit_url_count = 0;		//	浏览话题数量

			//	检索数据库，检索用户浏览信息
			$Visit = Bu_userurlvisit::where('user_name', $user_name)->get();

			foreach($Visit as $visit)
			{
				$arr_visit_surls[] = $visit->short_url;

				//	找出某一url的信息
				$url = Bu_userurl::where('short_url', $visit->short_url)->first();

				//	找到某一URL的话题信息
				$arr_topic = $url->belongsToManyTopic()->get();
				foreach($arr_topic as $topic)
				{
					$arr_visit_topic[] = $topic->name;
				}
				$visit_url_count++;
			}
			//	统计每一浏览话题数量
			$arr_v_cot_topic = array_count_values($arr_visit_topic);
			//	去除浏览shorturl数组重复值
			$arr_visit_surls = array_unique($arr_visit_surls);

			//	筛选不进行推荐的url
			$un_arr_recommend_url = array_merge($arr_share_surls, $arr_visit_surls);
			$un_arr_recommend_url = array_unique($un_arr_recommend_url);

			//	获取不推荐的url_id
			$un_arr_recommend_url_info = Bu_userurl::whereIn('short_url', $un_arr_recommend_url)->get();
			foreach($un_arr_recommend_url_info as $url)
			{
				$un_arr_recommend_url_id[] = $url->id;
			}

			//	筛选进行推荐的话题信息
			//	分享&浏览
			if($share_url_count != 0 AND $visit_url_count != 0)
			{
				//	消除分享、浏览话题最小值
				$arr_s_cot_topic = $this->deleteMin($arr_s_cot_topic);
				$arr_v_cot_topic = $this->deleteMin($arr_v_cot_topic);

				//	推荐话题 = 分享 交 浏览
				$arr_intersect_topic = array_intersect_key($arr_s_cot_topic, $arr_v_cot_topic);
				
				//	提取话题名称
				foreach($arr_intersect_topic as $key => $value)
				{
					$arr_recommend_topics[] = $key;
				}
				
				//	获取推荐话题id
				$topics_id_info = Bu_topic::whereIn('name', $arr_recommend_topics)->get();
				foreach($topics_id_info as $topic)
				{
					$arr_recommend_topics_id[] = $topic->id;
				}

				//	获取话题对应全部URLid
				$recommend_topic_info = Bu_userurl_topic::whereIn('bu_topics_id', $arr_recommend_topics_id)->get();
				foreach($recommend_topic_info as $topic)
				{
					$recommend_id_arr[] = $topic->bu_userurls_id;
				}
				//	清除冗余urlid数据
				$recommend_id_arr = array_unique($recommend_id_arr);
				$recommend_id_arr = array_diff($recommend_id_arr,$un_arr_recommend_url_id);
				
				//	抽取进行推荐的url_id
				if(count($recommend_id_arr) < 20)
				{
					$recommend_id_rand_key_arr = $recommend_id_arr;
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_arr)->get();
				}
				else
				{
					//	由于是按数组键值抽取，所以要将找到键值对应id
					$recommend_id_rand_key_arr = array_rand($recommend_id_arr, 20);
					foreach($recommend_id_rand_key_arr as $key)
					{
						$recommend_id_rand_arr[] = $recommend_id_arr[$key];
					}
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_rand_arr)->get();
				}
				foreach($recommend_info as $url)
				{
					if($url->url_status == 0)
					{
						$arr_recommend[] = $url;
					}
				}
				return view('home.HomeUserrecommend')->with('recommend_info',$arr_recommend);
			}
			//	分享&未浏览
			else if($share_url_count != 0 AND $visit_url_count == 0)
			{
				//	消除分享话题最小值
				$arr_s_cot_topic = $this->deleteMin($arr_s_cot_topic);

				//	推荐话题 = 分享话题
				$arr_intersect_topic = $arr_s_cot_topic;
				
				//	提取话题名称
				foreach($arr_intersect_topic as $key => $value)
				{
					$arr_recommend_topics[] = $key;
				}
				
				//	获取推荐话题id
				$topics_id_info = Bu_topic::whereIn('name', $arr_recommend_topics)->get();
				foreach($topics_id_info as $topic)
				{
					$arr_recommend_topics_id[] = $topic->id;
				}

				//	获取话题对应全部URLid
				$recommend_topic_info = Bu_userurl_topic::whereIn('bu_topics_id', $arr_recommend_topics_id)->get();
				foreach($recommend_topic_info as $topic)
				{
					$recommend_id_arr[] = $topic->bu_userurls_id;
				}
				//	清除冗余urlid数据
				$recommend_id_arr = array_unique($recommend_id_arr);
				$recommend_id_arr = array_diff($recommend_id_arr,$un_arr_recommend_url_id);
				
				//	抽取进行推荐的url_id
				if(count($recommend_id_arr) < 20)
				{
					$recommend_id_rand_key_arr = $recommend_id_arr;
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_arr)->get();
				}
				else
				{
					//	由于是按数组键值抽取，所以要将找到键值对应id
					$recommend_id_rand_key_arr = array_rand($recommend_id_arr, 20);
					foreach($recommend_id_rand_key_arr as $key)
					{
						$recommend_id_rand_arr[] = $recommend_id_arr[$key];
					}
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_rand_arr)->get();
				}
				foreach($recommend_info as $url)
				{
					if($url->url_status == 0)
					{
						$arr_recommend[] = $url;
					}
				}
				return view('home.HomeUserrecommend')->with('recommend_info',$arr_recommend);
			}
			//	浏览&未分享
			else if($share_url_count == 0 AND $visit_url_count != 0)
			{
				//	消除浏览话题最小值
				$arr_v_cot_topic = $this->deleteMin($arr_v_cot_topic);

				//	推荐话题 = 浏览话题
				$arr_intersect_topic = $arr_v_cot_topic;
				
				//	提取话题名称
				foreach($arr_intersect_topic as $key => $value)
				{
					$arr_recommend_topics[] = $key;
				}
		
				//	获取推荐话题id
				$topics_id_info = Bu_topic::whereIn('name', $arr_recommend_topics)->get();
				foreach($topics_id_info as $topic)
				{
					$arr_recommend_topics_id[] = $topic->id;
				}

				//	获取话题对应全部URLid
				$recommend_topic_info = Bu_userurl_topic::whereIn('bu_topics_id', $arr_recommend_topics_id)->get();
				foreach($recommend_topic_info as $topic)
				{
					$recommend_id_arr[] = $topic->bu_userurls_id;
				}
				//	清除冗余urlid数据
				$recommend_id_arr = array_unique($recommend_id_arr);
				$recommend_id_arr = array_diff($recommend_id_arr,$un_arr_recommend_url_id);

				//	抽取进行推荐的url_id
				if(count($recommend_id_arr) < 20)
				{
					$recommend_id_rand_key_arr = $recommend_id_arr;
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_arr)->get();
				}
				else
				{
					//	由于是按数组键值抽取，所以要将找到键值对应id
					$recommend_id_rand_key_arr = array_rand($recommend_id_arr, 20);
					foreach($recommend_id_rand_key_arr as $key)
					{
						$recommend_id_rand_arr[] = $recommend_id_arr[$key];
					}
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_rand_arr)->get();
				}
				foreach($recommend_info as $url)
				{
					if($url->url_status == 0)
					{
						$arr_recommend[] = $url;
					}
				}
				return view('home.HomeUserrecommend')->with('recommend_info',$arr_recommend);
			}
			//	未分享&未浏览
			else
			{
				//	信息推荐
				$recommend_info = DB::select('SELECT * FROM bu_userurls WHERE url_status=0 ORDER BY rand() LIMIT 20');

				foreach($recommend_info as $url)
				{
					if($url->url_status == 0)
					{
						$arr_recommend[] = $url;
					}
				}
				return view('home.HomeUserrecommend')->with('recommend_info',$arr_recommend);
			}
		}
		//	游客信息推荐
		else
		{
			//	信息推荐
			$recommend_info = DB::select('SELECT * FROM bu_userurls WHERE url_status=0 ORDER BY rand() LIMIT 20');

			foreach($recommend_info as $url)
			{
				if($url->url_status == 0)
				{
					$arr_recommend[] = $url;
				}
			}
			return view('home.HomeUserrecommend')->with('recommend_info',$arr_recommend);
		}
	}

	/**
	 * 剔除数组最小值
	 *
	 * @return Response
	 */
	public function deleteMin($arr2) 
	{
		if(count($arr2) > 1)
		{
			$arr = array();
			foreach($arr2 as $value)
			{
				$arr[] = $value;
			}
			$cmpTime = 0;
			$count = count($arr);
			$biggest = $smallest = $arr[$count-1];

			//	检查数组值是否全部一致
			$mark = 0;
			$first_value = $arr[0];
			for($i = 0; $i < $count - 1; $i += 1) 
			{
				if($first_value != $arr[$i])
				{
					$mark = 1;
					break;
				}
			}
			if($mark == 1)
			{
				for($i = 0; $i < $count - 1; $i += 2) 
				{
					$cmpTime++;
					if($arr[$i] > $arr[$i + 1]) 
					{
						$bigger = $arr[$i];
						$smaller = $arr[$i + 1];
					}
					else
					{
						$bigger = $arr[$i + 1];
						$smaller = $arr[$i];   
					}
					$cmpTime++;    
					if($bigger > $biggest) 
					{
						$biggest = $bigger;
					}
					$cmpTime++;
					if($smaller < $smallest) 
					{
						$smallest = $smaller;
					}
				}
				$ret_arr = array();
				foreach($arr2 as $key => $value) 
				{
					if($value != $smallest)
					{
						$ret_arr[$key] = $value;
					}
				}
				return $ret_arr;
			}
			else
			{
				return $arr2;
			}
		}
		else
		{
			return $arr2;
		}
	}

	/**
	 * 首页——浏览量排序
	 *
	 * @return Response
	 */
	public function index_hot()
	{
		$page_line_num = 10;	//	每页显示的条数
		$arr_info = array();	
		$arr_urls = array();	
		$arr_recommend = array();
		$recommend = array();	
		$info = array();

		if(Session::has('auth_state'))
		{	
			$user_name = Session::get('user_name');
			$user_info = Bu_user::where('name', $user_name)->first();
			$User_id = $user_info->id;
			$user_group = $user_info->user_group;
			$info['user_name'] = Session::get('user_name');
			$info['user_id'] = $user_info->id;
			$info['user_img'] = $user_info->user_img;

			//	select Bu_userurl对象实例
			$urls_arr = Bu_userurl::where('url_status', '==','0')->orderBy('url_pv', 'desc')->skip(0)->take($page_line_num)->get();
	
			//	遍历，找寻与某一url对应的多个话题信息
			foreach($urls_arr as $url)
			{
				if($url->url_status == 0)
				{
					$arr_urls['bu_userurls'] = $url;
					$arr_urls['topics'] = $url->belongsToManyTopic()->get();
					$arr_urls['user'] = $url->belongsToUser()->get();

					if(strlen($url->short_url) < 10)
					{
						$short_demo = substr($url->short_url,0,4);
						$white_list = Bu_white_list::where('short_demo',$short_demo)->first();
						if($white_list != "")
						{
							$arr_urls['white_list'] = 1;
							$arr_urls['white_title'] = $white_list->demo_name;
						}
						else
						{
							$arr_urls['white_list'] = 0;
							$arr_urls['white_title'] = "";
						}
					}
					else
					{
						$arr_urls['white_list'] = 0;
						$arr_urls['white_title'] = "";
					}
					//	将url与话题数组打包成大数组
					$arr_info[] = $arr_urls;
				}
			}

			$info['url_info'] = $arr_info;
			
			//	向视图页面返回此大数组
			return view('home.HomeUserHot')->with('info',$info);
		}
		else
		{
			$urls_arr = Bu_userurl::where('url_status', '==','0')->orderBy('url_pv', 'desc')->skip(0)->take($page_line_num)->get();
			
			foreach($urls_arr as $url)
			{
				if($url->url_status == 0)
				{
					$arr_urls['bu_userurls'] = $url;
					$arr_urls['topics'] = $url->belongsToManyTopic()->get();
					$arr_urls['user'] = $url->belongsToUser()->get();

					if(strlen($url->short_url) < 10)
					{
						$short_demo = substr($url->short_url,0,4);
						$white_list = Bu_white_list::where('short_demo',$short_demo)->first();
						if($white_list != "")
						{
							$arr_urls['white_list'] = 1;
							$arr_urls['white_title'] = $white_list->demo_name;
						}
						else
						{
							$arr_urls['white_list'] = 0;
							$arr_urls['white_title'] = "";
						}
					}
					else
					{
						$arr_urls['white_list'] = 0;
						$arr_urls['white_title'] = "";
					}
					$arr_info[] = $arr_urls;
				}
			}
			$info['url_info'] = $arr_info;

			//	向视图页面返回此大数组
			return view('home.HomeTouristHot')->with('info',$info);
		}
	}

	/**
	 * 首页加载下一页——时间排序
	 *
	 * @return Response
	 */
	public function load_next_page()
	{	
		$arr_info = array();
		$arr_urls = array();

		$limlt_begin = $_GET['limlt_begin'];		//	每页显示的条数
		$page_line_num = $_GET['page_line_num'];	//	开始条

		$urls_arr = Bu_userurl::where('url_status', '==','0')->orderBy('created_at', 'desc')->skip($limlt_begin)->take($page_line_num)->get();

		foreach($urls_arr as $url)
		{
			if($url->url_status == 0)
			{
				$arr_urls['bu_userurls'] = $url;
				$arr_urls['topics'] = $url->belongsToManyTopic()->get();
				$arr_urls['user'] = $url->belongsToUser()->get();

				if(strlen($url->short_url) < 10)
				{
					$short_demo = substr($url->short_url,0,4);
					$white_list = Bu_white_list::where('short_demo',$short_demo)->first();
					if($white_list != "")
					{
						$arr_urls['white_list'] = 1;
						$arr_urls['white_title'] = $white_list->demo_name;
					}
					else
					{
						$arr_urls['white_list'] = 0;
						$arr_urls['white_title'] = "";
					}
				}
				else
				{
					$arr_urls['white_list'] = 0;
					$arr_urls['white_title'] = "";
				}
				$arr_info[] = $arr_urls;
			}
		}

		//	向视图页面返回此大数组
		return view('home.HomeUserMore')->with('info',$arr_info);
	}

	/**
	 * 首页加载下一页——浏览量排序
	 *
	 * @return Response
	 */
	public function load_next_hot_page()
	{	
		$arr_info = array();
		$arr_urls = array();

		$limlt_begin = $_GET['limlt_begin'];		//	每页显示的条数
		$page_line_num = $_GET['page_line_num'];	//	开始条

		$urls_arr = Bu_userurl::where('url_status', '==','0')->orderBy('url_pv', 'desc')->skip($limlt_begin)->take($page_line_num)->get();

		foreach($urls_arr as $url)
		{
			if($url->url_status == 0)
			{
				$arr_urls['bu_userurls'] = $url;
				$arr_urls['topics'] = $url->belongsToManyTopic()->get();
				$arr_urls['user'] = $url->belongsToUser()->get();

				if(strlen($url->short_url) < 10)
				{
					$short_demo = substr($url->short_url,0,4);
					$white_list = Bu_white_list::where('short_demo',$short_demo)->first();
					if($white_list != "")
					{
						$arr_urls['white_list'] = 1;
						$arr_urls['white_title'] = $white_list->demo_name;
					}
					else
					{
						$arr_urls['white_list'] = 0;
						$arr_urls['white_title'] = "";
					}
				}
				else
				{
					$arr_urls['white_list'] = 0;
					$arr_urls['white_title'] = "";
				}
				$arr_info[] = $arr_urls;
			}
		}

		//	向视图页面返回此大数组
		return view('home.HomeUserMore')->with('info',$arr_info);
	}

}
