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
	 * ��ҳ����ʱ������
	 *
	 * @return Response
	 */
	public function index()
	{
		$page_line_num = 10;	//	ÿҳ��ʾ������
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

			//	select Bu_userurl����ʵ��
			$urls_arr = Bu_userurl::where('url_status', '==','0')->orderBy('created_at', 'desc')->skip(0)->take($page_line_num)->get();
	
			//	��������Ѱ��ĳһurl��Ӧ�Ķ��������Ϣ
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
					//	��url�뻰���������ɴ�����
					$arr_info[] = $arr_urls;
				}
			}

			$info['url_info'] = $arr_info;

			//	����ͼҳ�淵�ش˴�����
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
			//	����ͼҳ�淵�ش˴�����
			return view('home.HomeTourist')->with('info',$info);
		}
	}

	
	/**
	 * ��Ϣ�Ƽ�
	 *
	 * @return Response
	 */
	public function recommendUrl() 
	{
		$User_id = $_POST['user_id'];
		$user_name = $_POST['user_name'];

		//	ע����Ϣ�Ƽ�
		if($User_id != "-1")
		{
			$un_arr_recommend_url = array();    //	���Ƽ���url������ע��ļ��Ѿ�������ģ�
			$un_arr_recommend_url_id = array(); //	���Ƽ���url_id
			$arr_recommend_topics = array();    //	�Ƽ��Ļ�����
			$arr_recommend_topics_id = array(); //	�Ƽ��Ļ���id
			$arr_recommend = array();			//	�����Ƽ���Ϣ

			/*
			 * ͳ���û���������Ϣ           
			 */
			$arr_share_topic = array();	//	����������
			$arr_share_surls = array();	//	����shorturl����
			$arr_s_cot_topic = array();	//	��������������
			$share_url_count = 0;		//	����������

			//	�ҵ�ĳһ�û���������е�urls
			$Urls = Bu_user::find($User_id)->hasManyUrls()->get();

			foreach($Urls as $url)
			{
				//	ĳ�û����е�short_url
				$arr_share_surls[] = $url->short_url;

				//	�ҵ�ĳһURL�Ļ�����Ϣ
				$arr_topic = $url->belongsToManyTopic()->get();
				foreach($arr_topic as $topic)
				{
					$arr_share_topic[] = $topic->name;
				}
				$share_url_count++;
			}
			//	ͳ��ÿһ����������
			$arr_s_cot_topic = array_count_values($arr_share_topic);
			$arr_share_surls = array_unique($arr_share_surls);

			/*
			 * ͳ���û����������Ϣ           
			 */
			$arr_visit_topic = array();	//	�����������
			$arr_visit_surls = array();	//	���shorturl����
			$arr_v_cot_topic = array();	//	���������������
			$visit_url_count = 0;		//	�����������

			//	�������ݿ⣬�����û������Ϣ
			$Visit = Bu_userurlvisit::where('user_name', $user_name)->get();

			foreach($Visit as $visit)
			{
				$arr_visit_surls[] = $visit->short_url;

				//	�ҳ�ĳһurl����Ϣ
				$url = Bu_userurl::where('short_url', $visit->short_url)->first();

				//	�ҵ�ĳһURL�Ļ�����Ϣ
				$arr_topic = $url->belongsToManyTopic()->get();
				foreach($arr_topic as $topic)
				{
					$arr_visit_topic[] = $topic->name;
				}
				$visit_url_count++;
			}
			//	ͳ��ÿһ�����������
			$arr_v_cot_topic = array_count_values($arr_visit_topic);
			//	ȥ�����shorturl�����ظ�ֵ
			$arr_visit_surls = array_unique($arr_visit_surls);

			//	ɸѡ�������Ƽ���url
			$un_arr_recommend_url = array_merge($arr_share_surls, $arr_visit_surls);
			$un_arr_recommend_url = array_unique($un_arr_recommend_url);

			//	��ȡ���Ƽ���url_id
			$un_arr_recommend_url_info = Bu_userurl::whereIn('short_url', $un_arr_recommend_url)->get();
			foreach($un_arr_recommend_url_info as $url)
			{
				$un_arr_recommend_url_id[] = $url->id;
			}

			//	ɸѡ�����Ƽ��Ļ�����Ϣ
			//	����&���
			if($share_url_count != 0 AND $visit_url_count != 0)
			{
				//	�����������������Сֵ
				$arr_s_cot_topic = $this->deleteMin($arr_s_cot_topic);
				$arr_v_cot_topic = $this->deleteMin($arr_v_cot_topic);

				//	�Ƽ����� = ���� �� ���
				$arr_intersect_topic = array_intersect_key($arr_s_cot_topic, $arr_v_cot_topic);
				
				//	��ȡ��������
				foreach($arr_intersect_topic as $key => $value)
				{
					$arr_recommend_topics[] = $key;
				}
				
				//	��ȡ�Ƽ�����id
				$topics_id_info = Bu_topic::whereIn('name', $arr_recommend_topics)->get();
				foreach($topics_id_info as $topic)
				{
					$arr_recommend_topics_id[] = $topic->id;
				}

				//	��ȡ�����Ӧȫ��URLid
				$recommend_topic_info = Bu_userurl_topic::whereIn('bu_topics_id', $arr_recommend_topics_id)->get();
				foreach($recommend_topic_info as $topic)
				{
					$recommend_id_arr[] = $topic->bu_userurls_id;
				}
				//	�������urlid����
				$recommend_id_arr = array_unique($recommend_id_arr);
				$recommend_id_arr = array_diff($recommend_id_arr,$un_arr_recommend_url_id);
				
				//	��ȡ�����Ƽ���url_id
				if(count($recommend_id_arr) < 20)
				{
					$recommend_id_rand_key_arr = $recommend_id_arr;
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_arr)->get();
				}
				else
				{
					//	�����ǰ������ֵ��ȡ������Ҫ���ҵ���ֵ��Ӧid
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
			//	����&δ���
			else if($share_url_count != 0 AND $visit_url_count == 0)
			{
				//	������������Сֵ
				$arr_s_cot_topic = $this->deleteMin($arr_s_cot_topic);

				//	�Ƽ����� = ������
				$arr_intersect_topic = $arr_s_cot_topic;
				
				//	��ȡ��������
				foreach($arr_intersect_topic as $key => $value)
				{
					$arr_recommend_topics[] = $key;
				}
				
				//	��ȡ�Ƽ�����id
				$topics_id_info = Bu_topic::whereIn('name', $arr_recommend_topics)->get();
				foreach($topics_id_info as $topic)
				{
					$arr_recommend_topics_id[] = $topic->id;
				}

				//	��ȡ�����Ӧȫ��URLid
				$recommend_topic_info = Bu_userurl_topic::whereIn('bu_topics_id', $arr_recommend_topics_id)->get();
				foreach($recommend_topic_info as $topic)
				{
					$recommend_id_arr[] = $topic->bu_userurls_id;
				}
				//	�������urlid����
				$recommend_id_arr = array_unique($recommend_id_arr);
				$recommend_id_arr = array_diff($recommend_id_arr,$un_arr_recommend_url_id);
				
				//	��ȡ�����Ƽ���url_id
				if(count($recommend_id_arr) < 20)
				{
					$recommend_id_rand_key_arr = $recommend_id_arr;
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_arr)->get();
				}
				else
				{
					//	�����ǰ������ֵ��ȡ������Ҫ���ҵ���ֵ��Ӧid
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
			//	���&δ����
			else if($share_url_count == 0 AND $visit_url_count != 0)
			{
				//	�������������Сֵ
				$arr_v_cot_topic = $this->deleteMin($arr_v_cot_topic);

				//	�Ƽ����� = �������
				$arr_intersect_topic = $arr_v_cot_topic;
				
				//	��ȡ��������
				foreach($arr_intersect_topic as $key => $value)
				{
					$arr_recommend_topics[] = $key;
				}
		
				//	��ȡ�Ƽ�����id
				$topics_id_info = Bu_topic::whereIn('name', $arr_recommend_topics)->get();
				foreach($topics_id_info as $topic)
				{
					$arr_recommend_topics_id[] = $topic->id;
				}

				//	��ȡ�����Ӧȫ��URLid
				$recommend_topic_info = Bu_userurl_topic::whereIn('bu_topics_id', $arr_recommend_topics_id)->get();
				foreach($recommend_topic_info as $topic)
				{
					$recommend_id_arr[] = $topic->bu_userurls_id;
				}
				//	�������urlid����
				$recommend_id_arr = array_unique($recommend_id_arr);
				$recommend_id_arr = array_diff($recommend_id_arr,$un_arr_recommend_url_id);

				//	��ȡ�����Ƽ���url_id
				if(count($recommend_id_arr) < 20)
				{
					$recommend_id_rand_key_arr = $recommend_id_arr;
					$recommend_info = Bu_userurl::whereIn('id', $recommend_id_arr)->get();
				}
				else
				{
					//	�����ǰ������ֵ��ȡ������Ҫ���ҵ���ֵ��Ӧid
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
			//	δ����&δ���
			else
			{
				//	��Ϣ�Ƽ�
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
		//	�ο���Ϣ�Ƽ�
		else
		{
			//	��Ϣ�Ƽ�
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
	 * �޳�������Сֵ
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

			//	�������ֵ�Ƿ�ȫ��һ��
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
	 * ��ҳ�������������
	 *
	 * @return Response
	 */
	public function index_hot()
	{
		$page_line_num = 10;	//	ÿҳ��ʾ������
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

			//	select Bu_userurl����ʵ��
			$urls_arr = Bu_userurl::where('url_status', '==','0')->orderBy('url_pv', 'desc')->skip(0)->take($page_line_num)->get();
	
			//	��������Ѱ��ĳһurl��Ӧ�Ķ��������Ϣ
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
					//	��url�뻰���������ɴ�����
					$arr_info[] = $arr_urls;
				}
			}

			$info['url_info'] = $arr_info;
			
			//	����ͼҳ�淵�ش˴�����
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

			//	����ͼҳ�淵�ش˴�����
			return view('home.HomeTouristHot')->with('info',$info);
		}
	}

	/**
	 * ��ҳ������һҳ����ʱ������
	 *
	 * @return Response
	 */
	public function load_next_page()
	{	
		$arr_info = array();
		$arr_urls = array();

		$limlt_begin = $_GET['limlt_begin'];		//	ÿҳ��ʾ������
		$page_line_num = $_GET['page_line_num'];	//	��ʼ��

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

		//	����ͼҳ�淵�ش˴�����
		return view('home.HomeUserMore')->with('info',$arr_info);
	}

	/**
	 * ��ҳ������һҳ�������������
	 *
	 * @return Response
	 */
	public function load_next_hot_page()
	{	
		$arr_info = array();
		$arr_urls = array();

		$limlt_begin = $_GET['limlt_begin'];		//	ÿҳ��ʾ������
		$page_line_num = $_GET['page_line_num'];	//	��ʼ��

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

		//	����ͼҳ�淵�ش˴�����
		return view('home.HomeUserMore')->with('info',$arr_info);
	}

}
