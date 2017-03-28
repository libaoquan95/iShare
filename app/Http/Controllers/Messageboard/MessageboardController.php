<?php namespace App\Http\Controllers\Messageboard;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_userurl;
use App\Bu_user;
use App\Bu_message_board;
use Session;

class MessageboardController extends Controller {

	public function index()
	{
		if(Session::has('auth_state'))
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
			return view('messageboard.MessageBoardHome')->with('info', $info);
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}

	/*
	 *	上传留言
	 */
	public function submit_messsage()
	{
		if(!empty($_POST["sub_user"]) && !empty($_POST["message"]))
		{
			$sub_user = $_POST["sub_user"];
			$message = $_POST["message"];

			$created_at = $updated_at = date('Y-m-d H:i:s');
			Bu_message_board::insertGetId(
				array(  'message'	 => $message,
						'user'       => $sub_user,
						'state'      => 0,
						'created_at' => $created_at,
						'updated_at' => $updated_at)
			);
			return "success";
		}
		else
		{
			return "error";
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
			$info['user_img'] = $user_info->user_img;

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
					return view('messageboard.MessageBoardWaitaudit')->with('info', $info);
					break;
				//	待解决
				case 1:
					return view('messageboard.MessageBoardWaitsolved')->with('info', $info);
					break;
				//	已解决
				case 2:
					return view('messageboard.MessageBoardSuccess')->with('info', $info);
					break;
				//	未解决
				case 3:
					return view('messageboard.MessageBoardError')->with('info', $info);
					break;
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
	public function next_page()
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
		return view('messageboard.MessageBoardMore')->with('info', $info);
	}
	
	/*
	 *	管理
	 */
	public function manage($state_name, $manage_id)
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
				else if($state_name == "delete")
				{
					$state = -2;
				}

				switch($state)
				{
					case -1:
						return view('errors.404')->with('info', $info);
					break;

					//	待审查
					case 0:
						Bu_message_board::where('id', $manage_id)->update(array('state' => 0));
						break;
					//	待解决
					case 1:
						Bu_message_board::where('id', $manage_id)->update(array('state' => 1));
						break;
					//	已解决
					case 2:
						Bu_message_board::where('id', $manage_id)->update(array('state' => 2));
						break;
					//	未解决
					case 3:
						Bu_message_board::where('id', $manage_id)->update(array('state' => 3));
						break;
					//	删除
					case -2:
						Bu_message_board::where('id', $manage_id)->delete();
						break;
				}
				echo "<script>";
				echo "window.location.href='/administrator/messageboard'";
				echo "</script>";
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
}
