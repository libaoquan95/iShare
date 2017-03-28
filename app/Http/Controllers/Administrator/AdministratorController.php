<?php namespace App\Http\Controllers\Administrator;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_userurl;
use App\Bu_user;
use App\Bu_topic;
use App\Bu_userurlvisit;
use App\Bu_message_board;
use App\Bu_report_url;
use App\Bu_white_list;
use Session;

class AdministratorController extends Controller {
	
	/**
	 * 管理员主页面
	 *
	 * @return Response
	 */
	public function index()
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

				return view('administrator.AdministratorHome')->with('info',$info);
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
	 * 管理员url管理
	 *
	 * @return Response
	 */
	public function allurl($page)
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
				return view('administrator.Allurl')->with('info',$info);
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
	public function topics()
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
				return view('administrator.Topics')->with('info',$info);
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
	public function messageboard()
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
				return view('administrator.MessageboardHome')->with('info', $info);
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
						return view('administrator.MessageBoardWaitaudit')->with('info', $info);
						break;
					//	待解决
					case 1:
						return view('administrator.MessageBoardWaitsolved')->with('info', $info);
						break;
					//	已解决
					case 2:
						return view('administrator.MessageBoardSuccess')->with('info', $info);
						break;
					//	未解决
					case 3:
						return view('administrator.MessageBoardError')->with('info', $info);
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
	public function messageboard_next_page()
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
		return view('administrator.MessageBoardMore')->with('info', $info);
	}

	/**
	 * 管理员管理注册用户_全部用户
	 *
	 * @return Response
	 */
	public function users()
	{
		if(Session::has('auth_state'))
		{
			$info = array();
			$user_info = array();
			$user_info2 = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info['user_group'] = $user_group;
				$users = Bu_user::get();
				
				foreach($users as $user)
				{
					$user_info2['base_info'] = $user;
					$user_info2['invalid_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('invalid','<>','0')
						                    ->count();
					$user_info2['harmful_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('harmful','<>','0')
						                    ->count();
					$user_info[] = $user_info2;
				}
				$info['users'] = $user_info;
				return view('administrator.Users')->with('info',$info);
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
	 * 管理员管理注册用户_管理员用户
	 *
	 * @return Response
	 */
	public function users_admin()
	{
		if(Session::has('auth_state'))
		{
			$info = array();
			$user_info = array();
			$user_info2 = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info['user_group'] = $user_group;
				$users = Bu_user::where('user_group',1)->get();
				
				foreach($users as $user)
				{
					$user_info2['base_info'] = $user;
					$user_info2['invalid_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('invalid','<>','0')
						                    ->count();
					$user_info2['harmful_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('harmful','<>','0')
						                    ->count();
					$user_info[] = $user_info2;
				}
				$info['users'] = $user_info;
				return view('administrator.Users')->with('info',$info);
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
	 * 管理员管理注册用户_普通用户
	 *
	 * @return Response
	 */
	public function users_normal()
	{
		if(Session::has('auth_state'))
		{
			$info = array();
			$user_info = array();
			$user_info2 = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info['user_group'] = $user_group;
				$users = Bu_user::where('user_group',2)->get();
				
				foreach($users as $user)
				{
					$user_info2['base_info'] = $user;
					$user_info2['invalid_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('invalid','<>','0')
						                    ->count();
					$user_info2['harmful_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('harmful','<>','0')
						                    ->count();
					$user_info[] = $user_info2;
				}
				$info['users'] = $user_info;
				return view('administrator.Users')->with('info',$info);
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
	 * 管理员管理注册用户_封禁用户
	 *
	 * @return Response
	 */
	public function users_forbid()
	{
		if(Session::has('auth_state'))
		{
			$info = array();
			$user_info = array();
			$user_info2 = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info['user_group'] = $user_group;
				$users = Bu_user::where('user_state',0)->get();
				
				foreach($users as $user)
				{
					$user_info2['base_info'] = $user;
					$user_info2['invalid_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('invalid','<>','0')
						                    ->count();
					$user_info2['harmful_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('harmful','<>','0')
						                    ->count();
					$user_info[] = $user_info2;
				}
				$info['users'] = $user_info;
				return view('administrator.Users')->with('info',$info);
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
	 * 管理员管理注册用户_按无效网址数量牌排序
	 *
	 * @return Response
	 */
	public function sort_for_invalid()
	{
		if(Session::has('auth_state'))
		{
			/*$info = array();
			$user_info = array();
			$user_info2 = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info['user_group'] = $user_group;
				$users = Bu_user::where('user_state',0)->get();
				
				foreach($users as $user)
				{
					$user_info2['base_info'] = $user;
					$user_info2['invalid_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('invalid','<>','0')
						                    ->count();
					$user_info2['harmful_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('harmful','<>','0')
						                    ->count();
					$user_info[] = $user_info2;
				}
				$info['users'] = $user_info;
				return view('administrator.Users')->with('info',$info);
			}
			else
			{
				return view('errors.404_pagegone');
			}*/
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/**
	 * 管理员管理注册用户_按不良网址数量牌排序
	 *
	 * @return Response
	 */
	public function sort_for_harmful()
	{
		if(Session::has('auth_state'))
		{
			$info = array();
			$user_info = array();
			$user_info2 = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info['user_group'] = $user_group;
				$users = Bu_user::where('user_state',0)->get();
				
				foreach($users as $user)
				{
					$user_info2['base_info'] = $user;
					$user_info2['invalid_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('invalid','<>','0')
						                    ->count();
					$user_info2['harmful_count'] = Bu_report_url::where('url_user',$user->name)
						                    ->where('harmful','<>','0')
						                    ->count();
					$user_info[] = $user_info2;
				}
				$info['users'] = $user_info;
				return view('administrator.Users')->with('info',$info);
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
	 * 管理员管理注册用户_用户状态
	 *
	 * @return Response
	 */
	public function set_user_state()
	{
		if(Session::has('auth_state'))
		{
			$user_name = $_POST['user_name'];
			$fob_token = $_POST['fob_token'];

			if($fob_token == "yes")
			{
				Bu_user::where('name', $user_name)->update(
						array(
							'user_state' => 0
						)
				);
			}
			else if($fob_token == "no")
			{
				Bu_user::where('name', $user_name)->update(
						array(
							'user_state' => 1
						)
				);
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
	 * 管理员管理注册用户_用户组
	 *
	 * @return Response
	 */
	public function set_user_group()
	{
		if(Session::has('auth_state'))
		{
			$user_name = $_POST['user_name'];
			$fob_token = $_POST['fob_token'];

			if($fob_token == "normal")
			{
				Bu_user::where('name', $user_name)->update(
						array(
							'user_group' => 2
						)
				);
			}
			else if($fob_token == "admin")
			{
				Bu_user::where('name', $user_name)->update(
						array(
							'user_group' => 1
						)
				);
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
	 * 管理员管理_白名单
	 *
	 * @return Response
	 */
	public function white_list()
	{
		if(Session::has('auth_state'))
		{
			$info = array();
			$user_info = array();
			$user_info2 = array();

			//	检索数据库
			$User = Bu_user::where('name', Session::get('user_name'))->first();
			$user_group = $User->user_group;
			$info['user_img'] = $User->user_img;

			if($user_group == 1)
			{
				$info['user_group'] = $user_group;
				$white_list = Bu_white_list::get();
				
				$info['white_list'] = $white_list;
				return view('administrator.WhiteList')->with('info',$info);
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
	 * 管理员管理_白名单增加
	 *
	 * @return Response
	 */
	public function white_list_add()
	{
		$short_demo = $_POST['short_demo'];
		$long_demo = $_POST['long_demo'];
		$demo_name = $_POST['demo_name'];

		$list = Bu_white_list::where('short_demo', $short_demo)->first();

		if($list == '')
		{
			$created_at = $updated_at = date('Y-m-d H:i:s');
			$list_id = Bu_white_list::insertGetId(
				array(  'short_demo'	=> $short_demo,
						'long_demo'		=> $long_demo,
						'demo_name'		=> $demo_name,
						'created_at'	=> $created_at,
						'updated_at'	=> $updated_at)
			);
			return "sucess";
		}
		else
		{
			return "error";
		}
	}

	/**
	 * 管理员管理_白名单删除
	 *
	 * @return Response
	 */
	public function white_list_del()
	{
		$short_demo = $_POST['short_demo'];
		Bu_white_list::where('short_demo', $short_demo)->delete();
	}
}
