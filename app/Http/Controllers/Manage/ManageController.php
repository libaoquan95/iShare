<?php namespace App\Http\Controllers\Manage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_userurl;
use App\Bu_user;
use App\Bu_topic;
use App\Bu_userurlvisit;
use App\Bu_message_board;
use Session;

class ManageController extends Controller {

	/**
	 * 展示用户信息
	 *
	 * @return Response
	 */
	public function index($userName)
	{
		if(Session::has('auth_state') && $userName == Session::get('user_name'))
		{
			/*
	         * 统计用户分享话题信息           
			 */
			$arr_share_topic = array();	//	分享话题数组
			$arr_s_cot_topic = array();	//	分享话题数量数组
			$info = array();
	
			$info['user_name'] = $userName;

			//	检索数据库
			$User = Bu_user::where('name', $userName)->first();
			$User_id = $User->id;
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;
			$info['user_group'] = $user_group;

			//	找到某一用户所有的urls
			$Urls = Bu_user::find($User->id)->hasManyUrls()->get();
			
			$share_url_count = 0;
			foreach($Urls as $url)
			{
				//	找到某一URL的话题信息
				$arr_topic = $url->belongsToManyTopic()->get();
				foreach($arr_topic as $topic)
				{
					$arr_share_topic[] = $topic->name;
				}
				$share_url_count++;
			}
			$info['share_url_count'] = $share_url_count;

			//	统计每一话题数量
			$arr_s_cot_topic = array_count_values($arr_share_topic);
			$info['share_topic_count'] = $arr_s_cot_topic;

			/*
	         * 统计用户浏览话题信息           
			 */
			$arr_visit_topic = array();	//	分享话题数组
			$arr_v_cot_topic = array();	//	分享话题数量数组
	
			//	检索数据库，检索用户浏览信息
			$Visit = Bu_userurlvisit::where('user_name', $userName)->get();
			
			$visit_url_count = 0;
			foreach($Visit as $visit)
			{
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
			$info['visit_url_count'] = $visit_url_count;

			//	统计每一话题数量
			$arr_v_cot_topic = array_count_values($arr_visit_topic);
			$info['visit_topic_count'] = $arr_v_cot_topic;

			return view('manage.Myinfo')->with('info',$info);
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/**
	 * 个人url管理
	 *
	 * @return Response
	 */
	public function allurls_index($userName)
	{
		if(Session::has('auth_state') && $userName == Session::get('user_name'))
		{
			$page = 1;
			$info = array();
			$page_line_num = 20;	//	每页显示的条数

			//	检索数据库
			$User = Bu_user::where('name', $userName)->first();
			$User_id = $User->id;
			$info['user_img'] = $User->user_img;

			//	找到某一用户所有的urls
			$info['urls_info'] = Bu_user::find($User->id)->hasManyUrls()->skip($page_line_num*($page-1))->take($page_line_num)->get();
			$i = 0;
			foreach($info['urls_info'] as $urlinfo)
			{
				$i++;
			}
			if($i==0 || $i<$page_line_num)
			{
				$info['page_next'] = 0;
			}
			else
			{
				$info['page_next'] = $page+1;
			}
			if($page > 1)
			{
				$info['page_last'] = $page-1;
			}
			else
			{
				$info['page_last'] = 0;
			}
			return view('manage.Myurl')->with('info', $info);
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}
	 
	public function allurls($userName, $page)
	{
		if(Session::has('auth_state') && $userName == Session::get('user_name'))
		{
			$info = array();
			$page_line_num = 20;	//	每页显示的条数

			//	检索数据库
			$User = Bu_user::where('name', $userName)->first();
			$User_id = $User->id;
			$info['user_img'] = $User->user_img;

			//	找到某一用户所有的urls
			$info['urls_info'] = Bu_user::find($User->id)->hasManyUrls()->skip($page_line_num*($page-1))->take($page_line_num)->get();
			$i = 0;
			foreach($info['urls_info'] as $urlinfo)
			{
				$i++;
			}
			if($i==0 || $i<$page_line_num)
			{
				$info['page_next'] = 0;
			}
			else
			{
				$info['page_next'] = $page+1;
			}
			if($page > 1)
			{
				$info['page_last'] = $page-1;
			}
			else
			{
				$info['page_last'] = 0;
			}
			return view('manage.Myurl')->with('info', $info);
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/**
	 * 管理员url管理
	 *
	 * @return Response
	 */
	public function administrator_allurl($page)
	{
		if(Session::has('auth_state'))
		{
			$info = array();
			$page_line_num = 20;	//	每页显示的条数

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$User_id = $User->id;
			$User_user_group = $User->user_group;
			$info['user_img'] = $User->user_img;
			
			if($User_user_group == 1)
			{
				//	找到某一用户所有的urls
				$info['urls_info'] = Bu_userurl::skip($page_line_num*($page-1))->take($page_line_num)->get();
				$i = 0;
				foreach($info['urls_info'] as $urlinfo)
				{
					$i++;
				}
				if($i==0 || $i<$page_line_num)
				{
					$info['page_next'] = 0;
				}
				else
				{
					$info['page_next'] = $page+1;
				}
				if($page > 1)
				{
					$info['page_last'] = $page-1;
				}
				else
				{
					$info['page_last'] = 0;
				}
				return view('manage.AdministratorMyurl')->with('info',$info);
			}
			else
			{
				return view('errors.404_pagegone');
			}
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/**
	 * 管理员管理话题页面
	 *
	 * @return Response
	 */
	public function administrator_topic()
	{
		if(Session::has('auth_state'))
		{
			$info = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info['user_group'] = $user_group;
				$topics = Bu_topic::get();
				$info['topics'] = $topics;
				return view('manage.AdministratorTopics')->with('info',$info);
			}
			else
			{
				return view('errors.404_pagegone');
			}
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}
	
	/**
	 * 管理员管理留言板
	 *
	 * @return Response
	 */
	public function administrator_messageboard()
	{
		if(Session::has('auth_state'))
		{
			$info = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info = array();
				$message_arr = array();

				$page_line_num= 10;
				$user_name = Session::get('user_name');
				$user_info = Bu_user::where('name', $user_name)->first();
				$info['user_img'] = $user_info->user_img;

				$message_info = Bu_message_board::orderBy('created_at', 'desc')
										  ->skip(0)->take($page_line_num)
										  ->get();
				foreach($message_info as $message)
				{
					$messages = array();

					$user_info = Bu_user::where('name', $message->user)->first();
					$user_group = $user_info->user_group;

					$messages['user_img'] = $user_info->user_img;
					$messages['messages'] = $message;
					$created_at = $message->created_at;
					$messages['created_at'] = $created_at;
					$messages['created_at_date'] = substr($created_at,2,8);
					$messages['created_at_time'] = substr($created_at,11,5);
					$message_arr[] = $messages;
				}

				$info['message_info'] = $message_arr;
				return view('manage.AdministratorMessageBoardHome')->with('info', $info);
			}
			else
			{
				return view('errors.404_pagegone');
			}
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/*
	 *	状态分类
	 */
	public function many_state($state_name)
	{
		if(Session::has('auth_state'))
		{	
			$info = array();
			$message_arr = array();

			$page_line_num= 10;
			$state = -1;

			$user_name = Session::get('user_name');
			$user_info = Bu_user::where('name', $user_name)->first();
			$user_group = $user_info->user_group;
			$info['user_img'] = $user_info->user_img;

			if($user_group == 1)
			{
				$message_info="";

				if($state_name == "waitaudit")
				{
					$state = 0;
				}
				else if($state_name == "waitsolved")
				{
					$state = 1;
				}
				else if($state_name == "success")
				{
					$state = 2;
				}
				else if($state_name == "error")
				{
					$state = 3;
				}

				switch($state)
				{
					case -1:
						return view('errors.404')->with('info', $info);
					break;

					//	待审查
					case 0:
						$message_info = Bu_message_board::where('state', 0)
										  ->orderBy('created_at', 'desc')
										  ->skip(0)->take($page_line_num)
										  ->get();
						break;
					//	待解决
					case 1:
						$message_info = Bu_message_board::where('state', 1)
										  ->orderBy('created_at', 'desc')
										  ->skip(0)->take($page_line_num)
										  ->get();
						break;
					//	已解决
					case 2:
						$message_info = Bu_message_board::where('state', 2)
										  ->orderBy('created_at', 'desc')
										  ->skip(0)->take($page_line_num)
										  ->get();
						break;
					//	未解决
					case 3:
						$message_info = Bu_message_board::where('state', 3)
										  ->orderBy('created_at', 'desc')
										  ->skip(0)->take($page_line_num)
										  ->get();
						break;
				}

				foreach($message_info as $message)
				{
					$messages = array();

					$user_info = Bu_user::where('name', $message->user)->first();
					$user_group = $user_info->user_group;

					$messages['user_img'] = $user_info->user_img;
					$messages['messages'] = $message;
					$created_at = $message->created_at;
					$messages['created_at'] = $created_at;
					$messages['created_at_date'] = substr($created_at,2,8);
					$messages['created_at_time'] = substr($created_at,11,5);
					$message_arr[] = $messages;
				}

				$info['message_info'] = $message_arr;
				
				switch($state)
				{
					//	待审查
					case 0:
						return view('manage.AdministratorMessageBoardWaitaudit')->with('info', $info);
						break;
					//	待解决
					case 1:
						return view('manage.AdministratorMessageBoardWaitsolved')->with('info', $info);
						break;
					//	已解决
					case 2:
						return view('manage.AdministratorMessageBoardSuccess')->with('info', $info);
						break;
					//	未解决
					case 3:
						return view('manage.AdministratorMessageBoardError')->with('info', $info);
						break;
				}
			}
			else
			{
				return view('errors.404_pagegone');
			}
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/*
	 *	下一页
	 */
	public function administrator_messageboard_next_page()
	{
		$info = array();
		$message_arr = array();

		$limlt_begin = $_POST['limlt_begin'];
		$page_line_num = $_POST['page_line_num'];
		$state = $_POST['state'];		

		$user_name = Session::get('user_name');
		$user_info = Bu_user::where('name', $user_name)->first();
		$info['user_img'] = $user_info->user_img;

		$message_info="";
		switch($state)
		{
			//	全部
			case -1:
				$message_info = Bu_message_board::orderBy('created_at', 'desc')
								  ->skip($limlt_begin)->take($page_line_num)
								  ->get();
				break;
			//	待审查
			case 0:
				$message_info = Bu_message_board::where('state', 0)
				                  ->orderBy('created_at', 'desc')
								  ->skip($limlt_begin)->take($page_line_num)
								  ->get();
				break;
			//	待解决
			case 1:
				$message_info = Bu_message_board::where('state', 1)
				                  ->orderBy('created_at', 'desc')
								  ->skip($limlt_begin)->take($page_line_num)
								  ->get();
				break;
			//	已解决
			case 2:
				$message_info = Bu_message_board::where('state', 2)
				                  ->orderBy('created_at', 'desc')
								  ->skip($limlt_begin)->take($page_line_num)
								  ->get();
				break;
			//	未解决
			case 3:
				$message_info = Bu_message_board::where('state', 3)
				                  ->orderBy('created_at', 'desc')
								  ->skip($limlt_begin)->take($page_line_num)
								  ->get();
				break;

		}
		foreach($message_info as $message)
		{
			$messages = array();

			$user_info = Bu_user::where('name', $message->user)->first();
			$user_group = $user_info->user_group;

			$messages['user_img'] = $user_info->user_img;
			$messages['messages'] = $message;
			$created_at = $message->created_at;
			$messages['created_at'] = $created_at;
			$messages['created_at_date'] = substr($created_at,2,8);
			$messages['created_at_time'] = substr($created_at,11,5);
			$message_arr[] = $messages;
		}

		$info['message_info'] = $message_arr;
		return view('manage.AdministratorMessageBoardMore')->with('info', $info);
	}
}
