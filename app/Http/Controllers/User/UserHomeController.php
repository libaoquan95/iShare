<?php namespace App\Http\Controllers\User;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_topic;
use App\Bu_user;
use App\Bu_userurl;
use App\Bu_white_list;
use Session;

class UserHomeController extends Controller {

	/**
	 * �û�����չʾ
	 *
	 * @return Response
	 */
	public function user_urls($userName)
	{
		if(Session::has('auth_state'))
		{
			$users = Bu_user::all();
			$usersWithUserurls = '';
			$page_line_num = 10;	//	ÿҳ��ʾ������
			$arr_info = array();
			$url_info = array();

			//	��ȡ��ǰ�ύ�û���Ϣ
			foreach($users as $user)
			{
				if($user->name == $userName)
				{
					$usersWithUserurls = Bu_userurl::where('url_status', '==','0')
											->where('user_id',$user->id)
											->orderBy('created_at', 'desc')
											->skip(0)->take($page_line_num)
											->get();
					
					//	��������Ѱ��ĳһurl��Ӧ�Ķ��������Ϣ
					foreach($usersWithUserurls as $url)
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
					$arr_info['user_img_2'] = $user_info->user_img;

					$arr_info['user_img'] = $user->user_img;
					$arr_info['user_name'] = $userName;
					$arr_info['urls_info'] = $url_info;
					return view('users.OneUser')->with('info', $arr_info);
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
	 * ������һҳ
	 *
	 * @return Response
	 */
	public function load_next_userurl_page()
	{
		$userName = $_GET['user_name'];				//	����
		$limlt_begin = $_GET['limlt_begin'];		//	ÿҳ��ʾ������
		$page_line_num = $_GET['page_line_num'];	//	��ʼ��
	
		$users = Bu_user::all();

		$arr_info = array();
		$url_info = array();

		//	��ȡ��ǰ�ύ�û���Ϣ
		foreach($users as $user)
		{
			if($user->name == $userName)
			{
				$usersWithUserurls = Bu_userurl::where('url_status', '==','0')
										->where('user_id',$user->id)
										->orderBy('created_at', 'desc')
										->skip($limlt_begin )->take($page_line_num)
										->get();
				
				//	��������Ѱ��ĳһurl��Ӧ�Ķ��������Ϣ
				foreach($usersWithUserurls as $url)
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
				return view('home.HomeUserMore')->with('info',$arr_info);
			}
		}
	}

	/*
	 * ��������
	 */
	public function user_urls_hot($userName)
	{
		if(Session::has('auth_state'))
		{
			$users = Bu_user::all();
			$usersWithUserurls = '';
			$page_line_num = 10;	//	ÿҳ��ʾ������
			$arr_info = array();
			$url_info = array();

			//	��ȡ��ǰ�ύ�û���Ϣ
			foreach($users as $user)
			{
				if($user->name == $userName)
				{
					$usersWithUserurls = Bu_userurl::where('url_status', '==','0')
											->where('user_id',$user->id)
											->orderBy('url_pv', 'desc')
											->skip(0)->take($page_line_num)
											->get();
					
					//	��������Ѱ��ĳһurl��Ӧ�Ķ��������Ϣ
					foreach($usersWithUserurls as $url)
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
					$arr_info['user_img_2'] = $user_info->user_img;

					$arr_info['user_img'] = $user->user_img;
					$arr_info['user_name'] = $userName;
					$arr_info['urls_info'] = $url_info;
					return view('users.OneUserHot')->with('info', $arr_info);
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
	 * ��������������һҳ
	 *
	 * @return Response
	 */
	public function load_next_hot_userurl_page()
	{
		$userName = $_GET['user_name'];				//	����
		$limlt_begin = $_GET['limlt_begin'];		//	ÿҳ��ʾ������
		$page_line_num = $_GET['page_line_num'];	//	��ʼ��
	
		$users = Bu_user::all();

		$arr_info = array();
		$url_info = array();

		//	��ȡ��ǰ�ύ�û���Ϣ
		foreach($users as $user)
		{
			if($user->name == $userName)
			{
				$usersWithUserurls = Bu_userurl::where('url_status', '==','0')
										->where('user_id',$user->id)
										->orderBy('url_pv', 'desc')
										->skip($limlt_begin )->take($page_line_num)
										->get();
				
				//	��������Ѱ��ĳһurl��Ӧ�Ķ��������Ϣ
				foreach($usersWithUserurls as $url)
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
				return view('home.HomeUserMore')->with('info',$arr_info);
			}
		}
	}
}
