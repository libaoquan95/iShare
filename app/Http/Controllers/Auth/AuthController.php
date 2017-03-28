<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\Bu_user;
use App\Bu_userurl;
use Session;

class AuthController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('auth.Login')->withBu_users(Bu_user::all());
	}

	/**
	 * 登陆界面
	 *
	 * @return Response
	 */
	public function login()
	{
		return view('auth.Login')->withBu_users(Bu_user::all());
	}

	/**
	 * 注册界面
	 *
	 * @return Response
	 */
	public function register()
	{
		return view('auth.Register')->withBu_users(Bu_user::all());
	}

	/**
	 * 登出
	 *
	 * @return Response
	 */
	public function logout()
	{
		Session::forget('auth_state');

		echo "<script>";
		echo "window.location.href='/'";
		echo "</script>";
	}

	/**
	 * 登录验证
	 *
	 * @return Response
	 */
	public function login_sumbit()
	{
		// 处理表单:
		if ( (!empty($_POST['user_nicename'])) && (!empty($_POST['user_pass'])) )
		{
			$user_nicename = htmlspecialchars(stripslashes(trim($_POST['user_nicename'])));
			$user_pass = htmlspecialchars(stripslashes(trim($_POST['user_pass'])));
			$user_pass = md5($user_pass);

			//	查找与用户名对应行
			$user_info = Bu_user::where('name', $user_nicename)->first();

			//	信息匹配
			if($user_info != NULL AND $user_info->password == $user_pass)
			{
				Session::put('auth_state', '1');
				Session::put('user_name', "$user_nicename");

				return "1";
			}
			//	信息不匹配
			else
			{
				return "0";
			}
		}
		else
		{
			return "0";
		}
	}
	
	/**
	 * 注册验证
	 *
	 * @return Response
	 */
	public function register_sumbit()
	{
		// 处理表单:
		if ( (!empty($_POST['user_nicename'])) && (!empty($_POST['user_pass'])) && (!empty($_POST['user_email'])) )
		{
			$user_nicename = htmlspecialchars(stripslashes(trim($_POST['user_nicename'])));
			$user_pass = htmlspecialchars(stripslashes(trim($_POST['user_pass'])));
			$user_pass = md5($user_pass);
			$user_email = htmlspecialchars(stripslashes(trim($_POST['user_email'])));

			$created_at = $updated_at = date('Y-m-d H:i:s');
			$userurl_id = Bu_user::insertGetId(
				array(  'name'		=> $user_nicename,
						'email'		=> $user_email,
						'password'		=> $user_pass,
						'user_url_count'=> 0,
						'user_state'	=> 1,
						'user_group'	=> 2,
						'created_at'	=> $created_at,
						'updated_at'	=> $updated_at)
			);

			
			Session::put('auth_state', '1');
			Session::put('user_name', "$user_nicename");
		}
	}
	
	/**
	 * 注册验证--邮箱
	 *
	 * @return Response
	 */
	public function register_sumbit_user_email()
	{
		// 处理表单:
		if ( !empty($_POST['user_email']) )
		{
			//	查找与用户名对应行
			$user_info = Bu_user::where('email', $_POST['user_email'])->first();

			//	邮箱信息存在
			if($user_info != NULL)
			{
				return "1";
			}
			else
			{
				return "0";
			}
		}
	}
	/**
	 * 注册验证--用户名
	 *
	 * @return Response
	 */
	public function register_sumbit_user_name()
	{
		// 处理表单:
		if ( !empty($_POST['user_name']) )
		{
			//	查找与用户名对应行
			$user_info = Bu_user::where('name', $_POST['user_name'])->first();

			//	邮箱信息存在
			if($user_info != NULL)
			{
				return "1";
			}
			else
			{
				return "0";
			}
		}
	}
	
	/**
	 * 用户密码修改
	 *
	 * @return Response
	 */
	public function alert_password()
	{
		return view('auth.alertPassword')->withBu_users(Bu_user::all());
	}
	
	/**
	 * 用户密码修改
	 *
	 * @return Response
	 */
	public function alert_password_sumbit()
	{
		// 处理表单:
		if ( (!empty($_POST['user_new_pass'])) && (!empty($_POST['user_pass'])) && (!empty($_POST['user_name'])) )
		{
			$user_pass = htmlspecialchars(stripslashes(trim($_POST['user_pass'])));
			$user_pass = md5($user_pass);
			$user_new_pass = htmlspecialchars(stripslashes(trim($_POST['user_new_pass'])));
			$user_new_pass = md5($user_new_pass);
			$user_name = $_POST['user_name'];

			//	查找与用户名对应行
			$user_info = Bu_user::where('name', $user_name)->first();

			//	信息匹配
			if($user_info != NULL AND $user_info->password == $user_pass)
			{
				Bu_user::where('name', $user_name)->update(array('password' => $user_new_pass));
				return "1";
			}
			//	信息不匹配
			else
			{
				return "0";
			}
		}
		else
		{
			return "0";
		}
	}
}
