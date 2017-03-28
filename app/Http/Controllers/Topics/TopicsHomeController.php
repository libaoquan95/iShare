<?php namespace App\Http\Controllers\Topics;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_topic;
use App\Bu_user;
use App\Bu_userurl;
use App\Bu_white_list;
use Session;

class TopicsHomeController extends Controller {

	/**
	 * �����б�
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Session::has('auth_state'))
		{
			$user_name = Session::get('user_name');
			$user_info = Bu_user::where('name', $user_name)->first();
			$info['user_img'] = $user_info->user_img;

			$info['topics_info'] = Bu_topic::all();
			return view('topics.TopicsHome')->with('info', $info);
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/**
	 * ����ѡ�񡪡�ʱ��˳��
	 *
	 * @return Response
	 */
	public function topic($name)
	{
		if(Session::has('auth_state'))
		{
			$topics = Bu_topic::all();
			$topic_name = $name;
			$topic_id = 0;
			$topicsWithUserurls = '';
			$page_line_num = 10;	//	ÿҳ��ʾ������
			$arr_info = array();
			$url_info = array();

			//	��ȡ��ǰ�ύ������Ϣ
			foreach($topics as $topic)
			{
				if($topic->name == $name)
				{	
					$arr_info['topic_name'] = $topic->name;
					$arr_info['topic_img'] = $topic->topics_img;
					$arr_info['topic_id'] = $topic->id;
					$arr_info['topic_urlCount'] = $topic->urlCount;
					$topicsWithUserurls = $topic->belongsToManyUserurl()->orderBy('created_at', 'desc')->skip(0)->take($page_line_num)->get();
					//$arr_info['bu_userurls'] = $topicsWithUserurls;
					
					//	��������Ѱ��ĳһurl��Ӧ�Ķ��������Ϣ
					foreach($topicsWithUserurls as $url)
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
							$url_info[] = $arr_urls;
						}
					}

					$user_name = Session::get('user_name');
					$user_info = Bu_user::where('name', $user_name)->first();
					$arr_info['user_img'] = $user_info->user_img;

					$arr_info['urls_info'] = $url_info;
					return view('topics.OneTopic')->with('info', $arr_info);
				}
			}
			return view('errors.404');
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/**
	 * ����ҳ������һҳ����ʱ��˳��
	 *
	 * @return Response
	 */
	public function load_topic_next_page()
	{
		$name = $_GET['topic_name'];				//	����
		$limlt_begin = $_GET['limlt_begin'];		//	ÿҳ��ʾ������
		$page_line_num = $_GET['page_line_num'];	//	��ʼ��

		$topics = Bu_topic::all();

		$arr_info = array();
		$url_info = array();

		//	��ȡ��ǰ�ύ������Ϣ
		foreach($topics as $topic)
		{
			if($topic->name == $name)
			{	
				$urls_arr = $topic->belongsToManyUserurl()->orderBy('created_at', 'desc')->skip($limlt_begin)->take($page_line_num)->get();

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

				return view('home.HomeUserMore')->with('info',$arr_info);
			}
		}
	}

	/**
	 * ����ѡ�񡪡������˳��
	 *
	 * @return Response
	 */
	public function topic_hot($name)
	{
		if(Session::has('auth_state'))
		{
			$topics = Bu_topic::all();
			$topic_name = $name;
			$topic_id = 0;
			$topicsWithUserurls = '';
			$page_line_num = 10;	//	ÿҳ��ʾ������
			$arr_info = array();
			$url_info = array();

			//	��ȡ��ǰ�ύ������Ϣ
			foreach($topics as $topic)
			{
				if($topic->name == $name)
				{	
					$arr_info['topic_name'] = $topic->name;
					$arr_info['topic_img'] = $topic->topics_img;
					$arr_info['topic_id'] = $topic->id;
					$arr_info['topic_urlCount'] = $topic->urlCount;
					$topicsWithUserurls = $topic->belongsToManyUserurl()->orderBy('url_pv', 'desc')->skip(0)->take($page_line_num)->get();
					//$arr_info['bu_userurls'] = $topicsWithUserurls;
					
					//	��������Ѱ��ĳһurl��Ӧ�Ķ��������Ϣ
					foreach($topicsWithUserurls as $url)
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
							$url_info[] = $arr_urls;
						}
					}
					$user_name = Session::get('user_name');
					$user_info = Bu_user::where('name', $user_name)->first();
					$arr_info['user_img'] = $user_info->user_img;

					$arr_info['urls_info'] = $url_info;
					return view('topics.OneTopicHot')->with('info', $arr_info);
				}
			}
			return view('errors.404');
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/**
	 * ����ҳ������һҳ���������˳��
	 *
	 * @return Response
	 */
	public function load_topic_next_hot_page()
	{
		$name = $_GET['topic_name'];				//	����
		$limlt_begin = $_GET['limlt_begin'];		//	ÿҳ��ʾ������
		$page_line_num = $_GET['page_line_num'];	//	��ʼ��

		$topics = Bu_topic::all();

		$arr_info = array();
		$url_info = array();

		//	��ȡ��ǰ�ύ������Ϣ
		foreach($topics as $topic)
		{
			if($topic->name == $name)
			{	
				$urls_arr = $topic->belongsToManyUserurl()->orderBy('url_pv', 'desc')->skip($limlt_begin)->take($page_line_num)->get();

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

				return view('home.HomeUserMore')->with('info',$arr_info);
			}
		}
	}
}
