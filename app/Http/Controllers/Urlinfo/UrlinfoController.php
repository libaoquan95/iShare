<?php namespace App\Http\Controllers\Urlinfo;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_userurl;
use App\Bu_user;
use App\Bu_urlvisit;
use Session;

class UrlinfoController extends Controller {

	/**
	 * URL��Ϣչʾ
	 *
	 * @return Response
	 */
	public function index($shortUrl)
	{
		$arr_info = array();
		$days_pv = array();
		$months_pv = array();


		if(Session::has('auth_state'))
		{	
			$user_name = Session::get('user_name');
			$user_info = Bu_user::where('name', $user_name)->first();
			$arr_info['user_img_2'] = $user_info->user_img;
		}

		//	����ĳһurl��Ϣ
		$Url = Bu_userurl::where('short_url', $shortUrl)->first();
		if($Url != '')
		{
			//	����ĳһurl��Ϣ��Ӧ������Ϣ
			$topics = $Url->belongsToManyTopic()->get();
			$user_id = $Url->user_id;
			$User = Bu_user::where('id', $user_id)->first();
			$user_name = Session::get('user_name');
			$user_info = Bu_user::where('name', $user_name)->first();

			//	ͳ�Ƶ��µ��շ�����
			$today_year = date('Y');
			$today_month = date('m');
			$today_day = date('d');
			$days_pv = Bu_urlvisit::where('short_url', $shortUrl)
										->where('visit_year', $today_year)
										->where('visit_month', $today_month)
										->where('visit_day', $today_day)
										->get();

										
			//	ͳ�Ƶ��굥�·�����
			for($month=1; $month<=12; $month++)
			{
				$month_pv = Bu_urlvisit::where('short_url', $shortUrl)
										->where('visit_year', $today_year)
										->where('visit_month', $month)
										->sum('url_pv');
				$months_pv[$month] = $month_pv;
			}

			//	��ϳ����� 
			$arr_info['userurl'] = $Url;
			$arr_info['topics'] = $topics;
			$arr_info['user_name'] = $User->name;
			$arr_info['user_img'] = $User->user_img;
			$arr_info['days_pv'] = $days_pv;
			$arr_info['months_pv'] = $months_pv;
			$arr_info['today_month'] = $today_month;

			return view('urlinfo.Urlinfo')->with('info',$arr_info);
		}
		else
		{
			return view('errors.404');
		}
	}
}
