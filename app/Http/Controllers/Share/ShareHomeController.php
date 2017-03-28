<?php namespace App\Http\Controllers\Share;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_topic;
use App\Bu_user;
use Session;

class ShareHomeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Session::has('auth_state'))
		{
			$arr_info = array();
			$user_name = Session::get('user_name');
			$user_info = Bu_user::where('name', $user_name)->first();
			$info['user_img'] = $user_info->user_img;

			$info['topics_info'] = Bu_topic::all();
			return view('share.ShareHome')->with('info', $info);
		}
		else
		{
			echo "<script>";
			echo "window.location.href='/auth/login'";
			echo "</script>";
		}
	}
}
