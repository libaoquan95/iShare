<?php namespace App\Http\Controllers\Aboutus;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Bu_userurl;
use App\Bu_user;
use Session;

class AboutusController extends Controller {

	public function index()
	{
		return view('aboutus.Aboutus');
	}
}
